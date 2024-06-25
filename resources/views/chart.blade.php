<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Force-Directed Tree</title>
    <script src="https://d3js.org/d3.v6.min.js"></script>
    <script src="https://d3js.org/d3-scale.v3.min.js"></script>
    <script src="https://d3js.org/d3-zoom.v3.min.js"></script>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: Arial, sans-serif;
            overflow: hidden; /* Αποκρύπτουμε τυχόν προεξοχές που δημιουργούνται από το particles.js */
        }

        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: black; /* Φόντο σε περίπτωση που δεν φορτώσει το animation */
            background-repeat: repeat;
        }

        #chart-container {
            width: 100%;
            height: calc(100% - 100px);
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 1px;
            position: relative; /* Χρησιμοποιούμε position relative για να τοποθετήσουμε το #node-details */
        }

        #chart {
            width: 80vw;
            height: 80vh;
            max-width: 100%;
            max-height: 100%;
            border: 2px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            background-color: white;
        }

        .node circle {
            stroke: #fff;
            stroke-width: 2px;
            cursor: pointer;
            transition: fill 0.3s;
        }

        .node text {
            font-size: 12px;
            pointer-events: none;
            user-select: none;
        }

        .link {
            stroke: #999;
            stroke-opacity: 0.6;
        }

        .tooltip {
            position: absolute;
            text-align: center;
            padding: 8px;
            font-size: 14px;
            font-weight: bold;
            color: #fff;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 8px;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .node:hover circle {
            fill: #2980b9;
        }

        svg {
            overflow: hidden;
        }

        #footer {
            background-color: whitesmoke;
            border-top: 2px solid #ddd;
            padding: 50px;
            text-align: center;
            position: relative;
            margin: 0 auto;
            bottom: 0;
        }

        #footer-image-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        #footer-image-container img {
            max-width: 100%;
            max-height: 100%;
            display: block;
            margin: 0 auto;
        }

        .selected circle {
            fill: yellow;
        }

        #node-details {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            display: none; /* Αρχικά αποκρύπτουμε τα node-details */
            z-index: 1000; /* Επικάλυψη για να είναι πάνω από τον χάρτη */
        }
    </style>
</head>

