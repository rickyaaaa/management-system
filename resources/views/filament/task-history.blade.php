<div class="space-y-3 p-2 text-sm text-gray-700">
    @forelse($task->logs()->latest()->get() as $log)
        <div class="flex items-start gap-3 border-b pb-3">
            <div class="mt-1 h-2 w-2 rounded-full bg-blue-500 flex-shrink-0"></div>
            <div>
                <div class="font-semibold">
                    {{ $log->new_status }}
                    @if($log->previous_status)
                        <span class="text-gray-400 font-normal">(from {{ $log->previous_status }})</span>
                    @endif
                </div>
                @if($log->action_note)
                    <div class="text-gray-500">{{ $log->action_note }}</div>
                @endif
                <div class="text-xs text-gray-400">{{ $log->created_at->format('d M Y H:i') }} by {{ optional($log->user)->username ?? 'System' }}</div>
            </div>
        </div>
    @empty
        <p class="text-gray-400">No history yet.</p>
    @endforelse
</div>
