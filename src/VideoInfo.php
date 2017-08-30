<?php

namespace YoutubeDownloader;

@trigger_error('The '.__NAMESPACE__.'\VideoInfo class is deprecated since version 0.4 and will be removed in 0.5. Use the YoutubeDownloader\Provider\Youtube\VideoInfo class instead.', E_USER_DEPRECATED);

use YoutubeDownloader\Provider\Youtube\VideoInfo as YoutubeVideoInfo;

/**
 * VideoInfo
 *
 * @deprecated since version 0.4, to be removed in 0.5. Use `YoutubeDownloader\Provider\Youtube\VideoInfo` instead
 */
class VideoInfo extends YoutubeVideoInfo {}
