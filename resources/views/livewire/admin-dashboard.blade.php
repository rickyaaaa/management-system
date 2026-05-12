<div class="p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Admin Dashboard (Level 1)</h2>

    <!-- Analytics Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-indigo-600 rounded-xl shadow-lg p-5 text-white transform transition hover:-translate-y-1">
            <h4 class="text-indigo-200 text-sm font-semibold mb-1 uppercase tracking-wider">Active Tasks</h4>
            <p class="text-3xl font-bold">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-yellow-500 rounded-xl shadow-lg p-5 text-white transform transition hover:-translate-y-1">
            <h4 class="text-yellow-100 text-sm font-semibold mb-1 uppercase tracking-wider">Awaiting Review</h4>
            <p class="text-3xl font-bold">{{ $stats['awaiting_review'] }}</p>
        </div>
        <div class="bg-green-600 rounded-xl shadow-lg p-5 text-white transform transition hover:-translate-y-1">
            <h4 class="text-green-200 text-sm font-semibold mb-1 uppercase tracking-wider">Completed</h4>
            <p class="text-3xl font-bold">{{ $stats['completed'] }}</p>
        </div>
        <div class="bg-purple-600 rounded-xl shadow-lg p-5 text-white transform transition hover:-translate-y-1">
            <h4 class="text-purple-200 text-sm font-semibold mb-1 uppercase tracking-wider">Top Specialist</h4>
            <p class="text-xl font-bold truncate mt-1">{{ $topSpecialist->name ?? 'N/A' }}</p>
            <p class="text-xs text-purple-200 mt-1">{{ $topSpecialist->tasks_count ?? 0 }} tasks completed</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-xl font-semibold mb-4">Create New Task</h3>
        <form wire:submit="createTask" class="space-y-4">
            <div>
                <x-input-label for="title" :value="__('Title')" />
                <x-text-input wire:model="title" id="title" class="block mt-1 w-full" type="text" required />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="description" :value="__('Description')" />
                <textarea wire:model="description" id="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <x-input-label for="assignee_id" :value="__('Assign To (Level 2)')" />
                    <select wire:model="assignee_id" id="assignee_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        <option value="">-- Select Specialist --</option>
                        @foreach($productionUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role_specialty }})</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('assignee_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="deadline" :value="__('Deadline')" />
                    <x-text-input wire:model="deadline" id="deadline" class="block mt-1 w-full" type="date" required />
                    <x-input-error :messages="$errors->get('deadline')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="priority" :value="__('Priority')" />
                    <select wire:model="priority" id="priority" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        <option value="low">Low</option>
                        <option value="normal">Normal</option>
                        <option value="high">High</option>
                    </select>
                    <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                </div>
            </div>

            <x-primary-button class="mt-4">
                {{ __('Create Task') }}
            </x-primary-button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4">All Tasks</h3>
        <div class="space-y-6">
            @forelse($tasks as $task)
                <div class="border rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-lg font-bold">{{ $task->title }} <span class="text-gray-500 text-sm font-normal">(v{{ $task->version }})</span></h4>
                            <p class="text-sm text-gray-600">Assigned to: {{ $task->assignee->name ?? 'N/A' }} | Deadline: {{ $task->deadline }} | Priority: <span class="capitalize">{{ $task->priority }}</span></p>
                        </div>
                        <div class="flex items-center space-x-3">
                            @if($task->status === 'ready_for_admin')
                                <button wire:click="markAsCompleted('{{ $task->id }}')" class="text-sm px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded font-bold shadow transition animate-pulse">Mark Completed</button>
                            @endif
                            <button wire:click="viewDetails('{{ $task->id }}')" class="text-sm px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition font-medium">History & Details</button>
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs font-semibold capitalize">{{ str_replace('_', ' ', $task->status) }}</span>
                        </div>
                    </div>
                    <livewire:global-tracking :task="$task" :key="'track-'.$task->id" />
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">No tasks found.</p>
            @endforelse
        </div>
    </div>

    <!-- Task Details Modal -->
    @if($detailTask)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg w-full max-w-5xl max-h-[90vh] flex flex-col shadow-xl">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-bold">Task Details: {{ $detailTask->title }}</h3>
                <button wire:click="closeDetails" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto flex-1 bg-gray-50">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Submission History -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                        <h4 class="font-bold text-lg mb-4 text-indigo-900 border-b pb-2">Submission History</h4>
                        @if($detailTask->submissions->count() > 0)
                            <div class="space-y-4">
                                @foreach($detailTask->submissions as $sub)
                                <div class="bg-gray-50 border rounded p-4">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="font-bold text-indigo-600 bg-indigo-100 px-2 py-0.5 rounded text-sm">Version {{ $sub->version }}</span>
                                        <span class="text-xs text-gray-500">{{ $sub->created_at->format('M d, Y H:i') }}</span>
                                    </div>
                                    <div class="text-sm text-gray-700 bg-white p-3 border border-gray-200 rounded mb-3 shadow-inner italic">
                                        "{{ $sub->notes ?: 'No notes provided.' }}"
                                    </div>
                                    <div class="flex space-x-4 text-sm font-medium">
                                        <a href="{{ route('secure.file', [$sub->id, 'mov']) }}" target="_blank" class="flex items-center text-blue-600 hover:text-blue-800">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Watch Video <span class="ml-1 text-gray-500 font-normal">({{ $sub->mov_size }})</span>
                                        </a>
                                        <a href="{{ route('secure.file', [$sub->id, 'blend']) }}" download class="flex items-center text-blue-600 hover:text-blue-800">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            Download .blend <span class="ml-1 text-gray-500 font-normal">({{ $sub->blend_size }})</span>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 italic py-4 text-center">No submissions yet.</p>
                        @endif
                    </div>

                    <!-- Tracking Logs Timeline -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                        <h4 class="font-bold text-lg mb-4 text-indigo-900 border-b pb-2">Activity Timeline</h4>
                        <div class="relative border-l border-gray-200 ml-3 space-y-6 pb-4">
                            @foreach($detailTask->logs as $log)
                            <div class="relative pl-6">
                                <span class="absolute -left-1.5 top-1.5 w-3 h-3 rounded-full {{ str_contains($log->new_status, 'revision') ? 'bg-red-500' : (str_contains($log->new_status, 'ready') ? 'bg-green-500' : 'bg-indigo-500') }} ring-4 ring-white"></span>
                                <div class="flex justify-between items-start mb-1">
                                    <div class="font-bold text-slate-900 capitalize text-sm">{{ str_replace('_', ' ', $log->new_status) }}</div>
                                    <div class="text-xs text-slate-500 font-medium">{{ $log->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="text-slate-700 text-sm bg-gray-50 p-2 rounded border border-gray-100 mt-1">
                                    {{ $log->action_note }}
                                </div>
                                <div class="text-xs text-slate-400 mt-1 font-medium">By: {{ $log->user->name ?? 'System' }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
