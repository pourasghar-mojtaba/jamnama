<!DOCTYPE html>
<html>
<head>
	<?php
		echo $this->element('header_info');		
	?>
</head>
<script type="text/javascript">
	_url = '<?php echo __SITE_URL  ?>';
</script>		
<!-- BEGIN BODY -->
<body class="page-header-fixed">	
	<?php echo $this->element(__USER.'header'); ?>		
	<?php echo $this->fetch('content'); ?>
	<?php /*echo $this->element('sql_dump');*/ ?>
	<?php echo $this->Flash->render(); ?>
    <?php echo $this->element(__USER.'footer'); ?>	
</body>
<!-- END BODY -->
</html>