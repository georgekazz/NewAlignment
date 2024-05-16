<script type="text/javascript" src="{{asset('/plugins/slimScroll/jquery.slimscroll.js')}}"></script>
<meta name="source_json" content="{{$_SESSION["source_json"]}}">
<meta name="target_json" content="{{$_SESSION["target_json"]}}">
<script>
    // Slimscroll Doc: http://rocha.la/jQuery-slimScroll    
    const wheelStep = '10px';
    $(function () {
        const scrollOptions = {
            height: '250px',
            wheelStep: wheelStep
        };
        $('#details_source').slimScroll(scrollOptions);
        $('#source').slimScroll(scrollOptions);
        $('#details_target').slimScroll(scrollOptions);
        $('#target').slimScroll(scrollOptions);
    });
</script>
<div id="info_wrapper" class="row">
    <!--    source graph code-->
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Source</h3>
                <div class="card-tools">
                    <button id="sort-source" type="button" class="btn btn-tool sort-button"
                        onclick="sortGraph('source')">Sort by Name</button>
                </div>
            </div>
            <div class="card-body">
                <div class="search-container">
                </div>
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
    <!--target graph code-->
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header with-border">
                <h3 class="card-title">Target</h3>
                <div id="block_container2">
                    <div id="searchName2"></div>
                </div>
                <div class="card-tools pull-right">
                    <button id="sort-target" type="button" class="btn btn-tool sort-button"
                        onclick="sortGraph('target')">Sort by Name</button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
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
            <!-- /.card-body -->
        </div>
        <div id="target_info" class="card card-primary collapsed-card">
            @include('createlinks.partials.info', array("dump" => "target"))
        </div>
    </div>
</div>
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