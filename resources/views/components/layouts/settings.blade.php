<div>
    <x-slot:header>
        <a href="{{ route('admin.settings.profile') }}" wire:navigate class="text-dim hover:text-foreground transition-colors">Settings</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-dim/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
        </svg>
        <span class="font-medium text-foreground">
            @if(request()->routeIs('admin.settings.profile')) Profile
            @elseif(request()->routeIs('admin.settings.password')) Password
            @elseif(request()->routeIs('admin.settings.delete')) Delete Account
            @endif
        </span>
    </x-slot:header>

    {{-- Settings tab navigation --}}
    <div class="mb-6">
        <nav class="flex gap-1 border-b border-line">
            <a href="{{ route('admin.settings.profile') }}" wire:navigate
                class="relative px-4 py-2.5 text-sm font-medium transition-colors
                    {{ request()->routeIs('admin.settings.profile') ? 'text-foreground' : 'text-dim hover:text-foreground' }}">
                Profile
                @if(request()->routeIs('admin.settings.profile'))
                    <span class="absolute inset-x-0 -bottom-px h-0.5 bg-primary"></span>
                @endif
            </a>
            <a href="{{ route('admin.settings.password') }}" wire:navigate
                class="relative px-4 py-2.5 text-sm font-medium transition-colors
                    {{ request()->routeIs('admin.settings.password') ? 'text-foreground' : 'text-dim hover:text-foreground' }}">
                Password
                @if(request()->routeIs('admin.settings.password'))
                    <span class="absolute inset-x-0 -bottom-px h-0.5 bg-primary"></span>
                @endif
            </a>
            <a href="{{ route('admin.settings.delete') }}" wire:navigate
                class="relative px-4 py-2.5 text-sm font-medium transition-colors
                    {{ request()->routeIs('admin.settings.delete') ? 'text-foreground' : 'text-dim hover:text-foreground' }}">
                Delete Account
                @if(request()->routeIs('admin.settings.delete'))
                    <span class="absolute inset-x-0 -bottom-px h-0.5 bg-primary"></span>
                @endif
            </a>
        </nav>
    </div>

    {{-- Settings content --}}
    <div class="max-w-2xl">
        {{ $slot }}
    </div>
</div>
