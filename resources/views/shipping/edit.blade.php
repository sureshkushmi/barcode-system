@extends('layouts.admin')

@section('title', 'Configure ShippingEasy')

@section('content')

<div class="container-fluid py-4">
    <div class="row justify-content-center">
      <div class="col-md-12">

        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif

        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Settings</h3>
          </div>

          <form method="POST" action="{{ route('superadmin.settings.update') }}">
            @csrf
            <div class="card-body">

              <div class="form-group mb-3">
                <label for="api_key">API Key</label>
                <input type="text" name="api_key" id="api_key" value="{{ old('api_key', $settings->api_key ?? '') }}"
                  class="form-control @error('api_key') is-invalid @enderror" placeholder="Enter API Key">
                @error('api_key')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group mb-3">
                <label for="api_secret">API Secret</label>
                <input type="text" name="api_secret" id="api_secret" value="{{ old('api_secret', $settings->api_secret ?? '') }}"
                  class="form-control @error('api_secret') is-invalid @enderror" placeholder="Enter API Secret">
                @error('api_secret')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group mb-3">
                <label for="store_api_key">Store API Key</label>
                <input type="text" name="store_api_key" id="store_api_key" value="{{ old('store_api_key', $settings->store_api_key ?? '') }}"
                  class="form-control @error('store_api_key') is-invalid @enderror" placeholder="Enter Store API Key">
                @error('store_api_key')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group mb-3">
                <label for="api_url">API URL</label>
                <input type="text" name="api_url" id="api_url" value="{{ old('api_url', $settings->api_url ?? '') }}"
                  class="form-control @error('api_url') is-invalid @enderror" placeholder="Enter API URL">
                @error('api_url')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>

            </div>

            <div class="card-footer text-right">
              <button type="submit" class="btn btn-primary">
                Save Settings
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>


@endsection
