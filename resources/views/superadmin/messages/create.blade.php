@extends('layouts.admin')

@section('title', 'Send Message')

@section('content')
<div class="container-fluid py-4">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title font-weight-bold">Send Message to All Users</h3>
    </div>

    <div class="card-body">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('superadmin.messages.store') }}">
    @csrf
<div class="mb-3">
  <label class="form-label">Subject</label>
  <input type="text" name="subject" value="{{ old('subject') }}" class="form-control @error('subject') is-invalid @enderror" required>
  @error('subject')
    <div class="text-danger">{{ $message }}</div>
  @enderror
</div>


<div class="mb-3">
  <label for="receiver_id" class="form-label">Send To</label>
  <select name="receiver_id" class="form-select @error('receiver_id') is-invalid @enderror" required>
    <option value="all">All Users</option>
    @foreach ($users as $user)
      <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
    @endforeach
  </select>
  @error('receiver_id')
    <div class="text-danger">{{ $message }}</div>
  @enderror
</div>


    <div class="mb-3">
      <label class="form-label">Message</label>
      <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
      @error('body')
        <div class="text-danger">{{ $message }}</div>
      @enderror
    </div>

    <button type="submit" class="btn btn-primary">
      <i class="bi bi-send"></i> Send Message
    </button>
  </form>
</div>

  </div>
</div>
@endsection
