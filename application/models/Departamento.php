<?php

class Departamento extends Zend_Db_Table_Abstract {

    protected $_name = "departamento";
    protected $_dependentTables = array('usuario');
    protected $_referenceMap = array(
        'Empresa' => array(
            'refTableClass' => 'Empresa',
            'refColumns' => array('id'),
            'columns' => array('id_empresa')
        )
    );

    public function acceptFromUserData($dados) {

        $validadores = array(
            'nome' => array(
                'allowEmpty' => false
            )
        );

        return new Zend_Filter_Input(array(), $validadores, $dados);
    }

}
