<script src="https://d3js.org/d3.v7.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script>
function select2DataCollectName2(d) {
    if (d.children)
        d.children.forEach(select2DataCollectName2);
    else if (d._children)
        d._children.forEach(select2DataCollectName2);
    select2Data2.push({"name": d.data.name , "url": d.data.url});
}

function select2DataCollectName(d) {
    if (d.children)
        d.children.forEach(select2DataCollectName);
    else if (d._children)
        d._children.forEach(select2DataCollectName);
    select2Data.push({"name": d.data.name , "url": d.data.url});
}

window.onload = function start(){
    check_connectivity();
    check_connectivity_right();
}
</script>

<script>
var margin = {top: 30, right: 20, bottom: 30, left: 100},
    width = 960 - margin.left - margin.right,
    barHeight = 20,
    barWidth = width * .3;

var i = 0,
    duration = 400;

var tree = d3.tree();

var diagonal = d3.linkVertical()
    .x(function(d) { return d.y; })
    .y(function(d) { return d.x; });

var svg = d3.select("div#source").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr('id', 'left')
    .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

// add clippath
d3.select("svg").append("clipPath")
    .attr("id","clip_path1")
    .append("rect")
    .attr("x",-10)
    .attr("y",-10)
    .attr("width",barWidth+"px")
    .attr("height",barHeight+"px");

function toggle(d) {
    if (d.children) {
        d._children = d.children;
        d.children = null;
    } else {
        d.children = d._children;
        d._children = null;
    }
}

$(document).ready(function(){
    source_graph("{{$_SESSION['source_json']}}");
    target_graph("{{$_SESSION['target_json']}}");
});

function source_graph(file) {
    console.log("file:", file);
    d3.json(file)
        .then(function(flare) {
            root = d3.hierarchy(flare);
            root.x0 = 0;
            root.y0 = 0;

            function toggleAll(d) {
                if (d.children) {
                    d.children.forEach(toggleAll);
                    toggle(d);
                }
            }

            function closeAll(d) {
                if (d.children) {
                    d.children.forEach(closeAll);
                    toggle(d);
                }
            }

            tree(root);
            root.children.forEach(closeAll);
            update(root);

            select2Data = [];
            select2DataCollectName(root);
            select2DataObject = select2Data
                .sort(function(a, b) {
                    return a.name.localeCompare(b.name);
                })
                .filter(function(item, i, ar) {
                    return ar.indexOf(item) === i;
                })
                .map(function(item, i) {
                    return {
                        "id": i,
                        "text": item.name,
                        "url": item.url || "No URL"
                    };
                });

            $("#searchName").select2({
                data: select2DataObject,
                minimumInputLength: 3,
                containerCssClass: "search",
                placeholder: "search a source element",
                allowClear: true
            });
        })
        .catch(function(error) {
            console.error('There was a problem with the fetch operation:', error);
        });
}

