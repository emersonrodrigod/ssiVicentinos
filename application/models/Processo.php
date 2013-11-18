<?php

class Processo extends Zend_Db_Table_Abstract {

    protected $_name = 'processo';
    protected $_dependentTables = array('Ssi');

    public function acceptFromUserData($dados) {

        $validadores = array(
            'nome' => array(
                'allowEmpty' => false
            )
        );

        return new Zend_Filter_Input(array(), $validadores, $dados);
    }

}