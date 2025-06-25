@extends('layouts.admin')

@section('title', 'Inbox')

@section('content')
<div class="container-fluid py-4">
  {{-- Success message --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  {{-- Validation errors --}}
  @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card">
    <div class="card-header">
      <h3 class="card-title font-weight-bold">Inbox</h3>
    </div>

    <div class="card-body table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Subject</th>
            <th>Received</th>
            <th></th>
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
                   data-id="{{ $message->sender->id }}"
                   data-subject="{{ e($message->subject) }}"
                   data-message="{{ e($message->message) }}"
                   data-parent-id="{{ $message->id }}">
                   <i class="fas fa-eye"></i> View
                </a>
              </td>
            </tr>
          @empty
            <tr><td colspan="3">No messages yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

{{-- Message Details Modal --}}
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="replyForm" method="POST" action="{{ route('messages.reply') }}">
        @csrf
        <input type="hidden" name="receiver_id" id="modal-receiver-id">
        <input type="hidden" name="subject" id="modal-original-subject">
        <input type="hidden" name="parent_id" id="modal-parent-id">

        <div class="modal-header">
          <h5 class="modal-title" id="messageModalLabel">Message Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <h5 id="modal-subject"></h5>
          <p id="modal-message"></p>

          <div class="form-group mt-4">
            <label for="reply-message">Your Reply</label>
            <textarea name="message" id="reply-message" rows="4" class="form-control" required></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Send Reply</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var messageModal = document.getElementById('messageModal');

    messageModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;

      var subject = button.getAttribute('data-subject');
      var message = button.getAttribute('data-message');
      var receiverId = button.getAttribute('data-id');
      var parentId = button.getAttribute('data-parent-id');

      messageModal.querySelector('#modal-subject').textContent = subject;
      messageModal.querySelector('#modal-message').textContent = message;
      messageModal.querySelector('#modal-receiver-id').value = receiverId;
      messageModal.querySelector('#modal-parent-id').value = parentId;
      messageModal.querySelector('#modal-original-subject').value = 'Re: ' + subject;
    });
  });
</script>
