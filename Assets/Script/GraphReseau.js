sigma.classes.graph.addMethod('neighbors', function(nodeId) {//ajoute la méthode pour trouver les voisins
    var k,
        neighbors = {},
        index = this.allNeighborsIndex[nodeId] || {};

    for (k in index)
        neighbors[k] = this.nodesIndex[k];

    return neighbors;
});

function uniq(a) {//fonction permettant de supprimer les doublons
    var seen = {};
    return a.filter(function (item) {
        return seen.hasOwnProperty(item) ? false : (seen[item] = true);
    });
}

//init le graphique sigma
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

    let filename = document.getElementById("uploadfile").value;//get le nom du document envoyé
    let extension = filename.split(".").pop();//prend l'extension
    if (extension != 'csv'){//verifie l'extension
        alert("Type de fichier selectionné incorrect");
        return;
    }
    //lis le fichier CSV
    Papa.parse(document.getElementById("uploadfile").files[0],
        {
            download: true,
            header: true,
            skipEmptyLines: true,
            complete: function (results) {
                var header = results.meta['fields'];//Get le titre des colonnes

                if (!header.includes("Titre") || !header.includes("Tags")) {//verifie si les necessaires existes
                    alert("Une colonne est manquante.");
                    return;
                }

                var ele = document.getElementsByName('rendu');//Get le rendu que veut l'utilisateur
                var typeRendu = "";
                for(i = 0; i < ele.length; i++) {//parcours les boutons radio
                    if(ele[i].checked)
                        typeRendu = ele[i].value;
                }
                var TagArrayNode = [];

                for (let i = 0; i < results.data.length; i++) {//Met les nodes titres
                    graph.nodes.push({
                        id: i,
                        label: results.data[i].Titre,
                        x: Math.random(),
                        y: Math.random(),
                        size: 4,
                        originSize : 4,
                        color: '#0080ff',
                        originalColor: '#0080ff',
                        outDegree : 0
                    })
                    const TagsArrayCourant = results.data[i].Tags.split(' ');//get les tags de la ligne courante
                    TagArrayNode.push.apply(TagArrayNode, TagsArrayCourant);//push dans le tableau
                }

                TagArrayNode = uniq(TagArrayNode);//supprime les Tags en double

                //remove_len_15(TagArrayNode);

                for (let i = 0; i < TagArrayNode.length; i++) {//Met les nodes tags
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

                for (let i = 0; i < results.data.length; i++) {//réalisation des liaisons entre les titres et les tags
                    const TagsArrayCourant = results.data[i].Tags.split(' ');//recupere les tags par rapport au titre de la vidéo courante

                    for (let j = 0; j < TagsArrayCourant.length; j++) {//parcours les tags
                        var tmp = Object.keys(TagArrayNode).find(key => TagArrayNode[key] == TagsArrayCourant[j]);//get l'id node du tags
                        graph.edges.push({//fait la liaison
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
                        graph.nodes[parseInt(tmp) + parseInt(results.data.length)].inDegree +=1;//+1 au nombre de degré entant dans le node tags
                        graph.nodes[i].outDegree += 1;//+1 au nombre de degré sortant du node titre
                    }
                }

                // Charge le tableau de node et de link dans sigma
                s.graph.read(graph);

                // Dessine le graph
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
                            document.getElementById('layout-notification').classList.remove("displayNone");//Met l'animation de chargement
                            document.getElementById('form').classList.add("displayNone");//Cache le formulaire d'envoie du fichier CSV
                        }
                        if (e.type == 'stop') {
                            document.getElementById('layout-notification').classList.add("displayNone");//retirer l'animation de chargement
                            document.getElementById('reset').classList.remove("displayNone");//Affiche la barre d'outils 1
                            document.getElementById('tools').classList.remove("displayNone");//Affiche la barre d'outils 2
                            s.refresh();
                        }
                    });

                    // Start the ForceLink algorithm:
                    sigma.layouts.startForceLink();
                }

                //Selection du type de rendu

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
                            document.getElementById('layout-notification').classList.remove("displayNone");//Met l'animation de chargement
                            document.getElementById('form').classList.add("displayNone");//Cache le formulaire d'envoie du fichier CSV
                        }
                        if (e.type == 'stop') {
                            document.getElementById('layout-notification').classList.add("displayNone");//retirer l'animation de chargement
                            document.getElementById('reset').classList.remove("displayNone");//Affiche la barre d'outils 1
                            document.getElementById('tools').classList.remove("displayNone");//Affiche la barre d'outils 2
                            s.refresh();
                        }
                    });

                    // Start the Fruchterman-Reingold algorithm:
                    sigma.layouts.fruchtermanReingold.start(s);
                }

                if (typeRendu==="ForceAtlas2"){
                    document.getElementById('form').classList.add("displayNone");//Cache le formulaire d'envoie du fichier CSV
                    document.getElementById('reset').classList.remove("displayNone");//Affiche la barre d'outils 1
                    document.getElementById('tools').classList.remove("displayNone");//Affiche la barre d'outils 2
                    s.startForceAtlas2();
                    window.setTimeout(function() {s.killForceAtlas2()}, 5000);
                }

                s.bind('clickNode', function(e) {
                    console.log("Voisin");
                    console.log(e.data.node.outDegree);
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

const degreeEntrant = document.getElementById('degreeEntrant').addEventListener("click", () => {
    s.graph.nodes().forEach(function(n) {
        n.size = n.originSize;
    });
    s.refresh();

    s.graph.nodes().forEach(function(n) {
        if (n.inDegree != null){
            n.size = n.inDegree;
        }
    });
    s.refresh();
})

const degreeSortant = document.getElementById('degreeSortant').addEventListener("click", () => {
    s.graph.nodes().forEach(function(n) {
        n.size = n.originSize;
    });
    s.refresh();

    s.graph.nodes().forEach(function(n) {
        if (n.outDegree != null){
            n.size = n.outDegree;
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

const textEtat = document.getElementById('textEtat').addEventListener("click", () => {
    if(s.settings('drawLabels')){
        s.settings('drawLabels', false);
    }else {
        s.settings('drawLabels', true);
    }
    s.refresh();


})

