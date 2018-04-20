var app = angular.module('paysApp', []);



app.controller('PaysListController', function ($scope, $http) {

    $scope.pays = {};

    $scope.submit = function($event) {

        $event.preventDefault();

        var csrf = $event.target[5].value;
        $scope.pays._token = csrf;

        var pays = {};
        pays.form = $scope.pays;


        $http.post( '/calc', pays ).then(function( res ){

            if ( res.data !='undefined') {
                var result = [];
                for(k in res.data.data){
                    result.push(JSON.parse(res.data.data[k]));
                }

                /*$scope.pays = {};
                $scope.form.$setPristine();*/

                $scope.catchedPays = result;

            }
        });



    }



});