<?php
App::uses('AppController', 'Controller');

class ProjectsController extends ProjectAppController
{
	/**
	* Components
	*
	* @var array
	*/
	public $components = array('Paginator','Httpupload','CmsAcl'=>array('allUsers'=>array('index')));
	public $helpers = array('AdminHtml'=>array('action'=>'Project'));
	/**
	* admin_index method
	*
	* @return void
	*/
	public
	function admin_index()
	{
		$this->Project->recursive = 0;
		$this->set('title_for_layout',__d(__PROJECT_LOCALE,'project_list'));
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}
		else $limit = 10;

		if(isset($this->request->data['Project']['search'])){
			$this->request->data = Sanitize::clean($this->request->data);
			$this->paginate = array(
				'conditions' => array('Project.title LIKE'=> ''.$this->request->data['Project']['search'].'%' ),
				'limit'     => $limit,
				'order'                     => array(
					'Project.id'=> 'desc'
				)
			);
		}
		else
		{
			$this->paginate = array(
				'limit'=> $limit,
				'order' => array(
					'Project.id'=> 'desc'
				)
			);
		}
		$projects = $this->paginate('Project');
		$this->set(compact('projects'));
	}


	/**
	* admin_add method
	*
	* @return void
	*/
	public
	function admin_add()
	{
		$this->set('title_for_layout',__d(__PROJECT_LOCALE,'add_project'));
		if($this->request->is('post')){

			$datasource = $this->Project->getDataSource();
			try
			{
				$datasource->begin();

				if(!$this->Project->save($this->request->data))					
				throw new Exception(__d(__PROJECT_LOCALE,'the_project_could_not_be_saved_Please_try_again'));
				$project_id = $this->Project->getLastInsertID();
				if(!empty($this->request->data['Projectimage']['image'])){

					foreach($this->request->data['Projectimage']['image'] as $key => $value){
						if(trim($value['name']) == '')
						{
							unset($this->request->data['Projectimage']['image'][$key]);
							unset($this->request->data['Projectimage']['title'][$key]);
						}
					}
					$data = array();
					$image_list = array();
					foreach($this->request->data['Projectimage']['image'] as $key => $value){
						$output = $this->_picture($value,$key);
						if(!$output['error']) $image = $output['filename'];
						else
						{
							$image = '';
							if(!empty($image_list))
							{
								foreach($image_list as $img){
									@unlink(__PROJECT_IMAGE_PATH."/".$img);
									@unlink(__PROJECT_IMAGE_PATH."/".__UPLOAD_THUMB."/".$img);
								}
							}
							throw new Exception($output['message'].'  '.__d(__PROJECT_LOCALE,'image_name').' '.$value['name']);
						}

						$image_list[] = $image;

						$data[] = array('Projectimage' => array(
								'image'     => $image ,
								'title'     => $this->request->data['Projectimage']['title'][$key],
								'project_id'=> $project_id
							));
					}
					if(!$this->Project->Projectimage->saveMany($data,array('deep' => true)))						
					throw new Exception(__d(__PROJECT_LOCALE,'the_project_image_not_saved'));
				}

				$datasource->commit();

				$this->Redirect->flashSuccess(__d(__PROJECT_LOCALE,'the_project_has_been_saved'),array('action'=>'index'));
			} catch(Exception $e){
				$datasource->rollback();
				$this->Redirect->flashWarning($e->getMessage(),array('action'=>'index'));
			}

		}

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
		$this->set('title_for_layout',__d(__PROJECT_LOCALE,'edit_project'));
		if(!$this->Project->exists($id)){
			$this->Redirect->flashWarning(__d(__PROJECT_LOCALE,'invalid_project'));
		}
		if($this->request->is(array('post', 'put'))){

			$datasource = $this->Project->getDataSource();
			try
			{
				$datasource->begin();
				if(!$this->Project->save($this->request->data))			        
				throw new Exception(__d(__PROJECT_LOCALE,'the_project_could_not_be_saved_Please_try_again'));
				// image opration

				$options = array();
				$this->Project->Projectimage->recursive=-1;
				$options['fields'] = array(
					'Projectimage.id',
					'Projectimage.title',
					'Projectimage.image'
				);
				$options['conditions'] = array(
					'Projectimage.project_id'=> $id
				);
				$projectimages = $this->Project->Projectimage->find('all',$options);
				//pr($this->request->data);throw new Exception();
				if(!empty($projectimages))
				{
					foreach($projectimages as $projectimage){
						if(!in_array($projectimage['Projectimage']['id'],$this->request->data['Projectimage']['id'])){
							if(!$this->Project->Projectimage->delete($projectimage['Projectimage']['id']))							
							throw new Exception(__d(__PROJECT_LOCALE,'the_project_image_not_saved'));
							else
							{
								@unlink(__PROJECT_IMAGE_PATH."/".$projectimage['Projectimage']['image']);
								@unlink(__PROJECT_IMAGE_PATH."/".__UPLOAD_THUMB."/".$projectimage['Projectimage']['image']);
							}
						}
					}
				}


				if(!empty($this->request->data['Projectimage']['id']))
				{
					foreach($this->request->data['Projectimage']['id'] as $key => $value){

						if($this->request->data['Projectimage']['image'][$key]['size'] > 0)
						{

							@unlink(__PROJECT_IMAGE_PATH."/".$this->request->data['Projectimage']['old_image'][$key]);
							@unlink(__PROJECT_IMAGE_PATH."/".__UPLOAD_THUMB."/".$this->request->data['Projectimage']['old_image'][$key]);
							$output = $this->_picture($this->request->data['Projectimage']['image'][$key],$key);
							if(!$output['error']) $image = $output['filename'];
							else
							{
								$image = '';
								if(!empty($image_list))
								{
									foreach($image_list as $img){
										@unlink(__PROJECT_IMAGE_PATH."/".$img);
										@unlink(__PROJECT_IMAGE_PATH."/".__UPLOAD_THUMB."/".$img);
									}
								}
								throw new Exception($output['message'].'  '.__d(__PROJECT_LOCALE,'image_name').' '.$this->request->data['Projectimage']['image'][$key]['name']);
							}

							$image_list[] = $image;
						}
						else $image = $this->request->data['Projectimage']['old_image'][$key];

						$ret   = $this->Project->Projectimage->updateAll(
							array('Projectimage.title'=> '"'.$this->request->data['Projectimage']['title'][$key].'"' ,
								'Projectimage.image'=> '"'.$image.'"'
							),   //fields to update
							array('Projectimage.id'=> $value )  //condition
						);
						if(!$ret)
						{
							throw new Exception(__d(__PROJECT_LOCALE,'the_project_image_not_saved'));
						}
					}
				}




				if(!empty($this->request->data['Projectimage']['id']))
				{
					foreach($this->request->data['Projectimage']['id'] as $key => $value){
						unset($this->request->data['Projectimage']['title'][$key]);
						unset($this->request->data['Projectimage']['image'][$key]);
					}
				}



				$data = array();
				if(!empty($this->request->data['Projectimage']['image']))
				{

					foreach($this->request->data['Projectimage']['image'] as $key => $value){
						$output = $this->_picture($value,$key);
						if(!$output['error']) $image = $output['filename'];
						else
						{
							$image = '';
							if(!empty($image_list))
							{
								foreach($image_list as $img){
									@unlink(__PROJECT_IMAGE_PATH."/".$img);
									@unlink(__PROJECT_IMAGE_PATH."/".__UPLOAD_THUMB."/".$img);
								}
							}
							throw new Exception($output['message'].'  '.__d(__PROJECT_LOCALE,'image_name').' '.$value['name']);
						}

						$image_list[] = $image;

						$data[] = array('Projectimage' => array(
								'image'     => $image ,
								'title'     => $this->request->data['Projectimage']['title'][$key],
								'project_id'=> $id
							));
					}
					//pr($data);throw new Exception();
					if(!empty($data))
					{
						if(!$this->Project->Projectimage->saveMany($data,array('deep' => true)))					        
						throw new Exception(__d(__PROJECT_LOCALE,'the_project_image_not_saved'));
					}
				}


				// image opration


				$datasource->commit();
				$this->Redirect->flashSuccess(__d(__PROJECT_LOCALE,'the_project_has_been_saved'),array('action'=>'index'));
	
			} catch(Exception $e)
			{
				$datasource->rollback();
				$this->Redirect->flashWarning($e->getMessage(),array('action'=>'index'));
			}

		}
		else
		{
			$options = array('conditions' => array('Project.' . $this->Project->primaryKey=> $id));
			$this->request->data = $this->Project->find('first', $options);
			$options=array();
			$options['fields'] = array(
					'Projectimage.id',
					'Projectimage.title',
					'Projectimage.image'
				   );
			$options['conditions'] = array(
				'Projectimage.project_id' => $id	
				);
			$projectimages = $this->Project->Projectimage->find('all',$options);
		    $this->set('projectimages', $projectimages);
		}
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
		$this->Project->id = $id;
		if(!$this->Project->exists()){
			$this->Redirect->flashWarning(__d(__PROJECT_LOCALE,'invalid_project'));
		}
		$this->Project->Projectimage->recursive = -1;
		$options['fields'] = array(
			'Projectimage.id',
			'Projectimage.image'
		   );
				   
		$options['conditions'] = array(
			'Projectimage.project_id'=>$id
		);
		$images = $this->Project->Projectimage->find('all',$options);
		if($this->Project->delete()){
			if(!empty($images)){
			 	foreach($images as $img){
					@unlink(__PROJECT_IMAGE_PATH."/".$img['Projectimage']['image']);
					@unlink(__PROJECT_IMAGE_PATH."/".__UPLOAD_THUMB."/".$img['Projectimage']['image']);
				}
			 }
			$this->Redirect->flashSuccess(__d(__PROJECT_LOCALE,'the_project_has_been_deleted'));
		}
		else
		{
			$this->Redirect->flashWarning(__d(__PROJECT_LOCALE,'the_project_could_not_be_deleted_please_try_again'));
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
			if(file_exists(__PROJECT_IMAGE_PATH.$filename.'.'.$ext))				$filename = md5(rand().$_SERVER[REMOTE_ADDR]);

			$this->Httpupload->setmodel('Projectimage');
			$this->Httpupload->setuploadindex($index);
			$this->Httpupload->setuploaddir(__PROJECT_IMAGE_PATH);
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
	
	function index(){
			
		$this->set('title_for_layout',__d(__PROJECT,'projects'));
		$this->set('description_for_layout',__d(__PROJECT,'projects'));
		$this->set('keywords_for_layout',__d(__PROJECT,'projects'));
		
		$options = array();
		$this->Project->recursive = - 1;
		$options['fields'] = array(
			'Project.id',
			'Project.title',
			'Project.detail'
		);	 
		$options['conditions'] = array(
			'Project.status'=> 1
		);
		$options['order'] = array(
			'Project.id'=>'desc'
		);
		//$options['limit'] = 5;
		$projects = $this->Project->find('all',$options);
		$this->set('projects',$projects);
	}
}
