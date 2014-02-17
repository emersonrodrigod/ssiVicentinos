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

    public function detalhesAction() {
        $ssi = new Ssi();
        $atual = $ssi->find(intval($this->_getParam('id')))->current();
        $storage = new Zend_Auth_Storage_Session("usuario");

        if (!$this->verficaPermissao($atual)) {
            $this->_redirect("/error/access-denied");
        }

        $this->view->ssi = $atual;
        $this->view->solicitante = $atual->findParentRow('Usuario');
        $this->view->sessionStorage = $storage;
    }

    public function solicitaPosicaoAction() {

        $this->_helper->layout()->disableLayout();
        $this->getHelper('viewRenderer')->setNoRender();

        $ssi = new Ssi();
        $atual = $ssi->find(intval($this->_getParam('id')))->current();
        $storage = new Zend_Auth_Storage_Session("usuario");

        try {

            $dadosHistorico = array(
                'id_ssi' => $atual->id,
                'id_usuario' => $storage->read()->id,
                'descricao' => "Usuário solicita posição sobre andamento da Solicitação de Serviço."
            );

            $ssi->solicitaPosicao($dadosHistorico);
            $this->_helper->flashMessenger(array('success' => 'Solicitação de posicionamento gravada com sucesso!'));
            $this->_redirect("/solicitacoes/detalhes/id/{$atual->id}");
        } catch (Exception $e) {
            $this->_helper->flashMessenger(array('error' => 'Desculpe, ocorreu um erro: ' . $e->getMessage()));
        }
    }

    public function cancelaSolicitacaoAction() {
        $this->_helper->layout()->disableLayout();
        $this->getHelper('viewRenderer')->setNoRender();

        $ssi = new Ssi();
        $atual = $ssi->find(intval($this->_getParam('id')))->current();
        $storage = new Zend_Auth_Storage_Session("usuario");

        try {

            $dadosHistorico = array(
                'id_ssi' => $atual->id,
                'id_usuario' => $storage->read()->id,
                'descricao' => "Usuário Cancelou a Solicitação."
            );

            $ssi->cancelaSolicitacao($atual, $dadosHistorico);

            $this->_helper->flashMessenger(array('success' => 'Solicitação Cancelada com sucesso!'));
            $this->_redirect("/solicitacoes/detalhes/id/{$atual->id}");
        } catch (Exception $e) {
            $this->_helper->flashMessenger(array('error' => 'Desculpe, ocorreu um erro: ' . $e->getMessage()));
        }
    }

    public function reabreAction() {
        $this->_helper->layout()->disableLayout();
        $this->getHelper('viewRenderer')->setNoRender();

        $ssi = new Ssi();
        $atual = $ssi->find(intval($this->_getParam('id')))->current();
        $storage = new Zend_Auth_Storage_Session("usuario");

        try {

            $dadosHistorico = array(
                'id_ssi' => $atual->id,
                'id_usuario' => $storage->read()->id,
                'descricao' => "Usuário reabriu solicitação."
            );

            $ssi->reabreSolicitacao($atual, $dadosHistorico);

            $this->_helper->flashMessenger(array('success' => 'Solicitação reaberta com sucesso!'));
            $this->_redirect("/solicitacoes/detalhes/id/{$atual->id}");
        } catch (Exception $e) {
            $this->_helper->flashMessenger(array('error' => 'Desculpe, ocorreu um erro: ' . $e->getMessage()));
        }
    }

    public function transferirAction() {
        $this->_helper->layout()->disableLayout();
        $this->getHelper('viewRenderer')->setNoRender();

        $post = $this->_request->getPost();

        $ssi = new Ssi();
        $atual = $ssi->find($post['id_ssi'])->current();
        $storage = new Zend_Auth_Storage_Session("usuario");

        try {

            $dadosHistorico = array(
                'id_ssi' => $atual->id,
                'id_usuario' => $storage->read()->id,
                'descricao' => "Solicitação transferida para {$post['terceiro']}."
            );

            $ssi->transferirSolicitacao($atual, $dadosHistorico);

            $this->_helper->flashMessenger(array('success' => 'Solicitação Transferida com sucesso!'));
            $this->_redirect("/solicitacoes/detalhes/id/{$atual->id}");
        } catch (Exception $e) {
            $this->_helper->flashMessenger(array('error' => 'Desculpe, ocorreu um erro: ' . $e->getMessage()));
        }
    }

    public function concluirAction() {
        $this->_helper->layout()->disableLayout();
        $this->getHelper('viewRenderer')->setNoRender();

        $post = $this->_request->getPost();

        $ssi = new Ssi();
        $atual = $ssi->find($post['id_ssi'])->current();
        $storage = new Zend_Auth_Storage_Session("usuario");

        try {

            $dadosHistorico = array(
                'id_ssi' => $atual->id,
                'id_usuario' => $storage->read()->id,
                'descricao' => "Solicitação Concluída: <br/> {$post['descricao']}."
            );

            $ssi->concluirSolicitacao($atual, $dadosHistorico);

            $this->_helper->flashMessenger(array('success' => 'Solicitação Concluída com sucesso!'));
            $this->_redirect("/solicitacoes/detalhes/id/{$atual->id}");
        } catch (Exception $e) {
            $this->_helper->flashMessenger(array('error' => 'Desculpe, ocorreu um erro: ' . $e->getMessage()));
        }
    }

    public function classificarAction() {
        $this->_helper->layout()->disableLayout();
        $this->getHelper('viewRenderer')->setNoRender();

        $post = $this->_request->getPost();

        $ssi = new Ssi();
        $atual = $ssi->find($post['id_ssi'])->current();
        $storage = new Zend_Auth_Storage_Session("usuario");

        try {

            $dadosHistorico = array(
                'id_ssi' => $atual->id,
                'id_usuario' => $storage->read()->id,
                'descricao' => "Solicitação em andamento."
            );

            $ssi->classificarSolicitacao($atual, $dadosHistorico, $post);

            $this->_helper->flashMessenger(array('success' => 'Solicitação Classificada com sucesso!'));
            $this->_redirect("/solicitacoes/detalhes/id/{$atual->id}");
        } catch (Exception $e) {
            $this->_helper->flashMessenger(array('error' => 'Desculpe, ocorreu um erro: ' . $e->getMessage()));
        }
    }

    public function verficaPermissao($solicitacao) {
        $storage = new Zend_Auth_Storage_Session("usuario");
        $role = $storage->read()->role;
        $usuario = $storage->read()->id;

        if ($role == 'usuario') {

            if ($solicitacao->id_usuario != $usuario) {
                return false;
            }
        }

        return true;
    }

}
