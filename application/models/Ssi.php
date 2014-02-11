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

    /*
     * Array
      (
      [codigo] =>
      [texto] =>
      [empresa] =>
      [departamento] =>
      [situacao] => Array
      (
      [0] => PENDENTE
      [1] => ANDAMENTO
      [2] => PARALISADA
      [3] => TERCEIROS
      [4] => CONCLUIDA
      [5] => CANCELADA
      )
      [inicio] => PT-BR;
      [final] => PT-BR;
      )
     */

    public function pesquisar($parametros) {
        $where = "1 = 1";

        if ($parametros['codigo'] != '') {
            $where .= " and id = {$parametros['codigo']}";
        }

        if ($parametros['texto'] != '') {
            $where .= " and (resumo like '%{$parametros['texto']}%' or descricao like '%{$parametros['texto']}%')";
        }

        if ($parametros['empresa'] != '') {
            $where .= " and id_empresa = {$parametros['empresa']}";
        }

        if ($parametros['departamento'] != '') {
            $where .= " and id_departamento = {$parametros['departamento']}";
        }

        if (!empty($parametros['situacao'])) {

            $where .= " and status in(";

            foreach ($parametros['situacao'] as $situacao) {
                $where .= "'{$situacao}',";
            }

            $where = substr($where, 0, -1);

            $where .= ")";
        }

        if ($parametros['inicio'] != '' && $parametros['final'] != '') {
            $dataInicial = Util::dataMysql($parametros['inicio']);
            $dataFinal = Util::dataMysql($parametros['final']);
            $where .= " and date(dataAbertura) >= '{$dataInicial}' and date(dataAbertura) <= '{$dataFinal}'";
        }
        
        if($parametros['tipo'] != ''){
            $where .= " and id_tipo = {$parametros['tipo']}";
        }

        return $this->fetchAll($where);
    }

}
