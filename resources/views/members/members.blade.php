<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Members') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h1 class="text-2xl font-semibold">Members</h1>

                @if(session('success'))
                    <p class="text-green-600">{{ session('success') }}</p>
                @endif

                <div class="overflow-x-auto">
                    <table class="table-auto w-full mt-4 border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border px-4 py-2 text-left">Name</th>
                                <th class="border px-4 py-2 text-left">Email</th>
                                <th class="border px-4 py-2 text-left">Role</th>
                                <th class="border px-4 py-2 text-left">Status</th>
                                <th class="border px-4 py-2 text-left">Expiry Date</th>
                                <th class="border px-4 py-2 text-left">Company</th>
                                <th class="border px-4 py-2 text-left">Phone</th>
                                <th class="border px-4 py-2 text-left">Address</th>
                                <th class="border px-4 py-2 text-left">Document</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($members as $member)
                                <tr class="border-b">
                                    <td class="border px-4 py-2">{{ $member->name }}</td>
                                    <td class="border px-4 py-2">{{ $member->email }}</td>
                                    <td class="border px-4 py-2">{{ $member->role }}</td>
                                    <td class="border px-4 py-2">{{ $member->status }}</td>
                                    <td class="border px-4 py-2">
                                        @if($member->expiry_date)
                                            {{ \Carbon\Carbon::parse($member->expiry_date)->format('d-m-Y') }}
                                        @else
                                            Not Available
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">{{ $member->company_name }}</td>
                                    <td class="border px-4 py-2">{{ $member->phone }}</td>
                                    <td class="border px-4 py-2">{{ $member->address }}</td>
                                    <td class="border px-4 py-2">
                                        @if($member->document)
                                            <a href="{{ asset('storage/'.$member->document) }}" target="_blank">View Document</a>
                                        @else
                                            No Document
                                        @endif
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
