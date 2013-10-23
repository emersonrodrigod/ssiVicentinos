<?php

class TipoSsi extends Zend_Db_Table_Abstract {

    protected $_name = 'tipo_ssi';
    protected $_dependentTables = array('Ssi');

}