<div>
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <a href="{{ route('dashboard') }}" wire:navigate class="mr-4 p-2.5 bg-white rounded-xl shadow-soft-sm text-gray-400 hover:text-purple-600 transition border border-gray-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h3 class="text-xl font-bold text-soft-dark">Task Management</h3>
                <p class="text-sm text-gray-400 mt-1">Create, filter, and track all production tasks</p>
            </div>
        </div>
        <button wire:click="openCreate" class="bg-gradient-to-tl from-purple-700 to-pink-500 hover:from-purple-600 hover:to-pink-400 text-white px-5 py-2.5 rounded-xl font-semibold shadow-soft-sm hover:shadow-soft transition flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            New Task
        </button>
    </div>

    <!-- Status Tabs -->
    <div class="flex flex-wrap gap-2 mb-6">
        @php
            $tabStyles = [
                'all' => 'from-gray-700 to-gray-500',
                'pending' => 'from-gray-400 to-gray-300',
                'in_progress' => 'from-blue-600 to-blue-400',
                'awaiting_review' => 'from-orange-500 to-yellow-400',
                'revision' => 'from-red-500 to-red-400',
                'ready_for_admin' => 'from-purple-600 to-purple-400',
                'completed' => 'from-green-600 to-green-400',
            ];
            $tabLabels = [
                'all' => 'All',
                'pending' => 'Pending',
                'in_progress' => 'In Progress',
                'awaiting_review' => 'Awaiting Review',
                'revision' => 'Revision',
                'ready_for_admin' => 'Ready for Admin',
                'completed' => 'Completed',
            ];
        @endphp
        @foreach($tabLabels as $key => $label)
            <button wire:click="$set('filterStatus', '{{ $key }}')"
                class="px-4 py-2 rounded-xl text-sm font-bold transition shadow-sm
                {{ $filterStatus === $key 
                    ? 'bg-gradient-to-tl ' . $tabStyles[$key] . ' text-white shadow-soft-sm' 
                    : 'bg-white text-gray-500 hover:bg-gray-50 border border-gray-200' }}">
                {{ $label }}
                <span class="ml-1 {{ $filterStatus === $key ? 'text-white opacity-80' : 'text-gray-400' }}">{{ $statusCounts[$key] }}</span>
            </button>
        @endforeach
    </div>

    <!-- Search & Filter Bar -->
    <div class="flex flex-col md:flex-row gap-3 mb-6">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search task by name..."
            class="flex-1 border-gray-200 rounded-xl shadow-soft-sm focus:border-soft-primary focus:ring focus:ring-soft-primary focus:ring-opacity-30 text-sm px-4 py-3" />
        <select wire:model.live="filterAssignee"
            class="w-full md:w-56 border-gray-200 rounded-xl shadow-soft-sm focus:border-soft-primary focus:ring focus:ring-soft-primary focus:ring-opacity-30 text-sm px-4 py-3">
            <option value="">All Assignees</option>
            @foreach($productionUsers as $user)
                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role_specialty }})</option>
            @endforeach
        </select>
    </div>

    <!-- Tasks Table -->
    <div class="bg-white rounded-2xl shadow-soft border-0 overflow-hidden mb-8">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-wider">
                    <th class="px-6 py-4">Task</th>
                    <th class="px-6 py-4">Assignee</th>
                    <th class="px-6 py-4">Priority</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Deadline</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tasks as $task)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="font-bold text-soft-dark text-sm">{{ $task->title }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">v{{ $task->version }} • {{ Str::limit($task->description, 40) }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-tl from-purple-700 to-pink-500 text-white flex items-center justify-center font-bold text-xs shadow-sm mr-2">
                                {{ substr($task->assignee->name ?? '?', 0, 1) }}
                            </div>
                            <span class="text-sm font-medium text-gray-600">{{ $task->assignee->name ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold 
                            {{ $task->priority === 'high' ? 'bg-red-100 text-red-600' : ($task->priority === 'normal' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-500') }}">
                            {{ strtoupper($task->priority) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $statusColors = [
                                'pending' => 'text-gray-500 bg-gray-400',
                                'in_progress' => 'text-blue-500 bg-blue-500',
                                'awaiting_review' => 'text-orange-500 bg-orange-500',
                                'revision' => 'text-red-500 bg-red-500',
                                'ready_for_admin' => 'text-purple-500 bg-purple-500',
                                'completed' => 'text-green-500 bg-green-500',
                            ];
                            $dotColor = $statusColors[$task->status]['bg'] ?? 'bg-gray-400';
                            $textColor = explode(' ', $statusColors[$task->status] ?? 'text-gray-500')[0];
                        @endphp
                        <div class="flex items-center text-sm font-bold {{ $textColor }}">
                            <span class="w-2 h-2 rounded-full mr-2 {{ explode(' ', $statusColors[$task->status] ?? 'bg-gray-400 text-gray-500')[1] }}"></span>
                            {{ strtoupper(str_replace('_', ' ', $task->status)) }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $dl = \Carbon\Carbon::parse($task->deadline);
                            $isUrgent = !$dl->isPast() && $dl->diffInHours(now()) <= 24 && $task->status !== 'completed';
                            $isOverdue = $dl->isPast() && $task->status !== 'completed';
                        @endphp
                        <span class="text-sm font-medium {{ $isOverdue ? 'text-red-500' : ($isUrgent ? 'text-orange-500' : 'text-gray-500') }}">
                            {{ $dl->format('M d, Y') }}
                            @if($isOverdue) <span class="text-xs">(Overdue)</span> @endif
                            @if($isUrgent && !$isOverdue) <span class="text-xs">(Urgent)</span> @endif
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        @if($task->status === 'ready_for_admin')
                            <button wire:click="markAsCompleted('{{ $task->id }}')" class="text-green-500 hover:text-green-700 font-bold text-sm transition">✓ Approve</button>
                        @endif
                        <button wire:click="viewDetails('{{ $task->id }}')" class="text-gray-400 hover:text-soft-primary transition font-semibold text-sm">Details</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        No tasks match your filters.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
            <span class="text-sm text-gray-500 font-medium">Showing {{ $tasks->count() }} tasks</span>
        </div>
    </div>

    <!-- Create Task Modal -->
    @if($showCreateModal)
    <div wire:click.self="$set('showCreateModal', false)" class="fixed inset-0 z-50 bg-gray-900 bg-opacity-40 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-2xl shadow-soft-hover border-0">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-soft-dark">Create New Task</h3>
                <button wire:click="$set('showCreateModal', false)" class="text-gray-400 hover:text-soft-primary transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6">
                <form wire:submit="createTask" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Task Title</label>
                        <input wire:model="title" type="text" class="block w-full border-gray-200 rounded-xl shadow-sm focus:border-soft-primary focus:ring focus:ring-soft-primary focus:ring-opacity-30 text-sm" required />
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea wire:model="description" class="block w-full border-gray-200 rounded-xl shadow-sm focus:border-soft-primary focus:ring focus:ring-soft-primary focus:ring-opacity-30 text-sm" rows="3"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Assign To</label>
                            <select wire:model="assignee_id" class="block w-full border-gray-200 rounded-xl shadow-sm focus:border-soft-primary focus:ring focus:ring-soft-primary focus:ring-opacity-30 text-sm" required>
                                <option value="">Select...</option>
                                @foreach($productionUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role_specialty }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                            <input wire:model="deadline" type="datetime-local" class="block w-full border-gray-200 rounded-xl shadow-sm focus:border-soft-primary focus:ring focus:ring-soft-primary focus:ring-opacity-30 text-sm" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                            <select wire:model="priority" class="block w-full border-gray-200 rounded-xl shadow-sm focus:border-soft-primary focus:ring focus:ring-soft-primary focus:ring-opacity-30 text-sm" required>
                                <option value="low">Low</option>
                                <option value="normal">Normal</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 space-x-3">
                        <button type="button" wire:click="$set('showCreateModal', false)" class="px-5 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm transition">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-tl from-purple-700 to-pink-500 text-white font-bold rounded-xl shadow-soft-sm hover:shadow-soft transition">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Task Details Modal -->
    @if($detailTask)
    <div wire:click.self="closeDetails" class="fixed inset-0 z-50 bg-gray-900 bg-opacity-40 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-5xl max-h-[90vh] flex flex-col shadow-soft-hover border-0">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-soft-dark">{{ $detailTask->title }} <span class="text-sm font-normal text-gray-400">v{{ $detailTask->version }}</span></h3>
                <button wire:click="closeDetails" class="text-gray-400 hover:text-soft-primary transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto flex-1 bg-soft-bg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Submissions -->
                    <div class="bg-white p-5 rounded-2xl shadow-soft-sm border-0">
                        <h4 class="font-bold text-lg mb-4 text-soft-dark border-b border-gray-100 pb-2">Submission History</h4>
                        @if($detailTask->submissions->count() > 0)
                            <div class="space-y-4">
                                @foreach($detailTask->submissions as $sub)
                                <div class="bg-gray-50 border-0 rounded-xl p-4 shadow-soft-sm">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-bold text-soft-primary bg-purple-50 px-3 py-1 rounded-lg text-sm">Version {{ $sub->version }}</span>
                                        <span class="text-xs text-gray-500">{{ $sub->created_at->format('M d, Y H:i') }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600 italic mb-3">"{{ $sub->notes ?: 'No notes' }}"</p>
                                    <div class="flex space-x-4 text-sm font-medium">
                                        <a href="{{ route('secure.file', [$sub->id, 'mov']) }}" target="_blank" class="text-soft-primary hover:text-purple-800 transition">▶ Video</a>
                                        <a href="{{ route('secure.file', [$sub->id, 'blend']) }}" download class="text-soft-info hover:text-cyan-600 transition">↓ .blend</a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-400 italic text-center py-4">No submissions yet.</p>
                        @endif
                    </div>

                    <!-- Timeline -->
                    <div class="bg-white p-5 rounded-2xl shadow-soft-sm border-0">
                        <h4 class="font-bold text-lg mb-4 text-soft-dark border-b border-gray-100 pb-2">Activity Timeline</h4>
                        <div class="relative border-l-2 border-gray-100 ml-3 space-y-6 pb-4">
                            @foreach($detailTask->logs as $log)
                            <div class="relative pl-6">
                                <span class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full {{ str_contains($log->new_status, 'revision') ? 'bg-red-500' : (str_contains($log->new_status, 'completed') ? 'bg-green-500' : 'bg-soft-primary') }} ring-4 ring-white shadow-soft"></span>
                                <div class="flex justify-between items-start mb-1">
                                    <div class="font-bold text-soft-dark capitalize text-sm">{{ str_replace('_', ' ', $log->new_status) }}</div>
                                    <div class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="text-gray-600 text-sm bg-gray-50 p-3 rounded-xl mt-1">{{ $log->action_note }}</div>
                                <div class="text-xs text-gray-400 mt-1">By: {{ $log->user->name ?? 'System' }}</div>
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
