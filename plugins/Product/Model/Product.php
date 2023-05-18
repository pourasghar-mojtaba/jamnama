<?php

class Product extends ProductAppModel {
	
	var $actsAs = array('Containable');
	
	public $belongsTo = array(
		'Productcategory' => array(
			'className' => 'Productcategory',
			'foreignKey' => 'product_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
 	public $hasMany = array(
        'Productimage' => array(
			'className' => 'Productimage',
			'foreignKey' => 'product_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
		
	);

	
	public
	function _getCategories($parent_id)
	{
		$category_data = array();
		$this->Productcategory->recursive = - 1;
		$query = $this->Productcategory->find('all',array('fields'=> array('id','parent_id','title as title'),'conditions' => array('parent_id'=> $parent_id)));
		foreach($query as $result)
		{
			$category_data[] = array(
				'id'   => $result['Productcategory']['id'],
				'title'=> $this->_getPath($result['Productcategory']['id'])
			);

			$category_data = array_merge($category_data, $this->_getCategories($result['Productcategory']['id']));
		}
		return $category_data;
	}
	public
	function _getPath($category_id)
	{
		$this->Productcategory->recursive = - 1;
		$query = $this->Productcategory->find('all',array('fields'=> array('id','parent_id','title as title'),'conditions' => array('id'=> $category_id)));

		foreach($query as $category_info)
		{
			if($category_info['Productcategory']['parent_id'])
			{
				return $this->_getPath($category_info['Productcategory']['parent_id']) .
				" > " .$category_info['Productcategory']['title'];
			}
			else
			{
				return $category_info['Productcategory']['title'];
			}
		}
	}
	
}

?>