@extends('layouts.admin') {{-- Extends your admin layout --}}

@section('title', 'Add User')

@section('content')
<div class="container-fluid py-4">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title font-weight-bold">Add New User</h3>
    </div>

    <div class="card-body">
      {{-- Success Message --}}
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      {{-- Error Messages --}}
      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- User Form --}}
      <form method="POST" action="{{ route('superadmin.users.store') }}">
        @csrf

        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Role</label>
          <select name="role" class="form-select" required>
            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select" required>
            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>

        <button type="submit" class="btn btn-success">
          <i class="bi bi-check-circle"></i> Create User
        </button>
        <a href="{{ route('superadmin.users') }}" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Back to List
        </a>
      </form>
    </div>
  </div>
</div>
@endsection
