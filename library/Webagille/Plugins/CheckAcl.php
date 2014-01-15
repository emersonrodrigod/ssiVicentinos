<?php

class Webagille_Plugins_CheckAcl extends Zend_Controller_Plugin_Abstract {

    private $_acl = null;

    public function __construct(Zend_Acl $acl) {
        $this->_acl = $acl;
    }

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
        $module = $request->getModuleName();
        $resource = $request->getControllerName();
        $action = $request->getActionName();

        if (!$this->_acl->has($module . ':' . $resource)) {
            $this->redirect404($request);
        } elseif (!$this->_acl->isAllowed(Zend_Registry::get('role'), $module . ':' . $resource, $action)) {
            $this->redirect($request);
        }
    }

    public function redirect($request) {
        $request->setModuleName('default')
                ->setControllerName('error')
                ->setActionName('access-denied');
    }

    public function redirect404($request) {
        $request->setModuleName('default')
                ->setControllerName('error')
                ->setActionName('page-not-found');
    }

}
