@extends('layouts.app')
@section('content')
<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-md-2 d-none d-md-block">@include('partials.admin-sidebar')</div>
        <div class="col-md-10 p-4 p-md-5" style="background-color: #fdfaf5; min-height: 100vh;">
            <h3 style="color: #5E1929; font-weight: bold;">📜 Sales Invoices</h3>
            
            <form method="GET" action="{{ route('admin.invoices') }}" class="row g-3 my-4 align-items-end">
                <div class="col-md-3">
                    <label class="small fw-bold text-muted">From Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-muted">To Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn text-white fw-bold w-100" style="background: #5E1929;">Filter</button>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('admin.invoices') }}" class="btn btn-outline-secondary w-100 fw-bold">Reset</a>
                </div>
            </form>

            <div class="card shadow-sm p-4 border-0" style="border-radius: 15px;">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead style="background: #5E1929; color: #fff;">
                            <tr>
                                <th>Invoice No.</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>GST Added</th>
                                <th>Total Amount</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                            <tr>
                                <td class="fw-bold">#{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $invoice->created_at->format('d M Y') }}</td>
                                <td>{{ $invoice->name }}<br><small class="text-muted">{{ $invoice->state }}</small></td>
                                <td class="text-success">₹{{ number_format($invoice->gst_amount, 2) }}</td>
                                <td class="fw-bold">₹{{ number_format($invoice->total_amount, 2) }}</td>
                                <td class="text-end">
                                    <a href="{{ route('order.invoice', $invoice->id) }}" class="btn btn-sm btn-outline-dark fw-bold" target="_blank">📄 Download</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-4 text-muted">No invoices found for selected dates.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection