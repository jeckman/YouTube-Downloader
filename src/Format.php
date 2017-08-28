<?php

namespace YoutubeDownloader;

@trigger_error('The '.__NAMESPACE__.'\Format class is deprecated since version 0.4 and will be removed in 0.5. Use the YoutubeDownloader\Provider\Youtube\Format class instead.', E_USER_DEPRECATED);

use YoutubeDownloader\Provider\Youtube\Format as YoutubeFormat;

/**
 * a video format
 *
 * @deprecated since version 0.4, to be removed in 0.5. Use `YoutubeDownloader\Provider\Youtube\Format` instead
 */
class Format extends YoutubeFormat {}
