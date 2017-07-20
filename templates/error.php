<?php echo $this->inc('header.php', ['title' => 'Youtube Downloader Error']); ?>
<div class="download">
	<h1 class="download-heading">Youtube Downloader Error</h1>
	<p><?php echo $this->get('error_message'); ?></p>
	<hr />
	<p class="muted"><a href="https://github.com/jeckman/YouTube-Downloader" target="_blank">Youtube Downloader <?php echo $this->get('app_version', ''); ?></a> is licensed under GPL 2.</p>
</div>
<?php echo $this->inc('footer.php'); ?>
