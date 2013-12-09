<?php

class ErrorController extends Zend_Controller_Action {

    public function errorAction() {
        // limpa o conteúdo gerado antes do erro
        $this->getResponse()->clearBody();

        // pega a exceção e manda para o template
        $errors = $this->_getParam('error_handler');
        $this->view->exception = $errors->exception;

        // escolhe a view de acordo com o erro
        switch ($errors->type) {

            // página não encontrada (404)
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:

                $this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');

                // renderiza a view "error/404.phtml" no lugar da view padrão
                $this->render('404');
                break;

            // erro no programa, exceção não tratada
            // deixa renderizar o template padrão (error/error.phtml)
            default:
        }
    }

}

