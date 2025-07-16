@extends('layouts.admin') {{-- Your AdminLTE layout --}}
@section('title', 'Pending Orders to Scan')

@section('content')
<div class="container-fluid">
    {{-- Summary Box --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $pendingOrderCount }}</h3>
                    <p>Pending Orders to Scan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-box"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Order List --}}
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Pending Orders</h3>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Order Date</th>
                        <th>Tracking #</th>
                        <th>Required Qty</th>
                        <th>Scanned Qty</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pendingOrders as $order)
                        <tr>
                            <td>{{ $order->shippingeasy_order_id }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') }}</td>
                            <td>{{ $order->tracking_number ?? 'N/A' }}</td>
                            <td>{{ $order->total_required }}</td>
                            <td>{{ $order->user_scanned_qty }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $order->order_id }}">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            </td>
                        </tr>

                        {{-- Modal for Scan Details --}}
                        <div class="modal fade" id="detailsModal{{ $order->order_id }}" tabindex="-1" aria-labelledby="detailsLabel{{ $order->order_id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-info">
                                        <h5 class="modal-title" id="detailsLabel{{ $order->order_id }}">
                                            <i class="fas fa-barcode"></i> Scan Details for Order #{{ $order->shippingeasy_order_id }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        @php
                                            $itemsInOrder = $scanDetails->filter(fn($group) =>
                                                optional($group->first())->order_id == $order->order_id
                                            );
                                        @endphp

                                        @forelse ($itemsInOrder as $itemId => $scans)
                                            @php $item = $scans->first(); @endphp
                                            <div class="mb-3">
                                                <h6>{{ $item->item_name }}</h6>
                                                <p><strong>Barcode:</strong> {{ $item->barcode }}</p>

                                                <table class="table table-sm table-bordered">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Scanned By</th>
                                                            <th>Quantity</th>
                                                            <th>Scanned At</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($scans as $i => $scan)
                                                            <tr>
                                                                <td>{{ $i + 1 }}</td>
                                                                <td>{{ $scan->scanned_by }}</td>
                                                                <td>{{ $scan->quantity_scanned }}</td>
                                                                <td>{{ \Carbon\Carbon::parse($scan->scanned_at)->format('Y-m-d H:i') }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <hr>
                                        @empty
                                            <p class="text-muted">No scan data available for this order.</p>
                                        @endforelse
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times"></i> Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">🎉 You have no pending orders!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pendingOrders->hasPages())
            <div class="card-footer clearfix">
                {{ $pendingOrders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
