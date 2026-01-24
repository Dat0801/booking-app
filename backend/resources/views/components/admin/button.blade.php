<!-- Button Component -->
<button {{ $attributes->merge(['class' => 'px-4 py-2 rounded-lg font-medium text-sm transition focus:outline-none focus:ring-2 focus:ring-offset-2']) }} 
        @class([
            'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500 focus:ring-offset-slate-900' => $variant === 'primary',
            'bg-slate-700 hover:bg-slate-600 text-white focus:ring-slate-500 focus:ring-offset-slate-900' => $variant === 'secondary',
            'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500 focus:ring-offset-slate-900' => $variant === 'danger',
            'bg-green-600 hover:bg-green-700 text-white focus:ring-green-500 focus:ring-offset-slate-900' => $variant === 'success',
        ])>
    {{ $slot }}
</button>
