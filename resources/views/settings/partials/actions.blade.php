<button onclick="" class="btn btn-primary" title="Show this Setting">
    <img src="../img/eye.png" alt="Edit Icon" class="small-icon" height="20" width="20">
</button>

<button onclick="deleteSetting({{ $setting->id }})" class="btn btn-primary" title="Delete this Setting">
    <img src="../img/bin.png" alt="Edit Icon" class="small-icon" height="20" width="20">
</button>

<form id="deleteForm" action="{{ route('settings.delete', ['id' => ':id']) }}" method="POST">
    @csrf
    @method('DELETE')
</form>



<script>
    function deleteSetting(id) {
    if(confirm('Are you sure you want to delete this setting?')) {
        var form = document.getElementById('deleteForm');
        var url = form.action.replace(':id', id);
        form.setAttribute('action', url);
        form.submit();
    }
}
</script>