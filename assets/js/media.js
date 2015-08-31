/**
 * Opendrop.
 *
 * @author   Patrick Schroen / https://github.com/pschroen
 * @license  MIT Licensed
 */

/*jshint
 strict:true, boss:true, eqeqeq:true, newcap:false,
 loopfunc:true, shadow:true, browser:true, indent:4
*/

function toTimer(time) {
    "use strict";
    var h, m, s;
    h = Math.floor(time/3600);
    h = isNaN(h) ? '--' : h > 9 ? h : '0'+h;
    m = Math.floor(time/60%60);
    m = isNaN(m) ? '--' : m > 9 ? m : '0'+m;
    s = Math.floor(time%60);
    s = isNaN(s) ? '--' : s > 9 ? s : '0'+s;
    return h+':'+m+':'+s;
}

var background = document.getElementsByClassName('background')[0],
    container = document.getElementsByClassName('container')[0],
    controls = document.getElementsByClassName('controls')[0],
    timecode = controls.getElementsByTagName('code')[0],
    buttons = controls.getElementsByTagName('span'),
    audio = null,
    video = null,
    nowplaying = null,
    timeout = null;

var mediaElements = document.getElementsByClassName('media');
for (var i = 0; i < mediaElements.length; i++) {
    var element = mediaElements[i],
        type = element.getAttribute('data-type'),
        media = null;

    if (/audio/.test(type)) {
        if (!audio) {
            audio = document.createElement('audio');
            audio.ontimeupdate = function () {
                "use strict";
                timecode.innerHTML = toTimer(audio.currentTime);
            };
        }
        media = audio;
    }
    if (/video/.test(type)) {
        if (!video) {
            video = document.createElement('video');
            video.ontimeupdate = function () {
                "use strict";
                timecode.innerHTML = toTimer(video.currentTime);
            };
            background.appendChild(video);
        }
        media = video;
    }
    if (media && media.canPlayType(type)) {
        element.onclick = function () {
            "use strict";
            var href = this.getAttribute('href'),
                type = this.getAttribute('data-type'),
                media = /audio/.test(type) ? audio : /video/.test(type) ? video : null;

            if (media) {
                container.className = 'container fadeout';
                media.src = href;
                media.type = type;
                media.play();
                nowplaying = media;
                background.className = 'background fadein';
                showControls();
            }
            return false;
        };
        element.className = 'media canplay';
    }
}

buttons[0].onclick = function () { // esc
    "use strict";
    if (nowplaying) mediaStop();
    return false;
};

buttons[1].onclick = function () { // space
    "use strict";
    if (nowplaying) mediaTogglePlay();
    return false;
};

buttons[2].onclick = function () { // left
    "use strict";
    if (nowplaying) mediaRewind();
    return false;
};

buttons[3].onclick = function () { // right
    "use strict";
    if (nowplaying) mediaFastForward();
    return false;
};

window.onkeydown = function (e) {
    "use strict";
    if (nowplaying) {
        if (e.keyCode === 27) { // esc
            mediaStop();
        } else {
            if (e.keyCode === 32) { // space
                mediaTogglePlay();
            } else if (e.keyCode === 37) { // left
                mediaRewind();
            } else if (e.keyCode === 39) { // right
                mediaFastForward();
            }
            showControls();
        }
    }
};

window.onmousemove = function (e) {
    "use strict";
    if (nowplaying) showControls();
};

function showControls() {
    "use strict";
    clearTimeout(timeout);
    controls.className = 'controls fadein';
    timeout = setTimeout(function () {
        controls.className = 'controls fadeout';
    }, 5000);
}

function mediaStop() {
    "use strict";
    nowplaying.pause();
    controls.className = 'controls fadeout';
    container.className = 'container fadein';
    nowplaying = null;
}

function mediaTogglePlay() {
    "use strict";
    if (!nowplaying.paused) {
        nowplaying.pause();
    } else {
        nowplaying.play();
    }
}

function mediaRewind() {
    "use strict";
    nowplaying.currentTime -= 30;
}

function mediaFastForward() {
    "use strict";
    nowplaying.currentTime += 30;
}
