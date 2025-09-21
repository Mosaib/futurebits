<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white shadow rounded-lg p-4 text-center">
                    <h5>Total Withdrawals</h5>
                    <p class="text-xl font-bold">
                        ₹{{ number_format($withdrawals->sum('amount'), 2) }}
                    </p>
                </div>
                <div class="bg-white shadow rounded-lg p-4 text-center">
                    <h5>Approved Withdrawals</h5>
                    <p class="text-xl font-bold text-green-600">
                        ₹{{ number_format($withdrawals->where('status', 'approved')->sum('amount'), 2) }}
                    </p>
                </div>
                <div class="bg-white shadow rounded-lg p-4 text-center">
                    <h5>Pending Requests</h5>
                    <p class="text-xl font-bold text-yellow-600">
                        {{ $withdrawals->where('status', 'pending')->count() }}
                    </p>
                </div>
                <div class="bg-white shadow rounded-lg p-4 text-center">
                    <h5>Rejected Requests</h5>
                    <p class="text-xl font-bold text-red-600">
                        {{ $withdrawals->where('status', 'rejected')->count() }}
                    </p>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <table class="text-center table-auto w-full border-collapse border border-gray-300">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border px-4 py-2">Agent</th>
                            <th class="border px-4 py-2">Amount</th>
                            <th class="border px-4 py-2">Status</th>
                            <th class="border px-4 py-2">Requested On</th>
                            <th class="border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($withdrawals as $w)
                        <tr>
                            <td class="border px-4 py-2">{{ $w->agent?->name ?? 'N/A' }} ({{ $w->agent?->email ?? '-' }})</td>
                            <td class="border px-4 py-2">₹{{ number_format($w->amount,2) }}</td>
                            <td class="border px-4 py-2">{{ ucfirst($w->status) }}</td>
                            <td class="border px-4 py-2">{{ $w->created_at->format('d M Y H:i') }}</td>
                            <td class="border px-4 py-2">
                                @if($w->status == 'pending')
                                <form action="{{ route('admin.withdrawals.approve', $w->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button style="
                                        background-color: #e1e1e1;
                                        border-radius: 5px;
                                        padding: 8px;
                                        font-weight: bold;
                                        color: green;">Approve</button>
                                </form>
                                <form action="{{ route('admin.withdrawals.reject', $w->id) }}" method="POST" class="inline-block">
                                    @csrf
                                     <button style="
                                        background-color: #e1e1e1;
                                        border-radius: 5px;
                                        padding: 8px;
                                        font-weight: bold;
                                        margin-left: 10px;
                                        color: red;">Reject</button>
                                </form>
                                @else
                                    <span class="text-gray-500">No actions</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
