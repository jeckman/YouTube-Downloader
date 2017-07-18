<?php echo $this->include('header.php', ['title' => 'Youtube Downloader Error']); ?>
	<div class="container-fluid">
		<h1>Youtube Downloader Error</h1>
		<p><?php echo $this->get('error_message'); ?></p>
	</div>
<?php echo $this->include('footer.php'); ?>
