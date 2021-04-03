@php $comment = $post @endphp

{{-- Approved --}}
<div class="mb-6">
    <backend-form-input type="single-checkbox" name="approved" value="1" label="Approved?" {{old('approved', $comment->approved) ? "checked" : ""}}></backend-form-input>
</div>

{{-- Title --}}
<backend-form-input type="textarea" rows="6" name="comment" value="{{old('comment', $comment->comment)}}" required label="Comment"></backend-form-input>