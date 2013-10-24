<?php

class EmpresasController extends Zend_Controller_Action {

    public function indexAction() {
        $empresa = new Empresa();
        $this->view->empresas = $empresa->fetchAll();
    }

    public function novoAction() {
        if ($this->_request->isPost()) {

            $data = $this->_request->getPost();
            $empresa = new Empresa();

            try {
                $empresa->insert($data);
                $this->_helper->flashMessenger(array('success' => 'Empresa gravada com sucesso!'));
                $this->_redirect('/empresas');
            } catch (Zend_Db_Exception $exc) {
                $this->_helper->flashMessenger(array('error' => 'Desculpe, ocorreu um erro: ' . $exc->getMessage()));
            }
        }
    }

    public function editarAction() {
        $empresa = new Empresa();
        $atual = $empresa->find(intval($this->_getParam('id')))->current();
        $this->view->empresa = $atual;

        if ($this->_request->isPost()) {

            $data = $this->_request->getPost();

            try {
                $empresa->update($data, "id = {$atual->id}");
                $this->_helper->flashMessenger(array('success' => 'Empresa atualizada com sucesso!'));
                $this->_redirect('/empresas');
            } catch (Zend_Db_Exception $exc) {
                $this->_helper->flashMessenger(array('error' => 'Desculpe, ocorreu um erro: ' . $exc->getMessage()));
            }
        }
    }

    public function removerAction() {
        $this->_helper->layout()->disableLayout();
        $this->getHelper('viewRenderer')->setNoRender();

        $empresa = new Empresa();
        $atual = $empresa->find(intval($this->_getParam('id')))->current();

        try {
            $atual->delete();
            $this->_helper->flashMessenger(array('success' => 'Empresa Removida com sucesso!'));
            $this->_redirect('/empresas');
        } catch (Zend_Db_Exception $exc) {
            $this->_helper->flashMessenger(array('error' => 'Desculpe, ocorreu um erro: ' . $exc->getMessage()));
        }
    }

}