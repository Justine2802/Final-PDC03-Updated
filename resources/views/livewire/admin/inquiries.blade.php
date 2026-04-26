<div>
    <x-slot:header>
        <span class="font-medium text-foreground">Inquiries</span>
    </x-slot:header>

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-foreground font-serif tracking-tight">Inquiries</h1>
        <p class="text-xs text-dim mt-1">View and respond to renter inquiries.</p>
    </div>

    <div class="rounded-sm border border-line bg-card" style="box-shadow: var(--shadow-xs);">
        {{-- Toolbar --}}
        <div class="p-4 border-b border-line flex flex-col sm:flex-row sm:items-center gap-3">
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="Search by message, property or renter..."
                   class="w-full sm:max-w-xs rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground placeholder-dim/50 focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground" />

            <div class="flex items-center gap-2 sm:ml-auto">
                <select wire:model.live="filterStatus"
                        class="rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="responded">Responded</option>
                    <option value="closed">Closed</option>
                </select>

                <select wire:model.live="perPage"
                        class="rounded-sm border border-line bg-page text-foreground text-sm py-2 px-2 focus:outline-none focus:ring-1 focus:ring-foreground">
                    <option value="10">10 / page</option>
                    <option value="25">25 / page</option>
                    <option value="50">50 / page</option>
                </select>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto transition-opacity duration-200" wire:loading.class="opacity-50 pointer-events-none">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-line bg-subtle/50">
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">#</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Renter</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Property</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Message</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Status</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Date</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse($inquiries as $inquiry)
                        <tr class="hover:bg-subtle/50 transition-colors">
                            <td class="px-4 py-3 text-dim">{{ $inquiry->id }}</td>
                            <td class="px-4 py-3 font-medium text-foreground">{{ $inquiry->user->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-foreground">{{ $inquiry->property->title ?? '—' }}</td>
                            <td class="px-4 py-3 text-dim max-w-[200px] truncate">{{ $inquiry->message }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $badge = match($inquiry->status) {
                                        'pending'   => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400',
                                        'responded' => 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400',
                                        'closed'    => 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400',
                                        default     => 'bg-subtle text-dim',
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-sm px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider {{ $badge }}">
                                    {{ ucfirst($inquiry->status ?? 'pending') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-dim">{{ $inquiry->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <button wire:click="viewInquiry({{ $inquiry->id }})"
                                            class="text-xs text-dim hover:text-foreground font-medium transition-colors">View / Respond</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-dim">No inquiries found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t border-line">
            {{ $inquiries->links() }}
        </div>
    </div>

    {{-- View / Respond Modal --}}
    @if($viewing)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-foreground/40 backdrop-blur-sm" wire:click="closeView"></div>
            <div class="relative w-full max-w-lg rounded-sm border border-line bg-card overflow-hidden" style="box-shadow: var(--shadow-lg);">
                {{-- Header --}}
                <div class="flex items-center justify-between p-4 border-b border-line">
                    <div>
                        <h3 class="text-base font-semibold text-foreground font-serif tracking-tight">Inquiry #{{ $viewing->id }}</h3>
                        <p class="text-[10px] text-dim mt-0.5">{{ $viewing->created_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>
                    @php
                        $mBadge = match($viewing->status) {
                            'pending'   => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400',
                            'responded' => 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400',
                            'closed'    => 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400',
                            default     => 'bg-subtle text-dim',
                        };
                    @endphp
                    <span class="inline-flex items-center rounded-sm px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider {{ $mBadge }}">
                        {{ ucfirst($viewing->status) }}
                    </span>
                </div>

                {{-- Body --}}
                <div class="p-5 space-y-4 max-h-[60vh] overflow-y-auto">
                    {{-- From / Property --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-subtle/50 border border-line rounded-sm p-3">
                            <span class="block text-[10px] text-dim uppercase tracking-wider mb-1">From</span>
                            <p class="text-sm font-medium text-foreground">{{ $viewing->user->name ?? '—' }}</p>
                            <p class="text-[10px] text-dim">{{ $viewing->user->email ?? '' }}</p>
                        </div>
                        <div class="bg-subtle/50 border border-line rounded-sm p-3">
                            <span class="block text-[10px] text-dim uppercase tracking-wider mb-1">Property</span>
                            <p class="text-sm font-medium text-foreground">{{ $viewing->property->title ?? '—' }}</p>
                        </div>
                    </div>

                    {{-- Message --}}
                    <div class="bg-subtle/50 border border-line rounded-sm p-3">
                        <span class="block text-[10px] text-dim uppercase tracking-wider mb-1">Message</span>
                        <p class="text-sm text-foreground">{{ $viewing->message }}</p>
                    </div>

                    {{-- Existing Response --}}
                    @if($viewing->response)
                        <div class="bg-green-50 dark:bg-green-500/5 border border-green-200 dark:border-green-500/20 rounded-sm p-3">
                            <span class="block text-[10px] text-green-700 dark:text-green-400 uppercase tracking-wider mb-1 font-semibold">
                                Your Previous Response
                                @if($viewing->responded_at)
                                    ({{ $viewing->responded_at->format('M d, Y') }})
                                @endif
                            </span>
                            <p class="text-sm text-green-800 dark:text-green-300">{{ $viewing->response }}</p>
                        </div>
                    @endif

                    {{-- Response Form --}}
                    @if($viewing->status !== 'closed')
                        <div>
                            <label class="block text-[10px] text-dim uppercase tracking-wider mb-1 font-semibold">
                                {{ $viewing->response ? 'Update Response' : 'Write Response' }}
                            </label>
                            <textarea wire:model="responseText" rows="3"
                                class="w-full rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground placeholder-dim/50 focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground"
                                placeholder="Type your response..."></textarea>
                            @error('responseText') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-between p-4 border-t border-line">
                    <div>
                        @if($viewing->status !== 'closed')
                            <button wire:click="markClosed({{ $viewing->id }})"
                                    class="text-xs text-red-500 hover:text-red-700 font-medium transition-colors">Mark as Closed</button>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        @if($viewing->status !== 'closed')
                            <button wire:click="submitResponse"
                                    class="rounded-sm bg-foreground px-4 py-2 text-sm font-medium text-on-primary hover:opacity-90 transition-all">
                                {{ $viewing->response ? 'Update Response' : 'Send Response' }}
                            </button>
                        @endif
                        <button wire:click="closeView"
                                class="rounded-sm border border-line px-4 py-2 text-sm font-medium text-dim hover:text-foreground hover:bg-subtle transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
