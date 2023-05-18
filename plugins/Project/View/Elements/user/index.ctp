 
<div class='page-container'>

	<!-- BEGIN BREADCRUMBS -->
	<div class='row breadcrumbs margin-bottom-40'>
		<div class='container'>
			<div class='col-md-8 col-sm-8 '>
				<ul class='pull-left breadcrumb'>
					<li>
						<?php echo $this->Html->link(__('home'),__SITE_URL) ?>
					</li>
					<li class='active'>
						<?php echo __d(__PROJECT,'projects') ?>
					</li>
				</ul>
			</div>
			<div class='col-md-4 col-sm-4 text-center'>
				<h1>
					<?php echo __d(__PROJECT,'projects') ?>
				</h1>
			</div>

		</div>
	</div>
	<!-- END BREADCRUMBS -->

	<!-- BEGIN CONTAINER -->
	<div class='container min-hight'>
		<!-- BEGIN BLOG -->
		<div class='row'>
			<!-- BEGIN LEFT SIDEBAR -->
			<div class='col-md-12 col-sm-9 blog-posts margin-bottom-40'>
				<?php
					if(!empty($projects)){
						foreach($projects as $key=>$project){
							echo "<div class='row'>
									<div class='col-md-4 col-sm-4'>
										<!-- BEGIN CAROUSEL -->
										<div class='front-carousel'>
											<div id='myCarousel_".$key."' class='carousel slide'>
												<!-- Carousel items -->
												<div class='carousel-inner'>";
												$images=$this->requestAction(__SITE_URL.'project/projectimages/getimages/'.$project['Project']['id']);
												if(!empty($images)){
													$i = 1;
													$active = '';
													foreach($images as $image){
														if($i==1) $active = 'active';else $active = '';
														echo "<div class=' ".$active." item'>";
														echo "<a class='mix-preview fancybox-button' href='".__SITE_URL.__PROJECT_IMAGE_URL.$image['Projectimage']['image']."' title='".$image['Projectimage']['title']."' data-rel='fancybox-button'>";
														echo $this->Html->image(__SITE_URL.__PROJECT_IMAGE_URL.$image['Projectimage']['image'],array('alt'=>$image['Projectimage']['title']));
														echo "</a>";
														echo "</div>";
														$i++;
													}
												}													
												echo "</div>
												<!-- Carousel nav -->
												<a class='carousel-control left' href='#myCarousel_".$key."' data-slide='prev'>
													<i class='fa fa-angle-right'>
													</i>
												</a>
												<a class='carousel-control right' href='#myCarousel_".$key."' data-slide='next'>
													<i class='fa fa-angle-left'>
													</i>
												</a>
											</div>
										</div>

										<!-- END CAROUSEL -->
									</div>
									<div class='col-md-8 col-sm-8'>
										<h2>
											<a href='#'>
												".$project['Project']['title']."
											</a>
										</h2>
						 
										<p>
											".$this->Cms->convert_character_editor($project['Project']['detail'])."
										</p>
										 
									</div>
								</div>
								<hr class='blog-post-sep'>";
						}
					}
				?>
				
			</div>
			<!-- END LEFT SIDEBAR -->
		</div>
		<!-- END BEGIN BLOG -->
	</div>
	<!-- END CONTAINER -->

</div>
