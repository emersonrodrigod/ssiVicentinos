<?php

class AjaxController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->layout()->disableLayout();
        $this->getHelper('viewRenderer')->setNoRender();
    }

    public function getEmpresasAction() {
        $empresa = new Empresa();
        $todas = $empresa->fetchAll()->toArray();
        echo Zend_Json_Encoder::encode($todas);
    }

    public function getDepartamentosByEmpresaAction() {
        $departamento = new Departamento();
        $departamentos = $departamento->getByEmpresa($this->_getParam('empresa'))->toArray();
        echo Zend_Json_Encoder::encode($departamentos);
    }

    public function getTipoAction() {
        $tipo = new TipoSsi();
        $tipos = $tipo->fetchAll('ativo = 1')->toArray();
        echo Zend_Json_Encoder::encode($tipos);
    }

    public function getUsuarioByDepartamentoAction() {
        $usuario = new Usuario();
        $usuarios = $usuario->getByDepartamento($this->_getParam(('departamento')))->toArray();
        echo Zend_Json_Encoder::encode($usuarios);
    }

}
