<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ShippingEasy API Settings') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        <div class="bg-white p-6 rounded shadow">
            @if(session('success'))
                <div class="text-green-600 mb-4">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('superadmin.shipping.save') }}">
                @csrf

                <div class="mb-4">
                    <label>API Key</label>
                    <input type="text" name="api_key" value="{{ old('api_key', $settings->api_key ?? '') }}" class="w-full border p-2">
                </div>

                <div class="mb-4">
                    <label>API Secret</label>
                    <input type="text" name="api_secret" value="{{ old('api_secret', $settings->api_secret ?? '') }}" class="w-full border p-2">
                </div>

                <div class="mb-4">
                    <label>Store API Key</label>
                    <input type="text" name="store_api_key" value="{{ old('store_api_key', $settings->store_api_key ?? '') }}" class="w-full border p-2">
                </div>

                <div class="mb-4">
                    <label>API URL</label>
                    <input type="text" name="api_url" value="{{ old('api_url', $settings->api_url ?? '') }}" class="w-full border p-2">
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Settings</button>
            </form>
        </div>
    </div>
</x-app-layout>
