<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
    <div class="w-full max-w-md">
        <div class="mb-6 text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-500 to-indigo-400 shadow-lg shadow-blue-500/40">
                <span class="text-2xl font-bold text-white">BA</span>
            </div>
            <h1 class="mt-4 text-2xl font-semibold text-white">Admin Console</h1>
            <p class="mt-1 text-sm text-slate-300">Sign in to manage bookings, orders and products.</p>
        </div>

        <div class="bg-white/95 backdrop-blur rounded-2xl shadow-2xl shadow-slate-900/40 border border-white/60 p-8">
            <h2 class="text-lg font-semibold mb-4 text-slate-900 text-center">Sign in to your admin account</h2>

        @if($errorMessage ?? false)
            <div class="mb-4 text-sm text-red-600 rounded-lg border border-red-200 bg-red-50 px-3 py-2">
                {{ $errorMessage }}
            </div>
        @endif

        <form wire:submit.prevent="login" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input
                    type="email"
                    wire:model="email"
                    class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                >
                @error('email')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input
                    type="password"
                    wire:model="password"
                    class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                >
                @error('password')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="inline-flex items-center text-sm text-slate-700">
                    <input type="checkbox" wire:model="remember" class="mr-2 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    Remember me
                </label>
            </div>

            <button
                type="submit"
                class="w-full bg-gradient-to-r from-blue-600 to-indigo-500 text-white py-2.5 rounded-lg font-medium text-sm hover:from-blue-700 hover:to-indigo-600 transition shadow-md shadow-blue-500/40 disabled:opacity-60 disabled:cursor-not-allowed"
            >
                Sign in
            </button>
        </form>
        </div>
    </div>
</div>
