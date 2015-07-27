var geptoolApp = angular.module('geptoolApp', []);

geptoolApp.controller('UsersReportCtrl', function ($scope, $http) {

    $http.get('/api/v1/users.json').success(function(data) {
        $scope.users = data;
    });
});
