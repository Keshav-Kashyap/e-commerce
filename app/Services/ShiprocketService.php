<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ShiprocketService
{
    public function syncOrder(Order $order): array
    {
        if (! $this->isEnabled()) {
            return [
                'success' => false,
                'message' => 'Shiprocket credentials are missing.',
            ];
        }

        $order->loadMissing('items.product');

        $response = $this->httpClient()
            ->withToken($this->getToken())
            ->post($this->baseUrl() . '/orders/create/adhoc', $this->buildPayload($order));

        if (! $response->successful()) {
            $message = $response->json('message') ?? $response->body() ?? 'Shiprocket order sync failed.';

            return [
                'success' => false,
                'message' => $message,
                'response' => $response->json(),
            ];
        }

        $data = $response->json();
        $trackingId = data_get($data, 'awb_code')
            ?? data_get($data, 'data.awb_code')
            ?? data_get($data, 'shipment_id')
            ?? data_get($data, 'data.shipment_id');

        $order->forceFill([
            'shiprocket_order_id' => data_get($data, 'order_id') ?? data_get($data, 'data.order_id'),
            'shiprocket_shipment_id' => data_get($data, 'shipment_id') ?? data_get($data, 'data.shipment_id'),
            'shiprocket_awb' => data_get($data, 'awb_code') ?? data_get($data, 'data.awb_code'),
            'shiprocket_status' => data_get($data, 'status') ?? data_get($data, 'data.status') ?? 'synced',
            'shiprocket_response' => json_encode($data),
            'shiprocket_sync_error' => null,
            'shiprocket_synced_at' => now(),
            'tracking_id' => $trackingId ?: $order->tracking_id,
            'status' => $order->status === 'pending' ? 'confirmed' : $order->status,
        ])->save();

        return [
            'success' => true,
            'message' => 'Shiprocket order synced successfully.',
            'response' => $data,
        ];
    }

    public function isEnabled(): bool
    {
        return filled(config('shiprocket.email')) && filled(config('shiprocket.password'));
    }

    private function getToken(): string
    {
        return Cache::remember('shiprocket.api.token', now()->addMinutes(50), function () {
            $response = $this->httpClient()
                ->post($this->baseUrl() . '/auth/login', [
                    'email' => config('shiprocket.email'),
                    'password' => config('shiprocket.password'),
                ]);

            if (! $response->successful()) {
                throw new \RuntimeException($response->json('message') ?? 'Unable to authenticate with Shiprocket.');
            }

            $token = data_get($response->json(), 'token')
                ?? data_get($response->json(), 'data.token')
                ?? data_get($response->json(), 'access_token');

            if (! $token) {
                throw new \RuntimeException('Shiprocket token was not returned by the API.');
            }

            return $token;
        });
    }

    private function httpClient()
    {
        $client = Http::acceptJson()->asJson();

        if (app()->environment('local')) {
            $client = $client->withoutVerifying();
        }

        return $client;
    }

    private function baseUrl(): string
    {
        return rtrim((string) config('shiprocket.base_url'), '/');
    }

    private function buildPayload(Order $order): array
    {
        $items = $order->items->map(function ($item) {
            $product = $item->product;

            return [
                'name' => $product->name ?? 'Product #' . $item->product_id,
                'sku' => (string) ($product->sku ?? 'SKU-' . $item->product_id),
                'units' => (int) $item->quantity,
                'selling_price' => (float) $item->price,
                'discount' => 0,
                'tax' => 0,
                'hsn' => (string) ($product->hsn ?? '0000'),
            ];
        })->values()->all();

        return [
            'order_id' => 'SR-' . $order->id,
            'order_date' => optional($order->created_at)->format('Y-m-d H:i') ?? now()->format('Y-m-d H:i'),
            'channel_id' => '',
            'comment' => 'Auto-created from Shringar store',
            'customer_name' => $order->name,
            'customer_email' => $order->email,
            'customer_phone' => $order->phone,
            'billing_customer_name' => $order->name,
            'billing_last_name' => '',
            'billing_address' => $order->address,
            'billing_address_2' => $order->city,
            'billing_city' => $order->city,
            'billing_pincode' => $order->pincode,
            'billing_state' => $order->state,
            'billing_country' => 'India',
            'billing_email' => $order->email,
            'billing_phone' => $order->phone,
            'shipping_is_billing' => true,
            'shipping_customer_name' => $order->name,
            'shipping_last_name' => '',
            'shipping_address' => $order->address,
            'shipping_address_2' => $order->city,
            'shipping_city' => $order->city,
            'shipping_pincode' => $order->pincode,
            'shipping_state' => $order->state,
            'shipping_country' => 'India',
            'shipping_email' => $order->email,
            'shipping_phone' => $order->phone,
            'order_items' => $items,
            'payment_method' => strtolower((string) $order->payment_method) === 'cod' ? 'COD' : 'Prepaid',
            'sub_total' => (float) $order->subtotal,
            'length' => config('shiprocket.package.length'),
            'breadth' => config('shiprocket.package.breadth'),
            'height' => config('shiprocket.package.height'),
            'weight' => config('shiprocket.package.weight'),
        ];
    }
}