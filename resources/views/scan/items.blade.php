@extends('layouts.admin')

@section('title', 'Scan Items for ' . $shipment->tracking_number)

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
      <div class="col-lg-12">

        <div class="card card-primary shadow-sm">
          <div class="card-header">
            <h3 class="card-title">Scan Items for {{ $shipment->tracking_number }}</h3>
          </div>

          <div class="card-body">
            @foreach($shipment->items as $item)
              <div class="border rounded p-3 mb-3">
                <strong>{{ $item->name }}</strong>
                <p>Required: {{ $item->required_quantity }}</p>
                <p>Scanned: {{ $item->scanned_quantity }}</p>
                <p>Status: {!! $item->completed ? '<span class="text-success">✅ Completed</span>' : '<span class="text-danger">❌ Pending</span>' !!}</p>

                @if(!$item->completed)
                  <form method="POST" action="{{ route('scan.item.update', $item->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm mt-2">Scan Item</button>
                  </form>
                @endif
              </div>
            @endforeach

            <a href="{{ route('scan.next') }}" class="btn btn-primary mt-4">Next Label</a>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>
@endsection
