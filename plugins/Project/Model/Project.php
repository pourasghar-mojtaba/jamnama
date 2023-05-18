<?php

class Project extends ProjectAppModel {
	
	var $actsAs = array('Containable');
 	public $hasMany = array(
        'Projectimage' => array(
			'className' => 'Projectimage',
			'foreignKey' => 'project_id',
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
	
}

?>