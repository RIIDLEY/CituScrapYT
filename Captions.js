var getSubtitles = require('youtube-captions-scraper').getSubtitles;
const ObjectsToCsv = require('objects-to-csv');
var arguments = process.argv;

var idvideo = arguments[2];
var lang = arguments[3];

    getSubtitles({
        videoID: arguments[2], // youtube video id
        lang: arguments[3] // default: `en`
    }).then(function(captions) {
        const csv = new ObjectsToCsv(captions);//prepare CSV
        var filename = 'CSV/Captions_' + idvideo + '_' + lang + '.csv';
        csv.toDisk(filename);//ecris le CSV dans un fichier
        console.log("Done");//send etat au php
    }).catch(err => console.log('403'));


