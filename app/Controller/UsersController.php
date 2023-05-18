<?php

App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
/**
* Users Controller
*
* @property User $User
* @property PaginatorComponent $Paginator
*/
class UsersController extends AppController
{

	/**
	* Components
	*
	* @var array
	*/
	public $components = array('Paginator','Httpupload');
	public $helpers = array('AdminHtml'=>array('action'=>'User'));
	/**
	* index method
	*
	* @return void
	*/
	public
	function index()
	{
		$this->User->recursive = 0;
		$this->set('users', $this->Paginator->paginate());
	}

	/**
	* view method
	*
	* @throws NotFoundException
	* @param string $id
	* @return void
	*/
	public
	function view($id = null)
	{
		if(!$this->User->exists($id)){
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey=> $id));
		$this->set('user', $this->User->find('first', $options));
	}

	/**
	* add method
	*
	* @return void
	*/
	public
	function add()
	{
		if($this->request->is('post')){
			$this->User->create();
			if($this->User->save($this->request->data)){
				$this->Flash->success(__('The user has been saved.'));
				return $this->redirect(array('action'=> 'index'));
			}
			else
			{
				$this->Flash->error(__('The user could not be saved. Please, try again.'));
			}
		}
		$roles = $this->User->Role->find('list');
		$this->set(compact('roles'));
	}

	/**
	* edit method
	*
	* @throws NotFoundException
	* @param string $id
	* @return void
	*/
	public
	function edit($id = null)
	{
		if(!$this->User->exists($id)){
			throw new NotFoundException(__('Invalid user'));
		}
		if($this->request->is(array('post', 'put'))){
			if($this->User->save($this->request->data)){
				$this->Flash->success(__('The user has been saved.'));
				return $this->redirect(array('action'=> 'index'));
			}
			else
			{
				$this->Flash->error(__('The user could not be saved. Please, try again.'));
			}
		}
		else
		{
			$options = array('conditions' => array('User.' . $this->User->primaryKey=> $id));
			$this->request->data = $this->User->find('first', $options);
		}
		$roles = $this->User->Role->find('list');
		$this->set(compact('roles'));
	}

	/**
	* delete method
	*
	* @throws NotFoundException
	* @param string $id
	* @return void
	*/
	public
	function delete($id = null)
	{
		$this->User->id = $id;
		if(!$this->User->exists()){
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->allowMethod('post', 'delete');
		if($this->User->delete()){
			$this->Flash->success(__('The user has been deleted.'));
		}
		else
		{
			$this->Flash->error(__('The user could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action'=> 'index'));
	}

	public
	function admin_dashboard()
	{
		$this->set('title_for_layout',__('panel_name'));
	}
	public
	function admin_login()
	{
		$this->set('title_for_layout',__('panel_name'));
		if($this->request->is('post'))
		{
			if($this->request->data['User']['login_email'] == '' || $this->request->data['User']['login_password'] == ''){
				$this->Redirect->flashWarning(__('insert_information'));
				return;
			}

			/*if($this->request->data['User']['captcha'] != $this->Session->read('captcha'))
			{
				$this->Redirect->flashWarning(__('insert_captcha'));
				return;
			}*/

			//pr($this->request->data);return;

			$email      = $this->request->data['User']['login_email'];
			$conditions = array('User.email'=> $email);
			$ret = $this->User->find('first',array('fields'                        =>array('role_id'),'conditions'=> $conditions));

			if(empty($ret)){
				$this->Redirect->flashWarning(__('please_enter_valid_username_and_password'));
				return;
			}

			if($ret['User']['role_id'] == 2)
			{
				$this->Redirect->flashWarning(__('can_not_login_in_admin'));
				return;
			}
			$this->request->data['User']['email'] = $this->request->data['User']['login_email'];
			$this->request->data['User']['password'] = $this->request->data['User']['login_password'];
			$this->request->data = Sanitize::clean($this->request->data);

			if($this->Auth->login()){
				$user = $this->User->_getUser($this->request->data['User']['email']);
				$this->Session->write('AdminUser_Info',array(
						'id'     => $user['User']['id'] ,
						'name'   => $user['User']['name'] ,
						'email'  => $user['User']['email'],
						'role_id'=> $user['User']['role_id']
					));


				$this->redirect($this->Auth->redirect());
			}
			else
			{
				$this->Redirect->flashWarning(__('please_enter_valid_username_and_password'));
			}
		}
		//else $this->Redirect->flashWarning(__('please_login_with_your_email_and_password')); *
	}

	function admin_logout()
	{
		$this->Session->delete('AdminUser_Info');
		$this->redirect($this->Auth->logout());
	}
	/**
	* admin_index method
	*
	* @return void
	*/
	public
	function admin_index()
	{
		$this->set('title_for_layout',__('users'));
		$this->User->recursive = - 1;
		if(isset($_REQUEST['filter']))
		{
			$limit = $_REQUEST['filter'];
		}
		else $limit = 50;

		if(isset($this->request->data['User']['search']))
		{
			$this->request->data = Sanitize::clean($this->request->data);
			$this->paginate = array(
				'fields'                    =>array(
					'User.id',
					'User.name',
					'User.user_name',
					'User.email',
					'User.status',
					'User.created',
					'Role.name'
				),
				'joins'                    =>array(array('table'     => 'roles',
						'alias'     => 'Role',
						'type'      => 'LEFT',
						'conditions' => array(
							'Role.id = User.role_id ',
						)
					)),
				'conditions' => array('User.name LIKE' => ''.$this->request->data['User']['search'].'%' ,'User.role_id <>'=>1),
				'limit'     => $limit,
				'order'                          => array(
					'User.id'=> 'desc'
				)
			);
		}
		else
		{
			$this->paginate = array(
				'fields'                    =>array(
					'User.id',
					'User.name',
					'User.user_name',
					'User.email',
					'User.status',
					'User.created',
					'Role.name'
				),
				'joins'                         =>array(array('table'     => 'roles',
						'alias'     => 'Role',
						'type'      => 'LEFT',
						'conditions' => array(
							'Role.id = User.role_id ',
						)
					)),
				'conditions' => array('User.role_id <> 1 '),
				'limit'     => $limit,
				'order'                          => array(
					'User.id'=> 'desc'
				)
			);
		}
		$users = $this->paginate('User');
		$this->set(compact('users'));
	}

	/**
	* admin_view method
	*
	* @throws NotFoundException
	* @param string $id
	* @return void
	*/
	public
	function admin_view($id = null)
	{
		if(!$this->User->exists($id)){
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey=> $id));
		$this->set('user', $this->User->find('first', $options));
	}

	function _image_picture()
	{
		App::uses('Sanitize', 'Utility');

		$output = array();
		$data = Sanitize::clean($this->request->data);
		$file = $data['User']['image'];

		if($file['size'] > 0){
			$ext      = $this->Httpupload->get_extension($file['name']);
			$filename = md5(rand().$_SERVER['REMOTE_ADDR']);
			if(file_exists(__USER_IMAGE_PATH.$filename.'.'.$ext))				$filename = md5(rand().$_SERVER[REMOTE_ADDR]);

			$this->Httpupload->setmodel('User');
			$this->Httpupload->setuploaddir(__USER_IMAGE_PATH);
			$this->Httpupload->setuploadname('image');
			$this->Httpupload->settargetfile($filename.'.'.$ext);
			$this->Httpupload->setmaxsize(__UPLOAD_IMAGE_MAX_SIZE);
			$this->Httpupload->setimagemaxsize(__UPLOAD_IMAGE_MAX_WIDTH,__UPLOAD_IMAGE_MAX_HEIGHT);
			$this->Httpupload->allowExt = __UPLOAD_IMAGE_EXTENSION;
			$this->Httpupload->create_thumb = true;
			$this->Httpupload->thumb_folder = __UPLOAD_THUMB;
			$this->Httpupload->thumb_width = 120;
			$this->Httpupload->thumb_height = 120;
			if(!$this->Httpupload->upload()){
				return array('error'   =>true,'filename'=>'','message' =>$this->Httpupload->get_error());
			}
			$filename .= '.'.$ext;

		}
		return array('error'   =>false,'filename'=>$filename);
	}

	/**
	* admin_add method
	*
	* @return void
	*/
	public
	function admin_add()
	{
		$this->set('title_for_layout',__('add_user'));
		if($this->request->is('post')){
			$data = Sanitize::clean($this->request->data);
			$file = $data['User']['image'];
			if($file['size'] > 0)
			{
				$output   = $this->_image_picture();
				if(!$output['error']) $this->request->data['User']['image'] = $output['filename'];
				else $this->request->data['User']['image'] = '';
			}
			else $this->request->data['User']['image'] = '';
			$this->User->create();
			if($this->User->save($this->request->data)){
				$this->Redirect->flashSuccess(__('the_user_has_been_saved'));
			}
			else
			{
				if($file['size'] > 0)
				{
					@unlink(__USER_IMAGE_PATH.$output['filename']);
					@unlink(__USER_IMAGE_PATH.__UPLOAD_THUMB."/".$output['filename']);
				}
				$this->Redirect->flashWarning(__('the_user_could_not_be_saved_Please_try_again'));
			}
		}
		$roles = $this->User->Role->find('list',array('conditions'=>'Role.id <>1'));
		$this->set(compact('roles'));
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
		$this->set('title_for_layout',__('edit_user'));
		if(!$this->User->exists($id)){
			$this->Redirect->flashWarning(__('invalid_user'));
		}
		if($this->request->is(array('post', 'put'))){
			$data = Sanitize::clean($this->request->data);
			$file = $data['User']['image'];
			$result= $this->User->findById($id);
			if($file['size'] > 0){

				$filename = $result['User']['image'];
				@unlink(__USER_IMAGE_PATH.$filename);
				@unlink(__USER_IMAGE_PATH.__UPLOAD_THUMB."/".$filename);

				$output   = $this->_image_picture();
				if(!$output['error']) $this->request->data['User']['image'] = $output['filename'];
				else $this->request->data['User']['image'] = '';
			}
			else $this->request->data['User']['image'] = $this->request->data['User']['old_image'];
			if($this->User->save($this->request->data)){
				$this->Redirect->flashSuccess(__('the_user_has_been_saved'));
				return $this->redirect(array('action'=> 'index'));
			}
			else
			{
				if($file['size'] > 0){
					@unlink(__USER_IMAGE_PATH."/".$output['filename']);
					@unlink(__USER_IMAGE_PATH."/".__UPLOAD_THUMB."/".$output['filename']);
				}
				$this->Redirect->flashWarning(__('the_user_could_not_be_saved_Please_try_again'));
			}
		}
		else
		{
			$options = array('conditions' => array('User.' . $this->User->primaryKey=> $id));
			$this->request->data = $this->User->find('first', $options);
		}
		$roles = $this->User->Role->find('list',array('conditions'=>'Role.id <>1'));
		$this->set(compact('roles'));
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
		$this->User->id = $id;
		if(!$this->User->exists()){
			$this->Redirect->flashWarning(__('invalid_user'));
		}
		$result= $this->User->findById($id);
		if($this->User->delete()){
			$filename=$result['User']['image'];
			@unlink(__USER_IMAGE_PATH.$filename);
			@unlink(__USER_IMAGE_PATH.__UPLOAD_THUMB."/".$filename);
			$this->Redirect->flashSuccess(__('the_user_has_been_deleted'));
		}
		else
		{			
			$this->Redirect->flashWarning(__('the_user_could_not_be_deleted_please_try_again'));
		}
		return $this->redirect(array('action'=> 'index'));
	}


	function captcha_image()
	{
		App::import('Vendor', 'captcha/captcha');
		$captcha = new captcha();
		$captcha->show_captcha();
	}
}
