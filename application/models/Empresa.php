<?php

class Empresa extends Zend_Db_Table_Abstract {

    protected $_name = 'empresa';
    protected $_dependentTables = array('Usuario');

}
