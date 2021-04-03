@php $series = $post @endphp


{{-- Cover --}}
<div class="mb-6">
    <backend-form-label name="cover" class="mb-1" required>Series Cover</backend-form-label>
    <div class="flex sm:items-center sm:flex-row flex-col">
        <div class="border rounded mr-4 block flex-shrink-0 sm:mb-0 mb-2">
            @if(isset($series['cover']) && !empty($series['cover']))
                <img src="{{$series['cover']}}" style="max-width: 200px" />
            @else
            <div class="flex items-center justify-center" style="width: 212px; height: 300px">
                No cover uploaded yet.
            </div>    
            @endif
        </div>
        <div>
            <p class="mb-1">Upload a new cover:</p>
            <input type="file" name="cover">
        </div>
    </div>
</div>


{{-- Title --}}
<backend-form-input name="title" value="{{old('title', $series['title'])}}" required label="Series Title"></backend-form-input>


{{-- Slug --}}
@if(isset($series['slug']))
<backend-form-update-slug route="{{route('frontend.series') . '/'}}" slug="{{old('slug', $series['slug'])}}" submit="{{route('api.backend.series.updateSlug', $series['id'])}}"></backend-form-update-slug>
@endif


{{-- Staffs, Completed --}}
<div class="grid grid-cols-8 gap-6">
    <div class="col-span-8 sm:col-span-4 xl:col-span-2 -mb-6">
        <backend-form-input name="author" value="{{old('author', $series['author'])}}" required label="Author"></backend-form-input>
    </div>
    <div class="col-span-8 sm:col-span-4 xl:col-span-2 -mb-6">
        <backend-form-input name="editor" value="{{old('editor', $series['staffs']['editor'] ?? '')}}" label="editor(s)"></backend-form-input>
    </div>
    <div class="col-span-8 sm:col-span-4 xl:col-span-2">
        <backend-form-input name="translator" value="{{old('translator', $series['staffs']['translator'] ?? '')}}" label="Translator(s)"></backend-form-input>
    </div>
    <div class="col-span-8 sm:col-span-4 xl:col-span-2">
        <backend-form-input type="single-checkbox" name="completed" {{old('completed', $series['completed'] ?? false) ? "checked" : ""}} value="1" label="Completed?"></backend-form-input>
    </div>
</div>


{{-- Tags --}}
<backend-form-label>Tags</backend-form-label>
<div class="mb-6">
    <backend-form-tags @if(isset($extra['selected'])) :selected="{{ $extra['selected'] }}" @endif :options="{{ $extra['tags'] }}"></backend-form-tags>
</div>


{{-- Summary / Excerpt --}}
<backend-form-input name="summary" value="{{old('summary', $series['summary'])}}" required label="Summary" type="textarea" :rows="10"></backend-form-input>
<backend-form-input name="excerpt" value="{{old('excerpt', $series['excerpt'])}}" required label="Excerpt" type="textarea" :rows="4" :maxlength="300" desc="Shorter summary for preview. Max 300 characters."></backend-form-input>


{{-- Schedule --}}
@php
$schedule = ['sporadic','monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
@endphp   
<backend-form-label class="mb-1">Schedule</backend-form-label>
<div class="grid grid-cols-8 gap-1 mb-6">
    @foreach ($schedule as $day)
        <div class="col-span-4 sm:col-span-2 xl:col-span-1">
            {{-- Check if it's checked --}}
            <backend-form-input type="checkbox" name="schedule[]" value="{{$day}}" label="{{ucfirst($day)}}" {{ in_array($day, (old('schedule') ?? ($series['schedule'] ?? []))) ? 'checked' : '' }}></backend-form-input>
        </div>
    @endforeach
</div>

{{-- Licensing status --}}
@can('edit all series')
@inject('SeriesClass', 'App\Models\Series')
@php 
$legal_status = [$SeriesClass::LEGAL_EXCLUSIVE, $SeriesClass::LEGAL_LICENSED, $SeriesClass::LEGAL_ORIGINAL, $SeriesClass::LEGAL_OTHER];
@endphp
<backend-form-input name="legal_status" type="select" label="Legal Status" required placeholder="Select legal status of this work:">
    @foreach ($legal_status as $status)
        <option value="{{$status}}"
        {{old('legal_status', $series['legal_status']) == $status ? 'selected' : ''}}>
            {{$status}}
        </option>
    @endforeach
</backend-form-input>
@endcan

<div class="grid grid-cols-8 gap-1">
    {{-- Publishing time --}}
    <div class="col-span-4">
        <backend-form-input name="date" label="Publish Date:" required placeholder="Date" desc="Current server time: {{ $extra['server_time'] }}" value="{{ old('date', $post['published_at']) }}">
    </div>

    @php  
    $hours = $post['published_at']->format('H');
    $minutes = $post['published_at']->format('i');
    @endphp
    <div class="col-span-2">
        <backend-form-input name="hours" label="Hour:" required placeholder="00" value="{{ old('hours', $hours) }}" desc="24-hour format">
    </div>
    <div class="col-span-2">
        <backend-form-input name="minutes" label="Minutes:" required placeholder="00" value="{{ old('minutes', $minutes) }}">
    </div>
</div>


@push('head_scripts_before')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/triangle.min.css">
@endpush

@push('footer_scripts')
<script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.min.js"></script>
<script type="text/javascript">
    var picker = new Pikaday({ 
        field: document.getElementById('date'),
        theme: "triangle-theme",
        format: 'D MMM YYYY',
        });
</script>
@endpush