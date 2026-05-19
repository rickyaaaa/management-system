<div class="space-y-4 p-2 text-sm">

    {{-- Task Info --}}
    <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3 flex items-center justify-between">
        <div>
            <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-0.5">Task</div>
            <div class="font-bold text-gray-800">{{ $task->title }}</div>
        </div>
        <span class="text-xs font-bold px-2 py-1 rounded-lg bg-purple-100 text-purple-700">v{{ $task->version }}</span>
    </div>

    @if(!$submission)
    {{-- No submission --}}
    <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 text-center text-gray-400 italic text-sm">
        Belum ada submission untuk task ini.
    </div>

    @elseif(!$review)
    {{-- Submission exists but no review yet --}}
    <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-4 text-center text-yellow-700 text-sm font-medium">
        ⏳ Submission ada, namun belum ada review yang dicatat.
    </div>

    @else
    {{-- Review exists --}}
    {{-- Status Badge --}}
    <div class="rounded-xl border p-4
        {{ $review->status === 'approved'
            ? 'border-green-200 bg-green-50'
            : 'border-red-200 bg-red-50' }}">
        <div class="flex items-center gap-3">
            <span class="text-2xl">
                {{ $review->status === 'approved' ? '✅' : '❌' }}
            </span>
            <div>
                <div class="font-bold text-sm
                    {{ $review->status === 'approved' ? 'text-green-800' : 'text-red-800' }}">
                    {{ $review->status === 'approved' ? 'DISETUJUI' : 'DITOLAK' }}
                </div>
                <div class="text-xs text-gray-500 mt-0.5">
                    {{ $review->created_at->format('d M Y, H:i') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Reviewer Info --}}
    <div class="rounded-xl border border-gray-200 p-4">
        <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Reviewer</div>
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-gradient-to-tl from-violet-600 to-purple-400 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                {{ substr($review->reviewer->name ?? '?', 0, 1) }}
            </div>
            <div>
                <div class="font-semibold text-gray-800 text-sm">{{ $review->reviewer->name ?? 'Unknown' }}</div>
                <div class="text-xs text-gray-400">{{ $review->reviewer->role_specialty ?? 'Reviewer' }}</div>
            </div>
        </div>
    </div>

    {{-- Feedback / Notes --}}
    @if($review->feedback)
    <div class="rounded-xl border border-orange-200 bg-orange-50 p-4">
        <div class="text-xs font-semibold uppercase tracking-wider text-orange-600 mb-2">💬 Feedback / Catatan Reviewer</div>
        <p class="text-gray-700 text-sm leading-relaxed">{{ $review->feedback }}</p>
    </div>
    @else
    <div class="rounded-xl border border-gray-100 bg-gray-50 p-3 text-xs text-gray-400 italic text-center">
        Tidak ada catatan tambahan dari reviewer.
    </div>
    @endif

    {{-- Submission Notes --}}
    @if($submission->notes)
    <div class="rounded-xl border border-blue-100 bg-blue-50 p-4">
        <div class="text-xs font-semibold uppercase tracking-wider text-blue-600 mb-2">📝 Production Notes</div>
        <p class="text-gray-700 text-sm leading-relaxed">{{ $submission->notes }}</p>
    </div>
    @endif

    @endif

</div>
