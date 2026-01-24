<!-- Input Component -->
<input 
    {{ $attributes->merge(['class' => 'block w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition']) }}
    @if($type ?? null) type="{{ $type }}" @endif
>
