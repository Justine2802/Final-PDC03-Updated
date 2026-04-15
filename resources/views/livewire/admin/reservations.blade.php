<div>
    <x-slot:header>
        <span class="font-medium text-foreground">Reservations</span>
    </x-slot:header>

    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-4">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search reservations..."
                    class="input input-bordered w-full sm:max-w-xs" />
            </div>

            <div class="overflow-x-auto transition-opacity duration-200" wire:loading.class="opacity-50 pointer-events-none">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Property</th>
                            <th>Move In</th>
                            <th>Move Out</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $reservation)
                            <tr>
                                <td>{{ $reservation->id }}</td>
                                <td>{{ $reservation->property->title ?? 'N/A' }}</td>
                                <td>{{ $reservation->move_in_date }}</td>
                                <td>{{ $reservation->move_out_date ?? '—' }}</td>
                                <td>{{ number_format($reservation->total_price, 2) }}</td>
                                <td>
                                    @php
                                        $colors = ['pending' => 'badge-warning', 'confirmed' => 'badge-success', 'cancelled' => 'badge-error', 'completed' => 'badge-info'];
                                    @endphp
                                    <span class="badge {{ $colors[$reservation->status] ?? 'badge-ghost' }}">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-ghost btn-xs">View</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-base-content/60">No reservations found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-sm text-base-content/60">
                    <span>Rows per page:</span>
                    <select wire:model.live="perPage" class="select select-bordered select-sm">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div class="flex-1">
                    {{ $reservations->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
