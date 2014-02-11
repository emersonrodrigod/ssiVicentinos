<?php

class SolicitacoesController extends Zend_Controller_Action {

    public function indexAction() {

        if ($this->getRequest()->isPost()) {

            $parametros = $this->getRequest()->getPost();

            $ssi = new Ssi();
            $this->view->chamados = $ssi->pesquisar($parametros);
        }
    }

}
