sigma.classes.graph.addMethod('neighbors', function(nodeId) {
    var k,
        neighbors = {},
        index = this.allNeighborsIndex[nodeId] || {};

    for (k in index)
        neighbors[k] = this.nodesIndex[k];

    return neighbors;
});

function uniq(a) {
    var seen = {};
    return a.filter(function (item) {
        return seen.hasOwnProperty(item) ? false : (seen[item] = true);
    });
}

var s = new sigma(
    {
        renderer: {
            container: document.getElementById('sigma-container'),
            type: 'canvas'
        },
        settings: {
            scalingMode: 'outside',
            drawLabels: false,
            maxNodeSize: 10,
            minNodeSize: 2,
        }
    }
);

var graph = {
    nodes: [],
    edges: []
}

const uploadconfirm = document.getElementById('uploadconfirm').addEventListener("click", () => {
    Papa.parse(document.getElementById("uploadfile").files[0],
        {
            download: true,
            header: true,
            skipEmptyLines: true,
            complete: function (results) {
                var header = results.meta['fields']

                if (!header.includes("Titre") || !header.includes("Tags")) {
                    alert("Une colonne est manquante.");
                    return;
                }

                var ele = document.getElementsByName('rendu');
                var typeRendu = "";
                for(i = 0; i < ele.length; i++) {
                    if(ele[i].checked)
                        typeRendu = ele[i].value;
                }
                var TagArrayNode = [];

                for (let i = 0; i < results.data.length; i++) {//Node titre
                    graph.nodes.push({
                        id: i,
                        label: results.data[i].Titre,
                        x: Math.random(),
                        y: Math.random(),
                        size: 4,
                        originSize : 4,
                        color: '#0080ff',
                        originalColor: '#0080ff'
                    })
                    const TagsArrayCourant = results.data[i].Tags.split(' ');
                    TagArrayNode.push.apply(TagArrayNode, TagsArrayCourant);
                }

                TagArrayNode = uniq(TagArrayNode);

                for (let i = 0; i < TagArrayNode.length; i++) {//Node tags
                    graph.nodes.push({
                        id: i + results.data.length,
                        label: TagArrayNode[i],
                        x: Math.random(),
                        y: Math.random(),
                        size: 2,
                        originSize : 2,
                        color: '#ff0000',
                        originalColor: '#ff0000',
                        inDegree: 0
                    })
                }

                for (let i = 0; i < results.data.length; i++) {//link
                    const TagsArrayCourant = results.data[i].Tags.split(' ');

                    for (let j = 0; j < TagsArrayCourant.length; j++) {
                        var tmp = Object.keys(TagArrayNode).find(key => TagArrayNode[key] == TagsArrayCourant[j]);

                        graph.edges.push({
                            id: graph.edges.length + 1,
                            source: i,
                            target: parseInt(tmp) + parseInt(results.data.length),
                            color: '#000',
                            type: 'curvedArrow',
                            data: {
                                properties: {
                                    aString: 'abc ' + i,
                                    aBoolean: false,
                                    anInteger: i,
                                    aFloat: Math.random(),
                                    anArray: [1, 2, 3]
                                }
                            }
                        })
                        graph.nodes[parseInt(tmp) + parseInt(results.data.length)].inDegree +=1;
                    }
                }

                // Load the graph in sigma
                s.graph.read(graph);

                // Ask sigma to draw it
                s.refresh();

                if (typeRendu==="configForceLink"){
                    var fa = sigma.layouts.configForceLink(s, {
                        worker: true,
                        autoStop: true,
                        background: true,
                        scaleRatio: 30,
                        gravity: 3,
                        easing: 'cubicInOut'
                    });

                    // Bind the events:
                    fa.bind('start stop', function (e) {
                        console.log(e.type);
                        if (e.type == 'start') {
                            document.getElementById('layout-notification').classList.remove("displayNone");
                            document.getElementById('form').classList.add("displayNone");
                        }
                        if (e.type == 'stop') {
                            document.getElementById('layout-notification').classList.add("displayNone");
                            document.getElementById('reset').classList.remove("displayNone");
                        }
                    });

                    // Start the ForceLink algorithm:
                    sigma.layouts.startForceLink();
                }

                if (typeRendu==="fruchtermanReingold"){
                    var frListener = sigma.layouts.fruchtermanReingold.configure(s, {
                        iterations: 500,
                        easing: 'quadraticInOut',
                        duration: 800
                    });

                    // Bind the events:
                    frListener.bind('start stop interpolate', function (e) {
                        console.log(e.type);
                        if (e.type == 'start') {
                            document.getElementById('layout-notification').classList.remove("displayNone");
                            document.getElementById('form').classList.add("displayNone");
                        }
                        if (e.type == 'stop') {
                            document.getElementById('layout-notification').classList.add("displayNone");
                            document.getElementById('reset').classList.remove("displayNone");

                        }
                    });

                    // Start the Fruchterman-Reingold algorithm:
                    sigma.layouts.fruchtermanReingold.start(s);
                }

                if (typeRendu==="ForceAtlas2"){
                    document.getElementById('form').classList.add("displayNone");
                    document.getElementById('reset').classList.remove("displayNone");
                    s.startForceAtlas2();
                    window.setTimeout(function() {s.killForceAtlas2()}, 5000);
                }

                s.bind('clickNode', function(e) {
                    console.log("Voisin");
                    var nodeId = e.data.node.id,
                        toKeep = s.graph.neighbors(nodeId);
                    toKeep[nodeId] = e.data.node;
                    console.log(toKeep);
                    s.graph.nodes().forEach(function(n) {
                        if (toKeep[n.id])
                            n.color = '#24ff03';
                        else
                            n.color = n.originalColor;
                    });

                    s.graph.edges().forEach(function(e) {
                        if (toKeep[e.source] || toKeep[e.target]){
                            e.color = '#24ff03';
                        }
                        else
                            e.color = e.originalColor;
                    });

                    //Refresh graph to update colors
                    s.refresh();
                });

                s.bind('rightClickStage', function(e) {
                    s.graph.nodes().forEach(function(n) {
                            n.color = n.originalColor,
                            n.hidden = false;
                    });

                    s.graph.edges().forEach(function(e) {
                        e.color = '#000',
                            e.hidden = false;
                    });

                    //Refresh graph to update colors
                    s.refresh();
                });

            }
        })
})

const degree = document.getElementById('degree').addEventListener("click", () => {
    s.graph.nodes().forEach(function(n) {
        if (n.inDegree != null){
            n.size = n.inDegree;
        }
    });
    s.refresh();
})


const resetdegree = document.getElementById('resetdegree').addEventListener("click", () => {
    s.graph.nodes().forEach(function(n) {
        n.size = n.originSize;
    });
    s.refresh();
})

const download = document.getElementById('download').addEventListener("click", () => {
    s.toGEXF({
        download: true,
        filename: 'myGraph.gexf',
        nodeAttributes: 'data',
        edgeAttributes: 'data.properties',
        renderer: s.renderers[0]
    });
})

