<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://d3js.org/d3.v7.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/svg-pan-zoom/3.6.1/svg-pan-zoom.min.js"></script>

<script>
var margin = {top: 30, right: 20, bottom: 30, left: 100},
    width = 960 - margin.left - margin.right,
    barHeight = 20,
    barWidth = width * .3;

var i_right = 0,
    duration = 400,
    root_right;

var tree_right = d3.tree().size([width, barHeight]);

var diagonal_right = d3.linkVertical()
    .x(function(d) { return d.y; })
    .y(function(d) { return d.x; });

var svg_right = d3.select("div#target").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("id","right")
    .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

// Add clippath
d3.select("svg#right").append("clipPath")
    .attr("id","clip_path2")
    .append("rect")
    .attr("x",-10)
    .attr("y",-10)
    .attr("width",barWidth+"px")
    .attr("height",barHeight+"px");

function target_graph(file){
    console.log(file);
    d3.json(file)
    .then(function(flare_right) {
        // Initialize the hierarchy
        root_right = d3.hierarchy(flare_right);
        root_right.x0 = 0;
        root_right.y0 = 0;

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

        // Update the tree layout
        tree_right(root_right);

        // Initialize the display to show a few nodes.
        if(root_right.children) root_right.children.forEach(closeAll);

        update_right(root_right);

        var select2Data2 = [];
        select2DataCollectName2(root_right);
        var select2DataObject2 = select2Data2.map(function(item, i) {
            return {
                "id": i,
                "text": item.name,
                "url": item.url
            };
        });
        
        $("#searchName2").select2({
            data: select2DataObject2,
            containerCssClass: "search",
            minimumInputLength: 3,
            placeholder: "search a target element",
            allowClear:true
        });
    })
    .catch(function(error) {
        console.error('Error fetching JSON:', error);
    });
}

function update_right(source) {
    var nodes_right = root_right.descendants();

    var height_right = Math.max(500, nodes_right.length * barHeight + margin.top + margin.bottom);

    d3.select("svg#right").transition()
        .duration(duration)
        .attr("height", height_right);

    d3.select(self.frameElement).transition()
        .duration(duration)
        .style("height", height_right + "px");

    nodes_right.forEach(function(n_right, i_right) {
        n_right.x = i_right * barHeight;
    });

    var node_right = svg_right.selectAll("g.node")
        .data(nodes_right, function(d) { return d.id || (d.id = ++i_right); });

    var nodeEnter_right = node_right.enter().append("g")
        .attr("class", "node target_node")
        .attr("transform", function(d) { return "translate(" + source.y0 + "," + source.x0 + ")"; })
        .attr("id", function(d) { return d.id; })
        .style("opacity", 1e-6);

    nodeEnter_right.append("rect")
        .attr("y", -barHeight / 2)
        .attr("height", barHeight)
        .attr("width", barWidth)
        .style("fill", color)
        .on("click", click_right);

    nodeEnter_right.append("circle")
        .attr("cy", 0)
        .attr("cx", -15)
        .attr("r", 6)
        .style("fill", "lightgray")
        .style("stroke", "black")
        .style("stroke-width", 1)
        .on("click", click_right);

    nodeEnter_right.append("text")
        .attr("dy", 3.5)
        .attr("dx", 5.5)
        .attr("clip-path","url(#clip_path2)")
        .text(function(d) { return d.data.name; });

    nodeEnter_right.transition()
        .duration(duration)
        .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; })
        .style("opacity", 1);

    node_right.transition()
        .duration(duration)
        .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; });

    // Add this line to update the color of existing nodes
    node_right.select("rect").style("fill", color);

    node_right.exit().transition()
        .duration(duration)
        .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
        .style("opacity", 1e-6)
        .remove();

    nodes_right.forEach(function(d) {
        d.x0 = d.x;
        d.y0 = d.y;
        if (d.class === "found") {
            $("#target").slimScroll({scrollTo: d.x + 'px'});
        }
    });

    // var panZoomTarget = svgPanZoom('#right', {
    //     fit: false,
    //     zoomScaleSensitivity: 0.1,
    //     contain: false,
    //     center: false,
    //     minZoom: 0.7,
    //     mouseWheelZoomEnabled: false
    // });
}

function click_right(event, d) {
    clearAll(root_right);
    d.class = "found";
    if (d.children) {
        d._children = d.children;
        d.children = null;
    } else {
        d.children = d._children;
        d._children = null;
    }
    var collapsed_target = $("#target_info").hasClass("collapsed-box");
    $("#target_info").load("utility/infobox", {
        "uri": d.data.url,
        'dump': "target",
        "collapsed": collapsed_target,
        "project_id": {{$project->id}}
    });
    update_right(d);
}

function color(d) {
    if (d.class2) {
        return "blue";
    } else if (d.class === "found") {
        return "green";
    } else if (d._children) {
        return "#3182bd";
    } else if (d.children) {
        return "#c6dbef";
    } else {
        return "#fd8d3c";
    }
}

function indicatorColor(d) {
    if (d.suggestions !== 0) {
        return "yellow";
    } else {
        return "lightgrey";
    }
}

function indicator(d) {
    if (d.connected) {
        return "connected";
    } else {
        return "";
    }
}

function clearAll(root) {
    root.descendants().forEach(function(d) {
        d.class = "";
    });
}

function toggle(d) {
    if (d.children) {
        d._children = d.children;
        d.children = null;
    } else {
        d.children = d._children;
        d._children = null;
    }
}

function select2DataCollectName2(d) {
    if (d.data.name) {
        select2Data2.push({
            name: d.data.name,
            url: d.data.url
        });
    }
    if (d.children) {
        d.children.forEach(select2DataCollectName2);
    }
    if (d._children) {
        d._children.forEach(select2DataCollectName2);
    }
}
</script>
