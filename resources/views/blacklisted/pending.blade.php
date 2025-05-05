@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Pending Blacklist Approvals</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Reason</th>
                <th>Proof</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendingWorkers as $worker)
            <tr>
                <td>{{ $worker->name }}</td>
                <td>{{ $worker->phone }}</td>
                <td>{{ $worker->reason }}</td>
                <td>
                    @if($worker->proof)
                        <a href="{{ asset('storage/' . $worker->proof) }}" target="_blank">View Proof</a>
                    @else
                        No proof uploaded
                    @endif
                </td>
                <td>
                    <form action="{{ route('blacklist.approve', $worker->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                    </form>
                    <form action="{{ route('blacklist.reject', $worker->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
