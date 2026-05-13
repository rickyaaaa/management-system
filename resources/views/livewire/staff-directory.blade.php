<div>
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <a href="{{ route('dashboard') }}" wire:navigate class="mr-4 p-2.5 bg-white rounded-xl shadow-soft-sm text-gray-400 hover:text-purple-600 transition border border-gray-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h3 class="text-xl font-bold text-soft-dark">Production Specialists</h3>
                <p class="text-sm text-gray-400 mt-1">Manage production specialist access and roles</p>
            </div>
        </div>
        <button wire:click="openCreate" class="bg-gradient-to-tl from-purple-700 to-pink-500 hover:from-purple-600 hover:to-pink-400 text-white px-5 py-2.5 rounded-xl font-semibold shadow-soft-sm hover:shadow-soft transition flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            Add User
        </button>
    </div>

    <!-- Search -->
    <div class="mb-6">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by name, username, or specialty..." class="w-full md:w-96 border-gray-200 rounded-xl shadow-soft-sm focus:border-soft-primary focus:ring focus:ring-soft-primary focus:ring-opacity-30 text-sm px-4 py-3" />
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-soft border-0 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-wider">
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Username</th>
                    <th class="px-6 py-4">Specialty</th>
                    <th class="px-6 py-4">Tasks</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($staff as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-tl from-purple-700 to-pink-500 text-white flex items-center justify-center font-bold text-sm shadow-soft-sm mr-3">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-bold text-soft-dark text-sm">{{ $user->name }}</div>
                                <div class="text-xs text-gray-400">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 font-medium">{{ $user->username }}</td>
                    <td class="px-6 py-4">
                        @php
                            $colors = [
                                'Modeling' => 'bg-blue-100 text-blue-700',
                                'Texturing' => 'bg-green-100 text-green-700',
                                'RIG' => 'bg-yellow-100 text-yellow-700',
                                'Animation' => 'bg-orange-100 text-orange-700',
                                'LRC' => 'bg-red-100 text-red-700',
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $colors[$user->role_specialty] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ strtoupper($user->role_specialty ?? 'N/A') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 font-medium">
                        {{ $user->assignedTasks()->count() }} total
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button wire:click="openEdit('{{ $user->id }}')" class="text-soft-info hover:text-cyan-600 transition mr-3 font-semibold text-sm">Edit</button>
                        <button wire:click="confirmDelete('{{ $user->id }}')" class="text-red-400 hover:text-red-600 transition font-semibold text-sm">Delete</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        No specialists found. Add your first one!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Footer -->
        <div class="p-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
            <span class="text-sm text-gray-500 font-medium">Showing {{ $staff->count() }} specialists</span>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
    <div wire:click.self="$set('showModal', false)" class="fixed inset-0 z-50 bg-gray-900 bg-opacity-40 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-lg shadow-soft-hover border-0">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-soft-dark">{{ $editMode ? 'Edit Specialist' : 'Add New Specialist' }}</h3>
                <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-soft-primary transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6">
                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input wire:model="name" type="text" class="block w-full border-gray-200 rounded-xl shadow-sm focus:border-soft-primary focus:ring focus:ring-soft-primary focus:ring-opacity-30 text-sm" required />
                        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                            <input wire:model="username" type="text" class="block w-full border-gray-200 rounded-xl shadow-sm focus:border-soft-primary focus:ring focus:ring-soft-primary focus:ring-opacity-30 text-sm" required />
                            @error('username') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input wire:model="email" type="email" class="block w-full border-gray-200 rounded-xl shadow-sm focus:border-soft-primary focus:ring focus:ring-soft-primary focus:ring-opacity-30 text-sm" required />
                            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password {{ $editMode ? '(leave blank to keep)' : '' }}</label>
                            <input wire:model="password" type="password" class="block w-full border-gray-200 rounded-xl shadow-sm focus:border-soft-primary focus:ring focus:ring-soft-primary focus:ring-opacity-30 text-sm" {{ $editMode ? '' : 'required' }} />
                            @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Specialty</label>
                            <select wire:model="role_specialty" class="block w-full border-gray-200 rounded-xl shadow-sm focus:border-soft-primary focus:ring focus:ring-soft-primary focus:ring-opacity-30 text-sm" required>
                                @foreach($this->specialties as $spec)
                                    <option value="{{ $spec }}">{{ $spec }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 space-x-3">
                        <button type="button" wire:click="$set('showModal', false)" class="px-5 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm transition">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-tl from-purple-700 to-pink-500 text-white font-bold rounded-xl shadow-soft-sm hover:shadow-soft transition">{{ $editMode ? 'Update' : 'Create' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($confirmDeleteId)
    <div wire:click.self="cancelDelete" class="fixed inset-0 z-50 bg-gray-900 bg-opacity-40 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-sm shadow-soft-hover border-0 p-6 text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-soft-dark mb-2">Remove Specialist?</h3>
            <p class="text-sm text-gray-500 mb-6">This will permanently delete this user and all associated data. This action cannot be undone.</p>
            <div class="flex justify-center space-x-3">
                <button wire:click="cancelDelete" class="px-5 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm transition">Cancel</button>
                <button wire:click="deleteUser" class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl shadow-soft-sm transition">Delete</button>
            </div>
        </div>
    </div>
    @endif
</div>
