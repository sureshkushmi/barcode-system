<x-app-layout>
    <x-slot name="header">
        <h2>User Scanning Reports</h2>
    </x-slot>

    <div class="p-6 bg-white rounded shadow">
        <table class="table-auto w-full border border-gray-300">
            <thead>
                <tr>
                    <th class="border px-4 py-2">Shipment Tracking #</th>
                    <th class="border px-4 py-2">Item Name</th>
                    <th class="border px-4 py-2">Quantity Scanned</th>
                    <th class="border px-4 py-2">Scanned At</th>
                    <th class="border px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($scans as $scan)
                    <tr>
                        <td class="border px-4 py-2">{{ $scan->shipment->tracking_number ?? 'N/A' }}</td>
                        <td class="border px-4 py-2">{{ $scan->item->name ?? 'Label Scan' }}</td>
                        <td class="border px-4 py-2">{{ $scan->quantity_scanned }}</td>
                        <td class="border px-4 py-2">{{ $scan->scanned_at->format('Y-m-d H:i') }}</td>
                        <td class="border px-4 py-2">{{ ucfirst($scan->status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center p-4">No scans found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $scans->links() }}
        </div>
    </div>
</x-app-layout>
