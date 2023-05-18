<?php
echo $this->Html->css('/'.__PRODUCT.'/css/addrow');
echo $this->Html->script('/js/admin/ckeditor/ckeditor');
echo $this->Form->create('Product',array('enctype'=>'multipart/form-data'));
?>
<?php
$items = array();
$items['title'] = __d(__PRODUCT_LOCALE,'edit_product');
$items['link'] = array('title'=>__d(__PRODUCT_LOCALE,'product_list'),'url'  =>__SITE_URL.'admin/'.__PRODUCT.'/products/index');
echo $this->element('Admin/add_edit_header', array('items'=>$items) );
$category_list = $this->Cms->categoryToList($productcategories);
?>
<div class="col-md-6">
</div>
<div class="col-md-6">
	<?php

	echo $this->Form->input('id');
	echo  $this->Form->input('product_category_id', array(
			'type'   => 'select',
			'options'=> $category_list,
			'default'=> $this->request->data['Product']['product_category_id'],
			'label'  => __d(__PRODUCT_LOCALE,'parent'),
			'class'  => 'form-control input-sm'					
		));
	echo  $this->Form->input('title', array(
			'type' => 'text',
			'label'=> __d(__PRODUCT_LOCALE,'title'),
			'class'=> 'form-control'
		));
	echo  $this->Form->input('mini_detail', array(
			'type' => 'textarea',
			'label'=> __d(__PRODUCT_LOCALE,'mini_detail'),
			'class'=> 'form-control'
		));
	echo  $this->Form->input('detail', array(
			'type'       => 'textarea',
			'label'      => __d(__PRODUCT_LOCALE,'detail'),
			'id'      => 'detail',
			'value'=>$this->Cms->convert_character_editor($this->request->data['Product']['detail']),
			'class'      => 'form-control'
		));	
	?>
	<tr>
		<td colspan="5">
			<label class="control-label" for="focusedInput">
				<?php echo __d(__PRODUCT_LOCALE,'product_images') ?> :
			</label>
			<table id="expense_table" class="expense_table" cellspacing="0" cellpadding="0" width="500">
				<thead>
					<tr>
						<th>
							<?php echo __d(__PRODUCT_LOCALE,'image'); ?>
						</th>
						<th>
							<?php echo __d(__PRODUCT_LOCALE,'title'); ?>
						</th>
						<th>
							&nbsp;
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if(!empty($productimages)){
						foreach($productimages as $productimage){
							echo "
							<tr>
							<td>
							<input type='file' class='form-control' name='data[Productimage][image][]' id='image_no_01' maxlength='255'  />
							</td>
							<td>
							<input type='text' class='form-control' name='data[Productimage][title][]' id='title_no_01'
							maxlength='255' value='".$productimage['Productimage']['title']."' />
							</td>";
							if(fileExistsInPath(__PRODUCT_IMAGE_PATH.$productimage['Productimage']['image'] ) && $productimage['Productimage']['image'] != '' ){
								echo "<td><a target='_blank' href= '".__SITE_URL.__PRODUCT_IMAGE_URL.$productimage['Productimage']['image']."' >";
								echo $this->Html->image('/'.__PRODUCT_IMAGE_URL.$productimage['Productimage']['image'],array('id'    =>'image_img','height'=>100));
								echo "</a></td>";
							}



							echo"<td><input type='button' value='Delete' class='del_ExpenseRow' /></td>";
							echo "<input type='hidden' value='".$productimage['Productimage']['id']."' name='data[Productimage][id][]'>";
							echo "<input type='hidden' value='".$productimage['Productimage']['image']."' name='data[Productimage][old_image][]'>";

							echo"</tr>";
						}
					}
					else
					{
						echo "
						<tr>
						<td>
						<input type='file' name='data[Productimage][image][]' id='image_no_01' maxlength='255'  />
						</td>
						<td>
						<input type='text' name='data[Productimage][title][]' id='title_no_01' maxlength='255'  />
						</td>
						<td>&nbsp;</td>
						</tr>
						";
					}

					?>

				</tbody>
			</table>

			<input type="button" value="<?php echo __d(__PRODUCT_LOCALE,'add_image'); ?>" id="add_ExpenseRow" />
		</td>
	</tr>
	<?php
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
<script>
	$(function()
		{
			// GET ID OF last row and increment it by one
			var $lastChar =1, $newRow;
			$get_lastID = function()
			{
				var $id = $('.expense_table tr:last-child td:first-child input').attr("id");
				$lastChar = parseInt($id.substr($id.length - 2), 10);
				console.log('GET id: ' + $lastChar + ' | $id :'+$id);
				$lastChar = $lastChar + 1;
				$newRow = "<tr> \
				<td><input type='file' name='data[Productimage][image][]' class='form-control' id='image_no_0"+$lastChar+"' maxlength='255' /></td> \<td><input type='text' class='form-control' name='data[Productimage][title][]' id='title_no_0"+$lastChar+"' maxlength='255' /></td> \<td><input type='button' value='Delete' class='del_ExpenseRow' /></td> \
				</tr>"
				return $newRow;
			}

			// ***** -- START ADDING NEW ROWS
			$('#add_ExpenseRow').click(function()
				{
					//if($lastChar <= 9){
					$get_lastID();
					$('.expense_table tbody').append($newRow);
					/*} else {
					alert("Reached Maximum Rows!");
					};*/
				});

			$("body").on("click",'.del_ExpenseRow', function()
				{
					$(this).closest('tr').remove();
					$lastChar = $lastChar-2;
				});
		});
		CKEDITOR.replace( 'detail' );
</script>
