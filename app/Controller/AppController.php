<?php

App::uses('Controller', 'Controller');
App::uses('PluginHandler', 'Lib');
App::uses('SettingHandler', 'Lib');
class AppController extends Controller
{
    public $components = array(
        'Cookie',
        'Session',
        'RequestHandler',
        'Auth',
        'CmsAcl',
        'Redirect',
		'UserSession',
		'Cms');
    public $helpers = array(
        'Html',
        'Form',
        'Session',
        'Text',
        'Time',
        'Paginator',
		'UserSession',
		'PersianDate',
		'Cms',
		'Plugin');


    public function beforeRender()
    {
        $this->_configureErrorLayout();
    }
    public function _configureErrorLayout()
    {
        if ($this->name == 'CakeError')
        {
            if ($this->_isAdminMode())
            {
                $this->layout = 'admin_error';
            } else
            {
                $this->layout = 'error';
            }
        }
    }

    public function _isAdminMode()
    {
        $adminRoute = Configure::read('Routing.prefixes');
        if (isset($this->params['prefix']) && in_array($this->params['prefix'], $adminRoute))
        {
            return true;
        }
        return false;
    }


    public function beforeFilter()
    {
        PluginHandler::Instance($this->request)->attach();
        SettingHandler::Instance();
		
		$this->_setLanguage();

        $this->CmsAcl->check_permision($this->request->params);

        if ($this->request->is('ajax'))
        {
            Configure::write('debug', 0);
            $this->layout = 'ajax';
            $this->autoRender = false;
        }

        $this->Auth->authorize = array('Actions' => array('actionPath' => 'controllers'));

        if (isset($this->request->params['admin']) && ($this->request->params['prefix'] ==
            'admin'))
        {
            //$components = array('AdminHtml');
			$this->Auth->loginAction = array(
                'controller' => 'users',
                'action' => 'login',
                'admin' => true);
            $this->Auth->logoutRedirect = array(
                'controller' => 'users',
                'action' => 'login',
                'admin' => true);
            $this->Auth->loginRedirect = array('controller' => 'users', 'action' => 'dashboard', 'admin' => true);

            $this->Auth->authenticate = array(AuthComponent::ALL => array(
                    'userModel' => 'User',
                    'fields' => array('username' => 'email', 'password' => 'password'),
                    'scope' => array('User.status' => 1, 'User.role_id <>' => 2 /* admin */ )),
                    'Form');

        } else
        {

            $this->Auth->loginAction = array(
                'controller' => 'pages',
                'action' => 'display',
                'admin' => false);
            //$this->Auth->loginRedirect = array('controller' => 'orders', 'action' => 'index', 'admin' => true);
            //$this->Auth->logoutRedirect = array('controller' => 'pages', 'action' => 'display', 'admin' => false);

            $this->Auth->authenticate = array(AuthComponent::ALL => array(
                    'userModel' => 'User',
                    'fields' => array('username' => 'email', 'password' => 'password'),
                    'scope' => array('User.status' => 1 /*,
                            'User.role_id'=>2     /* user role */)), 'Form');
        }


        if (isset($this->request->params['admin']) && ($this->request->params['prefix'] ==
            'admin'))
        {

            if (!$this->_check_admin_login_user())
            {
                $this->layout = 'admin_login';
                $this->Auth->allow($this->CmsAcl->_adminAllUsers());

            } else
            {
                $this->layout = 'admin';
                $this->Auth->allow($this->CmsAcl->_adminMemberAllow());
            }

        } else
        {

            if (isset($this->is_mobile) && $this->is_mobile)
            {
                $this->layout = 'mobile';
            } else
            {
                if (!$this->_check_login_user())
                {
                    $this->Auth->allow($this->CmsAcl->_allUsers());
                } else
                {
                    $this->layout = 'default';
                    $this->Auth->allow($this->CmsAcl->_memberAllow());
                }
            }
        }

    }

    private function _setLanguage()
    {
        $this->Session->write('Config.language', 'fas');
        $this->Cookie->write('lang', 'fas', false, '2000 days');
        $this->set('locale', 'fas');
    }


    /**
     * check user for login
     * 
     */
    function _check_login_user()
    {
        if ($this->Session->check('User_Info'))
        {
            return true;
        }
        return false;
    }

    /**
     * check user for login
     * 
     */
    function _check_admin_login_user()
    {
        if ($this->Session->check('AdminUser_Info'))
        {
            return true;
        }
        return false;
    }


}
