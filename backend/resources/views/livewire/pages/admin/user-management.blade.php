<div class="min-h-screen bg-slate-900 text-white">
    <div class="flex">
        <!-- Sidebar -->
        @include('pages.admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Header -->
            <div class="bg-slate-800 border-b border-slate-700 px-8 py-6 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold">User & Guest Management</h1>
                    <p class="text-slate-400 text-sm mt-1">Manage your platform's partners and registered guests from a single interface.</p>
                </div>
                <div class="flex items-center gap-4">
                    <button 
                        wire:click="exportCSV()"
                        class="bg-slate-700 hover:bg-slate-600 px-6 py-2 rounded-lg font-semibold flex items-center gap-2 transition border border-slate-600"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2m0 0v-8m0 8H3m15-3h6"></path>
                        </svg>
                        Export CSV
                    </button>
                    <button 
                        wire:click="openForm()"
                        class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-lg font-semibold flex items-center gap-2 transition"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add User
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8 space-y-8">
                <!-- Flash Message -->
                @if (session('message'))
                    <div class="bg-green-600 text-white px-6 py-3 rounded-lg flex justify-between items-center animate-pulse">
                        <span>{{ session('message') }}</span>
                        <button class="text-2xl">&times;</button>
                    </div>
                @endif

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-br from-blue-600 to-blue-700 p-6 rounded-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-blue-200 mb-2">Total Users</p>
                                <p class="text-4xl font-bold">{{ $totalUsers }}</p>
                                <p class="text-blue-200 text-sm mt-2">👥 Users & Partners</p>
                            </div>
                            <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.488M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a9 9 0 0118 0v2h2v-2a11 11 0 00-20 0v2h2v-2z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-600 to-green-700 p-6 rounded-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-green-200 mb-2">Active Users</p>
                                <p class="text-4xl font-bold">{{ $activeUsers }}</p>
                                <p class="text-green-200 text-sm mt-2">✅ Currently active</p>
                            </div>
                            <svg class="w-12 h-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-red-600 to-red-700 p-6 rounded-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-red-200 mb-2">Banned Users</p>
                                <p class="text-4xl font-bold">{{ $bannedUsers }}</p>
                                <p class="text-red-200 text-sm mt-2">❌ Inactive/Banned</p>
                            </div>
                            <svg class="w-12 h-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Search & Filter Bar -->
                <div class="bg-slate-800 p-6 rounded-lg border border-slate-700">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="relative md:col-span-2">
                            <svg class="absolute left-3 top-3 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input 
                                type="text" 
                                wire:model.live="searchTerm"
                                placeholder="Search by name, email, or ID..."
                                class="w-full pl-10 pr-4 py-2 bg-slate-700 border border-slate-600 rounded text-white placeholder-slate-400 focus:outline-none focus:border-blue-500"
                            >
                        </div>

                        <select 
                            wire:model.live="filterStatus"
                            class="px-4 py-2 bg-slate-700 border border-slate-600 rounded text-white focus:outline-none focus:border-blue-500"
                        >
                            <option value="all">All Users</option>
                            <option value="active">Active</option>
                            <option value="banned">Banned</option>
                        </select>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="bg-slate-800 rounded-lg border border-slate-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-700 border-b border-slate-600">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold text-slate-300">USER</th>
                                    <th class="px-6 py-4 text-left font-semibold text-slate-300">FULLNAME</th>
                                    <th class="px-6 py-4 text-left font-semibold text-slate-300">EMAIL ADDRESS</th>
                                    <th class="px-6 py-4 text-left font-semibold text-slate-300">JOIN DATE</th>
                                    <th class="px-6 py-4 text-left font-semibold text-slate-300">TOTAL BOOKINGS</th>
                                    <th class="px-6 py-4 text-left font-semibold text-slate-300">STATUS</th>
                                    <th class="px-6 py-4 text-left font-semibold text-slate-300">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr class="border-b border-slate-700 hover:bg-slate-700/50 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center font-bold">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-medium">{{ $user->name }}</td>
                                        <td class="px-6 py-4 text-slate-300">{{ $user->email }}</td>
                                        <td class="px-6 py-4 text-slate-400">{{ $user->created_at->format('M d, Y') }}</td>
                                        <td class="px-6 py-4">
                                            <span class="font-semibold text-blue-400">{{ $user->bookings()->count() }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $user->is_active ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                                                {{ $user->is_active ? '● Active' : '● Banned' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <button 
                                                    wire:click="openForm({{ $user->id }})"
                                                    class="text-blue-400 hover:text-blue-300 transition"
                                                    title="Edit"
                                                >
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                                <button 
                                                    wire:click="toggleActive({{ $user->id }})"
                                                    wire:confirm="Are you sure?"
                                                    class="text-yellow-400 hover:text-yellow-300 transition"
                                                    title="{{ $user->is_active ? 'Ban' : 'Activate' }}"
                                                >
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </button>
                                                <button 
                                                    wire:click="deleteUser({{ $user->id }})"
                                                    wire:confirm="Are you sure you want to delete this user?"
                                                    class="text-red-400 hover:text-red-300 transition"
                                                    title="Delete"
                                                >
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center">
                                            <div class="text-slate-400">
                                                <svg class="w-12 h-12 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                </svg>
                                                <p class="text-lg">No users found</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="bg-slate-800 border-t border-slate-700 px-6 py-4 flex items-center justify-between">
                        <div class="text-sm text-slate-400">
                            Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
                        </div>
                        <div class="flex gap-2">
                            {{ $users->links('pagination::tailwind') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit User Modal -->
    @if ($showForm)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-slate-800 border border-slate-700 rounded-lg p-8 w-full max-w-md">
                <h2 class="text-2xl font-bold mb-6">{{ $editingUserId ? 'Edit User' : 'Add New User' }}</h2>

                <form wire:submit="saveUser" class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Full Name</label>
                        <input 
                            type="text" 
                            wire:model="name"
                            class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded text-white placeholder-slate-400 focus:outline-none focus:border-blue-500 @error('name') border-red-500 @enderror"
                            placeholder="John Doe"
                        >
                        @error('name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Email Address</label>
                        <input 
                            type="email" 
                            wire:model="email"
                            class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded text-white placeholder-slate-400 focus:outline-none focus:border-blue-500 @error('email') border-red-500 @enderror"
                            placeholder="john@example.com"
                        >
                        @error('email') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Password {{ $editingUserId ? '(Leave empty to keep current)' : '' }}
                        </label>
                        <input 
                            type="password" 
                            wire:model="password"
                            class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded text-white placeholder-slate-400 focus:outline-none focus:border-blue-500 @error('password') border-red-500 @enderror"
                            placeholder="••••••••"
                        >
                        @error('password') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Phone (Optional)</label>
                        <input 
                            type="text" 
                            wire:model="phone"
                            class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded text-white placeholder-slate-400 focus:outline-none focus:border-blue-500 @error('phone') border-red-500 @enderror"
                            placeholder="+1 234 567 8900"
                        >
                        @error('phone') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Active Status -->
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input 
                                type="checkbox" 
                                wire:model="is_active"
                                class="w-4 h-4 rounded bg-slate-700 border border-slate-600 accent-blue-600"
                            >
                            <span class="text-slate-300">User is Active</span>
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3 pt-6">
                        <button 
                            type="button"
                            wire:click="closeForm()"
                            class="flex-1 px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded font-semibold transition"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded font-semibold transition"
                        >
                            {{ $editingUserId ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
