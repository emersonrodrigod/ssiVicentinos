<?php

/**
 * Description of AuthController
 *
 * @author Anterio
 */
class AuthController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function indexAction() {
        $idUser = $this->getRequest()->getParam('act', null);
        $this->_helper->layout()->disableLayout();
     

        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();


            $authAdapter = $this->getAuthAdapter();
            $authAdapter->setIdentity($data ['email'])
                    ->setCredential($data ['password']);

            $result = $authAdapter->authenticate();

            if ($result->isValid()) {
                $auth = Zend_Auth::getInstance();
                $auth->setStorage(new Zend_Auth_Storage_Session('registered'));
                $dataAuth = $authAdapter->getResultRowObject(null, 'password');
                $userAuht = $user->find($dataAuth->id);
                $userAuht->setLast_accessed(Zend_Date::now()->get('yyyy-MM-dd HH:mm:ss', 'pt_BR'));
                $userAuht->save();

                $auth->getStorage()->write($userAuht);
                $this->_redirect("/index/visao-geral");
            } else {
                $this->view->messagem = '<div class="alert" style="margin-bottom: 0;"> Usuário ou senha incorretos!</div>';
            }
        }
    }

    public function requestPasswordAction() {
        $this->_helper->layout()->disableLayout();
        $this->_redirect('/auth');
    }

    public function registrarAction() {

        $this->_helper->layout()->disableLayout();

        $categoryMapper = new Category();
        $account = new Account();
        $form = new Form_Registration();
        $userMapper = new User();

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            if ($form->isValid($data)) {
                $data['role'] = 'registered';
                $data['active'] = 1;
                $userMapper->setOptions($data);

                /** Inicia transação */
                Zend_Db_Table::getDefaultAdapter()->beginTransaction();
                try {

                    $userId = $userMapper->save();

                    $idAccount = $account->setName('Conta Inicial')
                            ->setUsers_id($userId)
                            ->save();

                    $categoryMapper->setName('Outros')
                            ->setColor('#40850a')
                            ->setActive(1)
                            ->setAccounts_id($idAccount)
                            ->save();

                    /** Comita transação */
                    Zend_Db_Table::getDefaultAdapter()->commit();
                    $this->_redirect('/auth/index/act/' . $userId);
                    return true;
                } catch (Exception $exc) {
                    /** Desfaz transação */
                    Zend_Db_Table::getDefaultAdapter()->rollBack();
                    $this->view->messagem = '<div class="alert alert-error">' . $exc->getMessage() . '</div>';
                }
            } else {
                $form->populate($data);
            }
            $this->_redirect('/auth/index/box/reg');
        }

        $this->view->form = $form;
    }

    private function getAuthAdapter() {
        $bootstrap = $this->getInvokeArg('bootstrap');
        $resource = $bootstrap->getPluginResource('db');

        $db = $resource->getDbAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($db);
        $authAdapter->setTableName('users')
                ->setIdentityColumn('email')
                ->setCredentialColumn('password')
                ->setCredentialTreatment('SHA1(?)');
        return $authAdapter;
    }

    public function logoutAction() {
        $auth = Zend_Auth::getInstance();
        $auth->setStorage(new Zend_Auth_Storage_Session('registered'));
        $auth->clearIdentity();
        $this->_redirect("/auth");
    }

}