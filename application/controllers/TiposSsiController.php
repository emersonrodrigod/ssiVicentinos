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

            try {
                $tipo->insert($data);
                $this->_helper->flashMessenger(array('success' => 'Tipo de Solicitação gravada com sucesso!'));
                $this->_redirect('/tipos-ssi');
            } catch (Zend_Db_Exception $exc) {
                $this->_helper->flashMessenger(array('error' => 'Desculpe, ocorreu um erro: ' . $exc->getMessage()));
            }
        }
    }

    public function editarAction() {
        $tipo = new TipoSsi();
        $atual = $tipo->find(intval($this->_getParam('id')))->current();
        $this->view->tipo = $atual;

        if ($this->_request->isPost()) {

            $data = $this->_request->getPost();

            try {
                $tipo->update($data, "id = {$atual->id}");
                $this->_helper->flashMessenger(array('success' => 'Tipo de Solicitação atualizado com sucesso!'));
                $this->_redirect('/tipos-ssi');
            } catch (Zend_Db_Exception $exc) {
                $this->_helper->flashMessenger(array('error' => 'Desculpe, ocorreu um erro: ' . $exc->getMessage()));
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