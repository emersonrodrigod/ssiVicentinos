<?php

class AuthController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function indexAction() {
        $this->_helper->layout()->disableLayout();

        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();

            $authAdapter = $this->getAuthAdapter();
            $authAdapter->setIdentity($data ['email'])
                    ->setCredential($data ['senha']);

            $result = $authAdapter->authenticate();

            if ($result->isValid()) {
                $auth = Zend_Auth::getInstance();
                $auth->setStorage(new Zend_Auth_Storage_Session('usuario'));
                $dataAuth = $authAdapter->getResultRowObject(null, 'senha');

                $auth->getStorage()->write($dataAuth);
                $this->_redirect("/");
            } else {
                $this->view->messagem = '<div class="alert alert-error" style="margin-bottom: 0;"> Usuário ou senha incorretos!</div><br/>';
            }
        }
    }

    public function requestPasswordAction() {
        $this->_helper->layout()->disableLayout();
    }

    private function getAuthAdapter() {
        $bootstrap = $this->getInvokeArg('bootstrap');
        $resource = $bootstrap->getPluginResource('db');

        $db = $resource->getDbAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($db);
        $authAdapter->setTableName('usuario')
                ->setIdentityColumn('email')
                ->setCredentialColumn('senha')
                ->setCredentialTreatment('SHA1(?)');
        return $authAdapter;
    }

    public function logoutAction() {
        $auth = Zend_Auth::getInstance();
        $auth->setStorage(new Zend_Auth_Storage_Session('usuario'));
        $auth->clearIdentity();
        $this->_redirect("/auth");
    }

}