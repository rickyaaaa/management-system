<div class="p-6 relative">
    <h2 class="text-2xl font-bold mb-6">Reviewer Dashboard (Level 3)</h2>

    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50">
            {{ session('message') }}
        </div>
    @endif

    <div class="space-y-6">
        @forelse($tasks as $task)
            @php 
                $latestSubmission = $task->submissions->first(); 
                $viewingId = $viewingSubmissionId[$task->id] ?? ($latestSubmission->id ?? null);
                $activeSubmission = $task->submissions->firstWhere('id', $viewingId);
            @endphp
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-bold">{{ $task->title }} <span class="text-gray-500 font-normal text-sm">(Latest: v{{ $latestSubmission->version ?? $task->version }})</span></h3>
                        <p class="text-sm text-gray-600">Specialist: {{ $task->assignee->name ?? 'N/A' }}</p>
                    </div>
                </div>

                @if($activeSubmission)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-4">
                    <div class="lg:col-span-2">
                        @if($task->submissions->count() > 1)
                        <div class="mb-4 flex items-center space-x-2 overflow-x-auto pb-2">
                            <span class="text-sm font-semibold text-gray-600">View Version:</span>
                            @foreach($task->submissions as $sub)
                            <button wire:click="switchVersion('{{ $task->id }}', '{{ $sub->id }}')" 
                                class="px-3 py-1 text-sm rounded-full font-medium transition-colors border {{ $viewingId == $sub->id ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-gray-100 text-gray-700 border-gray-200 hover:bg-gray-200' }}">
                                v{{ $sub->version }}
                            </button>
                            @endforeach
                        </div>
                        @endif

                        <h4 class="font-semibold mb-2">Video Preview (v{{ $activeSubmission->version }}):</h4>
                        <div class="rounded-lg border bg-black shadow-sm overflow-hidden"
                             wire:key="video-player-{{ $activeSubmission->id }}"
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
                        
                        <div class="mt-4">
                            <h4 class="font-semibold">Notes from Production (v{{ $activeSubmission->version }}):</h4>
                            <p class="text-gray-700 bg-gray-50 p-3 rounded-md mt-1 shadow-inner border border-gray-100 italic">"{{ $activeSubmission->notes ?? 'No notes provided.' }}"</p>
                        </div>
                        
                        <div class="mt-6 flex flex-col space-y-3">
                            <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg border border-gray-200">
                                <div class="flex items-center text-gray-700">
                                    <svg class="w-6 h-6 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <div>
                                        <p class="font-bold">Video Preview (.mov)</p>
                                        <p class="text-xs text-gray-500">Size: {{ $activeSubmission->mov_size }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('secure.file', [$activeSubmission->id, 'mov']) }}" download class="px-4 py-2 bg-white hover:bg-gray-100 text-gray-800 border rounded text-sm font-semibold transition">Download</a>
                            </div>

                            <div class="flex items-center justify-between bg-indigo-50 p-3 rounded-lg border border-indigo-200">
                                <div class="flex items-center text-indigo-900">
                                    <svg class="w-6 h-6 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    <div>
                                        <p class="font-bold">Source File (.blend)</p>
                                        <p class="text-xs text-indigo-600">Size: {{ $activeSubmission->blend_size }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('secure.file', [$activeSubmission->id, 'blend']) }}" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded font-bold shadow transition transform hover:scale-105 text-sm">Download Source</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="lg:col-span-1 border-t lg:border-t-0 lg:border-l border-gray-100 lg:pl-6 pt-4 lg:pt-0">
                        <h4 class="font-semibold mb-4 text-indigo-900 border-b pb-2">QC Actions (Latest Version)</h4>
                        <div class="flex flex-col space-y-3">
                            <button wire:click="approve('{{ $latestSubmission->id }}', '{{ $task->id }}')" class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-semibold shadow-sm transition flex justify-center items-center">
                                Approve v{{ $latestSubmission->version }}
                            </button>
                            <button wire:click="openRevisionModal('{{ $latestSubmission->id }}', '{{ $task->id }}')" class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 font-semibold shadow-sm transition flex justify-center items-center">
                                Request Revision for v{{ $latestSubmission->version }}
                            </button>
                        </div>
                        
                        <div class="mt-8">
                            <h4 class="font-semibold mb-4 text-indigo-900 border-b pb-2">Activity Timeline</h4>
                            <div class="relative border-l border-gray-200 ml-3 space-y-6 pb-4 max-h-[300px] overflow-y-auto pr-2">
                                @foreach($task->logs as $log)
                                <div class="relative pl-6">
                                    <span class="absolute -left-1.5 top-1.5 w-3 h-3 rounded-full {{ str_contains($log->new_status, 'revision') ? 'bg-red-500' : (str_contains($log->new_status, 'ready') ? 'bg-green-500' : 'bg-indigo-500') }} ring-4 ring-white"></span>
                                    <div class="flex justify-between items-start mb-1">
                                        <div class="font-bold text-slate-900 capitalize text-sm">{{ str_replace('_', ' ', $log->new_status) }}</div>
                                    </div>
                                    <div class="text-xs text-slate-500 font-medium mb-1">{{ $log->created_at->diffForHumans() }} by {{ $log->user->name ?? 'System' }}</div>
                                    <div class="text-slate-700 text-sm bg-gray-50 p-2 rounded border border-gray-100">
                                        {{ $log->action_note }}
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-100">
                            <h4 class="font-semibold mb-2">Tracking Status</h4>
                            <livewire:global-tracking :task="$task" :key="'track-'.$task->id" />
                        </div>
                    </div>
                </div>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500 py-12">
                No tasks awaiting review.
            </div>
        @endforelse
    </div>

    <!-- Revision Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-lg shadow-xl">
            <h3 class="text-xl font-bold mb-4">Revision Notes</h3>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Timestamp (Optional)</label>
                <input wire:model="reviewTimestamp" type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="e.g. 00:12" />
                <p class="text-xs text-gray-500 mt-1">Helps specialist find the exact moment.</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Details</label>
                <textarea wire:model="reviewNotes" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500" rows="4" placeholder="Enter details about what needs to be revised..."></textarea>
                <x-input-error :messages="$errors->get('reviewNotes')" class="mt-2" />
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button wire:click="$set('showModal', false)" class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium">Cancel</button>
                <button wire:click="submitRevision" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 font-semibold shadow-sm transition">Submit Revision</button>
            </div>
        </div>
    </div>
    @endif
</div>
