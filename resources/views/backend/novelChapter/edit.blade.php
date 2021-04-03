@php $ch = $post @endphp

{{-- Title --}}
<backend-form-input name="title" value="{{old('title', $ch['title'])}}" label="Chapter Title" desc="You can leave this blank. Do not put series title, arc title, chapter numbers."></backend-form-input>

{{-- Series Selection --}}
<backend-form-input name="series_id" value="{{old('series_id', $ch['series_id'])}}" label="Select a Series:" required type="select" placeholder="Select a Series">
    @foreach ($extra['series'] as $series)
        <option value="{{ $series->id }}" {{ $series->id == old('series_id', $ch->series_id) ? "selected" : "" }}>{{ $series->title }}</option>
    @endforeach
</backend-form-input>

{{-- Chapter, Chapter Part, NSFW --}}
<div class="grid grid-cols-6 gap-6">
    <div class="col-span-2">
        <backend-form-input name="chapter" value="{{old('chapter', $ch['chapter'])}}" required label="Chapter"></backend-form-input>
    </div>
    <div class="col-span-2">
        <backend-form-input name="chapter_part" value="{{old('chapter_part', $ch['chapter_part'])}}" label="Chapter Part"></backend-form-input>
    </div>
    <div class="col-span-2">
        <backend-form-input type="single-checkbox" name="nsfw" {{ old('nsfw', ($ch['nsfw'] ?? false)) ? "checked" : ""}} value="1" label="NSFW Content"></backend-form-input>
    </div>
</div>

{{-- Important to import ql styles --}}
<div class="ql-toolbar ql-snow hidden">
    <div class="ql-removeRaw"></div>
    <div class="ql-popup"></div>
</div>
{{-- Content --}}
<div class="mb-6">
    <backend-form-label name="content" class="mb-1" required>Chapter Content</backend-form-label>
    <backend-form-desc class="-mt-1 mb-1">Only upload images that you have the licenses to use. Do <strong>NOT</strong> upload images that you don't own.</backend-form-desc>
    <div id="scrolling-container">
        <div id="chapter-editor">
        </div>
    </div>
    <backend-form-desc>Don't forget to save frequently!</backend-form-desc>
    <input type="hidden" name="content" id="chapter-content" />

</div>
    

{{-- Publishing time --}}
<div class="grid grid-cols-4 gap-1">
    <div class="col-span-2">
        <backend-form-input name="date" label="Publish Date:" required placeholder="Date" desc="Current server time: {{ $extra['server_time'] }}" value="{{ old('date', $post['published_at']) }}">
    </div>

    @php  
    $hours = $post['published_at']->format('H');
    $minutes = $post['published_at']->format('i');
    @endphp
    <div class="col-span-1">
        <backend-form-input name="hours" label="Hour:" required placeholder="00" value="{{ old('hours', $hours) }}" desc="24-hour format">
    </div>
    <div class="col-span-1">
        <backend-form-input name="minutes" label="Minutes:" required placeholder="00" value="{{ old('minutes', $minutes) }}">
    </div>
</div>


@push('head_scripts_before')
<link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
.ql-snow .ql-popup::before {
    display: none;
}
.quill-popup {
    background-color: yellow;
}
.ql-popup {
    z-index: 100;
}
.ql-snow .ql-tooltip a.ql-action::after {
    margin-left: 0;
}
.ql-snow .ql-tooltip .ql-popuptext {
    display: none;
}
.ql-snow .ql-tooltip.ql-editing .ql-popuptext {
    display: block;
}
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/triangle.min.css">
@endpush

@push('footer_scripts')
{{-- Quill JS --}}
<script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script src="//unpkg.com/quill-paste-smart@1.2.1/dist/quill-paste-smart.js"></script>
<script type="text/javascript">
    var content = {!! json_encode(old('content', $ch['content'])) !!};
</script>
<script src="{{ mix('/js/chapterEdit.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.min.js"></script>
<script type="text/javascript">
    var picker = new Pikaday({ 
        field: document.getElementById('date'),
        theme: "triangle-theme",
        format: 'D MMM YYYY',
        });
</script>
@endpush
