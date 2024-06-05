<div class="form-inline">
    <div class="form-group-lg d-flex align-items-center">
        <div class="col-lg-6" id="select-project-form">
            <form>
                <label for="selectProject" class="form-label">Select Project</label>
                <select id="selectProject" class="form-control form-control-lg" name='project'>
                    <option value="" selected="selected"></option>
                    @foreach($projects as $project)
                        <option value="{{$project->id}}">{{$project->name}}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="btn-group ml-3" role="group">
            <button id="refresh" class="btn btn-lg btn-primary" title="Refresh Link Table">
                <i class="fas fa-sync-alt"></i>
            </button>

            <button type="button" class="btn btn-lg btn-primary" data-bs-toggle="modal" data-bs-target="#export-dialog"
                title="Export Links">
                <i class="fas fa-file-export"></i> Export
            </button>
            <button type="button" class="btn btn-lg btn-primary" data-bs-toggle="modal" data-bs-target="#import-dialog"
                title="Import Links">
                <i class="fas fa-file-import"></i> Import
            </button>
        </div>
    </div>
</div>

<!-- Custom CSS for styling -->
<style>
    .form-inline {
        margin-top: 1rem;
        margin-bottom: 1rem;
    }

    .btn-group .btn {
        margin-left: 0.5rem;
    }

    #select-project-form {
        margin-bottom: 0;
    }

    #refresh i,
    .btn-primary i {
        margin-right: 0.5rem;
    }
</style>