<script type="text/javascript">
var table;
$(function () {
    table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ $ajax }}",
        @can($permission ?? '')
            dom: "<'flex justify-between -mb-2'Bl><'flex justify-between items-end'if><rt><'flex justify-between'ip>",
            buttons: [
                'selectAll',
                'selectNone'
            ],
            select: true,
        @endcan
        {!! $slot !!}
    });

    table
    .on( 'select', function ( e, dt, type, indexes ) {
        getSelected();
    } )
    .on( 'deselect', function ( e, dt, type, indexes ) {
        getSelected();
    } );
    function getSelected() {
        let ids = table.rows( { selected: true } ).data().map(data => data.id);
        $("[name='selected_ids']").val(ids.join(","));
    }

    @if(isset($count))
        // inject navigation
        var parent = document.getElementById('count');
        parent.classList.remove('hidden');
        parent.innerHTML = `
        <a id="count-all" class="mr-1 px-1 pb-1 select-none">All (<span></span>)</a> |
        <a id="count-deleted" class="ml-1 px-1 pb-1 select-none">Deleted (<span></span>)</a>
        `;

        var activeClass = ["border-b-2", "border-indigo-500"];
        var elCountAll = document.querySelector('#count-all');
        var elCountDeleted = document.querySelector('#count-deleted');
        elCountAll.addEventListener('click', e => {
            table.ajax
            .url("{{ $ajax }}" + "/")
            .load();
            elCountAll.classList.add(...activeClass);
            elCountDeleted.classList.remove(...activeClass);
        });
        elCountDeleted.addEventListener('click', e => {
            table.ajax
            .url("{{ $ajax }}" + "/?deleted=1")
            .load();
            elCountDeleted.classList.add(...activeClass);
            elCountAll.classList.remove(...activeClass);
        });

        refreshCount("{{ $count['all'] }}", "{{ $count['deleted'] }}");

        function refreshCount(countAll, countDeleted) {
            elCountAll.querySelector("span").textContent = countAll;
            elCountDeleted.querySelector("span").textContent = countDeleted;
        }
    @endif

});
</script>