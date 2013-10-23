<?php

class HistoricoSsi extends Zend_Db_Table_Abstract {

    protected $_name = 'historico_ssi';
    protected $_referenceMap = array(
        'Ssi' => array(
            'refTableClass' => 'Ssi',
            'refColumns' => array('id'),
            'columns' => array('id_ssi')
        ),
        'Usuario' => array(
            'refTableClass' => 'Usuario',
            'refColumns' => array('id'),
            'columns' => array('id_usuario')
        )
    );

}