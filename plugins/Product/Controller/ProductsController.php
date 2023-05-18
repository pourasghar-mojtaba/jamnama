<?php
App::uses('AppController', 'Controller');

class ProductsController extends ProductAppController
{
	/**
	* Components
	*
	* @var array
	*/
	public $components = array('Paginator','Httpupload','CmsAcl'=>array('allUsers'=>array('index')));
	public $helpers = array('AdminHtml'=>array('action'=>'Product'));
	/**
	* admin_index method
	*
	* @return void
	*/
	public
	function admin_index()
	{
		$this->Product->recursive = -1;
		$this->set('title_for_layout',__d(__PRODUCT_LOCALE,'product_list'));
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}
		else $limit = 10;

		if(isset($this->request->data['Product']['search'])){
			$this->request->data = Sanitize::clean($this->request->data);
			$this->paginate = array(
				'fields'=>array(
					'Productcategory.title',
					'Product.id',
					'Product.title',
					'Product.mini_detail',
					'Product.status',
					'Product.created',
				),
				'joins'=>array(array('table' => 'productcategories',
					'alias' => 'Productcategory',
					'type' => 'left',
					'conditions' => array(
					'Product.product_category_id = Productcategory.id ',
					)
				 )),
				'conditions' => array('Product.title LIKE'=> ''.$this->request->data['Product']['search'].'%' ),
				'limit'     => $limit,
				'order'                     => array(
					'Product.id'=> 'desc'
				)
			);
		}
		else
		{
			$this->paginate = array(
				'fields'=>array(
					'Productcategory.title',
					'Product.id',
					'Product.title',
					'Product.mini_detail',
					'Product.status',
					'Product.created',
				),
				'joins'=>array(array('table' => 'productcategories',
					'alias' => 'Productcategory',
					'type' => 'left',
					'conditions' => array(
					'Product.product_category_id = Productcategory.id ',
					)
				 )),
				'limit'=> $limit,
				'order' => array(
					'Product.id'=> 'desc'
				)
			);
		}
		$products = $this->paginate('Product');
		$this->set(compact('products'));
	}


	/**
	* admin_add method
	*
	* @return void
	*/
	public
	function admin_add()
	{
		$this->set('title_for_layout',__d(__PRODUCT_LOCALE,'add_product'));
		if($this->request->is('post')){

			$datasource = $this->Product->getDataSource();
			try
			{
				$datasource->begin();

				if(!$this->Product->save($this->request->data))					
				throw new Exception(__d(__PRODUCT_LOCALE,'the_product_could_not_be_saved_Please_try_again'));
				$product_id = $this->Product->getLastInsertID();
				if(!empty($this->request->data['Productimage']['image'])){

					foreach($this->request->data['Productimage']['image'] as $key => $value){
						if(trim($value['name']) == '')
						{
							unset($this->request->data['Productimage']['image'][$key]);
							unset($this->request->data['Productimage']['title'][$key]);
						}
					}
					$data = array();
					$image_list = array();
					foreach($this->request->data['Productimage']['image'] as $key => $value){
						$output = $this->_picture($value,$key);
						if(!$output['error']) $image = $output['filename'];
						else
						{
							$image = '';
							if(!empty($image_list))
							{
								foreach($image_list as $img){
									@unlink(__PRODUCT_IMAGE_PATH."/".$img);
									@unlink(__PRODUCT_IMAGE_PATH."/".__UPLOAD_THUMB."/".$img);
								}
							}
							throw new Exception($output['message'].'  '.__d(__PRODUCT_LOCALE,'image_name').' '.$value['name']);
						}

						$image_list[] = $image;

						$data[] = array('Productimage' => array(
								'image'     => $image ,
								'title'     => $this->request->data['Productimage']['title'][$key],
								'product_id'=> $product_id
							));
					}
					if(!$this->Product->Productimage->saveMany($data,array('deep' => true)))						
					throw new Exception(__d(__PRODUCT_LOCALE,'the_product_image_not_saved'));
				}

				$datasource->commit();

				$this->Redirect->flashSuccess(__d(__PRODUCT_LOCALE,'the_product_has_been_saved'),array('action'=>'index'));
			} catch(Exception $e){
				$datasource->rollback();
				$this->Redirect->flashWarning($e->getMessage(),array('action'=>'index'));
			}

		}
		$productcategories = $this->Product->_getCategories(0);
		$this->set(compact('productcategories'));	
	}

	/**
	* admin_edit method
	*
	* @throws NotFoundException
	* @param string $id
	* @return void
	*/
	public
	function admin_edit($id = null)
	{
		$this->set('title_for_layout',__d(__PRODUCT_LOCALE,'edit_product'));
		if(!$this->Product->exists($id)){
			$this->Redirect->flashWarning(__d(__PRODUCT_LOCALE,'invalid_product'));
		}
		if($this->request->is(array('post', 'put'))){

			$datasource = $this->Product->getDataSource();
			try
			{
				$datasource->begin();
				if(!$this->Product->save($this->request->data))			        
				throw new Exception(__d(__PRODUCT_LOCALE,'the_product_could_not_be_saved_Please_try_again'));
				// image opration

				$options = array();
				$this->Product->Productimage->recursive=-1;
				$options['fields'] = array(
					'Productimage.id',
					'Productimage.title',
					'Productimage.image'
				);
				$options['conditions'] = array(
					'Productimage.product_id'=> $id
				);
				$productimages = $this->Product->Productimage->find('all',$options);
				//pr($this->request->data);throw new Exception();
				if(!empty($productimages))
				{
					foreach($productimages as $productimage){
						if(!in_array($productimage['Productimage']['id'],$this->request->data['Productimage']['id'])){
							if(!$this->Product->Productimage->delete($productimage['Productimage']['id']))							
							throw new Exception(__d(__PRODUCT_LOCALE,'the_product_image_not_saved'));
							else
							{
								@unlink(__PRODUCT_IMAGE_PATH."/".$productimage['Productimage']['image']);
								@unlink(__PRODUCT_IMAGE_PATH."/".__UPLOAD_THUMB."/".$productimage['Productimage']['image']);
							}
						}
					}
				}


				if(!empty($this->request->data['Productimage']['id']))
				{
					foreach($this->request->data['Productimage']['id'] as $key => $value){

						if($this->request->data['Productimage']['image'][$key]['size'] > 0)
						{

							@unlink(__PRODUCT_IMAGE_PATH."/".$this->request->data['Productimage']['old_image'][$key]);
							@unlink(__PRODUCT_IMAGE_PATH."/".__UPLOAD_THUMB."/".$this->request->data['Productimage']['old_image'][$key]);
							$output = $this->_picture($this->request->data['Productimage']['image'][$key],$key);
							if(!$output['error']) $image = $output['filename'];
							else
							{
								$image = '';
								if(!empty($image_list))
								{
									foreach($image_list as $img){
										@unlink(__PRODUCT_IMAGE_PATH."/".$img);
										@unlink(__PRODUCT_IMAGE_PATH."/".__UPLOAD_THUMB."/".$img);
									}
								}
								throw new Exception($output['message'].'  '.__d(__PRODUCT_LOCALE,'image_name').' '.$this->request->data['Productimage']['image'][$key]['name']);
							}

							$image_list[] = $image;
						}
						else $image = $this->request->data['Productimage']['old_image'][$key];

						$ret   = $this->Product->Productimage->updateAll(
							array('Productimage.title'=> '"'.$this->request->data['Productimage']['title'][$key].'"' ,
								'Productimage.image'=> '"'.$image.'"'
							),   //fields to update
							array('Productimage.id'=> $value )  //condition
						);
						if(!$ret)
						{
							throw new Exception(__d(__PRODUCT_LOCALE,'the_product_image_not_saved'));
						}
					}
				}




				if(!empty($this->request->data['Productimage']['id']))
				{
					foreach($this->request->data['Productimage']['id'] as $key => $value){
						unset($this->request->data['Productimage']['title'][$key]);
						unset($this->request->data['Productimage']['image'][$key]);
					}
				}



				$data = array();
				if(!empty($this->request->data['Productimage']['image']))
				{

					foreach($this->request->data['Productimage']['image'] as $key => $value){
						$output = $this->_picture($value,$key);
						if(!$output['error']) $image = $output['filename'];
						else
						{
							$image = '';
							if(!empty($image_list))
							{
								foreach($image_list as $img){
									@unlink(__PRODUCT_IMAGE_PATH."/".$img);
									@unlink(__PRODUCT_IMAGE_PATH."/".__UPLOAD_THUMB."/".$img);
								}
							}
							throw new Exception($output['message'].'  '.__d(__PRODUCT_LOCALE,'image_name').' '.$value['name']);
						}

						$image_list[] = $image;

						$data[] = array('Productimage' => array(
								'image'     => $image ,
								'title'     => $this->request->data['Productimage']['title'][$key],
								'product_id'=> $id
							));
					}
					//pr($data);throw new Exception();
					if(!empty($data))
					{
						if(!$this->Product->Productimage->saveMany($data,array('deep' => true)))					        
						throw new Exception(__d(__PRODUCT_LOCALE,'the_product_image_not_saved'));
					}
				}


				// image opration


				$datasource->commit();
				$this->Redirect->flashSuccess(__d(__PRODUCT_LOCALE,'the_product_has_been_saved'),array('action'=>'index'));
	
			} catch(Exception $e)
			{
				$datasource->rollback();
				$this->Redirect->flashWarning($e->getMessage(),array('action'=>'index'));
			}

		}
		else
		{
			$this->Product->recursive = -1;
			$options = array('conditions' => array('Product.' . $this->Product->primaryKey=> $id));
			$this->request->data = $this->Product->find('first', $options);
			$this->Product->Productimage->recursive = -1;
			$options=array();
			$options['fields'] = array(
					'Productimage.id',
					'Productimage.title',
					'Productimage.image'
				   );
			$options['conditions'] = array(
				'Productimage.product_id' => $id	
				);
			$productimages = $this->Product->Productimage->find('all',$options);
		    $this->set('productimages', $productimages);
		}
		
		$productcategories = $this->Product->_getCategories(0);
		$this->set(compact('productcategories'));
	}

	/**
	* admin_delete method
	*
	* @throws NotFoundException
	* @param string $id
	* @return void
	*/
	public
	function admin_delete($id = null)
	{
		$this->Product->id = $id;
		if(!$this->Product->exists()){
			$this->Redirect->flashWarning(__d(__PRODUCT_LOCALE,'invalid_product'));
		}
		$this->Product->Productimage->recursive = -1;
		$options['fields'] = array(
			'Productimage.id',
			'Productimage.image'
		   );
				   
		$options['conditions'] = array(
			'Productimage.product_id'=>$id
		);
		$images = $this->Product->Productimage->find('all',$options);
		if($this->Product->delete()){
			if(!empty($images)){
			 	foreach($images as $img){
					@unlink(__PRODUCT_IMAGE_PATH."/".$img['Productimage']['image']);
					@unlink(__PRODUCT_IMAGE_PATH."/".__UPLOAD_THUMB."/".$img['Productimage']['image']);
				}
			 }
			$this->Redirect->flashSuccess(__d(__PRODUCT_LOCALE,'the_product_has_been_deleted'));
		}
		else
		{
			$this->Redirect->flashWarning(__d(__PRODUCT_LOCALE,'the_product_could_not_be_deleted_please_try_again'));
		}
		return $this->redirect(array('action'=> 'index'));
	}


	function _picture($data,$index)
	{
		App::uses('Sanitize', 'Utility');

		$output = array();

		if($data['size'] > 0)
		{
			$ext      = $this->Httpupload->get_extension($data['name']);
			$filename = md5(rand().$_SERVER['REMOTE_ADDR']);
			if(file_exists(__PRODUCT_IMAGE_PATH.$filename.'.'.$ext))				$filename = md5(rand().$_SERVER[REMOTE_ADDR]);

			$this->Httpupload->setmodel('Productimage');
			$this->Httpupload->setuploadindex($index);
			$this->Httpupload->setuploaddir(__PRODUCT_IMAGE_PATH);
			$this->Httpupload->setuploadname('image');
			$this->Httpupload->settargetfile($filename.'.'.$ext);
			$this->Httpupload->setmaxsize(__UPLOAD_IMAGE_MAX_SIZE);
			$this->Httpupload->setimagemaxsize(__UPLOAD_IMAGE_MAX_WIDTH,__UPLOAD_IMAGE_MAX_HEIGHT);
			$this->Httpupload->allowExt = __UPLOAD_IMAGE_EXTENSION;
			$this->Httpupload->create_thumb = true;
			$this->Httpupload->thumb_folder = __UPLOAD_THUMB;
			$this->Httpupload->thumb_width = 180;
			$this->Httpupload->thumb_height = 120;
			if(!$this->Httpupload->upload())
			{
				return array('error'   =>true,'filename'=>'','message' =>$this->Httpupload->get_error());
			}
			$filename .= '.'.$ext;

		}
		else return array('error'   =>true,'filename'=>'','message' =>'');

		return array('error'   =>false,'filename'=>$filename);
	}
	
	
	function index($category_id){
		
		$this->Product->Productcategory->recursive=-1;
		$options['fields'] = array(
				'Productcategory.id',
				'Productcategory.title'
			   );
		$options['conditions'] = array(
			'Productcategory.id' => $category_id	
			);
		$category = $this->Product->Productcategory->find('first',$options);
		
		$this->set('title_for_layout',__d(__PRODUCT,'products').' '.$category['Productcategory']['title']);
		$this->set('description_for_layout',$category['Productcategory']['title']);
		$this->set('keywords_for_layout',$category['Productcategory']['title']);
		
		$options = array();
		$this->Product->recursive = - 1;
		$options['fields'] = array(
			'Product.id',
			'Product.title',
			'Product.mini_detail',
			'(select image from productimages where product_id = Product.id limit 0,1)as image'
		);	 
		$options['conditions'] = array(
			'Product.status'=> 1,
			'Product.product_category_id'=> $category_id,
		);
		$options['order'] = array(
			'Product.id'=>'desc'
		);
		//$options['limit'] = 5;
		$products = $this->Product->find('all',$options);
		$this->set('products',$products);
	}
	
}
