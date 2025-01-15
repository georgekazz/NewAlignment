<style>
        /* CSS styles for the table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px auto;
            max-width: 800px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        /* CSS styles for the "Create a New Project" button */
        .create-project-btn {
            margin: 20px auto;
            display: block;
            padding: 12px 24px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .create-project-btn:hover {
            background-color: #218838;
        }


        /* CSS styles for the tabs */
        .tab {
            margin-bottom: 20px;
            display: inline-block;
            margin-right: 10px;
            padding: 12px;
            background-color: #f8f9fa;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .tab:hover {
            background-color: #E79116;
        }

        .active-tab {
            background-color: #218838;
            color: white;
        }

        /* CSS styles for the table rows */
        .table-row {
            transition: background-color 0.3s;
        }

        .table-row:hover {
            background-color: #f8f9fa;
        }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">



    <!-- Create Button -->
    <button type="button" class="btn btn-primary custom-btn" data-bs-toggle="modal" data-bs-target="#createProject">
        Create New Project
    </button>
    <p></p>
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
                    </tr>
                </thead>
                <tbody>
                @foreach ($projects as $project)
                    @if ($project->public || $project->user_id == auth()->id())
                        <tr>
                            <td>{{ $project->name }}</td>
                            <td><a href='profile/{{$project->admin_user}}' title='Show user profile'>{{ $project->user_id }}</a></td>
                            <td>{{ $project->source->filename }}</td>
                            <td>{{ $project->target->filename }}</td>
                            <td class="text-center">
                                @if($project->public)
                                    <img src="../img/check.png" alt="Edit Icon" class="small-icon" height="20" width="20">
                                @else
                                    <img src="../img/cross.png" alt="Edit Icon" class="small-icon" height="20" width="20">
                                @endif
                            </td>
                            <td>{{ $project->created_at }}</td>
                            <td class="text-center">
                                <!-- Δράσεις -->
                            
                            <form action="{{ url('settings/create_config/'.$project->id) }}" method="POST">
                                @csrf
                                <button title="Calculate Similarities" class="btn btn-primary custom-btn">
                                    <img src="../img/link.png" alt="Edit Icon" class="small-icon" height="20"
                                        width="20">
                                </button>
                            </form>
                        </td>
                        <td class="text-center">
                            <form action="{{ url('createlinks/'.$project->id) }}" method="GET">
                                <button title="Create New Links"
                                    class="btn btn-primary custom-btn <?php if(!$project->processed){echo 'disabled';}?>">
                                    <img src="../img/play-button.png" alt="Edit Icon" class="small-icon" height="20"
                                        width="20">
                                </button>
                            </form>
                        </td>
                        <td class="text-center">
                            <form action="{{ url('myprojects/delete/'.$project->id) }}" method="POST">
                                {!! csrf_field() !!}
                                {!! method_field('DELETE') !!}
                                <button title="Delete this Project" class="btn btn-primary custom-btn">
                                    <img src="../img/bin.png" alt="Edit Icon" class="small-icon" height="20" width="20">
                                </button>
                            </form>
                        </td>
                    </tr>
                    </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>




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
                    <form action="{{ route('myprojects.create') }}" method="POST" enctype="multipart/form-data">
                        <!-- Give Name Label -->
                        {{ csrf_field() }}
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
                                    $select[$key] = $value;
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

                        <!-- Silk Selection -->
                        <div class="form-group">
                            <label for="target">Select SiLK Framework Settings Profile</label>
                            <select name="settings_id"> <!-- Προσθέτουμε το χαρακτηριστικό name -->
                                <?php
                                    use \App\Models\Settings;
                                    use Illuminate\Support\Arr;
                                    
                                    $settings = Settings::where("valid", true)->get();
                                ?>
                                <!-- Προσθεση των επιλογων -->
                                @foreach($settings as $setting)
                                <option value="{{ $setting->id }}">{{ $setting->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>