/**
 * OpenDrop.
 *
 * Based on Carson McDonald's direct-browser-s3-upload-example.
 * https://github.com/carsonmcdonald/direct-browser-s3-upload-example
 *
 * @author   Patrick Schroen / https://github.com/pschroen
 * @license  MIT Licensed
 */

/*jshint
 strict:true, boss:true, eqeqeq:true, newcap:false,
 loopfunc:true, shadow:true, browser:true, indent:4
*/

var filesloaded = [],
    total = 0;
function createCORSRequest(method, url) {
    "use strict";
    var xhr = new XMLHttpRequest();
    if ('withCredentials' in xhr) {
        xhr.open(method, url, true);
    } else if (typeof XDomainRequest !== 'undefined') {
        xhr = new XDomainRequest();
        xhr.open(method, url);
    } else {
        xhr = null;
    }
    return xhr;
}

function handleFileSelect(evt) {
    "use strict";
    setProgress(0, "Upload started.");
    var files = evt.target.files,
        list = [];
    for (var i = 0, f; f = files[i]; i++) {
        filesloaded[i] = 0;
        total += f.size;
        uploadFile(i, f);
        list.push({
            name: f.name,
            type: f.type,
            size: f.size
        });
    }
    var file = new File([JSON.stringify(list)], 'filelist.json', {type:'text/plain', lastModified:new Date()});
    filesloaded[i] = 0;
    total += file.size;
    uploadFile(i, file);
}

function handleOpenDropClick(evt) {
    "use strict";
    evt.stopPropagation();
    evt.preventDefault();
    setProgress(0, "Waiting for upload.");
    document.getElementById('files').click();
}

function handleDrop(evt) {
    "use strict";
    evt.stopPropagation();
    evt.preventDefault();
    setProgress(0, "Upload started.");
    var files = evt.dataTransfer.files,
        list = [];
    for (var i = 0, f; f = files[i]; i++) {
        filesloaded[i] = 0;
        total += f.size;
        uploadFile(i, f);
        list.push({
            name: f.name,
            type: f.type,
            size: f.size
        });
    }
    var file = new File([JSON.stringify(list)], 'filelist.json', {type:'text/plain', lastModified:new Date()});
    filesloaded[i] = 0;
    total += file.size;
    uploadFile(i, file);
}

function handleDragOver(evt) {
    "use strict";
    evt.stopPropagation();
    evt.preventDefault();
    evt.dataTransfer.dropEffect = 'copy';
}

function executeOnSignedUrl(file, callback) {
    "use strict";
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/signput.php?box='+location.pathname.substring(1)+'&name='+file.name+'&type='+file.type, true);
    // Hack to pass bytes through unprocessed.
    xhr.overrideMimeType('text/plain; charset=x-user-defined');
    xhr.onreadystatechange = function (e) {
        if (this.readyState === 4 && this.status === 200) {
            callback(decodeURIComponent(this.responseText));
        } else if (this.readyState === 4 && this.status !== 200) {
            setProgress(0, "Could not contact signing script: "+this.status);
        }
    };
    xhr.send();
}

function uploadFile(index, file) {
    "use strict";
    executeOnSignedUrl(file, function (signedURL) {
        uploadToS3(index, file, signedURL);
    });
}

function uploadToS3(index, file, url) {
    "use strict";
    var xhr = createCORSRequest('PUT', url);
    if (!xhr) {
        setProgress(0, "CORS not supported.");
    } else {
        xhr.onload = function () {
            if (xhr.status === 200) {
                var loaded = 0;
                for (var i = 0; i < filesloaded.length; i++) loaded += filesloaded[i];
                if (loaded === total) {
                    setProgress(100, "Upload completed.");
                    location.reload();
                }
            } else {
                setProgress(0, "Upload error: "+xhr.status);
            }
        };
        xhr.onerror = function () {
            setProgress(0, "XHR error.");
        };
        xhr.upload.onprogress = function (e) {
            if (e.lengthComputable) {
                filesloaded[index] = e.loaded;
                var loaded = 0;
                for (var i = 0; i < filesloaded.length; i++) loaded += filesloaded[i];
                var percentLoaded = Math.round((loaded/total)*100);
                setProgress(percentLoaded, percentLoaded === 100 ? "Finalizing..." : "Uploading...");
            }
        };
        xhr.setRequestHeader('Content-Type', file.type);
        xhr.setRequestHeader('x-amz-acl', 'public-read');
        xhr.send(file);
    }
}

function setProgress(percent, statusLabel) {
    "use strict";
    var progress = document.querySelector('.percent');
    progress.textContent = percent+'%';
    document.getElementById('status').innerHTML = statusLabel;
}

document.getElementById('files').addEventListener('change', handleFileSelect, false);
document.getElementById('opendrop').addEventListener('click', handleOpenDropClick, false);
document.body.addEventListener('dragover', handleDragOver, false);
document.body.addEventListener('drop', handleDrop, false);
