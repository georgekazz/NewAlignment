<script src="//cdn.datatables.net/2.0.8/js/dataTables.min.js" type="text/javascript"></script>
<!--@include('links.script')-->
<div class="box" width="80%">
    <div class="box-header">
        @include('links.form')
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        @include('links.table')
    </div>
    <!-- /.box-body -->
</div>
<script>

    //initialize variable
    var table;

    //catch change event
    $('#selectProject').change(function () {
        if (this.value !== "") {
            initializeDataTable(this.value);
        }

    });

    function delete_link(id) {
        $.ajax({
            url: "{{ route('mylinks.delete', ':id') }}".replace(':id', id),
            method: "DELETE",
            data: { _token: "{{ csrf_token() }}" }
        })
            .done(function (data) {
                // $.toaster({ priority: data.priority, title: data.title, message: data.message });
                if (data.priority == "success") {
                    reload(false);
                }
            });
    }




    //reload button
    function reload(paging = true) {
        table.ajax.reload(null, paging);
    }
</script>
<script>
    function initializeDataTable(id) {
        table = $('#links-table').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '{!! route('links.ajax') !!}',
                type: 'GET',
                data: { project: id, route: "{{ Route::currentRouteName() }}"}
            },
            order: [],
            columns: [
                { data: 'source', name: 'source' },
                { data: 'link', name: 'link' },
                { data: 'target', name: 'target' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    }

</script>