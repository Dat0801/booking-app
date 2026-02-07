<div class="min-h-screen bg-slate-900 text-white">
    <div class="flex">
        <!-- Sidebar -->
        @include('pages.admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Header -->
            <div class="bg-slate-800 border-b border-slate-700 px-8 py-6 flex justify-between items-center sticky top-0 z-10">
                <div>
                    <h1 class="text-3xl font-bold">{{ $title ?? 'Admin' }}</h1>
                </div>
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <input type="text" placeholder="Search..." 
                               class="bg-slate-700 text-white rounded-lg px-4 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">
                        <svg class="absolute left-3 top-2.5 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="bg-amber-400 w-10 h-10 rounded-full flex items-center justify-center text-slate-900 font-bold">
                            AR
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
