<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/all.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>

<script>
    $(function () {
        $('#suggestions-box').slimScroll({
            height: '280px'
        });
    });
</script>
<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#radio").load(
            "{{URL::to('/')}}/linktype/update",
            { "group": "SKOS" },
            function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_polaris',
                    radioClass: 'iradio_polaris',
                    increaseArea: '-10%' // optional
                });
            }
        );

        updateLinksTable();
    });

    function updateLinksTable() {
        $("#select-project-form").hide();
        initializeDataTable({{$project->id}});
    }

    function updateRadio() {
        var group = $("#group-selector").val();
        $("#radio").load(
            "{{URL::to('/')}}/linktype/update",
            { "group": group },
            function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_polaris',
                    radioClass: 'iradio_polaris',
                    increaseArea: '-10%' // optional
                });
            }
        );
    }

</script>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<div id="linking_wrapper" class="container mt-5">
    <h3 class="text-center mb-4">Link Creation Helpers</h3>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">Suggestions</h4>
                </div>
                <div id="comparison" class="card-body">
                    <!-- Suggestion content goes here -->
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Choose link type to create:</h3>
                </div>
                <div class="card-body">
                    @include('createlinks.partials.groups')
                    <div id="create_links" class="mt-3">
                        <div class="skin skin-polaris" id="link_chooser">
                            @include('createlinks.linking_form')
                        </div>
                        <div id="links-utility" hidden></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="created_links" class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Created Links</h3>
            <button type="button" class="btn btn-box-tool text-white" data-bs-toggle="collapse"
                data-bs-target="#links-body" aria-expanded="false" aria-controls="links-body">
                <i class="fa fa-plus"></i>
            </button>
        </div>
        <div id="links-body" class="collapse show">
            <div class="card-body">
                <div id="links">
                    @include("links.full_link_table", ["projects" => [$project]])
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var previous_url = '';
    function click_button(url) {
        console.log("clicked");
        if (url !== previous_url) {
            searchField = "d.url";
            searchText = fixedEncodeURIComponent(url);
            clearAll(root_right);
            expandAll(root_right);
            searchTree(root_right);
            root_right.children.forEach(collapseAllNotFound);
            update_right(root_right);
            previous_url = url;
            var collapsed_target = $("#target_info").hasClass("collapsed-box");
            $("#target_info").load("utility/infobox", {
                "uri": url,
                "dump": "target",
                "collapsed": collapsed_target,
                "project_id": {{$project->id}}
            });
        }
    }

    function fixedEncodeURIComponent(str) {
        return encodeURIComponent(str).replace(/[!'()]/g, escape).replace(/\*/g, "%2A");
    }

    $("#searchName").on("select2-selecting", function (e) {
        clearAll(root);
        expandAll(root);
        searchField = "d.name";
        searchText = e.object.text;
        graph = "#source";
        searchTree(root);
        root.children.forEach(collapseAllNotFound);
        $('#comparison').html('<img id="spinner" src="../img/spinner.gif"/>');
        $("#source_info").load("utility/infobox", {
            "uri": e.object.url,
            "dump": "source",
            "project_id": {{$project->id}}
        });
        $("#comparison").load("utility/comparison/{{$project->id}}", { "url": e.object.url });
        update(root);
    });

    $("#searchName2").on("select2-selecting", function (e) {
        clearAll(root_right);
        expandAll(root_right);
        searchField = "d.name";
        searchText = e.object.text;
        graph = "#target";
        searchTree(root_right);
        root_right.children.forEach(collapseAllNotFound);
        $("#target_info").load("utility/infobox", {
            "uri": e.object.url,
            "dump": "target",
            "project_id": {{$project->id}}
        });
        update_right(root_right);
    });

    function searchTree(d) {
        if (d.children) {
            d.children.forEach(searchTree);
        } else if (d._children) {
            d._children.forEach(searchTree);
        }
        var searchFieldValue = eval(searchField);
        if (searchFieldValue && searchFieldValue == searchText) {
            var ancestors = [];
            var parent = d;
            var counter = 0;
            while (typeof (parent) !== "undefined") {
                ancestors.push(parent);
                if (counter) {
                    parent.class2 = "target";
                }
                parent.class = "found";
                parent = parent.parent;
                counter++;
            }
            return ancestors;
        }
    }

    function clearAll(d) {
        d.class = "";
        d.class2 = "";
        if (d.children) {
            d.children.forEach(clearAll);
        } else if (d._children) {
            d._children.forEach(clearAll);
        }
    }

    function collapse(d) {
        if (d.children) {
            d._children = d.children;
            d._children.forEach(collapse);
            d.children = null;
        }
    }

    function collapseAllNotFound(d) {
        if (d.children) {
            if (d.class !== "found") {
                d._children = d.children;
                d._children.forEach(collapseAllNotFound);
                d.children = null;
            } else {
                d.children.forEach(collapseAllNotFound);
            }
        }
    }

    function expandAll(d) {
        if (d._children) {
            d.children = d._children;
            d.children.forEach(expandAll);
            d._children = null;
        } else if (d.children) {
            d.children.forEach(expandAll);
        }
    }
</script>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>

<!-- Custom CSS  -->
<style>
    .card-header {
        background-color: #007bff;
        color: black;
    }

    .btn-box-tool {
        background: none;
        border: none;
    }

    .btn-box-tool:focus {
        outline: none;
    }

    .card {
        border-radius: 0.25rem;
    }

    .card-title {
        margin-bottom: 0;
    }

    .skin-polaris {
        padding: 1rem;
    }

    #linking_wrapper {
        margin-top: 2rem;
    }

    #created_links {
        margin-top: 3rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    .g-4>.col-md-6 {
        padding-bottom: 2rem;
    }

    .mt-3 {
        margin-top: 1rem !important;
    }
</style>