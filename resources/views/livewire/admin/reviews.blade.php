<div>
    <x-slot:header>
        <span class="font-medium text-foreground">Reviews</span>
    </x-slot:header>

    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <div class="overflow-x-auto transition-opacity duration-200" wire:loading.class="opacity-50 pointer-events-none">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Property</th>
                            <th>Renter</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Verified</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                            <tr>
                                <td>{{ $review->id }}</td>
                                <td>{{ $review->property->title ?? 'N/A' }}</td>
                                <td>{{ $review->renter->name ?? 'N/A' }}</td>
                                <td>
                                    <div class="rating rating-sm">
                                        @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" class="mask mask-star-2 bg-orange-400"
                                                {{ $i == $review->rating ? 'checked' : '' }} disabled />
                                        @endfor
                                    </div>
                                </td>
                                <td class="max-w-xs truncate">{{ $review->comment ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ $review->is_verified ? 'badge-success' : 'badge-ghost' }}">
                                        {{ $review->is_verified ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td>{{ $review->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-base-content/60">No reviews found.</td>
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
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
