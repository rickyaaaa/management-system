<div class="p-6">
    <!-- Analytics Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-gradient-to-tl from-purple-700 to-pink-500 rounded-2xl shadow-soft p-5 text-white transform transition hover:shadow-soft-hover hover:-translate-y-1">
            <h4 class="text-white text-sm font-semibold mb-1 opacity-80 uppercase tracking-wider">Active Tasks</h4>
            <p class="text-3xl font-bold">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-gradient-to-tl from-orange-500 to-yellow-400 rounded-2xl shadow-soft p-5 text-white transform transition hover:shadow-soft-hover hover:-translate-y-1">
            <h4 class="text-white text-sm font-semibold mb-1 opacity-80 uppercase tracking-wider">Awaiting Review</h4>
            <p class="text-3xl font-bold">{{ $stats['awaiting_review'] }}</p>
        </div>
        <div class="bg-gradient-to-tl from-green-600 to-green-400 rounded-2xl shadow-soft p-5 text-white transform transition hover:shadow-soft-hover hover:-translate-y-1">
            <h4 class="text-white text-sm font-semibold mb-1 opacity-80 uppercase tracking-wider">Completed</h4>
            <p class="text-3xl font-bold">{{ $stats['completed'] }}</p>
        </div>
        <div class="bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-2xl shadow-soft p-5 text-white transform transition hover:shadow-soft-hover hover:-translate-y-1">
            <h4 class="text-white text-sm font-semibold mb-1 opacity-80 uppercase tracking-wider">Top Specialist</h4>
            <p class="text-xl font-bold truncate mt-1">{{ $topSpecialist->name ?? 'N/A' }}</p>
            <p class="text-xs text-white opacity-80 mt-1">{{ $topSpecialist->assigned_tasks_count ?? 0 }} tasks completed</p>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-2xl shadow-soft border-0 flex flex-col mb-24">
        <!-- Card Header -->
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold text-soft-dark">Task Directory</h3>
                <p class="text-sm text-gray-400 mt-1">Manage production tasks and statuses</p>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-wider">
                        <th class="px-6 py-4">Task Name</th>
                        <th class="px-6 py-4">Assignee</th>
                        <th class="px-6 py-4">Priority</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tasks as $task)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-soft-dark">{{ $task->title }}</div>
                            <div class="text-xs text-gray-500">v{{ $task->version }} • Due {{ \Carbon\Carbon::parse($task->deadline)->format('M d') }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-600">
                            {{ $task->assignee->name ?? 'Unassigned' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold 
                                {{ $task->priority === 'high' ? 'bg-red-100 text-red-600' : ($task->priority === 'normal' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600') }}">
                                {{ strtoupper($task->priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center text-sm font-bold {{ $task->status === 'completed' ? 'text-green-500' : ($task->status === 'ready_for_admin' ? 'text-purple-500' : 'text-gray-500') }}">
                                <span class="w-2 h-2 rounded-full mr-2 {{ $task->status === 'completed' ? 'bg-green-500' : ($task->status === 'ready_for_admin' ? 'bg-purple-500' : 'bg-gray-400') }}"></span>
                                {{ strtoupper(str_replace('_', ' ', $task->status)) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($task->status === 'ready_for_admin')
                                <button wire:click="markAsCompleted('{{ $task->id }}')" class="mr-2 text-green-500 hover:text-green-700 font-bold text-sm transition" title="Mark Completed">✓ Approve</button>
                            @endif
                            <button wire:click="viewDetails('{{ $task->id }}')" class="text-gray-400 hover:text-soft-primary transition p-2" title="View Details">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 italic">No tasks found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination / Footer -->
        <div class="p-4 border-t border-gray-100 flex justify-between items-center bg-gray-50 rounded-b-2xl">
            <span class="text-sm text-gray-500 font-medium">Showing {{ $tasks->count() }} tasks</span>
            <div class="flex space-x-2">
                <button class="p-2 border border-gray-200 rounded-lg bg-white text-gray-400 hover:bg-gray-50">&lt;</button>
                <button class="p-2 border border-gray-200 rounded-lg bg-white text-gray-400 hover:bg-gray-50">&gt;</button>
            </div>
        </div>
    </div>

    <!-- Floating Action Bar -->
    <div class="fixed bottom-8 left-1/2 transform -translate-x-1/2 bg-white rounded-2xl shadow-soft-hover border border-gray-100 p-3 flex items-center space-x-4 z-30">
        <span class="text-sm font-bold text-gray-500 px-4 border-r border-gray-100">Admin Controls</span>
        <button wire:click="$toggle('showModal')" class="px-6 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-sm transition">Create New Task</button>
        <button wire:click="$refresh" class="px-6 py-2 rounded-xl bg-gradient-to-tl from-purple-700 to-pink-500 hover:from-purple-600 hover:to-pink-400 text-white font-bold text-sm shadow-soft-sm transition">Refresh Data</button>
    </div>

    <!-- Create Task Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-40 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-2xl shadow-soft-hover border-0">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-soft-dark">Create New Task</h3>
                <button wire:click="$toggle('showModal')" class="text-gray-400 hover:text-soft-primary transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6">
                <form wire:submit="createTask" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Task Title</label>
                        <input wire:model="title" type="text" class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:border-soft-primary focus:ring focus:ring-soft-primary focus:ring-opacity-50" required />
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea wire:model="description" class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:border-soft-primary focus:ring focus:ring-soft-primary focus:ring-opacity-50" rows="3" required></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Assign To</label>
                            <select wire:model="assignee_id" class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:border-soft-primary focus:ring focus:ring-soft-primary focus:ring-opacity-50" required>
                                <option value="">Select User...</option>
                                @foreach(\App\Models\User::where('role_level', 2)->get() as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Deadline</label>
                            <input wire:model="deadline" type="datetime-local" class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:border-soft-primary focus:ring focus:ring-soft-primary focus:ring-opacity-50" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Priority</label>
                            <select wire:model="priority" class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:border-soft-primary focus:ring focus:ring-soft-primary focus:ring-opacity-50" required>
                                <option value="low">Low</option>
                                <option value="normal">Normal</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-6 py-2 bg-gradient-to-tl from-purple-700 to-pink-500 text-white font-bold rounded-xl shadow-soft-sm hover:shadow-soft transition">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Task Details Modal -->
    @if($detailTask)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-40 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-5xl max-h-[90vh] flex flex-col shadow-soft-hover border-0">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-soft-dark">Task Details: {{ $detailTask->title }}</h3>
                <button wire:click="closeDetails" class="text-gray-400 hover:text-soft-primary transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto flex-1 bg-soft-bg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Submission History -->
                    <div class="bg-white p-5 rounded-2xl shadow-soft-sm border-0">
                        <h4 class="font-bold text-lg mb-4 text-soft-dark border-b border-gray-100 pb-2">Submission History</h4>
                        @if($detailTask->submissions->count() > 0)
                            <div class="space-y-4">
                                @foreach($detailTask->submissions as $sub)
                                <div class="bg-gray-50 border-0 rounded-xl p-4 shadow-soft-sm">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="font-bold text-soft-primary bg-purple-50 px-3 py-1 rounded-lg text-sm">Version {{ $sub->version }}</span>
                                        <span class="text-xs text-gray-500">{{ $sub->created_at->format('M d, Y H:i') }}</span>
                                    </div>
                                    <div class="text-sm text-gray-600 bg-white p-3 border-0 rounded-lg mb-3 shadow-soft-sm italic">
                                        "{{ $sub->notes ?: 'No notes provided.' }}"
                                    </div>
                                    <div class="flex flex-wrap gap-2 text-sm font-medium mt-1">
                                        @if($sub->blend_url)
                                        <a href="{{ route('secure.file', $sub->id) }}?type=blend" class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 px-3 py-1.5 text-indigo-700 hover:bg-indigo-100 transition font-semibold">
                                            📦 Download .blend
                                        </a>
                                        @endif
                                        @if($sub->video_url)
                                        <a href="{{ route('secure.file', $sub->id) }}?type=video" class="inline-flex items-center gap-1 rounded-lg bg-blue-50 px-3 py-1.5 text-blue-700 hover:bg-blue-100 transition font-semibold">
                                            🎬 Download Video
                                        </a>
                                        @endif
                                        @if(!$sub->blend_url && !$sub->video_url)
                                        <span class="text-gray-400 italic text-xs">Tidak ada file.</span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 italic py-4 text-center">No submissions yet.</p>
                        @endif
                    </div>

                    <!-- Tracking Logs Timeline -->
                    <div class="bg-white p-5 rounded-2xl shadow-soft-sm border-0">
                        <h4 class="font-bold text-lg mb-4 text-soft-dark border-b border-gray-100 pb-2">Activity Timeline</h4>
                        <div class="relative border-l-2 border-gray-100 ml-3 space-y-6 pb-4">
                            @foreach($detailTask->logs as $log)
                            <div class="relative pl-6">
                                <span class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full {{ str_contains($log->new_status, 'revision') ? 'bg-soft-danger' : (str_contains($log->new_status, 'ready') ? 'bg-soft-success' : 'bg-soft-primary') }} ring-4 ring-white shadow-soft"></span>
                                <div class="flex justify-between items-start mb-1">
                                    <div class="font-bold text-soft-dark capitalize text-sm">{{ str_replace('_', ' ', $log->new_status) }}</div>
                                    <div class="text-xs text-gray-500 font-medium">{{ $log->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="text-gray-600 text-sm bg-gray-50 p-3 rounded-xl border-0 mt-2 shadow-soft-sm">
                                    {{ $log->action_note }}
                                </div>
                                <div class="text-xs text-gray-400 mt-2 font-medium">By: {{ $log->user->name ?? 'System' }}</div>
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
