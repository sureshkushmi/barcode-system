@extends('layouts.admin') {{-- This extends your admin.blade.php --}}

@section('title', 'User Management')

@section('content')
  <div class="container-fluid py-4">
    <div class="card">
      <div class="card-header d-flex justify-between items-center">
        <h3 class="card-title font-weight-bold">User List</h3>
        <a href="{{ route('superadmin.users.create') }}" class="btn btn-success">
          <i class="bi bi-plus-lg"></i> Add User
        </a>
        
                <a href="{{ route('superadmin.users.export') }}" class="btn btn-outline-success ms-2" data-bs-toggle="tooltip" title="Export all users to Excel">
            <i class="bi bi-download"></i> Export Users
        </a>


      </div>

      <div class="card-body">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
        <table class="table table-striped table-hover">
  <thead class="thead-light">
    <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Role</th>
      <th style="width: 200px;">Actions</th>
    </tr>
  </thead>
  <tbody>
    @foreach($users as $user)
      <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->role }}</td>
        <td>
          <a href="{{ route('superadmin.users.edit', $user->id) }}" class="btn btn-sm btn-primary">
            <i class="bi bi-pencil-square"></i> Edit
          </a>
          <form action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">
              <i class="bi bi-trash"></i> Delete
            </button>
          </form>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>

<!-- Pagination -->
<div class="mt-3 d-flex justify-content-center">
    {!! $users->links('vendor.pagination.bootstrap-5') !!}
</div>

      </div>
    </div>
  </div>
@endsection
