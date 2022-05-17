var getSubtitles = require('youtube-captions-scraper').getSubtitles;
const ObjectsToCsv = require('objects-to-csv');
var arguments = process.argv;

var idvideo = arguments[2];

getSubtitles({
    videoID: arguments[2], // youtube video id
    lang: arguments[3] // default: `en`
}).then(function(captions) {
    const csv = new ObjectsToCsv(captions);
    var filename = 'CSV/fileCaptions_' + idvideo + '.csv';
    csv.toDisk(filename);
    console.log("Done");
});
