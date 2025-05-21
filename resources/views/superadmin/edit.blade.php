@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
      <div class="col-md-12">

        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        @if($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Edit User</h3>
          </div>

          <form method="POST" action="{{ route('superadmin.users.update', $user->id) }}">
            @csrf
            @method('PUT')
            <div class="card-body">

              <div class="form-group">
                <label for="name">Name</label>
                <input 
                  type="text" 
                  name="name" 
                  id="name" 
                  class="form-control @error('name') is-invalid @enderror" 
                  value="{{ old('name', $user->name) }}" 
                  required>
                @error('name')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group">
                <label for="email">Email</label>
                <input 
                  type="email" 
                  name="email" 
                  id="email" 
                  class="form-control @error('email') is-invalid @enderror" 
                  value="{{ old('email', $user->email) }}" 
                  required>
                @error('email')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group">
                <label for="password">Password <small class="text-muted">(Leave blank to keep current)</small></label>
                <input 
                  type="password" 
                  name="password" 
                  id="password" 
                  class="form-control @error('password') is-invalid @enderror">
                @error('password')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group">
                <label for="role">Role</label>
                <select 
                  name="role" 
                  id="role" 
                  class="form-control @error('role') is-invalid @enderror">
                  <option value="users" {{ old('role', $user->role) == 'users' ? 'selected' : '' }}>User</option>
                  <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group">
                <label for="status">Status</label>
                <select 
                  name="status" 
                  id="status" 
                  class="form-control @error('status') is-invalid @enderror">
                  <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                  <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>

            </div>

            <div class="card-footer text-right">
              <button type="submit" class="btn btn-primary">Update User</button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>

@endsection
