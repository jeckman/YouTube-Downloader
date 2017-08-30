<?php
// Because we will processing 1MB of file I will not use RegExp for processing strings

namespace YoutubeDownloader;

@trigger_error('The '.__NAMESPACE__.'\SignatureDecipher class is deprecated since version 0.4 and will be removed in 0.5. Use the YoutubeDownloader\Provider\Youtube\SignatureDecipher class instead.', E_USER_DEPRECATED);

use YoutubeDownloader\Provider\Youtube\SignatureDecipher as YoutubeSignatureDecipher;

/**
 * a youtube signatur decipher
 *
 * @deprecated since version 0.4, to be removed in 0.5. Use `YoutubeDownloader\Provider\Youtube\SignatureDecipher` instead
 */
class SignatureDecipher extends YoutubeSignatureDecipher {}
