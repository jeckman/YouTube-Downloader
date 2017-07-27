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
<?php if ($format['show_direct_url'] === true) { ?>
			<a class="btn btn-default btn-type" href="<?php echo $format['direct_url']; ?>" class="mime"><?php echo $format['type']; ?></a>
<?php }
else
{ ?>
			<span class="mime"><?php echo $format['type']; ?></span>
<?php } ?>
			
<?php if ($format['show_proxy_url'] === true) { ?>
			<a class="btn btn-primary btn-download" href="<?php echo $format['proxy_url']; ?>" class="mime"><i class="glyphicon glyphicon-download-alt"></i> Download - <?php echo $format['size']; ?></a>
<?php } ?>
			<div class="label label-warning">quality: <?php echo $format['quality']; ?></div>
		</li>
<?php } ?>
	</ul>
	<hr />
	<h2>Separated video and audio format</h2>
	<ul class="dl-list">
<?php foreach($this->get('formats', []) as $format) { ?>
		<li>
<?php if ($format['show_direct_url'] === true) { ?>
			<a class="btn btn-default btn-type" href="<?php echo $format['direct_url']; ?>" class="mime"><?php echo $format['type']; ?></a>
<?php }
else
{ ?>
			<span class="mime"><?php echo $format['type']; ?></span>
<?php } ?>
			
<?php if ($format['show_proxy_url'] === true) { ?>
			 <a class="btn btn-primary btn-download" href="<?php echo $format['proxy_url']; ?>" class="mime"><i class="glyphicon glyphicon-download-alt"></i> Download - <?php echo $format['size']; ?></a>
<?php } ?>
			<div class="label label-warning">quality: <?php echo $format['quality']; ?></div>
		</li>
<?php } ?>
	</ul>
<?php if ($this->get('showMP3Download', false) === true) { ?>
	<p align="center">Convert and Download to .mp3</p>
	<ul>
		<li>
			<a href="<?php echo $this->get('mp3_download_url'); ?>" class="mime">audio/mp3</a> (quality: <?php echo $this->get('mp3_download_quality'); ?>)
		</li>
	</ul>
<?php } ?>
	<hr />
	<p><small>Note that you initiate download either by clicking video format link or click "download" to use this server as proxy.</small></p>
<?php if ($this->get('showBrowserExtensions', false) === true) { ?>
	<p><a href="ytdl.user.js" class="userscript btn btn-mini" title="Install chrome extension to view a 'Download' link to this application on Youtube video pages."> Install Chrome Extension </a></p>
<?php } ?>
<?php } ?>
<hr />
<p class="muted pull-right"><a href="https://github.com/jeckman/YouTube-Downloader" target="_blank">Youtube Downloader <?php echo $this->get('app_version', ''); ?></a> is licensed under GPL 2.</p>
	<div class="clearfix"></div>
</div>
<?php echo $this->inc('footer.php'); ?>
