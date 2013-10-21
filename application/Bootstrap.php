<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    private $_acl = null;

    protected function _initAutoLoader() {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->setFallbackAutoloader(true); // pega tudo
    }

    public function _initViews() {
        $currency = new Zend_Currency('pt_BR');
        $currency->setFormat(array('symbol' => " R$ "));
        Zend_Registry::set('currency', $currency);
    }

    public function _initActionControlList() {
        if (Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session("registered"))->hasIdentity()) {
            Zend_Registry::set('role', Zend_Auth::getInstance()
                            ->setStorage(new Zend_Auth_Storage_Session("registered"))
                            ->getStorage()->read()->getRole());
        }
        else
            Zend_Registry::set('role', 'guest');

        $this->_acl = new Acl();
        Zend_Registry::set('acl', $this->_acl);

        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new Webagille_Plugins_CheckAcl($this->_acl));
    }

    public function _initMes() {
        $date = new Zend_Date();
        $month = $date->get('MM');

        if (!Zend_Registry::isRegistered('MONTH'))
            Zend_Registry::set('MONTH', $month);
    }

}

