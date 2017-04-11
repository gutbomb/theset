var app = angular.module('theSetApp', ['ngRoute', 'ngSanitize']);

import navMenuController from '../components/common/nav-menu/nav-menu-controller';
import navMenu from '../components/common/nav-menu/nav-menu-directive';
import jumbotron from '../components/common/jumbotron/jumbotron-directive';
import pageFooter from '../components/common/page-footer/page-footer-directive';
import homeController from '../components/home/home-controller';
import albumsController from '../components/albums/albums-controller';
import artistsController from '../components/artists/artists-controller';
import viewAlbumController from '../components/view-album/view-album-controller';
import viewArtistController from '../components/view-artist/view-artist-controller';
import genresController from '../components/genres/genres-controller';
import statusController from '../components/status/status-controller';
import accountController from '../components/account/account-controller';
import loginController from '../components/login/login-controller';
import loginService from '../components/login/login-service';
import authenticationInterceptor from '../components/login/authentication-interceptor-service';

app.config(function ($routeProvider, $locationProvider) {
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
            templateUrl: 'components/view-album/view-album-template.html',
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
            templateUrl: 'components/view-artist/view-artist-template.html',
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
        .when('/loginpage', {
            templateUrl: 'components/login/login-template.html',
            controller: 'loginController',
            controllerAs: 'lc',
            activeTab: 'login'
        })
        .otherwise({
            redirectTo: '/home'
        });
});

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
app.config(function($httpProvider) {
    $httpProvider.interceptors.push('authenticationInterceptor');
});
app.run(function ($rootScope, $location, loginService) {
    $rootScope.$on('$locationChangeStart', function () {
        $rootScope.isLoggedIn = loginService.isAuthenticated();
        $rootScope.userId = loginService.getUserId();
    });
});