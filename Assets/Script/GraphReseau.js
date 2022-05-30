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
            enableEdgeHovering: true,
            edgeHoverColor: 'edge',
            defaultEdgeHoverColor: '#000',
            edgeHoverSizeRatio: 1,
            edgeHoverExtremities: true
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

                for(i = 0; i < ele.length; i++) {
                    if(ele[i].checked)
                        console.log(ele[i].value);
                }
                var TagArrayNode = [];

                for (let i = 0; i < results.data.length; i++) {//Node titre
                    graph.nodes.push({
                        id: i,
                        label: results.data[i].Titre,
                        x: Math.random(),
                        y: Math.random(),
                        size: 4,
                        color: '#0080ff'
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
                        color: '#ff0000'
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
                            hover_color: '#00ff2f'
                        })
                    }
                }

                /*
                            for (let i = 0; i < results.data.length; i++) {//link
                              graph.edges.push({
                                id: i,
                                source: i,
                                target: results.data.length,
                                color: '#202020',
                                type: 'curvedArrow'
                              })
                            }*/

                // Load the graph in sigma
                //console.log(graph.nodes.length);
                s.graph.read(graph);
                // Ask sigma to draw it

                s.refresh();
                /*
                s.bind('rightClickNode', function(e) {
                  console.log(e.type, e.data.node.label, e.data.captor);
                })*/


                /*
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
                  document.getElementById('layout-notification').style.visibility = '';
                  if (e.type == 'start') {
                    document.getElementById('layout-notification').style.visibility = 'visible';
                  }
                });

                // Start the ForceLink algorithm:
                sigma.layouts.startForceLink();*/


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
        })
})
