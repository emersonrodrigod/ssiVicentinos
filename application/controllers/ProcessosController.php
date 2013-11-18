<?php

class ProcessosController extends Zend_Controller_Action {

    public function indexAction() {
        $processo = new Processo();
        $this->view->processos = $processo->fetchAll();
    }

    public function novoAction() {
        if ($this->_request->isPost()) {

            $data = $this->_request->getPost();
            $processo = new Processo();

            if ($processo->acceptFromUserData($data)->isValid()) {
                try {
                    $processo->insert($data);
                    $this->_helper->flashMessenger(array('success' => 'Processo gravado com sucesso!'));
                    $this->_redirect('/processos');
                } catch (Zend_Db_Exception $exc) {
                    $this->_helper->flashMessenger(array('error' => 'Desculpe, ocorreu um erro: ' . $exc->getMessage()));
                }
            } else {

                foreach ($processo->acceptFromUserData($data)->getMessages() as $message) {
                    foreach ($message as $m) {
                        $this->_helper->flashMessenger(array('error' => $m));
                    }
                }
            }
        }
    }

    public function editarAction() {
        $processo = new Processo();
        $atual = $processo->find(intval($this->_getParam('id')))->current();
        $this->view->processo = $atual;

        if ($this->_request->isPost()) {

            $data = $this->_request->getPost();
            if ($processo->acceptFromUserData($data)->isValid()) {
                try {
                    $processo->update($data, "id = {$atual->id}");
                    $this->_helper->flashMessenger(array('success' => 'Processo atualizado com sucesso!'));
                    $this->_redirect('/processos');
                } catch (Zend_Db_Exception $exc) {
                    $this->_helper->flashMessenger(array('error' => 'Desculpe, ocorreu um erro: ' . $exc->getMessage()));
                }
            } else {

                foreach ($processo->acceptFromUserData($data)->getMessages() as $message) {
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

        $processo = new Processo();
        $atual = $processo->find(intval($this->_getParam('id')))->current();

        try {
            $atual->delete();
            $this->_helper->flashMessenger(array('success' => 'Processo removido com sucesso!'));
            $this->_redirect('/processos');
        } catch (Zend_Db_Exception $exc) {
            $this->_helper->flashMessenger(array('error' => 'Desculpe, ocorreu um erro: ' . $exc->getMessage()));
        }
    }

}
