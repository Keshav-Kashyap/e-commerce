@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row g-0">
        
        <div class="col-md-2 d-none d-md-block">
            @include('partials.admin-sidebar')
        </div>

        <div class="col-md-10 p-4 p-md-5" style="background-color: #fdfaf5; min-height: 100vh;">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 style="color: #5E1929; font-weight: bold; margin-bottom: 5px;">👥 Registered Customers</h3>
                    <p class="text-muted small">View all users registered on Shringar.</p>
                </div>
            </div>
            
            <div class="card shadow-sm p-4 border-0" style="border-radius: 15px;">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead style="background: #5E1929; color: #fdfaf5;">
                            <tr>
                                <th class="p-3 border-0" style="font-weight: 600; letter-spacing: 1px;">NAME</th>
                                <th class="p-3 border-0" style="font-weight: 600; letter-spacing: 1px;">EMAIL ADDRESS</th>
                                <th class="p-3 border-0" style="font-weight: 600; letter-spacing: 1px;">PHONE NUMBER</th>
                                <th class="p-3 border-0 text-end" style="font-weight: 600; letter-spacing: 1px;">JOINED DATE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                            <tr style="border-bottom: 1px solid #f5ebe9; transition: 0.3s;" onmouseover="this.style.background='#f9f9f9'" onmouseout="this.style.background='transparent'">
                                <td class="fw-bold p-3" style="color: #333; font-size: 15px;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width: 35px; height: 35px; background: #fdfaf5; color: #c59d5f; font-weight: 700; border: 1px solid #e5c07b; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                                        </div>
                                        {{ $customer->name }}
                                    </div>
                                </td>
                                <td class="p-3 text-muted">{{ $customer->email }}</td>
                                <td class="p-3">
                                    <span style="background: #fdfaf5; color: #c59d5f; border: 1px solid #e8d5d1; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                        📞 {{ $customer->phone ?? 'Not Provided' }}
                                    </span>
                                </td>
                                <td class="p-3 text-muted text-end">{{ $customer->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div style="font-size: 40px; opacity: 0.3; margin-bottom: 10px;">👥</div>
                                    <h5 style="color: #5E1929; font-weight: 600;">No Customers Yet</h5>
                                    <p class="text-muted">When users register on your website, their details will appear here.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div> </div>
</div>
@endsection