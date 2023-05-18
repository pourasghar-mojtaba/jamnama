<section class="content-header">
      <h2>
        <?php echo $items['title']; ?>
      </h2>
      <ol class="breadcrumb">
        <li><a href="<?php echo __SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo __('home'); ?></a></li>
        <?php if(!empty($items['link'])){ ?><li><a href="<?php echo $items['link']['url'] ?>">
		<?php echo $items['link']['title'] ?></a></li><?php } ?>
        <li class="active"><?php echo $items['title']; ?></li>
      </ol>
    </section>
<?php echo $this->Session->flash(); ?>	
<section class="content">
	<div class="row">
		<div class="col-md-12">
		   <div class="box box-primary">
            <!--<div class="box-header with-border">
              <h3 class="box-title">Quick Example</h3>
            </div>-->

            <form role="form">              
			  <div class="box-body">