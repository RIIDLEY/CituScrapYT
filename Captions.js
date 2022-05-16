var getSubtitles = require('youtube-captions-scraper').getSubtitles;
const fs = require("fs");

const ObjectsToCsv = require('objects-to-csv');
var arguments = process.argv;

if (fs.existsSync("captions.csv")) {
    fs.unlinkSync("captions.csv");
}

getSubtitles({
    videoID: arguments[2], // youtube video id
    lang: arguments[3] // default: `en`
}).then(function(captions) {
    const csv = new ObjectsToCsv(captions);
    csv.toDisk('captions.csv');
    console.log("Done");
});
