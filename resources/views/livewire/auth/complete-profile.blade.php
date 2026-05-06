<div>
    <div class="w-full max-w-lg">

        {{-- Icon --}}
        <div class="mb-6 flex items-center justify-center">
            <div class="w-14 h-14 rounded-full bg-foreground/10 border border-line flex items-center justify-center">
                <svg class="w-6 h-6 text-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </div>
        </div>

        {{-- Header --}}
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-semibold text-foreground font-serif tracking-tight">Complete Your Profile</h1>
            <p class="mt-2 text-sm text-dim leading-relaxed">
                Provide your personal information so we can verify your identity.<br>
                Your account will be unlocked once an admin reviews your submission.
            </p>
        </div>

        {{-- Rejection notice --}}
        @if($isRejected && $rejectionReason)
            <div class="mb-5 rounded border border-red-500/30 bg-red-500/10 px-4 py-3">
                <p class="text-xs font-semibold text-red-400 uppercase tracking-wider mb-1">Submission Rejected</p>
                <p class="text-sm text-red-300">{{ $rejectionReason }}</p>
                <p class="text-xs text-dim mt-2">Please correct your information and resubmit.</p>
            </div>
        @endif

        {{-- Form --}}
        <form wire:submit.prevent="submit" class="space-y-4">

            {{-- Date of Birth --}}
            <div>
                <label class="block text-xs font-semibold text-dim uppercase tracking-wider mb-1">Date of Birth</label>
                <input type="date" wire:model="date_of_birth"
                    class="auth-input @error('date_of_birth') input-error @enderror" />
                @error('date_of_birth')
                    <span class="block mt-1 text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>

            {{-- Address Line 1 --}}
            <div>
                <label class="block text-xs font-semibold text-dim uppercase tracking-wider mb-1">Address Line 1</label>
                <input type="text" wire:model="address_line"
                    placeholder="House/Unit No., Street Name"
                    class="auth-input @error('address_line') input-error @enderror" />
                @error('address_line')
                    <span class="block mt-1 text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>

            {{-- Province --}}
            <div>
                <label class="block text-xs font-semibold text-dim uppercase tracking-wider mb-1">Province</label>
                <select wire:model.live="province_id"
                    class="auth-input @error('province_id') input-error @enderror">
                    <option value="">Select province…</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                    @endforeach
                </select>
                @error('province_id')
                    <span class="block mt-1 text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>

            {{-- City --}}
            <div>
                <label class="block text-xs font-semibold text-dim uppercase tracking-wider mb-1">City / Municipality</label>
                <select wire:model.live="city_id"
                    class="auth-input @error('city_id') input-error @enderror"
                    @disabled(!$province_id)>
                    <option value="">{{ $province_id ? 'Select city…' : 'Select a province first' }}</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                    @endforeach
                </select>
                @error('city_id')
                    <span class="block mt-1 text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>

            {{-- Barangay --}}
            <div>
                <label class="block text-xs font-semibold text-dim uppercase tracking-wider mb-1">Barangay</label>
                <select wire:model="barangay_id"
                    class="auth-input @error('barangay_id') input-error @enderror"
                    @disabled(!$city_id)>
                    <option value="">{{ $city_id ? 'Select barangay…' : 'Select a city first' }}</option>
                    @foreach($barangays as $barangay)
                        <option value="{{ $barangay->id }}">{{ $barangay->name }}</option>
                    @endforeach
                </select>
                @error('barangay_id')
                    <span class="block mt-1 text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>

            {{-- Government ID Type --}}
            <div>
                <label class="block text-xs font-semibold text-dim uppercase tracking-wider mb-1">Government ID Type</label>
                <select wire:model="id_type"
                    class="auth-input @error('id_type') input-error @enderror">
                    <option value="">Select an ID type…</option>
                    <option value="PhilSys / National ID">PhilSys / National ID</option>
                    <option value="Driver's License">Driver's License</option>
                    <option value="Passport">Passport</option>
                    <option value="SSS ID">SSS ID</option>
                    <option value="GSIS ID">GSIS ID</option>
                    <option value="PhilHealth ID">PhilHealth ID</option>
                    <option value="Pag-IBIG ID">Pag-IBIG ID</option>
                    <option value="Voter's ID">Voter's ID</option>
                    <option value="Postal ID">Postal ID</option>
                    <option value="PRC ID">PRC ID</option>
                </select>
                @error('id_type')
                    <span class="block mt-1 text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>

            {{-- ID Number --}}
            <div>
                <label class="block text-xs font-semibold text-dim uppercase tracking-wider mb-1">ID Number</label>
                <input type="text" wire:model="id_number"
                    placeholder="e.g. 1234-5678-9012"
                    class="auth-input @error('id_number') input-error @enderror" />
                @error('id_number')
                    <span class="block mt-1 text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>

            {{-- ID Image Upload --}}
            <div>
                <label class="block text-xs font-semibold text-dim uppercase tracking-wider mb-1">
                    Photo of Government ID
                    <span class="normal-case font-normal text-dim/70 ml-1">(front side, max 5 MB)</span>
                </label>

                {{-- Show existing image if re-submitting after rejection --}}
                @if($existingImage && !$id_image)
                    <div class="mb-2 rounded border border-line overflow-hidden">
                        <img src="{{ asset('storage/' . $existingImage) }}" alt="Current ID image"
                            class="w-full max-h-40 object-cover" />
                        <p class="text-[10px] text-dim px-2 py-1 bg-subtle/50">Previously uploaded — upload a new one to replace it</p>
                    </div>
                @endif

                {{-- Preview new upload --}}
                @if($id_image)
                    <div class="mb-2 rounded border border-line overflow-hidden">
                        <img src="{{ $id_image->temporaryUrl() }}" alt="ID preview"
                            class="w-full max-h-40 object-cover" />
                    </div>
                @endif

                <label class="flex flex-col items-center justify-center gap-2 w-full rounded border-2 border-dashed border-line bg-subtle/30 px-4 py-5 cursor-pointer hover:border-foreground/40 hover:bg-subtle/60 transition-colors @error('id_image') border-red-500/50 @enderror">
                    <svg class="w-7 h-7 text-dim" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                    </svg>
                    <div class="text-center">
                        <span class="text-sm font-medium text-foreground">Click to upload</span>
                        <span class="text-sm text-dim"> or drag and drop</span>
                        <p class="text-xs text-dim mt-0.5">JPG, PNG, WEBP up to 5 MB</p>
                    </div>
                    <input type="file" wire:model="id_image" accept="image/*" class="sr-only" />
                </label>

                {{-- Upload progress --}}
                <div wire:loading wire:target="id_image" class="mt-2 flex items-center gap-2 text-xs text-dim">
                    <svg class="h-3.5 w-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Uploading…
                </div>

                @error('id_image')
                    <span class="block mt-1 text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>

            {{-- Info note --}}
            <p class="text-xs text-dim leading-relaxed bg-subtle/50 border border-line rounded px-3 py-2">
                Your information is used solely for identity verification and will be kept confidential.
            </p>

            {{-- Submit --}}
            <button type="submit"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-60 cursor-not-allowed"
                wire:target="submit"
                class="w-full rounded bg-foreground px-4 py-2.5 text-sm font-medium text-on-primary hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-foreground focus:ring-offset-2 focus:ring-offset-page disabled:opacity-50 transition-all">
                <span wire:loading.remove wire:target="submit">Submit for Verification</span>
                <span wire:loading wire:target="submit" class="inline-flex items-center gap-2">
                    <svg class="h-4 w-4 animate-spin opacity-70" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Submitting…
                </span>
            </button>
        </form>

        {{-- Footer --}}
        <div class="mt-6 pt-5 border-t border-line flex items-center justify-center gap-1.5 text-sm">
            <span class="text-dim">Wrong account?</span>
            <form method="POST" action="{{ route('auth.logout') }}">
                @csrf
                <button type="submit" class="text-foreground font-semibold hover:underline underline-offset-4">Sign out</button>
            </form>
        </div>

    </div>
</div>
