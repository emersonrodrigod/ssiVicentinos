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
        ),
        'Empresa' => array(
            'refTableClass' => 'Empresa',
            'refColumns' => array('id'),
            'columns' => array('id_empresa')
        ),
        'Departamento' => array(
            'refTableClass' => 'Departamento',
            'refColumns' => array('id'),
            'columns' => array('id_departamento')
        ),
        'QuemAbriu' => array(
            'refTableClass' => 'Usuario',
            'refColumns' => array('id'),
            'columns' => array('id_usuario')
        ),
    );

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

        if ($parametros['tipo'] != '') {
            $where .= " and id_tipo = {$parametros['tipo']}";
        }

        if (isset($parametros['role'])) {
            $where .= " and id_usuario = {$parametros['role']}";
        }

        return $this->fetchAll($where);
    }

    public function gravar($dados) {

        if (isset($dados['anexo'])) {
            unset($dados['anexo']);

            //rotina para inserir o anexo
        }

        try {
            $lastId = $this->insert($dados);

            //Rotina para gravar o histórico
            $dadosHistorico = array(
                'id_ssi' => $lastId,
                'id_usuario' => $dados['id_usuario'],
                'descricao' => "Solicitação cadastrada no sistema."
            );

            $this->gravaHistorico($dadosHistorico);

            //Rotina para enviar o e-mail
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    public function gravaHistorico($dados) {
        $historico = new HistoricoSsi();
        $historico->insert($dados);
    }

}
