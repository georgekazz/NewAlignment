<div class="box" width="80%">
    <div class="box-header">
        @include('settings.partials.buttons')
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        @include('settings.partials.table')
    </div>
    <!-- /.box-body -->
</div>



<script>
    //reload button
    function reload(paging = true) {
        table.ajax.reload(null, paging);
    }

</script>