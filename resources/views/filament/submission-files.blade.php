<div class="space-y-4 p-2 text-sm">

    {{-- Blender File --}}
    @if($submission->blend_url)
    <div class="rounded-xl border border-gray-200 p-4">
        <div class="mb-1 text-xs font-semibold uppercase tracking-wider text-gray-400">📦 Blender File (.blend)</div>
        <div class="font-mono text-gray-700 break-all">{{ basename($submission->blend_url) }}</div>
        <a href="{{ route('secure.file', $submission->id) }}?type=blend"
           class="mt-2 inline-flex items-center gap-1 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-700">
            ⬇ Download .blend
        </a>
    </div>
    @endif

    {{-- Video File --}}
    @if($submission->video_url)
    <div class="rounded-xl border border-gray-200 p-4">
        <div class="mb-1 text-xs font-semibold uppercase tracking-wider text-gray-400">🎬 Video Preview (.mp4 / .mov)</div>
        <div class="font-mono text-gray-700 break-all">{{ basename($submission->video_url) }}</div>
        <a href="{{ route('secure.file', $submission->id) }}?type=video"
           target="_blank"
           class="mt-2 inline-flex items-center gap-1 rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">
            ▶ Putar / Download Video
        </a>
    </div>
    @endif

    {{-- Fallback: no files --}}
    @if(!$submission->blend_url && !$submission->video_url)
    <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 text-gray-400 text-center">
        Tidak ada file yang diupload.
    </div>
    @endif

    {{-- Production Notes --}}
    @if($submission->notes)
    <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-4">
        <div class="mb-1 text-xs font-semibold uppercase tracking-wider text-yellow-600">Production Notes</div>
        <p class="text-gray-700">{{ $submission->notes }}</p>
    </div>
    @endif
</div>
