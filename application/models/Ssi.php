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

            $this->enviaEmail(
                    "Solicitação de serviço cadastrada", "Sua Solicitação de serviço foi cadastrada no sistema. Em breve sua solicitação será concluída.", $this->find($lastId)->current(), array($this->find($lastId)->current()->findParentRow('Usuario')->email, 'ti@vicentinos.com.br')
            );

            //Rotina para enviar o e-mail
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    public function gravaHistorico($dados) {
        $historico = new HistoricoSsi();
        $historico->insert($dados);
    }

    public function solicitaPosicao($dados) {
        $this->gravaHistorico($dados);
    }

    public function cancelaSolicitacao($solicitacao, $dadosHistorico) {
        $dadosSsi = array(
            'status' => 'CANCELADA',
            'dataEncerramento' => date("Y-m-d H:i:s")
        );

        $this->update($dadosSsi, "id = {$solicitacao->id}");
        $this->gravaHistorico($dadosHistorico);

        $this->enviaEmail(
                "Cancelamento de solicitação de serviço", "Solicitação de Serviço cancelada pelo usuário", $solicitacao, array($solicitacao->findParentRow('Usuario')->email, 'ti@vicentinos.com.br')
        );
    }

    public function reabreSolicitacao($solicitacao, $dadosHistorico) {
        $dadosSsi = array(
            'status' => 'PENDENTE',
            'dataEncerramento' => null
        );

        $this->update($dadosSsi, "id = {$solicitacao->id}");
        $this->gravaHistorico($dadosHistorico);

        $this->enviaEmail(
                "Solicitação de serviço reaberta", "Solicitação de Serviço foi reaberta no sistema", $solicitacao, array($solicitacao->findParentRow('Usuario')->email, 'ti@vicentinos.com.br')
        );
    }

    public function transferirSolicitacao($solicitacao, $dadosHistorico) {
        $dadosSsi = array(
            'status' => 'TERCEIROS'
        );

        $this->update($dadosSsi, "id = {$solicitacao->id}");
        $this->gravaHistorico($dadosHistorico);

        $this->enviaEmail(
                "Solicitação de serviço Transferida", "Solicitação de Serviço foi Transferida para um terceiro", $solicitacao, array($solicitacao->findParentRow('Usuario')->email)
        );
    }

    public function concluirSolicitacao($solicitacao, $dadosHistorico) {
        $dadosSsi = array(
            'status' => 'CONCLUIDA',
            'dataEncerramento' => date("Y-m-d H:i:s")
        );

        $this->update($dadosSsi, "id = {$solicitacao->id}");
        $this->gravaHistorico($dadosHistorico);

        $this->enviaEmail(
                "Solicitação de serviço Concluída", "Solicitação de Serviço foi Concluída", $solicitacao, array($solicitacao->findParentRow('Usuario')->email)
        );
    }

    public function classificarSolicitacao($solicitacao, $dadosHistorico, $dados) {
        $dadosSsi = array(
            'status' => 'ANDAMENTO',
            'previsaoEncerramento' => Util::dataMysql($dados['previsaoConclusao']),
            'id_processo' => $dados['processo'],
            'id_tipo' => $dados['tipo']
        );

        $this->update($dadosSsi, "id = {$solicitacao->id}");
        $this->gravaHistorico($dadosHistorico);

        $this->enviaEmail(
                "Solicitação de serviço Em Andamento", "Solicitação de está em andamento", $solicitacao, array($solicitacao->findParentRow('Usuario')->email)
        );
    }

    public function enviaEmail($titulo, $texto, $ssi, $from = array()) {
        $html = new Zend_View();
        $html->setScriptPath(APPLICATION_PATH . '/views/scripts/templates/');

        $html->assign('titulo', $titulo);
        $html->assign('texto', $texto);
        $html->assign('registro', $ssi);

        $bodyText = $html->render('mailTemplate.phtml');

        $util = new Util();

        $util->sendMail("SSI - Notificação Automática", $bodyText, $from);
    }

}
