<?php


App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class ProductcategoriesController extends ProductAppController {

/*public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow('view','get_child_productcategories');
}*/

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Productcategories';
	public $helpers = array('AdminHtml'=>array('action'=>'Productcategory'));

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();

/**
 * Displays a view
 *
 * @param mixed What product_category to display
 * @return void
 */

 function admin_index()
	{
		$this->set('title_for_layout',__d(__PRODUCT_LOCALE,'category_list'));
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}else $limit = 10;
				
		$productcategories = $this->_indexgetCategories(0);
		$this->set(compact('productcategories'));
	}
	
	
  function admin_add(){
  	$this->set('title_for_layout',__d(__PRODUCT_LOCALE,'add_category'));
	if($this->request->is('post'))
		{				
			$this->Productcategory->create();
			if($this->Productcategory->save($this->request->data))
			{				
				$this->Redirect->flashSuccess(__d(__PRODUCT_LOCALE,'the_product_category_has_been_saved'),array('action'=>'index'));
			}
			else
			{				
				$this->Redirect->flashWarning(__d(__PRODUCT_LOCALE,'the_product_category_could_not_be_saved'),array('action'=>'index'));
			}
		}		
		 $productcategories = $this->Productcategory->_getCategories(0);
		 $this->set(compact('productcategories'));
  }	


	function admin_edit($id = null)
	{
		$this->set('title_for_layout',__d(__PRODUCT_LOCALE,'edit_category'));
		$this->Productcategory->id = $id;
		if(!$this->Productcategory->exists())
		{
			$this->Redirect->flashWarning(__d(__PRODUCT_LOCALE,'invalid_id_for_product_category'),array('action'=>'index'));
		}	
			
		if($this->request->is('post') || $this->request->is('put'))
		{			
			if($this->Productcategory->save($this->request->data))
			{
				$this->Redirect->flashSuccess(__d(__PRODUCT_LOCALE,'the_product_category_has_been_saved'),array('action'=>'index'));
			}
			else
			{
			   $this->Redirect->flashWarning(__d(__PRODUCT_LOCALE,'the_product_category_could_not_be_saved'),array('action'=>'index'));
			}
		}		

		$options = array('conditions' => array('Productcategory.' . $this->Productcategory->primaryKey=> $id));
		$this->request->data = $this->Productcategory->find('first', $options);
		
		$productcategories = $this->Productcategory->_getCategories(0);
		$this->set(compact('productcategories'));		
	}	

function admin_delete($id = null){		
		$this->Productcategory->id = $id;
		if(!$this->Productcategory->exists())
		{
			$this->Redirect->flashWarning(__d(__PRODUCT_LOCALE,'invalid_id_for_product_category'),array('action'=>'index'));
		}
		$this->Productcategory->Product->recursive = -1;
        
        $options['conditions'] = array(
			'Product.product_category_id' => $id
		);
		$count = $this->Productcategory->Product->find('count',$options);
		if($count>0){  
			$this->Redirect->flashWarning(__d(__PRODUCT_LOCALE,'invalid_id_for_product_category'),array('action'=>'index'));
        }
    				
		if($this->Productcategory->delete($id))
		{			
			$this->Redirect->flashSuccess(__d(__PRODUCT_LOCALE,'delete_product_category_success'),array('action'=>'index'));
		}else
		{
			$this->Redirect->flashWarning(__d(__PRODUCT_LOCALE,'delete_product_category_not_success'),array('action'=>'index'));
		}
  }
		
	
/**
 * get all childeren of category
 * @param undefined $parent_id
 * 
*/ 
  public function _indexgetCategories($parent_id) {	
    $this->Productcategory->recursive = -1;
	$category_data = array();		
	$query=	$this->Productcategory->find('all',array('conditions' => array('parent_id' => $parent_id)));

			foreach ($query as $result) {
				$category_data[] = array(
					'id'    => $result['Productcategory']['id'],
					'arrangment'    => $result['Productcategory']['arrangment'],
					'status'    => $result['Productcategory']['status'],
					'created'    => $result['Productcategory']['created'],
					'title' => $this->_indexgetPath($result['Productcategory']['id'],'title'),
					'slug' => $this->_indexgetPath($result['Productcategory']['id'],'slug')
					 
				);
	         $category_data = array_merge($category_data, $this->_indexgetCategories($result['Productcategory']['id']));
			}	
		return $category_data;
	}
/**
* get name from category
* @param undefined $category_id
* 
*/	
 public function _indexgetPath($category_id,$title) {
		$query=	$this->Productcategory->find('all',array('conditions' => array('id' => $category_id)));

		foreach ($query as $category_info) {
		if ($category_info['Productcategory']['parent_id']) {
			return $this->_indexgetPath($category_info['Productcategory']['parent_id'],$title) .
			 " > " .$category_info['Productcategory'][$title];			 
		} else {
			return $category_info['Productcategory'][$title];
		}
		}
}	
	
	
function get_main_productcategories()
{
	$options['fields'] = array(
			'Productcategory.id',
			'Productcategory.parent_id',
			'Productcategory.title as title'
		);
	$options['conditions'] = array(
		'Productcategory.status'=>1,
		'Productcategory.parent_id'=>0
	);
	$options['order'] = array(
		'Productcategory.arrangment'=>'asc'
	);
	$productcategories = $this->Productcategory->find('all',$options);
	return $productcategories;
}	
	
function get_child_productcategories($id=null)
{
	$options['fields'] = array(
			'Productcategory.id',
			'Productcategory.title as title'
		);
	$options['conditions'] = array(
		'Productcategory.status'=>1 ,
		'Productcategory.parent_id'=>$id
	);
	$options['order'] = array(
		'Productcategory.arrangment'=>'asc'
	);
	$productcategories = $this->Productcategory->find('all',$options);
	return $productcategories;
}	


 	
	
	
}

