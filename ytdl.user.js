// ==UserScript==
// @name            Youtube Downloader
// @description     Adds a link on youtube pages to download the video via proxy server.
//
// @author          Sepehr Lajevardi <me@sepehr.ws> modded by ewwink
// @namespace       http://github.com/sepehr
//
// @version         1.1
// @license         GPLv3 - http://www.gnu.org/licenses/gpl-3.0.txt
// @copyright       Copyright (C) 2013, by Sepehr Lajevardi <me@sepehr.ws>
//
// @include         http*://*.youtube.com/*
// @grant           none
// ==/UserScript==

/**
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

    var wrapper = document.getElementById('watch7-subscription-container'),
        btn = document.createElement('a'),
        vid = window.location.getParam('v'),
        style = document.createElement('style'),
        head = document.getElementsByTagName('head')[0],
        // Update this to point to your own installation:
        link = 'http://UPDATE_THIS_IN_CODE.com/getvideo.php?videoid=' + vid + '&type=Download';

    if (wrapper && vid) {
        // Assemble the button:
        btn.type = 'button';
        btn.setAttribute('href', link);
        btn.setAttribute('role', 'button');
        btn.setAttribute('style', 'line-height:inherit;height:23px;border-color:#b3b3b3 !important');
        btn.setAttribute('class', 'yt-uix-subscription-button yt-uix-button yt-uix-button-subscribe-branded');

        // Child elements:
        btn.innerHTML = '<span class="yt-uix-button-icon-wrapper" style="background:#b3b3b3;border-color:#b3b3b3">\
			<img class="guide-management-plus-icon" src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif">\
			<span class="yt-uix-button-valign"></span>\
		</span>\
		<span class="yt-uix-button-content">\
			<span class="subscribe-label">Download</span>\
			<span class="unsubscribe-label"></span>\
		</span>';

        // Append it:
        wrapper.appendChild(btn);

        // Style:
        style.type = 'text/css';
        style.innerHTML = '#watch7-subscription-container .yt-uix-button-subscription-container { float: left !important; margin-left: 10px !important; }';
        head.appendChild(style);
    }
}

var fireOnHashChangesToo = true;
var oldWatchCount = document.getElementsByClassName('watch-view-count')[0].innerHTML;
var pageURLCheckTimer = setInterval(function() {
    var newWatchCount = document.getElementsByClassName('watch-view-count')[0].innerHTML;
    if (this.lastPathStr !== location.pathname || this.lastQueryStr !== location.search || (fireOnHashChangesToo && this.lastHashStr !== location.hash || oldWatchCount != newWatchCount)) {
        oldWatchCount = newWatchCount;
        this.lastPathStr = location.pathname;
        this.lastQueryStr = location.search;
        this.lastHashStr = location.hash;
        console.log('page changed');
        YoutubeDownloader();
    }
}, 500);
