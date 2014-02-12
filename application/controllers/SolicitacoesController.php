<?php

class SolicitacoesController extends Zend_Controller_Action {

    public function indexAction() {

        if ($this->getRequest()->isPost()) {

            $parametros = $this->getRequest()->getPost();
            $storage = new Zend_Auth_Storage_Session("usuario");
            
            if ($storage->read()->role == 'usuario') {
                $parametros['role'] = $storage->read()->id;
            }

            $ssi = new Ssi();
            $this->view->chamados = $ssi->pesquisar($parametros);
        }
    }

    public function novoAction() {
        $storage = new Zend_Auth_Storage_Session("usuario");
        $this->view->usuarioLogado = $storage;

        if ($this->getRequest()->isPost()) {
            $dados = $this->getRequest()->getPost();
            try {
                $ssi = new Ssi();
                $ssi->gravar($dados);
                $this->_helper->flashMessenger(array('success' => 'Solicitação cadastrada com sucesso!'));
                $this->_redirect('/solicitacoes');
            } catch (Zend_Db_Exception $e) {
                $this->_helper->flashMessenger(array('error' => 'Desculpe, ocorreu um erro: ' . $e->getMessage()));
            }
        }
    }

}
