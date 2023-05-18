<?php
echo $this->Form->create('Productcategory');
?>
<?php
$items = array();
$items['title'] = __d(__PRODUCT_LOCALE,'edit_category');
$items['link'] = array('title'=>__d(__PRODUCT_LOCALE,'category_list'),'url'  =>__SITE_URL.'admin/'.__PRODUCT.'/productcategories/index');
echo $this->element('Admin/add_edit_header', array('items'=>$items) );
$category_list = $this->Cms->categoryToList($productcategories,TRUE);
?>
<div class="col-md-6">
</div>
<div class="col-md-6">
	<?php

	echo $this->Form->input('id');
	echo  $this->Form->input('parent_id', array(
			'type'   => 'select',
			'options'=> $category_list,
			'default'=> $this->request->data['Productcategory']['parent_id'],
			'label'  => __d(__PRODUCT_LOCALE,'parent'),
			'class'  => 'form-control input-sm'					
		));
	echo  $this->Form->input('title', array(
			'type' => 'text',
			'label'=> __d(__PRODUCT_LOCALE,'title'),
			'class'=> 'form-control'
		));
	
	echo  $this->Form->input('slug', array(
			'type' => 'text',
			'label'=> __d(__PRODUCT_LOCALE,'slug'),
			'class'=> 'form-control'
		));
	echo  $this->Form->input('arrangment', array(
			'type' => 'number',
			'label'=> __d(__PRODUCT_LOCALE,'arrangment'),
			'class'=> 'form-control'
		));
	
	echo  $this->Form->input('status', array(
			'type'   => 'select',
			'options'=> array(1=>__('active'),0=>__('inactive')),
			'label'  => __('status'),
			'default'=>'1',
			'class'  => 'form-control input-sm'
		));
	?>
</div>

<?php
echo $this->element('Admin/add_edit_footer', array('items'=>'') );
?>
<?php echo $this->Form->end(); ?>
