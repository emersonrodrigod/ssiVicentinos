<?php

class Ssi extends Zend_Db_Table_Abstract {

    protected $_name = "ssi";
    protected $_dependentTables = array('HistoricoSsi');
    protected $_referenceMap = array(
        'TipoSsi' => array(
            'refTableClass' => 'TipoSsi',
            'refColumns' => array('id'),
            'columns' => array('id_tipo')
        ),
        'Usuario' => array(
            'refTableClass' => 'Usuario',
            'refColumns' => array('id'),
            'columns' => array('id_usuario')
        ),
        'Responsavel' => array(
            'refTableClass' => 'Usuario',
            'refColumns' => array('id'),
            'columns' => array('id_responsavel')
        ),
        'Processo' => array(
            'refTableClass' => 'Processo',
            'refColumns' => array('id'),
            'columns' => array('id_processo')
        )
    );

}
