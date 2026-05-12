<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Production Dashboard (Level 2)</h2>

    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold mb-4">My Tasks (Action Required)</h3>
            <div class="space-y-4">
                @forelse($tasks as $task)
                    <div class="border rounded-lg p-4 {{ $selectedTaskId === $task->id ? 'border-indigo-500 bg-indigo-50' : '' }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-lg font-bold">{{ $task->title }}</h4>
                                @php
                                    $deadlineDate = \Carbon\Carbon::parse($task->deadline);
                                    $isWarning = $deadlineDate->isPast() || $deadlineDate->diffInHours(now()) <= 24;
                                @endphp
                                <p class="text-sm {{ $isWarning && $task->status !== 'completed' ? 'text-red-600 font-bold animate-pulse' : 'text-gray-600' }}">
                                    Deadline: {{ $task->deadline }} @if($isWarning && $task->status !== 'completed') ⚠️ (Hurry Up!) @endif | Priority: <span class="capitalize">{{ $task->priority }}</span>
                                </p>
                                <div class="mt-2">
                                    <span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded font-bold text-xs border border-indigo-200 shadow-sm">Target Version: v{{ $task->submissions()->count() + 1 }}</span>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold capitalize">{{ str_replace('_', ' ', $task->status) }}</span>
                        </div>
                        <p class="mt-2 text-sm text-gray-700">{{ $task->description }}</p>
                        
                        <div class="mt-4">
                            <x-primary-button wire:click="selectTask('{{ $task->id }}')" type="button">
                                Submit Work
                            </x-primary-button>
                        </div>
                        <livewire:global-tracking :task="$task" :key="'track-'.$task->id" />
                    </div>
                @empty
                    <p class="text-gray-500 py-4 text-center">No pending tasks.</p>
                @endforelse
            </div>
        </div>

        @if($selectedTaskId)
        <div class="bg-white rounded-lg shadow p-6 h-fit sticky top-6">
            <h3 class="text-xl font-semibold mb-4">Submit Work</h3>
            <form wire:submit="submitWork" class="space-y-4">
                <div>
                    <x-input-label for="file_blend" :value="__('File Mentah (.blend)')" />
                    <input wire:model="file_blend" id="file_blend" type="file" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept=".blend" required />
                    <x-input-error :messages="$errors->get('file_blend')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="file_mov" :value="__('File Preview Video (.mov / .mp4)')" />
                    <input wire:model="file_mov" id="file_mov" type="file" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="video/mp4,video/quicktime" required />
                    <x-input-error :messages="$errors->get('file_mov')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="notes" :value="__('Catatan (Opsional)')" />
                    <textarea wire:model="notes" id="notes" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3"></textarea>
                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                </div>

                <div wire:loading wire:target="submitWork" class="text-indigo-600 text-sm font-semibold">
                    Uploading files, please wait...
                </div>

                <x-primary-button class="mt-4" wire:loading.attr="disabled">
                    {{ __('Upload & Submit') }}
                </x-primary-button>
            </form>
        </div>
        @endif
    </div>
</div>
