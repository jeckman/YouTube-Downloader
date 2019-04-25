// ==UserScript==
// @name            Youtube Downloader
// @description     Adds a link on youtube pages to download the video via proxy server.
//
// @author          Sepehr Lajevardi <me@sepehr.ws> modded by ewwink
// @namespace       http://github.com/sepehr
//
// @version         1.2
// @license         GPLv3 - http://www.gnu.org/licenses/gpl-3.0.txt
// @copyright       Copyright (C) 2013, by Sepehr Lajevardi <me@sepehr.ws>
//
// @include         http*://*.youtube.com/*
// @grant           none
// ==/UserScript==

/**
 * PHP script for downloading videos from youtube
 * Copyright (C) 2012-2018  John Eckman
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

// ------------------------------------------------------------------------
// Helpers
// ------------------------------------------------------------------------
window.location.getParam = function(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        result = regex.exec(window.location.search);

    if (result == null) {
        return false;
    }

    return decodeURIComponent(result[1].replace(/\+/g, ' '));
};
// ------------------------------------------------------------------------

/**
 * Youtube downloader.
 *
 * @see http://wiki.greasespot.net/API_reference
 * @see http://wiki.greasespot.net/Metadata_Block
 */

function YoutubeDownloader() {
    var vid = window.location.getParam('v'),
        // Update this to point to your own installation:
        link = 'http://UPDATE_THIS_IN_CODE.com/getvideo.php?videoid=' + vid + '&type=Download',
        downloadButton = document.querySelector('#downloadButton');

    if (downloadButton) {
        downloadButton.href = link;
    } else {
        var wrapper = document.querySelector('#top-row ytd-video-owner-renderer'),
            btn = document.createElement('a');
        if (wrapper && vid) {
            // Assemble the button:
            btn.setAttribute('href', link);
            btn.setAttribute('id', "downloadButton");
            btn.setAttribute('style', 'text-decoration: none; margin: 7px 5px;');

            // Child elements:
            btn.innerHTML = '<paper-button class="ytd-subscribe-button-renderer">Download</paper-button>';

            // Append it:
            wrapper.appendChild(btn);
        }
    }
}

var oldURL, newURL;
setInterval(function() {
    newURL = window.location.href;
    if (oldURL !== newURL) {
        oldURL = newURL;
        console.log('page changed');
        YoutubeDownloader();
    }
}, 500);

