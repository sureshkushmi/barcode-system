@extends('layouts.admin')

@section('title', 'Shipment Items')

@section('content')
<div class="container-fluid py-3">
    <div class="row">
        <div class="col-12">

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Shipment Items for Tracking #{{ $shipment->tracking_number }}</h3>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Item Name</th>
                                    <th>Total Qty</th>
                                    <th>Scanned Qty</th>
                                    <th>Status</th>
                                    <th>Scan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($shipment->items as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->required_quantity }}</td>
                                        <td>{{ $item->scanned_quantity }}</td>
                                        <td>
                                            @if($item->completed)
                                                <span class="badge badge-success">✅ Completed</span>
                                            @elseif($item->scanned_quantity > 0)
                                                <span class="badge badge-warning">⏳ Partial</span>
                                            @else
                                                <span class="badge badge-secondary">🕓 Not Started</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form method="POST" action="{{ route('update.item', $item->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary">Scan</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No items found for this shipment.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <form method="GET" action="{{ route('next.label') }}">
                        <button type="submit" class="btn btn-success">Next Shipment</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
