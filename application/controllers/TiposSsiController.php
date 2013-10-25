<?php

class TiposSsiController extends Zend_Controller_Action {

    public function indexAction() {
        $tipo = new TipoSsi();
        $this->view->tipos = $tipo->fetchAll();
    }

    public function novoAction() {
        if ($this->_request->isPost()) {

            $data = $this->_request->getPost();
            $tipo = new TipoSsi();

            if ($tipo->acceptFromUserData($data)->isValid()) {
                try {
                    $tipo->insert($data);
                    $this->_helper->flashMessenger(array('success' => 'Tipo de Solicitação gravada com sucesso!'));
                    $this->_redirect('/tipos-ssi');
                } catch (Zend_Db_Exception $exc) {
                    $this->_helper->flashMessenger(array('error' => 'Desculpe, ocorreu um erro: ' . $exc->getMessage()));
                }
            } else {

                foreach ($tipo->acceptFromUserData($data)->getMessages() as $message) {
                    foreach ($message as $m) {
                        $this->_helper->flashMessenger(array('error' => $m));
                    }
                }
            }
        }
    }

    public function editarAction() {
        $tipo = new TipoSsi();
        $atual = $tipo->find(intval($this->_getParam('id')))->current();
        $this->view->tipo = $atual;

        if ($this->_request->isPost()) {

            $data = $this->_request->getPost();
            if ($tipo->acceptFromUserData($data)->isValid()) {
                try {
                    $tipo->update($data, "id = {$atual->id}");
                    $this->_helper->flashMessenger(array('success' => 'Tipo de Solicitação atualizado com sucesso!'));
                    $this->_redirect('/tipos-ssi');
                } catch (Zend_Db_Exception $exc) {
                    $this->_helper->flashMessenger(array('error' => 'Desculpe, ocorreu um erro: ' . $exc->getMessage()));
                }
            } else {

                foreach ($tipo->acceptFromUserData($data)->getMessages() as $message) {
                    foreach ($message as $m) {
                        $this->_helper->flashMessenger(array('error' => $m));
                    }
                }
            }
        }
    }

    public function removerAction() {
        $this->_helper->layout()->disableLayout();
        $this->getHelper('viewRenderer')->setNoRender();

        $tipo = new TipoSsi();
        $atual = $tipo->find(intval($this->_getParam('id')))->current();

        try {
            $atual->delete();
            $this->_helper->flashMessenger(array('success' => 'Tipo de Solicitação removido com sucesso!'));
            $this->_redirect('/tipos-ssi');
        } catch (Zend_Db_Exception $exc) {
            $this->_helper->flashMessenger(array('error' => 'Desculpe, ocorreu um erro: ' . $exc->getMessage()));
        }
    }

}