<!-- Create button -->
<button type="button" class="btn btn-primary custom-btn" data-bs-toggle="modal"
    data-bs-target="#create-settings-dialog">
    Create Settings
</button>

<!-- Reload button -->
<button onclick="reload()" class="btn btn-primary" title="Reload">
    <img src="../img/refreshicon.png" alt="Refresh" width="20" height="20">
</button>


<!-- Modal Form -->
<div class="modal fade" id="create-settings-dialog" tabindex="-1" aria-labelledby="createProjectModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create a New Setting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <form action="{{ action('SettingsController@create') }}" method="POST" enctype="multipart/form-data">

                        <!-- File Upload Label -->
                        <label class="form-label">File Upload</label>
                        <input type="file" id="fileUpload" class="form-control" name="file" accept="">
                        <p>Attach a valid provider specific configuration file </p>


                        <!-- Provider -->
                        <div class="mb-3">
                            <label class="form-label">Select Suggestions Provider</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="filetype" id="silk" value="silk">
                                <label class="form-check-label" for="silk">
                                    Silk
                                </label>
                            </div>
                        </div>

                        <!-- User Name Label -->
                        <div class="mb-3">
                            <label class="form-label">Enter a user friendly name</label>
                            <input type="text" class="form-control" name="username" id="username" required>
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
                        <button type="submit" class="btn btn-success">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>