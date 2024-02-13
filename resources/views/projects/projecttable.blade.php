<!DOCTYPE html>
<html>

<head>
    <style>
        /* CSS styles for the table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        /* CSS styles for the "Create a New Project" button */
        .create-project-btn {
            margin-top: 20px;
            margin-left: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        /* CSS styles for the tabs */
        .tab {
            display: inline-block;
            margin-right: 10px;
            padding: 10px;
            background-color: #f2f2f2;
            cursor: pointer;
        }

        .active-tab {
            background-color: #4CAF50;
            color: white;
        }

        /* CSS styles for the table rows */
        .table-row {
            transition: background-color 0.3s;
        }

        .table-row:hover {
            background-color: #f2f2f2;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>

    <!-- Create Button -->
    <button type="button" class="btn btn-primary custom-btn" data-bs-toggle="modal" data-bs-target="#createProject">
        Create New Project
    </button>

    <!-- Tabs -->
    <div>
        <div class="tab active-tab">My Projects</div>
        <div class="tab">Public Projects</div>
        <div class="tab">Vote Only Projects</div>
    </div>

    <!-- Table -->
    <div class="container">
        <div class="row">
            <table class="table">
                <thead>
                    <tr>
                        <th>Project Name</th>
                        <th>Creator</th>
                        <th>Source Ontology</th>
                        <th>Target Ontology</th>
                        <th>Public</th>
                        <th>Created At</th>
                        <th class="text-center"></th>
                        <th class="text-center"></th>
                        <th class="text-center"></th>
                        <th class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projects as $project)
                    <tr>
                        <td>{{ $project->name }}</td>
                        <td><a href='profile/{{$project->user->id}}' title='Show user profile'>{{ $project->user->name
                                }}</a></td>
                        <td>{{ $project->source->resource_file_name}}</td>
                        <td>{{ $project->target->resource_file_name}}</td>
                        <td class="text-center">@if($project->public)
                            <span class="glyphicon glyphicon-ok-sign text-green" title="This project is Public"></span>
                            @else
                            <span class="glyphicon glyphicon-ban-circle text-red"
                                title="This project is Private"></span>
                            @endif
                        </td>
                        <td>{{ $project->created_at }}</td>

                        <!-- Buttons -->
                        <td class="text-center">
                            <form action="{{ url('settings/create_config/'.$project->id) }}" method="POST">
                                <button title="Calculate Similarities" class="btn"><span
                                        class="glyphicon glyphicon-link text-green"></span></button>
                            </form>
                        </td>
                        <td class="text-center">
                            <form action="{{ url('createlinks/'.$project->id) }}" method="GET">

                                <button title="Create New Links"
                                    class="btn <?php if(!$project->processed){echo 'disabled';}?>"><span
                                        class="glyphicon glyphicon-play text-blue"></span></button>
                            </form>
                        </td>
                        <td class="text-center">
                            <form action="{{ url('project/delete/'.$project->id) }}" method="POST">
                                {!! csrf_field() !!}
                                {!! method_field('DELETE') !!}
                                <button title="Delete this Project" class="btn"><span
                                        class="glyphicon glyphicon-remove text-red"></span></button>
                            </form>
                        </td>
                        <td class="text-center">
                            <button title="Edit this Project" class="btn" data-toggle="modal"
                                data-project="{{$project->id}}" data-target="#editProject"><span
                                    class="glyphicon glyphicon-cog text-black"></span></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

<!-- Modal Form -->
<div class="modal fade" id="createProject" tabindex="-1" aria-labelledby="createProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Create New Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <form action="{{ route('mygraphs.store') }}" method="POST" enctype="multipart/form-data">

                        <!-- Give Name Label -->
                        <div class="mb-3">
                            <label for="file" class="form-label">Give a simple name to your project.</label>
                            <input type="text" name="name" required>
                        </div>

                        <!-- Access Type -->
                        <div class="mb-3">
                            <label class="form-label">Choose Access Type</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="access_type" id="public"
                                    value="public">
                                <label class="form-check-label" for="public">
                                    Public
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="access_type" id="private"
                                    value="private" checked>
                                <label class="form-check-label" for="private">
                                    Private
                                </label>
                            </div>
                        </div>

                        <!-- Source Ontology -->
                        <div class="form-group">
                            <?php
                            $files = request()->all();  
                            $select = array();
                            foreach ($files as $file) {
                                if ($file->parsed) {
                                    $key = $file->id;
                                    $value = $file->filename;
                                    $select = array_add($select, $key, $value);
                                }
                            }
                            $files = App\Models\File::where('public', '=', '1')->get();

                            foreach ($files as $file) {
                                if ($file->parsed) {
                                    $key = $file->id;
                                    $value = $file->filename;
                                    $select[$key] = $value;
                                }
                            }
                        ?>
                            <label for="source">Select Source ontology</label>
                            <select name="source_id">
                                <?php foreach ($select as $key => $value): ?>
                                <option value="<?= $key ?>">
                                    <?= $value ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Target Ontology -->
                        <div class="form-group">
                            <label for="target">Select Target ontology</label>
                            <select name="target_id">
                                <?php foreach ($select as $key => $value): ?>
                                <option value="<?= $key ?>">
                                    <?= $value ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Silk -->
                        <div class="form-group">

                        </div>

                        <button type="submit" class="btn btn-success">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</html>