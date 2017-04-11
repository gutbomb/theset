export default function ($q, $http, $window) {
    'ngInject';

    function loginUser(username, password) {
        var defer = $q.defer();
        var self = this;
        $http({
            method: 'POST',
            url: '/api/auth.php',
            data: {username: username, password: password},
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(resp) {
            if (resp.data && resp.data.token) {
                self.activeUser = self.parseToken(resp.data.token).id;
            }
            defer.resolve(true);
        }, function(response) {
            if (response.status === 405) { // also for bad username / password
                defer.resolve(false);
            } else if (response.status === 304) {
                defer.resolve(true); // login worked, whatever.
            } else {
                defer.resolve(false);
            }
        }).catch(function(error) {
            defer.reject(error);
        });

        return defer.promise;
    }

    function clearToken() {
        $window.localStorage.clear();
    }

    function getToken() {
        return $window.localStorage['theset_jwtToken'];
    }

    function getUserId() {
        return parseToken(getToken()).id;
    }

    function isAuthenticated() {
        var token = this.getToken();

        if(token) {
            return true;
        } else {
            return false;
        }
    }

    function parseToken(token) {
        var base64Url = token.split('.')[1];
        var base64 = base64Url.replace('-', '+').replace('_', '/');
        return JSON.parse($window.atob(base64));
    }

    function saveToken(token) {
        $window.localStorage['theset_jwtToken'] = token;
    }

    function getActiveUser() {
        return this.activeUser;
    }

    return {
        loginUser: loginUser,
        getActiveUser: getActiveUser,
        getToken: getToken,
        clearToken: clearToken,
        getUserId: getUserId,
        saveToken: saveToken,
        isAuthenticated: isAuthenticated,
        parseToken: parseToken
    };
}

