<div id="import-dialog" class="modal" role="dialog">
    <div class="modal-dialog" style="margin:80px auto">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Import Links</h4>
            </div>
            <div class="modal-body">
              <form method="POST" action="{{ route('links.import') }}" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="inputFile">File to import</label>
                        <input type="file" name="resource" id="inputFile" class="form-control-file" required>
                        <p class="help-block">Select a local File to import</p>
                    </div>

                    <div class="form-group">
                        <label for="project_id">Select Project to Import Links</label>
                        <select name="project_id" id="project_id" class="form-control">
                            @foreach($select as $project_id => $project_name)
                                <option value="{{ $project_id }}">{{ $project_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="filetype">File Format</label>
                        <div class="form-check">
                            <input type="radio" name="filetype" id="optionsRDFXML" value="rdfxml" checked>
                            <label class="form-check-label" for="optionsRDFXML">RDF/XML</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="filetype" id="optionsTurtle" value="turtle">
                            <label class="form-check-label" for="optionsTurtle">Turtle</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="filetype" id="optionsNtriples" value="ntriples">
                            <label class="form-check-label" for="optionsNtriples">N-Triples</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="filetype" id="optionsTriG" value="trig">
                            <label class="form-check-label" for="optionsTriG">TriG</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="filetype" id="optionsNQuads" value="nquads">
                            <label class="form-check-label" for="optionsNQuads">N-Quads</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Import</button>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
