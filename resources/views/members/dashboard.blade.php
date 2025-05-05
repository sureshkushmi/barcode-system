<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Member Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-xl font-semibold">{{ __('Member Dashboard') }}</h3>

                <div class="mt-4">
                    <p><strong class="font-medium">Welcome,</strong> {{ $user->name }}</p>
                    <p><strong class="font-medium">Email:</strong> {{ $user->email }}</p>

                    @if($membershipExpiry)
                        <p><strong class="font-medium">Membership Expiry:</strong> {{ $membershipExpiry->format('d-m-Y') }}</p>

                        @if($membershipExpiry->isBefore(now()->addMonth()))
                            <div class="mt-4 p-4 bg-yellow-100 text-yellow-700 border border-yellow-300 rounded-lg">
                                <strong>Your membership is expiring soon!</strong> Please renew before <strong>{{ $membershipExpiry->format('d-m-Y') }}</strong>.
                            </div>
                        @endif
                    @else
                        <p><strong class="font-medium">Membership Expiry:</strong> Not Available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
