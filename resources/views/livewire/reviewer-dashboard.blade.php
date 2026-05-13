<div class="p-6">
    <!-- Top Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 shadow-soft border border-gray-50 flex flex-col justify-between">
            <h4 class="text-sm font-bold text-gray-400 mb-4">Pending Approval</h4>
            <div class="flex items-end justify-between">
                <span class="text-4xl font-extrabold text-soft-dark">{{ $tasks->count() }}</span>
                <span class="text-sm font-bold text-green-500 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    +12%
                </span>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-soft border border-gray-50 flex flex-col justify-between">
            <h4 class="text-sm font-bold text-gray-400 mb-4">Revision Rate</h4>
            <div class="flex items-end justify-between">
                <span class="text-4xl font-extrabold text-orange-500">14.2%</span>
                <span class="text-sm font-bold text-gray-400 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                    -2%
                </span>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-soft border border-gray-50 flex flex-col justify-between">
            <h4 class="text-sm font-bold text-gray-400 mb-4">Avg. Turnaround</h4>
            <div class="flex items-end justify-between">
                <span class="text-4xl font-extrabold text-soft-primary">4.2h</span>
                <span class="text-sm font-bold text-gray-400">Target: 6h</span>
            </div>
        </div>
        
        <!-- Urgent Warning Card -->
        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl p-5 shadow-soft border border-red-200">
            <div class="flex justify-between items-start mb-4">
                <h4 class="text-sm font-bold text-red-700 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Urgent Revisions
                </h4>
                <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider">Action Req.</span>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between items-center text-sm border-b border-red-200 pb-2">
                    <span class="font-semibold text-red-900 truncate pr-2">Hero_Cape_Sim</span>
                    <span class="text-red-600 font-bold text-xs whitespace-nowrap">2h overdue</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="font-semibold text-red-900 truncate pr-2">Lighting_SceneB</span>
                    <span class="text-red-500 font-bold text-xs whitespace-nowrap">45m left</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Left Column -->
        <div class="lg:col-span-2">
            
            @if(!$selectedReviewTaskId)
                <!-- Queue View -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-soft-dark">Pending Review Queue</h3>
                    <div class="flex items-center space-x-3 text-sm">
                        <span class="text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Last synced: 1m ago</span>
                        <button class="text-gray-400 hover:text-soft-primary"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg></button>
                        <button class="text-soft-primary"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg></button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($tasks as $task)
                        @php $latestSub = $task->submissions->first(); @endphp
                        <div class="bg-white rounded-2xl shadow-soft border-0 overflow-hidden hover:shadow-soft-hover transition group cursor-pointer" wire:click="selectTask('{{ $task->id }}')">
                            <!-- Thumbnail Placeholder (Gradient) -->
                            <div class="h-40 bg-gradient-to-br from-indigo-900 via-purple-900 to-black relative p-4 flex flex-col justify-between group-hover:opacity-90 transition">
                                <div class="flex justify-between items-start">
                                    <span class="bg-white text-indigo-900 text-xs font-bold px-2 py-1 rounded uppercase tracking-wider shadow-sm">{{ $task->assignee->role_specialty ?? 'Task' }}</span>
                                    <span class="bg-black bg-opacity-50 text-white text-xs font-mono px-2 py-1 rounded backdrop-blur-sm">v{{ $latestSub->version ?? '1' }}</span>
                                </div>
                                <div class="flex justify-center items-center absolute inset-0">
                                    <div class="w-12 h-12 bg-white bg-opacity-20 backdrop-blur-md rounded-full flex items-center justify-center transform scale-0 group-hover:scale-100 transition duration-300">
                                        <svg class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-5">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-bold text-soft-dark text-lg truncate pr-2">{{ $task->title }}</h4>
                                    <span class="px-2 py-1 bg-{{ $task->priority === 'high' ? 'red' : 'gray' }}-100 text-{{ $task->priority === 'high' ? 'red' : 'gray' }}-600 text-[10px] font-bold rounded uppercase mt-1">{{ $task->priority }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-500 mb-4">
                                    <div class="w-6 h-6 rounded-full bg-gradient-to-tl from-purple-700 to-pink-500 text-white flex items-center justify-center font-bold text-[10px] mr-2">
                                        {{ substr($task->assignee->name ?? '?', 0, 1) }}
                                    </div>
                                    {{ $task->assignee->name ?? 'Unknown' }}
                                </div>
                                <div class="flex space-x-3">
                                    <button class="flex-1 py-2 rounded-xl border border-gray-200 text-gray-600 font-semibold text-sm hover:bg-gray-50 transition">Review Task</button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 bg-gray-50 rounded-2xl p-12 text-center border border-dashed border-gray-200">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-700">Queue is empty</h3>
                            <p class="text-gray-500 mt-1">All tasks have been reviewed. Great job!</p>
                        </div>
                    @endforelse
                </div>
            @else
                <!-- Detail View (Video Player & Actions) -->
                @php
                    $task = $tasks->find($selectedReviewTaskId);
                    $latestSub = $task->submissions->first();
                    $viewingId = $viewingSubmissionId[$task->id] ?? ($latestSub->id ?? null);
                    $activeSubmission = $task->submissions->firstWhere('id', $viewingId);
                @endphp
                
                <div class="flex justify-between items-center mb-4">
                    <button wire:click="closeTask" class="flex items-center text-gray-500 hover:text-soft-primary font-semibold text-sm transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Queue
                    </button>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-bold text-soft-dark">{{ $task->title }}</span>
                        <span class="px-2 py-0.5 bg-gray-200 text-gray-700 text-xs font-mono rounded">v{{ $activeSubmission->version ?? '?' }}</span>
                    </div>
                </div>

                <div class="bg-black rounded-2xl shadow-soft overflow-hidden mb-6">
                    @if($activeSubmission)
                    <div wire:key="video-player-{{ $activeSubmission->id }}"
                         x-data="{
                            player: null,
                            init() {
                                this.player = new Plyr(this.$refs.video, {
                                    controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'settings', 'pip', 'airplay', 'fullscreen'],
                                    settings: ['speed'],
                                    speed: { selected: 1, options: [0.25, 0.5, 0.75, 1, 1.25, 1.5, 2] }
                                });
                            }
                         }">
                        <video x-ref="video" class="w-full" preload="metadata" crossorigin playsinline controls>
                            <source src="{{ route('secure.file', [$activeSubmission->id, 'mov']) }}" type="video/mp4">
                        </video>
                    </div>
                    @else
                    <div class="h-64 flex items-center justify-center text-gray-500">No video found.</div>
                    @endif
                </div>

                <!-- Version Selector & Notes -->
                @if($task->submissions->count() > 1)
                <div class="flex items-center space-x-2 mb-6 overflow-x-auto pb-2">
                    <span class="text-sm font-semibold text-gray-600 mr-2">Version History:</span>
                    @foreach($task->submissions as $sub)
                    <button wire:click="switchVersion('{{ $task->id }}', '{{ $sub->id }}')" 
                        class="px-4 py-1.5 text-sm rounded-xl font-bold transition-colors border {{ $viewingId == $sub->id ? 'bg-soft-dark text-white border-soft-dark shadow-md' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
                        v{{ $sub->version }}
                    </button>
                    @endforeach
                </div>
                @endif

                <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 mb-6 flex">
                    <div class="mr-4 mt-1 text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-700 mb-1">Notes from Production (v{{ $activeSubmission->version }})</h4>
                        <p class="text-gray-600 text-sm italic">"{{ $activeSubmission->notes ?? 'No specific notes provided for this render.' }}"</p>
                    </div>
                </div>

                <!-- Pipeline Status Track (Action Bar) -->
                <h3 class="text-sm font-bold text-gray-400 mb-3 uppercase tracking-wider">Pipeline Status Track</h3>
                <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-2xl p-2 flex items-center justify-between shadow-soft-hover">
                    <div class="flex items-center text-white px-4">
                        <div class="w-10 h-10 bg-gray-700 rounded-xl flex items-center justify-center mr-3 shadow-inner">
                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Selected Task</p>
                            <p class="font-bold">{{ $task->title }} <span class="font-normal text-gray-400">v{{ $activeSubmission->version }}</span></p>
                        </div>
                    </div>
                    
                    <div class="flex space-x-2 pr-2">
                        <button wire:click="openRevisionModal('{{ $latestSub->id }}', '{{ $task->id }}')" class="px-6 py-3 bg-gray-700 hover:bg-red-600 text-white rounded-xl font-bold transition flex items-center text-sm shadow">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Request Revision
                        </button>
                        <button wire:click="approve('{{ $latestSub->id }}', '{{ $task->id }}')" class="px-6 py-3 bg-gradient-to-tl from-purple-600 to-indigo-500 hover:from-purple-500 hover:to-indigo-400 text-white rounded-xl font-bold transition flex items-center text-sm shadow-soft">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Submit Sign-off
                        </button>
                    </div>
                </div>

            @endif
        </div>

        <!-- Right Sidebar Area -->
        <div class="space-y-6">
            <!-- Artist Metrics -->
            <div class="bg-white rounded-2xl p-5 shadow-soft border border-gray-50">
                <h4 class="font-bold text-soft-dark mb-5 border-b border-gray-100 pb-3">Artist Metrics</h4>
                <div class="space-y-6">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold mr-3 shadow-sm border border-white">S</div>
                        <div class="flex-1">
                            <div class="flex justify-between items-end mb-1">
                                <span class="font-bold text-sm text-soft-dark">Sarah Jenkins</span>
                                <span class="text-xs font-mono font-bold text-green-500">98% Appr.</span>
                            </div>
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-2">Lighting Specialist • Eff: 1.2x</p>
                            <div class="w-full bg-gray-100 rounded-full h-1">
                                <div class="bg-blue-500 h-1 rounded-full" style="width: 98%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold mr-3 shadow-sm border border-white">D</div>
                        <div class="flex-1">
                            <div class="flex justify-between items-end mb-1">
                                <span class="font-bold text-sm text-soft-dark">David K.</span>
                                <span class="text-xs font-mono font-bold text-orange-500">82% Appr.</span>
                            </div>
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-2">FX Technical Dir. • Eff: 0.9x</p>
                            <div class="w-full bg-gray-100 rounded-full h-1">
                                <div class="bg-orange-500 h-1 rounded-full" style="width: 82%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center font-bold mr-3 shadow-sm border border-white">E</div>
                        <div class="flex-1">
                            <div class="flex justify-between items-end mb-1">
                                <span class="font-bold text-sm text-soft-dark">Elena Rodz</span>
                                <span class="text-xs font-mono font-bold text-green-500">94% Appr.</span>
                            </div>
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-2">Env Modeler • Eff: 1.1x</p>
                            <div class="w-full bg-gray-100 rounded-full h-1">
                                <div class="bg-purple-500 h-1 rounded-full" style="width: 94%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daily Throughput Graph -->
            <div class="bg-white rounded-2xl p-5 shadow-soft border border-gray-50">
                <h4 class="font-bold text-soft-dark mb-4">Daily Throughput</h4>
                <div class="grid grid-cols-7 gap-1.5 mb-3">
                    @for($i=0; $i<21; $i++)
                        @php
                            // random shades of purple/gray
                            $colors = ['bg-gray-100', 'bg-gray-100', 'bg-purple-200', 'bg-purple-300', 'bg-purple-500', 'bg-purple-700'];
                            $color = $colors[array_rand($colors)];
                        @endphp
                        <div class="aspect-square rounded-[3px] {{ $color }} cursor-pointer hover:ring-2 hover:ring-purple-300 transition" title="Activity score: {{ rand(1, 10) }}"></div>
                    @endfor
                </div>
                <p class="text-[10px] text-gray-400">Visualizing last 21 production sessions.</p>
            </div>
            
            <!-- Quick Actions (Download Links) -->
            @if($selectedReviewTaskId && isset($activeSubmission))
            <div class="bg-indigo-50 rounded-2xl p-5 border border-indigo-100">
                <h4 class="font-bold text-indigo-900 mb-3">Asset Downloads</h4>
                <div class="space-y-2">
                    <a href="{{ route('secure.file', [$activeSubmission->id, 'mov']) }}" download class="w-full px-4 py-2 bg-white hover:bg-gray-50 text-indigo-700 border border-indigo-200 rounded-xl text-sm font-bold transition shadow-sm flex justify-between items-center">
                        <span>Preview Video (.mov)</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </a>
                    <a href="{{ route('secure.file', [$activeSubmission->id, 'blend']) }}" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-sm transition text-sm flex justify-between items-center">
                        <span>Source File (.blend)</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Revision Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-50 bg-gray-900 bg-opacity-40 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-soft-hover border-0">
            <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                <h3 class="text-xl font-bold text-red-600 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Request Revision
                </h3>
                <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-1">Timestamp (Optional)</label>
                <input wire:model="reviewTimestamp" type="text" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" placeholder="e.g. 00:12" />
                <p class="text-[10px] text-gray-500 mt-1 uppercase">Helps specialist find the exact moment.</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-1">Feedback Details</label>
                <textarea wire:model="reviewNotes" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" rows="4" placeholder="Enter details about what needs to be fixed..."></textarea>
                @error('reviewNotes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button wire:click="$set('showModal', false)" class="px-5 py-2.5 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl font-bold text-sm transition">Cancel</button>
                <button wire:click="submitRevision" class="px-5 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 font-bold shadow-soft transition text-sm">Send Back for Fixes</button>
            </div>
        </div>
    </div>
    @endif
</div>
