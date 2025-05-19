<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-xl font-semibold">{{ __('Welcome, ') }}{{ $user->name }}</h3>

                <div class="mt-4 space-y-4">
                    <p><strong>Email:</strong> {{ $user->email }}</p>

                    <!-- Barcode Scanner Actions -->
                    <div class="mt-6">
                    <a href="{{ route('scan.label') }}"
   class="inline-block bg-blue-500 text-black font-bold py-2 px-4 rounded hover:bg-blue-700">
    📦 Start Scanning Shipping Label
</a>


                        <a href="{{ route('user.reports') }}"
                           class="inline-block bg-green-500 text-black font-bold py-2 px-4 rounded hover:bg-green-700 ml-4">
                            📊 View My Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
