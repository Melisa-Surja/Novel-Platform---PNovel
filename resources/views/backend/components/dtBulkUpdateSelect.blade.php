@can($permission ?? "")
    <form method="POST" action="{{ $action }}">
        @csrf
        <div class="flex">
            <div class="-mb-6 flex-shrink-0">
                <backend-form-input name="update_method" placeholder="With Selected:" type="select">
                    @if(isset($slot) && !empty($slot->toHtml()))
                        {{ $slot }}
                    @else
                        <option value="restore">Restore</option>
                        <option value="delete">Delete</option>
                        <option value="forceDelete">Delete Permanently</option>
                    @endif
                </backend-form-input>
            </div>
            <button type="submit" class="ml-2 flex items-center">Submit</button>
        </div>
        <input type="hidden" name="selected_ids" value="" />
    </form>
@endcan