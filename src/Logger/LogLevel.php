<?php

namespace YoutubeDownloader\Logger;

/**
 * Describes log levels
 *
 * This class is compatible with PSR-3 Psr\Log\LogLevel
 */
class LogLevel
{
	const EMERGENCY = 'emergency';
	const ALERT     = 'alert';
	const CRITICAL  = 'critical';
	const ERROR     = 'error';
	const WARNING   = 'warning';
	const NOTICE    = 'notice';
	const INFO      = 'info';
	const DEBUG     = 'debug';
}
