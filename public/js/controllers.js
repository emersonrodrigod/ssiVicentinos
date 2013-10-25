function DepartamentosController($scope, $http, $window) {

    /**
     * Metododo que retorna a lista dew departamentos de uma empresa
     * @param id_empresa O identificador da empresa em questão
     */
    $scope.getDepartamentos = function(id_empresa) {
        $http.get("/empresas/lista-departamentos/empresa/" + id_empresa).success(function(data) {
            $scope.departamentos = data.departamentos;
        }).error(function(status) {
            $scope.status = status;
        });
    };

    $scope.salvar = function(f, id_empresa) {
        var form = $('#' + f);
        var dados = form.serialize();
        console.log(dados);
        $.ajax({
            type: 'post',
            url: '/empresas/salva-departamento',
            data: dados
        }).success(function(data) {
            $scope.getDepartamentos(id_empresa);
            reset();
        });
    };

    $scope.excluir = function(departamento, id_empresa) {
        var confirm = $window.confirm('Tem certeza que deseja excluir o departamento ' + departamento.nome + '?');
        if (confirm) {
            $http.get('/empresas/remove-departamento/id/' + departamento.id).success(function(data) {
                $scope.getDepartamentos(id_empresa);
            });
        }
    };

    $scope.editar = function(departamento) {
        $scope.departamento = departamento;
        console.log($scope.departamento.id);
    };

    var reset = function() {
        $scope.departamento = {id: 0, nome: '', ativo: ''};
    };


}