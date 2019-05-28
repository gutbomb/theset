var app = angular.module('theSetApp', ['ngRoute', 'ngSanitize', 'theSetApp.config']);
// this is to test jenkins
import convertToNumber from '../components/common/convert-to-number/convert-to-number-directive';
import navMenuController from '../components/common/nav-menu/nav-menu-controller';
import navMenu from '../components/common/nav-menu/nav-menu-directive';
import jumbotron from '../components/common/jumbotron/jumbotron-directive';
import pageFooter from '../components/common/page-footer/page-footer-directive';
import homeController from '../components/home/home-controller';
import albumsController from '../components/albums/albums-controller';
import artistsController from '../components/artists/artists-controller';
import viewAlbumController from '../components/albums/view-album-controller';
import viewArtistController from '../components/artists/view-artist-controller';
import genresController from '../components/genres/genres-controller';
import statusController from '../components/status/status-controller';
import accountController from '../components/account/account-controller';
import loginController from '../components/login/login-controller';
import loginService from '../components/login/login-service';
import authenticationInterceptor from '../components/login/authentication-interceptor-service';
import changePasswordController from '../components/account/change-password-controller';
import usersController from '../components/users/users-controller';
import addUserController from '../components/users/add-user-controller';
import editUserController from '../components/users/edit-user-controller';
import deleteUserModal from '../components/users/delete-user-modal-directive';

app.config(function ($routeProvider, $locationProvider, $sceDelegateProvider) {
    $sceDelegateProvider.resourceUrlWhitelist([
        'self',
        'http://www.theaudiodb.com/**'
    ]);
    $locationProvider.html5Mode(true);
    $routeProvider
        .when('/home', {
            templateUrl: 'components/home/home-template.html',
            controller: 'homeController',
            controllerAs: 'hc',
            activeTab: 'home'
        })
        .when('/albums', {
            templateUrl: 'components/albums/albums-template.html',
            controller: 'albumsController',
            controllerAs: 'ac',
            activeTab: 'albums'
        })
        .when('/view-album/:id', {
            templateUrl: 'components/albums/view-album-template.html',
            controller: 'viewAlbumController',
            controllerAs: 'vac',
            activeTab: 'albums'
        })
        .when('/artists', {
            templateUrl: 'components/artists/artists-template.html',
            controller: 'artistsController',
            controllerAs: 'arc',
            activeTab: 'artists'
        })
        .when('/view-artist/:id', {
            templateUrl: 'components/artists/view-artist-template.html',
            controller: 'viewArtistController',
            controllerAs: 'varc',
            activeTab: 'artists'
        })
        .when('/genres', {
            templateUrl: 'components/genres/genres-template.html',
            controller: 'genresController',
            controllerAs: 'gc',
            activeTab: 'genres'
        })
        .when('/status', {
            templateUrl: 'components/status/status-template.html',
            controller: 'statusController',
            controllerAs: 'sc',
            activeTab: 'status'
        })
        .when('/account', {
            templateUrl: 'components/account/account-template.html',
            controller: 'accountController',
            controllerAs: 'acc',
            activeTab: 'account'
        })
        .when('/change-password', {
            templateUrl: 'components/account/change-password-template.html',
            controller: 'changePasswordController',
            controllerAs: 'cpc',
            activeTab: 'account'
        })
        .when('/loginpage', {
            templateUrl: 'components/login/login-template.html',
            controller: 'loginController',
            controllerAs: 'lc',
            activeTab: 'login'
        })
        .when('/users', {
            templateUrl: 'components/users/users-template.html',
            controller: 'usersController',
            controllerAs: 'uc',
            activeTab: 'users'
        })
        .when('/add-user', {
            templateUrl: 'components/users/add-user-template.html',
            controller: 'addUserController',
            controllerAs: 'auc',
            activeTab: 'users'
        })
        .when('/edit-user/:id', {
            templateUrl: 'components/users/edit-user-template.html',
            controller: 'editUserController',
            controllerAs: 'euc',
            activeTab: 'users'
        })
        .otherwise({
            redirectTo: '/home'
        });
});

app.directive('convertToNumber', convertToNumber);
app.controller('navMenuController', navMenuController);
app.directive('navMenu', navMenu);
app.directive('jumbotron', jumbotron);
app.directive('pageFooter', pageFooter);
app.controller('homeController', homeController);
app.controller('albumsController', albumsController);
app.controller('artistsController', artistsController);
app.controller('viewAlbumController', viewAlbumController);
app.controller('viewArtistController', viewArtistController);
app.controller('genresController', genresController);
app.controller('statusController', statusController);
app.controller('accountController', accountController);
app.controller('loginController', loginController);
app.factory('loginService', loginService);
app.factory('authenticationInterceptor', authenticationInterceptor);
app.controller('changePasswordController', changePasswordController);
app.controller('usersController', usersController);
app.controller('addUserController', addUserController);
app.controller('editUserController', editUserController);
app.directive('deleteUserModal', deleteUserModal);

app.config(function($httpProvider) {
    $httpProvider.interceptors.push('authenticationInterceptor');
});

app.run(function ($rootScope, $location, loginService) {
    $rootScope.$on('$routeChangeStart', function () {
        $rootScope.isLoggedIn = loginService.isAuthenticated();
        if ($rootScope.isLoggedIn === true) {
            $rootScope.userId = loginService.getUserId();
            loginService.getUser($rootScope.userId).then(function(user) {
                $rootScope.user=user;
                $rootScope.isAdmin = $rootScope.user.user_level === 'admin';
                if($rootScope.user.password_mustchange===1) {
                    $location.path('/change-password');
                }
            });
        }
    });
});