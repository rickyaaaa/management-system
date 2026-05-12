<div class="flex items-center space-x-2 text-sm mt-4 p-4 bg-gray-50 rounded-lg overflow-x-auto">
    @php
        $steps = [
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'awaiting_review' => 'Review',
            'revision' => 'Revision',
            'ready_for_admin' => 'Final',
            'completed' => 'Completed'
        ];
        $currentStatus = $task->status;
        $statusIndex = array_search($currentStatus, array_keys($steps));
    @endphp

    @foreach($steps as $key => $label)
        @php
            $thisIndex = array_search($key, array_keys($steps));
            if ($currentStatus === 'revision' && $key === 'ready_for_admin') {
                $color = 'text-gray-400';
                $bg = 'bg-gray-200';
            } elseif ($currentStatus === 'revision' && $key === 'revision') {
                $color = 'text-red-600 font-bold';
                $bg = 'bg-red-200';
            } elseif ($currentStatus !== 'revision' && $key === 'revision') {
                $color = 'text-gray-400';
                $bg = 'bg-gray-200';
            } else {
                if ($thisIndex <= $statusIndex) {
                    $color = 'text-green-600 font-bold';
                    $bg = 'bg-green-200';
                } else {
                    $color = 'text-gray-400';
                    $bg = 'bg-gray-200';
                }
            }
        @endphp
        <div class="flex items-center">
            <div class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full {{ $bg }} {{ $color }}">
                {{ $loop->iteration }}
            </div>
            <span class="ml-2 whitespace-nowrap {{ $color }}">{{ $label }}</span>
            @if(!$loop->last)
                <div class="w-8 h-1 mx-2 flex-shrink-0 {{ ($thisIndex < $statusIndex && $key !== 'revision') || ($currentStatus === 'revision' && $thisIndex < array_search('awaiting_review', array_keys($steps))) ? 'bg-green-200' : 'bg-gray-200' }}"></div>
            @endif
        </div>
    @endforeach
</div>
