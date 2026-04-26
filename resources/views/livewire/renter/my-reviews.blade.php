<div>
    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <h2 class="text-xl font-semibold text-foreground font-serif tracking-tight">My Reviews</h2>
            <p class="text-xs text-dim mt-1">Leave reviews for completed reservations.</p>
        </div>

        {{-- Reservations ready to review --}}
        @if($completedReservations->count() > 0)
            <div>
                <h3 class="text-[10px] font-semibold text-dim uppercase tracking-[0.12em] mb-3 flex items-center gap-1.5">
                    <svg class="w-3 h-3 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    Completed Stays — Ready to Review
                </h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    @foreach($completedReservations as $res)
                        @if(!$res->property) @continue @endif
                        @php $alreadyReviewed = in_array($res->id, $reviewedIds); @endphp
                        <div class="bg-card border border-line rounded-sm p-4 flex gap-3 items-start" style="box-shadow: var(--shadow-xs);">
                            <div class="w-16 h-16 flex-shrink-0 rounded-sm overflow-hidden bg-subtle">
                                @if($res->property->images?->first())
                                    <img src="{{ asset('storage/' . $res->property->images->first()->image_path) }}"
                                         class="w-full h-full object-cover" alt="">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-dim/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-3m0 0l7-4 7 4M5 7v10a1 1 0 001 1h12a1 1 0 001-1V7"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-foreground text-sm font-serif tracking-tight truncate">{{ $res->property->title }}</p>
                                <p class="text-[10px] text-dim mt-0.5">{{ $res->move_in_date->format('M d, Y') }} — {{ $res->move_out_date?->format('M d, Y') ?? 'Ongoing' }}</p>
                                <p class="text-xs font-bold text-foreground mt-1 font-serif">₱{{ number_format($res->total_price, 2) }}</p>

                                <div class="mt-2">
                                    @if($alreadyReviewed)
                                        <button wire:click="openReviewForm({{ $res->id }})"
                                                class="text-[10px] font-medium text-foreground border border-foreground hover:bg-foreground hover:text-on-primary px-2.5 py-1 rounded-sm transition-all uppercase tracking-wider">
                                            Edit Review
                                        </button>
                                    @else
                                        <button wire:click="openReviewForm({{ $res->id }})"
                                                class="text-[10px] font-medium text-on-primary bg-foreground hover:opacity-90 px-2.5 py-1 rounded-sm transition-all uppercase tracking-wider">
                                            Leave Review
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- My Reviews List --}}
        <div>
            <h3 class="text-[10px] font-semibold text-dim uppercase tracking-[0.12em] mb-3">Past Reviews</h3>

            @if($myReviews->count() > 0)
                <div class="space-y-4">
                    @foreach($myReviews as $review)
                        <div class="bg-card border border-line rounded-sm p-5" style="box-shadow: var(--shadow-xs);">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <p class="font-semibold text-foreground font-serif tracking-tight">{{ $review->property->title ?? '—' }}</p>
                                    <p class="text-[10px] text-dim mt-0.5">{{ $review->created_at->format('M d, Y') }}</p>

                                    {{-- Stars --}}
                                    <div class="flex items-center gap-0.5 mt-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-line' }}" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                            </svg>
                                        @endfor
                                        <span class="text-[10px] text-dim ml-1">{{ $review->rating }}/5</span>
                                    </div>

                                    <p class="text-sm text-dim mt-3 leading-relaxed">{{ $review->comment }}</p>

                                    @if($review->pros || $review->cons)
                                        <div class="mt-3 flex gap-4">
                                            @if($review->pros)
                                                <div>
                                                    <p class="text-[10px] font-semibold text-green-600 dark:text-green-400 mb-1 uppercase tracking-wider">Pros</p>
                                                    <ul class="text-[10px] text-dim space-y-0.5">
                                                        @foreach($review->pros as $pro)
                                                            <li class="flex items-center gap-1"><span class="text-green-500">+</span> {{ $pro }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            @if($review->cons)
                                                <div>
                                                    <p class="text-[10px] font-semibold text-red-500 dark:text-red-400 mb-1 uppercase tracking-wider">Cons</p>
                                                    <ul class="text-[10px] text-dim space-y-0.5">
                                                        @foreach($review->cons as $con)
                                                            <li class="flex items-center gap-1"><span class="text-red-400">−</span> {{ $con }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div class="flex flex-col items-end gap-2 flex-shrink-0">
                                    @if($review->is_verified)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-green-700 bg-green-50 dark:bg-green-500/10 dark:text-green-400 px-2 py-0.5 rounded-sm uppercase tracking-wider">
                                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                                            Verified
                                        </span>
                                    @endif
                                    <button wire:click="openReviewForm({{ $review->reservation_id }})"
                                            class="text-[10px] text-foreground hover:underline underline-offset-4 font-medium">Edit</button>
                                    <button wire:click="deleteReview({{ $review->id }})"
                                            class="text-[10px] text-red-500 hover:underline underline-offset-4 font-medium">Delete</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">{{ $myReviews->links() }}</div>
            @else
                <div class="text-center py-16 bg-card border border-line rounded-sm">
                    <svg class="w-12 h-12 mx-auto text-dim/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    <p class="text-base font-semibold text-foreground font-serif">No reviews yet</p>
                    <p class="text-xs text-dim mt-2">Complete a stay and share your experience</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Review Form Modal --}}
    @if($reviewingReservationId)
        <div class="fixed inset-0 bg-foreground/40 backdrop-blur-sm z-50 flex items-center justify-center p-4" wire:click.self="closeReviewForm">
            <div class="bg-card border border-line rounded-sm max-w-md w-full p-6" @click.stop style="box-shadow: var(--shadow-lg);">
                <h3 class="text-sm font-semibold text-foreground font-serif tracking-tight mb-0.5">
                    {{ $editingReviewId ? 'Edit Your Review' : 'Leave a Review' }}
                </h3>
                <p class="text-xs text-dim mb-5">Share your experience with this property.</p>

                <form wire:submit="submitReview" class="space-y-4">
                    {{-- Star Rating --}}
                    <div>
                        <label class="block text-xs font-medium text-dim mb-2 uppercase tracking-wider">Rating <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" wire:click="$set('rating', {{ $i }})"
                                        class="focus:outline-none transition-transform hover:scale-110">
                                    <svg class="w-7 h-7 {{ $i <= $rating ? 'text-yellow-400' : 'text-line' }} transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                </button>
                            @endfor
                            <span class="ml-2 text-xs font-medium text-dim">{{ $rating }}/5</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-dim mb-1.5 uppercase tracking-wider">Comment <span class="text-red-500">*</span></label>
                        <textarea wire:model="comment" placeholder="Describe your experience..." rows="3"
                                  class="w-full px-3 py-2 rounded-sm border border-line bg-page text-foreground placeholder-dim/50 text-sm focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground resize-none"></textarea>
                        @error('comment') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-dim mb-1.5 uppercase tracking-wider">
                            Pros <span class="text-dim/50 normal-case tracking-normal">(optional, comma-separated)</span>
                        </label>
                        <input type="text" wire:model="prosInput" placeholder="e.g. Great location, Clean, Quiet..."
                               class="w-full px-3 py-2 rounded-sm border border-line bg-page text-foreground placeholder-dim/50 text-sm focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-dim mb-1.5 uppercase tracking-wider">
                            Cons <span class="text-dim/50 normal-case tracking-normal">(optional, comma-separated)</span>
                        </label>
                        <input type="text" wire:model="consInput" placeholder="e.g. Noisy street, Small bathroom..."
                               class="w-full px-3 py-2 rounded-sm border border-line bg-page text-foreground placeholder-dim/50 text-sm focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground">
                    </div>

                    <div class="flex gap-2.5 pt-1">
                        <button type="submit"
                                class="flex-1 py-2.5 px-4 bg-foreground text-on-primary rounded-sm hover:opacity-90 font-medium text-sm transition-all">
                            {{ $editingReviewId ? 'Update Review' : 'Submit Review' }}
                        </button>
                        <button type="button" wire:click="closeReviewForm"
                                class="flex-1 py-2.5 px-4 border border-line text-dim rounded-sm hover:text-foreground hover:border-foreground font-medium text-sm transition-all">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
