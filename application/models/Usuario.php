<?php

class Usuario extends Zend_Db_Table_Abstract {

    protected $_name = 'usuario';
    protected $_dependentTables = array('Ssi', 'HistoricoSsi', 'LogAcessos');
    protected $_referenceMap = array(
        'Empresa' => array(
            'refTableClass' => 'Empresa',
            'refColumns' => array('id'),
            'columns' => array('id_empresa')
        ),
        'Departamento' => array(
            'refTableClass' => 'Departamento',
            'refColumns' => array('id'),
            'columns' => array('id_departamento')
        )
    );

}
