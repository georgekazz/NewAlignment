<style>
    .modal-content {
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        background-color: #3498db;
        color: white;
        border-bottom: none;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    th,
    td {
        padding: 12px 15px;
        text-align: center;
    }

    th {
        background-color: #3498db;
        color: black;
        text-transform: uppercase;
    }

    td {
        background-color: #f2f2f2;
    }

    tr:hover td {
        background-color: #dcdcdc;
    }

    .modal-body {
        padding: 20px;
    }

    .text-center img {
        display: block;
        margin: auto;
        height: 20px;
        width: 20px;
    }

    .btn-success,
    .btn-danger,
    .btn-primary {
        margin: 0 5px;
        border-radius: 50px;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }
</style>


<button type="button" class="btn btn-primary custom-btn" data-bs-toggle="modal" data-bs-target="#uploadModal">
    Upload New Graph
</button>
<p></p>
<div class="container">
    <div class="row">
        <table class="table">
            <thead>
                <tr>
                    <th>File ID</th>
                    <th>File Name</th>
                    <th>File Type</th>
                    <th>Created At</th>
                    <th>Public Status</th>
                    <th>Parsed</th>
                    <th class="text-center"></th>
                    <th class="text-center"></th>
                    <th class="text-center"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($files as $file)
                    <tr>
                        <td>{{ $file->id }}</td>
                        <td>{{ $file->filename }}</td>
                        <td>{{ $file->filetype}}</td>
                        <td>{{ $file->created_at }}</td>
                        <td class="text-center">
                            @if($file->public)
                                <img src="../img/check.png" alt="Check Icon">
                            @else
                                <img style="display: block; margin: auto;" src="../img/cross.png" alt="Cross Icon">
                            @endif
                        </td>
                        <td class="text-center">@if($file->parsed)
                            <img src="../img/check.png" alt="Check Icon">
                        @else
                            <img style="display: block; margin: auto;" src="../img/cross.png" alt="Cross Icon">
                        @endif
                        </td>
                        <td class="text-center">
                            <form action="{{ url('file/parse/' . $file->id) }}" method="POST">
                                {!! csrf_field() !!}
                                <button title="Parse this File" class="btn btn-success">Run<span
                                        class="btn btn-success"></span></button>
                            </form>
                        </td>
                        <td class="text-center">
                            <form action="{{ route('file.delete', ['file' => $file->id]) }}" method="POST">
                                {!! csrf_field() !!}
                                {!! method_field('DELETE') !!}
                                <button title="Delete this file" type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload New Graph</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <form action="{{ route('mygraphs.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">Choose File</label>
                            <input type="file" id="fileUpload" class="form-control" name="file"
                                accept=".rdf, .ttl, .nt">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Choose File Type</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="filetype" id="rdf" value="rdf"
                                    checked>
                                <label class="form-check-label" for="rdf">
                                    RDF
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="filetype" id="turtle" value="turtle">
                                <label class="form-check-label" for="turtle">
                                    Turtle
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="filetype" id="ntriples"
                                    value="ntriples">
                                <label class="form-check-label" for="ntriples">
                                    N-Triples
                                </label>
                            </div>
                        </div>

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

                        <button type="submit" class="btn btn-success">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>