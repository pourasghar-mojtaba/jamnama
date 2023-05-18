<?php

 
$this->add_hook('admin_group_menu','product_menu');

function product_menu($arg)
{
	$active = NULL;
	$controllers = array('products','productcategories');
	if(in_array($arg['request']->params['controller'],$controllers)) $active= 'active';
	echo "
		<li class='treeview ".$active."'>
			<a href='#'>
				<i class='fa fa-th'>
				</i>
				<span>
					". __d(__PRODUCT_LOCALE,'product_managment')."
				</span>
				<i class='fa fa-angle-left pull-right'>
				</i>
			</a> 	
			<ul class='treeview-menu'>";
			$active = NULL;
			if($arg['request']->params['controller'] == 'productcategories')	 $active = 'class="active"';
			echo"
				<li ".$active." >
					<a href='".__SITE_URL."admin/product/productcategories/index'>
						<i class='fa fa-circle-o'>
						</i> ".__d(__PRODUCT_LOCALE,'category_managment')."
					</a>
				</li>";
			$active = NULL;
			if($arg['request']->params['controller'] == 'products')	 $active = 'class="active"';	
			echo"	
				<li ".$active."> 
					<a href='".__SITE_URL."admin/product/products/index'>
						<i class='fa fa-circle-o'>
						</i> ". __d(__PRODUCT_LOCALE,'product_managment')."
					</a>
				</li>
			</ul>
		</li>
	";
	
}

$this->add_hook('user_menu','product_user_menu');
function product_user_menu($arg){		
		_get_header_catrgories(0);	 
}

function _get_header_catrgories($parent_id) {		
	
	App::uses('ProductAppModel', 'Product.Model');
	App::uses('Productcategory', 'Product.Model');
	
	$Productcategory = new Productcategory();
	
	$category_data = array();	
	$Productcategory->recursive=-1;
	$query=	$Productcategory->find('all',array('fields' => array('id','parent_id','title as title'),'conditions' => array('parent_id' => $parent_id,'status'=>1)));

		foreach ($query as $result) {
			
			$sub_query=	$Productcategory->find('all',array('fields' => array('id','parent_id','title as title'),
			'conditions' => array('parent_id' => $result['Productcategory']['id'])));
			if(empty($sub_query)){
				echo "
					<li class='dropdown'>
						<a href='".__SITE_URL."product/products/index/".$result['Productcategory']['id']."'  >".$result['Productcategory']['title']."</a>
					</li>
				";
			}
			else{
				// <a href='javascript:void(0)' title='".$result['Productcategory']['id']."' >".$result['Productcategory']['title']."</a>
				echo "
					<li class='dropdown'> 
						<a href='".__SITE_URL."product/products/index/".$result['Productcategory']['id']."' title='".$result['Productcategory']['id']."' >".$result['Productcategory']['title']."</a>
						<ul class='dropdown-menu'>
				";
						_get_header_catrgories($result['Productcategory']['id']);
				
				echo "  </ul>
					</li>";
				}
			
		}
}

$this->add_hook('last_product','last_product_slider');

function last_product_slider($arg){
	
	App::uses('ProductAppModel', 'Product.Model');
	App::uses('Product', 'Product.Model');
	$product = new Product();
	$product->recursive = - 1;
	$options['fields'] = array(
		'Message.id',
		'Message.user_id',
		'Message.message',
		'User.name',
		'User.image'
	);
	$options['joins'] = array(
    		array('table' => 'users',
        		'alias' => 'User',
        		'type' => 'INNER',
        		'conditions' => array(
        		'User.id = Message.user_id'
    		)
		)
    );	 
	$options['conditions'] = array(
		'Message.status'=> 1
	);
	$options['order'] = array(
			'Technicalinfoitem.arrangment'=>'asc'
	);
	$options['limit'] = 5;
	$messages = $message->find('all',$options);
	
	echo "<ul class='bxslider'>
				<li>
					<em>
						<img src='<?php echo __SITE_URL.__IMAGE_PATH; ?>photos/img11.jpg' alt='' />
						<a href='portfolio_item.html'>
							<i class='fa fa-link icon-hover icon-hover-1'>
							</i>
						</a>
						<a href='<?php echo __SITE_URL.__IMAGE_PATH; ?>photos/img11.jpg' class='fancybox-button' title='Project Name #1' data-rel='fancybox-button'>
							<i class='fa fa-search icon-hover icon-hover-2'>
							</i>
						</a>
					</em>
					<a class='bxslider-block' href='#'>
						<strong>
							نصب درب سکوريت
						</strong>
						<b>
							تهران:: فروشگاه هفت
						</b>
					</a>
				</li>

			</ul>";
	
}


?>

