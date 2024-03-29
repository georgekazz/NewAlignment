<script>
    const margin = { top: 30, right: 20, bottom: 30, left: 100 };
    const width = 960 - margin.left - margin.right;
    const barHeight = 20;
    const barWidth = width * 0.3;

    let iRight = 0;
    const duration = 400;
    let rootRight;

    const treeRight = d3.layout.tree().nodeSize([0, 20]);

    const diagonalRight = d3.svg.diagonal().projection((d) => [d.y, d.x]);

    const svgRight = d3
        .select("div#target")
        .append("svg")
        .attr("width", width + margin.left + margin.right)
        .attr("id", "right")
        .append("g")
        .attr("transform", `translate(${margin.left},${margin.top})`);

    // Add clip path
    d3.select("svg")
        .append("clipPath")
        .attr("id", "clip_path2")
        .append("rect")
        .attr("x", -10)
        .attr("y", -10)
        .attr("width", `${barWidth}px`)
        .attr("height", `${barHeight}px`);

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

    function targetGraph(file) {
        d3.json(file, (error, flareRight) => {
            if (error) throw error;

            flareRight.x0 = 0;
            flareRight.y0 = 0;
            updateRight((rootRight = flareRight));

            // Initialize the display to show a few nodes.
            rootRight.children.forEach(closeAll);

            updateRight((rootRight = flareRight));

            let select2Data2 = [];
            select2DataCollectName2(rootRight);
            let select2DataObject2 = [];
            select2Data2
                .sort((a, b) => (a > b ? 1 : a < b ? -1 : 0))
                .filter((item, i, ar) => ar.indexOf(item) === i)
                .filter((item, i, ar) => {
                    select2DataObject2.push({
                        id: i,
                        text: item.name,
                        url: item.url,
                    });
                });

            $("#searchName2").select2({
                data: select2DataObject2,
                containerCssClass: "search",
                minimumInputLength: 3,
                placeholder: "search a target element",
                allowClear: true,
            });
        });
    }

    function updateRight(source) {
        const nodesRight = treeRight.nodes(rootRight);
        const heightRight = Math.max(500, nodesRight.length * barHeight + margin.top + margin.bottom);

        d3.select("svg#right")
            .transition()
            .duration(duration)
            .attr("height", heightRight);

        d3.select(self.frameElement)
            .transition()
            .duration(duration)
            .style("height", `${heightRight}px`);

        nodesRight.forEach((nRight, iRight) => {
            nRight.x = iRight * barHeight;
        });

        const nodeRight = svgRight.selectAll("g.node").data(nodesRight, (d) => d.id || (d.id = ++iRight));

        const nodeEnterRight = nodeRight
            .enter()
            .append("g")
            .attr("class", "node target_node")
            .attr("transform", (d) => `translate(${source.y0},${source.x0})`)
            .attr("id", (d) => d.id)
            .style("opacity", 1e-6);

        nodeEnterRight
            .append("rect")
            .attr("y", -barHeight / 2)
            .attr("height", barHeight)
            .attr("width", barWidth)
            .style("fill", color)
            .on("click", clickRight);

        nodeEnterRight
            .append("circle")
            .attr("cy", 0)
            .attr("cx", -15)
            .attr("r", 6)
            .style("fill", "lightgray")
            .style("stroke", "black")
            .style("stroke-width", 1)
            .on("click", clickRight);

        nodeEnterRight
            .append("text")
            .attr("dy", 3.5)
            .attr("dx", 5.5)
            .attr("clip-path", "url(#clip_path2)")
            .text((d) => d.name);

        nodeEnterRight.append("url").text((d) => d.url);

        nodeEnterRight
            .transition()
            .duration(duration)
            .attr("transform", (d) => `translate(${d.y},${d.x})`)
            .style("opacity", 1);

        nodeRight
            .transition()
            .duration(duration)
            .attr("transform", (d) => `translate(${d.y},${d.x})`)
            .style("opacity", 1)
            .select("rect")
            .style("fill", color);

        nodeRight
            .exit()
            .transition()
            .duration(duration)
            .attr("transform", (d) => `translate(${source.y},${source.x})`)
            .style("opacity", 1e-6)
            .remove();

        nodesRight.forEach((d) => {
            d.x0 = d.x;
            d.y0 = d.y;
            if (d.class === "found") {
                $("#target").slimScroll({ scrollTo: `${d.x}px` });
            }
        });

        const panZoomTarget = svgPanZoom("#right", {
            fit: false,
            zoomScaleSensitivity: 0.1,
            contain: false,
            center: false,
            minZoom: 0.7,
            mouseWheelZoomEnabled: false,
        });

        document.getElementById("zoom-in-target").addEventListener("click", (ev) => {
            ev.preventDefault();
            panZoomTarget.zoomIn();
        });

        document.getElementById("zoom-out-target").addEventListener("click", (ev) => {
            ev.preventDefault();
            panZoomTarget.zoomOut();
        });

        document.getElementById("reset-target").addEventListener("click", (ev) => {
            ev.preventDefault();
            panZoomTarget.resetZoom();
            panZoomTarget.resetPan();
        });
    }

    function clickRight(d) {
        clearAll(rootRight);
        d.class = "found";
        if (d.children) {
            d._children = d.children;
            d.children = null;
        } else {
            d.children = d._children;
            d._children = null;
        }
        const collapsedTarget = $("#target_info").hasClass("collapsed-box");
        $("#target_info").load("utility/infobox", {
            uri: d.url,
            dump: "target",
            collapsed: collapsedTarget,
            project_id: {{$project->id}},
        });
        updateRight(d);
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
        return d.suggestions !== 0 ? "yellow" : "lightgrey";
    }

    function indicator(d) {
        return d.connected ? "connected" : "";
    }
</script>