<?php

class DepartamentoTest extends PHPUnit_Framework_TestCase {

    private $adapter;

    public function setUp() {

        $configPath = __DIR__ . '/../../../application/configs/application.ini';

        $config = new Zend_Config_Ini($configPath, 'development');
                
        $this->adapter = new Zend_Db_Adapter_Pdo_Mysql($config->db->toArray());
        Zend_Db_Table_Abstract::setDefaultAdapter();
    }

    public function assertPreConditions() {
        $this->assertTrue(
                class_exists($class = 'Departamento'), 'Class not found: ' . $class
        );
    }

    public function testInstantiationWithoutArgumentsShouldWork() {
        $instance = new Departamento();
        $this->assertInstanceOf('Departamento', $instance);
    }

}
