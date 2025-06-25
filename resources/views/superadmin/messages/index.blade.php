@extends('layouts.admin')

@section('title', 'All Sent Messages')

@section('content')
<div class="container-fluid py-4">
  <div class="card">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show mx-3 mt-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="card-header d-flex justify-content-between align-items-center">
      <h3 class="card-title font-weight-bold">Sent Messages</h3>
      <a href="{{ route('superadmin.messages.create') }}" class="btn btn-sm btn-primary">
  <i class="fas fa-plus"></i> Compose Message
</a>

    </div>

    <div class="card-body table-responsive p-0">
      <table class="table table-hover text-nowrap table-bordered">
        <thead class="thead-light">
          <tr>
            <th>Subject</th>
            <th>Sent At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($messages as $message)
            <tr>
              <td>{{ $message->subject }}</td>
              <td>{{ $message->created_at->diffForHumans() }}</td>
              <td>
                <a href="javascript:void(0)" 
   class="btn btn-sm btn-info view-message-btn" 
   data-bs-toggle="modal" 
   data-bs-target="#messageModal"
   data-subject="{{ e($message->subject) }}"
   data-message="{{ e($message->message) }}">
   <i class="fas fa-eye"></i> View
</a>

              </td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="text-center">No messages sent yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($messages->hasPages())
      <div class="card-footer clearfix">
        {{ $messages->links('pagination::bootstrap-5') }}
      </div>
    @endif
  </div>
</div>

@endsection

<!-- Message Details Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="messageModalLabel">Message Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5 id="modal-subject"></h5>
        <p id="modal-message"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var messageModal = document.getElementById('messageModal');

    messageModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget; // Button that triggered the modal
      var subject = button.getAttribute('data-subject');
      var message = button.getAttribute('data-message');

      // Update modal content
      messageModal.querySelector('#modal-subject').textContent = subject;
      messageModal.querySelector('#modal-message').textContent = message;
    });
  });
</script>
