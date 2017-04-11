export default function ($scope, $route, $location, $window, $rootScope) {
    'ngInject';

    $scope.$route = $route;

    function logout() {
        $window.localStorage.clear();
        $rootScope.isLoggedIn = false;
        $location.path('/loginpage');
    }

    $scope.logout = logout;
}