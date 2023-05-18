<?php
App::uses('AppController', 'Controller');

class ProjectimagesController extends ProjectAppController
{
	/**
	* Components
	*
	* @var array
	*/
	public $components = array('Paginator','Httpupload','CmsAcl'=>array('allUsers'=>array('getimages')));
	
	function getimages($project_id){
		$options = array();
		$this->Projectimage->recursive = - 1;
		$options['fields'] = array(
			'Projectimage.id',
			'Projectimage.title',
			'Projectimage.image'
		);	 
		$options['conditions'] = array(
			'Projectimage.project_id'=> $project_id
		);
		$options['order'] = array(
			'Projectimage.id'=>'asc'
		);
		//$options['limit'] = 5;
		$projects = $this->Projectimage->find('all',$options);
		return $projects;
	}
}