<body>
    <div id="particles-js"></div>
    <div id="chart-container" class="container-fluid">
        <div id="chart"></div>
    </div>
    <div id="node-details">
        <h4>Λεπτομέρειες Κόμβου</h4>
        <div id="node-id"></div>
        <div id="node-connections"></div>
    </div>
    <div id="footer">
        <div id="footer-image-container">
            <img src="https://okfn.gr/wp-content/uploads/2023/11/okfn-newlogo-gr-1.svg" alt="Footer Image">
        </div>
    </div>
    <div class="tooltip"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch('/newalignment/public/tree-data')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data fetched:', data);
                    const width = window.innerWidth;
                    const height = window.innerHeight;

                    const svg = d3.select("#chart").append("svg")
                        .attr("width", width)
                        .attr("height", height)
                        .append("g");

                    const container = svg.append("g");

                    const zoom = d3.zoom()
                        .scaleExtent([0.1, 10])
                        .on("zoom", zoomed);

                    d3.select("#chart")
                        .call(zoom);

                    const simulation = d3.forceSimulation(data.nodes)
                        .force("link", d3.forceLink(data.links).id(d => d.id).distance(50).strength(1))
                        .force("charge", d3.forceManyBody().strength(-200))
                        .force("center", d3.forceCenter(width / 2, height / 2));

                    const link = container.append("g")
                        .selectAll("line")
                        .data(data.links)
                        .enter().append("line")
                        .attr("class", "link");

                    const node = container.append("g")
                        .selectAll(".node")
                        .data(data.nodes)
                        .enter().append("g")
                        .attr("class", d => `node ${d.group} ${d.size === 'large' ? 'large' : ''}`)
                        .call(d3.drag()
                            .on("start", dragstarted)
                            .on("drag", dragged)
                            .on("end", dragended))
                        .on("click", (event, d) => {
                            updateNodeDetails(d);
                            d3.select('.node.selected').classed('selected', false); 
                            d3.select(event.currentTarget).classed('selected', true);
                        });

                    node.append("circle")
                        .attr("r", 8)
                        .attr("fill", d => d.details > 3 ? "orange" : "#3498db")
                        .attr("stroke", "#fff")
                        .attr("stroke-width", 2);

                    node.append("text")
                        .text(d => d.id)
                        .attr("dx", 12)
                        .attr("dy", 4);

                    data.nodes.forEach(node => {
                        node.numConnections = data.links.filter(link => link.source === node.id || link.target === node.id).length;
                    });

                    const tooltip = d3.select(".tooltip");

                    node.on("mouseover", (event, d) => {
                        tooltip.transition()
                            .duration(200)
                            .style("opacity", 0.9);
                        tooltip.html(`Name: ${d.id}<br>Number: ${d.details}`)
                            .style("left", (event.pageX + 10) + "px")
                            .style("top", (event.pageY + 10) + "px");
                    })
                        .on("mouseout", () => {
                            tooltip.transition()
                                .duration(500)
                                .style("opacity", 0);
                        });

                    simulation.on("tick", () => {
                        link
                            .attr("x1", d => d.source.x)
                            .attr("y1", d => d.source.y)
                            .attr("x2", d => d.target.x)
                            .attr("y2", d => d.target.y);

                        node.attr("transform", d => `translate(${d.x},${d.y})`);
                    });

                    function dragstarted(event, d) {
                        if (!event.active) simulation.alphaTarget(0.3).restart();
                        d.fx = d.x;
                        d.fy = d.y;
                    }

                    function dragged(event, d) {
                        d.fx = event.x;
                        d.fy = event.y;
                    }

                    function dragended(event, d) {
                        if (!event.active) simulation.alphaTarget(0);
                        d.fx = null;
                        d.fy = null;
                    }

                    function zoomed({ transform }) {
                        container.attr("transform", transform);
                    }

                    function updateNodeDetails(nodeData) {
                        const nodeDetailsContainer = document.getElementById('node-details');
                        const nodeIdElement = document.getElementById('node-id');
                        const nodeConnectionsElement = document.getElementById('node-connections');

                        nodeIdElement.textContent = `ID: ${nodeData.id}`;
                        nodeConnectionsElement.textContent = `Connections: ${nodeData.details}`;

                        nodeDetailsContainer.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                });

            // Ρύθμιση του particles.js για το animated background
            particlesJS('particles-js', {
                "particles": {
                    "number": {
                        "value": 80,
                        "density": {
                            "enable": true,
                            "value_area": 800
                        }
                    },
                    "color": {
                        "value": "#ffffff"
                    },
                    "shape": {
                        "type": "circle",
                        "stroke": {
                            "width": 0,
                            "color": "#000000"
                        },
                        "polygon": {
                            "nb_sides": 5
                        }
                    },
                    "opacity": {
                        "value": 0.5,
                        "random": false,
                        "anim": {
                            "enable": false,
                            "speed": 1,
                            "opacity_min": 0.1,
                            "sync": false
                        }
                    },
                    "size": {
                        "value": 3,
                        "random": true,
                        "anim": {
                            "enable": false,
                            "speed": 40,
                            "size_min": 0.1,
                            "sync": false
                        }
                    },
                    "line_linked": {
                        "enable": true,
                        "distance": 150,
                        "color": "#ffffff",
                        "opacity": 0.4,
                        "width": 1
                    },
                    "move": {
                        "enable": true,
                        "speed": 6,
                        "direction": "none",
                        "random": false,
                        "straight": false,
                        "out_mode": "out",
                        "bounce": false,
                        "attract": {
                            "enable": false,
                            "rotateX": 600,
                            "rotateY": 1200
                        }
                    }
                },
                "interactivity": {
                    "detect_on": "canvas",
                    "events": {
                        "onhover": {
                            "enable": true,
                            "mode": "repulse"
                        },
                        "onclick": {
                            "enable": true,
                            "mode": "push"
                        },
                        "resize": true
                    },
                    "modes": {
                        "grab": {
                            "distance": 400,
                            "line_linked": {
                                "opacity": 1
                            }
                        },
                        "bubble": {
                            "distance": 400,
                            "size": 40,
                            "duration": 2,
                            "opacity": 8,
                            "speed": 3
                        },
                        "repulse": {
                            "distance": 200,
                            "duration": 0.4
                        },
                        "push": {
                            "particles_nb": 4
                        },
                        "remove": {
                            "particles_nb": 2
                        }
                    }
                },
                "retina_detect": true
            });
        });
    </script>
</body>

</html>