function update(source) {
    console.log("inside update function");

    if (source) {
        source.x0 = source.x0 || 0;
        source.y0 = source.y0 || 0;
    }

    var treeData = tree(root);
    var nodes = root.descendants();
    var links = root.links();

    var height = Math.max(500, nodes.length * barHeight + margin.top + margin.bottom);

    d3.select("svg").transition()
        .duration(duration)
        .attr("height", height);

    d3.select(self.frameElement).transition()
        .duration(duration)
        .style("height", height + "px");

    nodes.forEach(function(n, i) {
        n.x = i * barHeight;
    });

    var node = svg.selectAll("g.node")
        .data(nodes, function(d) { return d.id || (d.id = ++i); });

    var nodeEnter = node.enter().append("g")
        .attr("class", "node source_node")
        .attr("transform", function(d) { 
            return "translate(" + (source.y0 || 0) + "," + (source.x0 || 0) + ")"; 
        })
        .style("opacity", 1e-6);

    nodeEnter.append("rect")
        .attr("y", -barHeight / 2)
        .attr("height", barHeight)
        .attr("width", barWidth)
        .style("fill", color)
        .on("click", click);

    nodeEnter.append("circle")
        .attr("cy", 0)
        .attr("cx", -15)
        .attr("r", 6)
        .attr("class", indicator)
        .style("fill", indicatorColor)
        .style("stroke", "black")
        .style("stroke-width", 1)
        .on("click", click);

    nodeEnter.append("text")
        .attr("dy", 3.5)
        .attr("dx", 5.5)
        .attr("clip-path", "url(#clip_path1)")
        .text(function(d) { return d.data.name; });

    nodeEnter.append("url")
        .text(function(d) { return d.data.url || ""; });

    nodeEnter.transition()
        .duration(duration)
        .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; })
        .style("opacity", 1);

    node.transition()
        .duration(duration)
        .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; })
        .style("opacity", 1)
        .select("rect")
        .style("fill", color);

    node.exit().transition()
        .duration(duration)
        .attr("transform", function(d) { return "translate(" + (source.y || 0) + "," + (source.x || 0) + ")"; })
        .style("opacity", 1e-6)
        .remove();

    nodes.forEach(function(d) {
        d.x0 = d.x;
        d.y0 = d.y;
        if (d.data.class === "found") {
            $("#source").slimScroll({ scrollTo: d.x + 'px' });
        }
    });

    // const panZoomTarget = svgPanZoom('#left', {
    //     fit: false,
    //     zoomScaleSensitivity: 0.1,
    //     contain: false,
    //     center: false,
    //     minZoom: 0.7,
    //     mouseWheelZoomEnabled: false
    // });
}

function click(event, d) {
    if (d.children) {
        d._children = d.children;
        d.children = null;
    } else {
        d.children = d._children;
        d._children = null;
    }
    clearAll(root);
    d.class = "found";

    if (d.data.url && d.data.url.trim() !== "") {
        $('#comparison').html('<img id="spinner" src="../img/spinner.gif"/>');
        var collapsed = $("#source_info").hasClass("collapsed-box");
        $("#source_info").load("utility/infobox", {"uri": d.data.url, 'dump': "source", "collapsed": collapsed, "project_id": {{$project->id}}});
        $("#comparison").load("utility/comparison/{{$project->id}}", {"url": d.data.url});
    } else {
        console.error("Invalid or empty URI:", d.data.url);
    }
    update(d);
}
function check_connectivity() {
    var nodes = $(".source_node")
    $.ajax({
        type: "GET",
        url: "utility/connected",
        data: {project_id : {{$project->id}}, type : "source"},
        success: function(data){
            console.log("Connect", data);
            var connected = JSON.parse(data);
            $.each(nodes, function(i, n) {
                var flag = false;
                connected.forEach(function(e, j){
                    if (n.children[3].innerHTML === fixedEncodeURIComponent(e)) {
                        n.children[1].setAttribute("class", "connected");
                        flag = true;
                        return;
                    }
                });
                if(n.children[1].className.baseVal === "connected" && !flag){
                    n.children[1].classList.remove("connected");
                }
            });
            setTimeout(check_connectivity,3000);
        }
    });
}

function check_connectivity_right(){
    var nodes = $(".target_node")
    $.ajax({
        type: "GET",
        url: "utility/connected",
        data: {project_id : {{$project->id}}, type : "target"},
        success: function(data){
            var connected = JSON.parse(data);
            $.each(nodes, function(i, n) {
                var flag = false;
                connected.forEach(function(e, j){
                    if (n.children[3].innerHTML === fixedEncodeURIComponent(e)){
                        n.children[1].setAttribute("class", "connected");
                        flag = true;
                        return;
                    }
                });
                if(n.children[1].className.baseVal === "connected" && !flag){
                    n.children[1].classList.remove("connected");
                }
            });
            setTimeout(check_connectivity_right, 3000);
        }
    });
}
</script>
