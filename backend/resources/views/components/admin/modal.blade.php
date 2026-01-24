<!-- Modal Component -->
@if($show)
<div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center" wire:click.self="close">
    <div class="bg-slate-800 rounded-lg border border-slate-700 max-w-md w-full mx-4 shadow-xl">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-700">
            <h2 class="text-lg font-semibold text-white">{{ $title }}</h2>
            <button wire:click="close" class="text-slate-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Content -->
        <div class="px-6 py-4">
            {{ $slot }}
        </div>

        <!-- Footer -->
        @if($footer ?? null)
        <div class="px-6 py-4 border-t border-slate-700 flex gap-3 justify-end">
            {{ $footer }}
        </div>
        @endif
    </div>
</div>
@endif
