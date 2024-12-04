<div class="container mt-4">
    <div class="row">
        <!-- Source Graph -->
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Source</h3>
                    <div class="card-tools">
                        <button id="sort-source" type="button" class="btn btn-tool sort-button"
                            onclick="sortGraph('source')">Sort by Name <i class="fas fa-expand-arrows-alt"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="source">
                        @include('createlinks.source_graph')
                    </div>
                    <div class="controls-source-graph">
                        <button id="zoom-in-source" class="btn btn-primary">Zoom in</button>
                        <button id="zoom-out-source" class="btn btn-primary">Zoom out</button>
                        <button id="reset-source" class="btn btn-primary">Reset</button>
                    </div>
                </div>
            </div>
            <div id="source_info" class="card card-primary collapsed-card">
                @include('createlinks.partials.info', array("dump" => "source"))
            </div>
        </div>
        
        <!-- Target Graph -->
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Target</h3>
                    <div class="card-tools">
                        <button id="sort-target" type="button" class="btn btn-tool sort-button"
                            onclick="sortGraph('target')">Sort by Name <i class="fas fa-expand-arrows-alt"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="target">
                        @include('createlinks.target_graph')
                    </div>
                    <div class="controls">
                        <button id="zoom-in-target" class="btn btn-primary">Zoom in</button>
                        <button id="zoom-out-target" class="btn btn-primary">Zoom out</button>
                        <button id="reset-target" class="btn btn-primary">Reset</button>
                    </div>
                </div>
            </div>
            <div id="target_info" class="card card-primary collapsed-card">
                @include('createlinks.partials.info', array("dump" => "target"))
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- SlimScroll Plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"></script>

<script>
    $(function () {
        $('#source').slimScroll({
            height: '350px' 
        });
        $('#target').slimScroll({
            height: '350px'
        });
    });
</script>

<script>
    function sortGraph(graph) {
        const meta = $("meta[name=" + graph + "_json]").attr("content");
        const json = meta.split(".");
        const newJSON = json[0] + "name." + json[1];
        const enabled = $("#sort-" + graph).hasClass("enabled");
        const file = enabled ? meta : newJSON;
        $("#sort-" + graph).toggleClass("enabled");
        graph == "source" ? source_graph(file) : target_graph(file);
    }
</script>
