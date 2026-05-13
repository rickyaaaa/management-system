<div class="space-y-4 p-2 text-sm">
    <div class="rounded-xl border border-gray-200 p-4">
        <div class="mb-1 text-xs font-semibold uppercase tracking-wider text-gray-400">Blend File</div>
        <div class="font-mono text-gray-700 break-all">{{ $submission->file_blend_url }}</div>
        <a href="{{ route('secure.file', [$submission->id, 'blend']) }}"
           target="_blank"
           class="mt-2 inline-flex items-center gap-1 rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">
            ⬇ Download .blend
        </a>
    </div>

    <div class="rounded-xl border border-gray-200 p-4">
        <div class="mb-1 text-xs font-semibold uppercase tracking-wider text-gray-400">Preview Video</div>
        <div class="font-mono text-gray-700 break-all">{{ $submission->file_mov_url }}</div>
        <a href="{{ route('secure.file', [$submission->id, 'mov']) }}"
           target="_blank"
           class="mt-2 inline-flex items-center gap-1 rounded-lg bg-purple-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-purple-700">
            ▶ Download Video
        </a>
    </div>

    @if($submission->notes)
    <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-4">
        <div class="mb-1 text-xs font-semibold uppercase tracking-wider text-yellow-600">Production Notes</div>
        <p class="text-gray-700">{{ $submission->notes }}</p>
    </div>
    @endif
</div>
