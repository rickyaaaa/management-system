<div class="p-6">
    <!-- Top Software Bar -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-4 shadow-soft-sm flex items-center border border-gray-100 hover:shadow-soft transition cursor-pointer">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white font-bold shadow-soft-sm mr-4">
                B
            </div>
            <div>
                <h4 class="font-bold text-soft-dark text-sm">Blender</h4>
                <p class="text-xs text-gray-400">3.6.0 Stable</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-soft-sm flex items-center border border-gray-100 hover:shadow-soft transition cursor-pointer">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center text-white font-bold shadow-soft-sm mr-4">
                M
            </div>
            <div>
                <h4 class="font-bold text-soft-dark text-sm">Maya</h4>
                <p class="text-xs text-gray-400">2024.1 SDK</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-soft-sm flex items-center border border-gray-100 hover:shadow-soft transition cursor-pointer">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center text-white font-bold shadow-soft-sm mr-4">
                S
            </div>
            <div>
                <h4 class="font-bold text-soft-dark text-sm">Substance</h4>
                <p class="text-xs text-gray-400">Adobe Painter</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-soft-sm flex items-center border border-gray-100 hover:shadow-soft transition cursor-pointer">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center text-white font-bold shadow-soft-sm mr-4">
                U
            </div>
            <div>
                <h4 class="font-bold text-soft-dark text-sm">Unreal Eng.</h4>
                <p class="text-xs text-gray-400">5.3 Preview</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Left Column -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Active Tasks (Pending/In Progress) -->
            <div>
                <div class="flex justify-between items-end mb-4">
                    <h3 class="text-xl font-bold text-soft-dark">Active Tasks</h3>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest bg-gray-100 px-3 py-1 rounded-lg">Filter: In Progress</span>
                </div>
                
                <div class="space-y-4">
                    @php
                        $activeTasks = $tasks->whereIn('status', ['pending', 'in_progress']);
                    @endphp
                    @forelse($activeTasks as $task)
                        <div wire:key="task-{{ $task->id }}" class="bg-white border {{ $selectedTaskId === $task->id ? 'border-purple-300 ring-4 ring-purple-50' : 'border-gray-100' }} rounded-2xl p-5 shadow-soft hover:shadow-soft-hover transition group">
                            <div class="flex justify-between items-start">
                                <div class="flex items-start">
                                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex-shrink-0 shadow-soft-sm mt-1 mr-4 flex items-center justify-center text-white opacity-90 group-hover:opacity-100 transition">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-soft-dark">{{ $task->title }}</h4>
                                        <p class="text-sm text-gray-500 mt-1 mb-2">Target Version: v{{ $task->submissions()->count() + 1 }} • {{ Str::limit($task->description, 50) }}</p>
                                        <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-bold uppercase tracking-wider">{{ auth()->user()->role_specialty }}</span>
                                    </div>
                                </div>
                                <div class="text-right flex flex-col items-end">
                                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-bold uppercase mb-3 flex items-center">
                                        <span class="w-2 h-2 rounded-full bg-purple-500 mr-2 animate-pulse"></span>
                                        {{ str_replace('_', ' ', $task->status) }}
                                    </span>
                                    @php
                                        $dl = \Carbon\Carbon::parse($task->deadline);
                                        $isUrgent = $dl->diffInHours(now()) <= 24;
                                    @endphp
                                    <p class="text-xs font-semibold {{ $isUrgent ? 'text-red-500' : 'text-gray-400' }}">Due {{ $dl->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-50 flex justify-between items-center">
                                <div class="flex-1 mr-6">
                                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                                        <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-1.5 rounded-full" style="width: 45%"></div>
                                    </div>
                                </div>
                                <button wire:click="selectTask('{{ $task->id }}')" class="px-5 py-2 bg-gradient-to-tl from-purple-700 to-pink-500 text-white text-sm font-bold rounded-xl shadow-soft-sm hover:shadow-soft transition">
                                    {{ $task->status === 'pending' ? 'Accept & Work' : 'Submit Work' }}
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="bg-gray-50 rounded-2xl p-8 text-center border border-dashed border-gray-200">
                            <p class="text-gray-400 font-medium">No active tasks at the moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Returned for Revision -->
            <div>
                <div class="flex justify-between items-end mb-4 mt-8">
                    <h3 class="text-xl font-bold text-red-500">Returned for Revision</h3>
                </div>
                
                <div class="space-y-4">
                    @php
                        $revisionTasks = $tasks->where('status', 'revision');
                    @endphp
                    @forelse($revisionTasks as $task)
                        <div wire:key="rev-task-{{ $task->id }}" class="bg-white border-l-4 border-red-400 rounded-r-2xl p-5 shadow-soft hover:shadow-soft-hover transition flex justify-between items-center group {{ $selectedTaskId === $task->id ? 'ring-4 ring-red-50' : '' }}">
                            <div class="flex items-start flex-1">
                                <div class="w-16 h-16 rounded-xl bg-red-50 flex-shrink-0 shadow-inner mt-1 mr-4 flex items-center justify-center text-red-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-soft-dark mb-1">{{ $task->title }}</h4>
                                    <span class="px-2 py-0.5 border border-gray-200 text-gray-500 rounded text-xs font-bold uppercase tracking-wider mb-2 inline-block">{{ auth()->user()->role_specialty }}</span>
                                    
                                    <div class="bg-red-50 border border-red-100 text-red-800 text-sm p-3 rounded-lg mt-2 relative">
                                        <span class="font-bold">Revision Required:</span> "{{ $task->logs->where('new_status', 'revision')->first()->action_note ?? 'Please review the file and submit again.' }}"
                                    </div>
                                </div>
                            </div>
                            <div class="ml-6 pl-6 border-l border-gray-100 flex flex-col items-center">
                                <button wire:click="selectTask('{{ $task->id }}')" class="p-3 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-xl transition shadow-sm group-hover:shadow-soft">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <span class="text-[10px] font-bold text-gray-400 mt-2 uppercase">Fix Now</span>
                            </div>
                        </div>
                    @empty
                        <div class="bg-green-50 rounded-2xl p-6 text-center border border-dashed border-green-200">
                            <p class="text-green-600 font-medium">All clear! No tasks need revision.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Sidebar Area -->
        <div class="space-y-6">
            <!-- Pipeline Health Widget -->
            <div class="bg-white rounded-2xl p-5 shadow-soft border border-gray-50">
                <h4 class="font-bold text-soft-dark flex items-center mb-4">
                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Pipeline Health
                </h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Render Nodes Online</span>
                        <span class="font-bold text-soft-primary">142/150</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Active Sync Streams</span>
                        <span class="font-bold text-gray-700">12 GB/s</span>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-2">Asset Verification</span>
                    <div class="flex space-x-2">
                        <div class="w-8 h-8 rounded bg-green-50 text-green-500 flex items-center justify-center shadow-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                        <div class="w-8 h-8 rounded bg-green-50 text-green-500 flex items-center justify-center shadow-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                        <div class="w-8 h-8 rounded bg-orange-50 text-orange-500 flex items-center justify-center shadow-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg></div>
                        <div class="w-8 h-8 rounded bg-gray-50 text-gray-400 flex items-center justify-center shadow-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"></path></svg></div>
                    </div>
                </div>
            </div>

            <!-- Active Submission Widget -->
            @if($selectedTaskId)
                @php $activeSubmitTask = $tasks->find($selectedTaskId); @endphp
                <div class="bg-gradient-to-b from-gray-800 to-gray-900 rounded-2xl shadow-soft-hover border-0 overflow-hidden transform transition-all duration-300">
                    <div class="p-5 border-b border-gray-700">
                        <div class="flex items-center text-gray-300 text-xs font-bold uppercase tracking-widest mb-3">
                            <svg class="w-4 h-4 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Submission Protocol
                        </div>
                        <h4 class="font-bold text-white text-lg">{{ $activeSubmitTask->title }}</h4>
                        <p class="text-gray-400 text-xs mt-1">LOD 0 (Hero Asset) • QA Ready</p>
                    </div>
                    <div class="p-5">
                        <form wire:submit="submitWork" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Source File (.blend)</label>
                                <div class="relative">
                                    <input wire:model="file_blend" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept=".blend" required />
                                    <div class="w-full bg-gray-700 hover:bg-gray-600 transition rounded-xl p-3 flex flex-col items-center justify-center border border-gray-600 border-dashed text-gray-300 text-sm font-medium">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                            {{ $file_blend ? 'Blend file selected' : 'Drop .blend file here' }}
                                        </div>
                                        @if($file_blend)
                                            <span class="text-[10px] text-gray-400 mt-1 truncate max-w-full px-2" title="{{ $file_blend->getClientOriginalName() }}">{{ $file_blend->getClientOriginalName() }}</span>
                                        @endif
                                    </div>
                                </div>
                                @error('file_blend') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Preview Media (.mov/.mp4)</label>
                                <div class="relative">
                                    <input wire:model="file_mov" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="video/mp4,video/quicktime" required />
                                    <div class="w-full bg-gray-700 hover:bg-gray-600 transition rounded-xl p-3 flex flex-col items-center justify-center border border-gray-600 border-dashed text-gray-300 text-sm font-medium">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                            {{ $file_mov ? 'Video selected' : 'Drop render video here' }}
                                        </div>
                                        @if($file_mov)
                                            <span class="text-[10px] text-gray-400 mt-1 truncate max-w-full px-2" title="{{ $file_mov->getClientOriginalName() }}">{{ $file_mov->getClientOriginalName() }}</span>
                                        @endif
                                    </div>
                                </div>
                                @error('file_mov') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Change Log / Notes</label>
                                <textarea wire:model="notes" class="w-full bg-gray-700 text-gray-200 border-gray-600 rounded-xl focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50 text-sm p-3 placeholder-gray-500" rows="2" placeholder="Describe fixes or updates..."></textarea>
                            </div>

                            <div wire:loading wire:target="submitWork" class="text-purple-400 text-xs font-bold text-center w-full block animate-pulse">
                                Encrypting & Uploading files...
                            </div>

                            <button type="submit" wire:loading.attr="disabled" class="w-full py-3 bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-400 hover:to-indigo-400 text-white font-bold rounded-xl shadow-lg transition transform hover:-translate-y-0.5">
                                Send to Reviewer
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Active Workflow Dummy (when no task selected) -->
                <div class="bg-gray-900 rounded-2xl p-5 shadow-soft border border-gray-800">
                    <div class="flex justify-between items-center mb-6">
                        <h4 class="font-bold text-white">Active Workflow</h4>
                        <span class="text-xs text-gray-500 font-mono">v.042.88</span>
                    </div>
                    
                    <div class="relative pt-2 pb-6 px-2">
                        <!-- Timeline lines -->
                        <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gray-800"></div>
                        <div class="absolute left-6 top-6 h-12 w-0.5 bg-purple-500 shadow-[0_0_8px_rgba(168,85,247,0.8)] z-0"></div>
                        
                        <div class="space-y-6 relative z-10">
                            <div class="flex items-center">
                                <span class="text-xs font-bold text-gray-500 w-8">RIG</span>
                                <div class="w-3 h-3 rounded bg-gray-700 ml-2 border border-gray-600"></div>
                            </div>
                            <div class="flex items-center relative">
                                <span class="text-xs font-bold text-white w-8">ANI</span>
                                <div class="w-3 h-3 transform rotate-45 bg-orange-400 ml-2 shadow-[0_0_8px_rgba(251,146,60,0.6)]"></div>
                                <div class="absolute left-20 w-3 h-3 transform rotate-45 bg-purple-400 shadow-[0_0_8px_rgba(192,132,252,0.6)]"></div>
                                <div class="absolute left-20 -top-4 bg-purple-600 text-[9px] font-bold text-white px-1 rounded">24:12</div>
                            </div>
                            <div class="flex items-center">
                                <span class="text-xs font-bold text-gray-500 w-8">TEX</span>
                                <div class="w-3 h-3 rounded bg-gray-700 ml-2 border border-gray-600"></div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-2 border-t border-gray-800 pt-4">
                        <p class="text-xs text-gray-500 italic">Select a task on the left to begin submission.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
