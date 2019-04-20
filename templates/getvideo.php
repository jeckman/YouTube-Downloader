<?php echo $this->inc('header.php', ['title' => 'Youtube Downloader Results']); ?>
<div class="well">
	<h1 class="download-heading">Youtube Downloader Results</h1>
	<hr />
	<div id="info">
<?php if ($this->get('show_thumbnail') === true) { ?>
		<a href="<?php echo $this->get('thumbnail_anchor'); ?>" target="_blank"><img src="<?php echo $this->get('thumbnail_src'); ?>" border="0" hspace="2" vspace="2"></a>
<?php } ?>
		<p><?php echo $this->get('video_title'); ?></p>
	</div>
<?php if ($this->get('no_stream_map_found', false) === true) { ?>
	<p>No encoded format stream found.</p>
	<p>Here is what we got from YouTube:</p>
	<pre>
		<?php echo $this->get('no_stream_map_found_dump'); ?>
	</pre>
<?php }
else
{ ?>
<?php if ($this->get('show_debug1', false) === true) { ?>
	<pre>
		<?php echo $this->get('debug1'); ?>
	</pre>
<?php } ?>
<?php if ($this->get('show_debug2', false) === true) { ?>
	<p>These links will expire at <?php echo $this->get('debug2_expires'); ?></p>
	<p>The server was at IP address <?php echo $this->get('debug2_ip'); ?> which is an <?php echo $this->get('debug2ipbits'); ?> bit IP address. Note that when 8 bit IP addresses are used, the download links may fail.</p>
<?php } ?>
	<h2>List of available formats for download</h2>
	<ul class="dl-list">
<?php foreach($this->get('streams', []) as $format) { ?>
		<li>
			<a class="btn btn-default btn-type disabled" href="#"><?php echo $format['type']; ?> - <?php echo $format['quality']; ?></a>
<?php if ($format['show_direct_url'] === true) { ?>
			<a class="btn btn-default btn-download" href="<?php echo $format['direct_url']; ?>" class="mime"><i class="glyphicon glyphicon-download-alt"></i> Direct</a>
<?php } ?>
<?php if ($format['show_proxy_url'] === true) { ?>
			<a class="btn btn-primary btn-download" href="<?php echo $format['proxy_url']; ?>" class="mime"><i class="glyphicon glyphicon-download-alt"></i> Proxy</a>
<?php } ?>
			<div class="label label-warning"><?php echo $format['size']; ?></div>
			<div class="label label-default"><?php echo $format['itag']; ?></div>
		</li>
<?php } ?>
	</ul>
	<hr />
	<h2>Separated video and audio format</h2>
	<ul class="dl-list">
<?php foreach($this->get('formats', []) as $format) { ?>
	<li>
		<a class="btn btn-default btn-type disabled" href="#"><?php echo $format['type']; ?> - <?php echo $format['quality']; ?></a>
<?php if ($format['show_direct_url'] === true) { ?>
		<a class="btn btn-default btn-download" href="<?php echo $format['direct_url']; ?>" class="mime"><i class="glyphicon glyphicon-download-alt"></i> Direct</a>
<?php } ?>
<?php if ($format['show_proxy_url'] === true) { ?>
		<a class="btn btn-primary btn-download" href="<?php echo $format['proxy_url']; ?>" class="mime"><i class="glyphicon glyphicon-download-alt"></i> Proxy</a>
<?php } ?>
		<div class="label label-warning"><?php echo $format['size']; ?></div>
		<div class="label label-default"><?php echo $format['itag']; ?></div>
	</li>
<?php } ?>
	</ul>
<?php if ($this->get('showMP3Download', false) === true) { ?>
	<h2>Convert and Download to .mp3</h2>
	<ul class="dl-list">
		<li>
			<a class="btn btn-default btn-type disabled" href="#" class="mime">audio/mp3 - <?php echo $this->get('mp3_download_quality'); ?></a>
			<a class="btn btn-primary btn-download" href="<?php echo $this->get('mp3_download_url'); ?>" class="mime"><i class="glyphicon glyphicon-download-alt"></i> Convert and Download</a>
		</li>
	</ul>
<?php } ?>
	<hr />
	<p><small>Note that you initiate download either by clicking "Direct" to download from the origin server or by clicking "Proxy" to use this server as proxy.</small></p>
<?php if ($this->get('showBrowserExtensions', false) === true) { ?>
	<p><a href="ytdl.user.js" class="userscript btn btn-mini" title="Install chrome extension to view a 'Download' link to this application on Youtube video pages."> Install Chrome Extension </a></p>
<?php } ?>
<?php } ?>
<hr />
<p class="muted pull-right"><a href="https://github.com/jeckman/YouTube-Downloader" target="_blank">Youtube Downloader <?php echo $this->get('app_version', ''); ?></a> is licensed under GPL 2.</p>
	<div class="clearfix"></div>
</div>
<?php echo $this->inc('footer.php'); ?>
