export default function ($injector) {
    'ngInject';
    return {
        // automatically attach Authorization header
        request: function(config) {
            if(config.url.indexOf('/api/auth.php') < 0 && config.url.indexOf('/api') >= 0) {
                var token = $injector.get('loginService').getToken();
                if (token) {
                    config.headers.Authorization = 'Bearer ' + token;
                }
            }

            return config;
        },

        // If a token was sent back, save it
        response: function(res) {
            if(res.config.url.indexOf('/api/auth.php') >= 0 && res.data.token) {
                var loginService = $injector.get('loginService');
                loginService.saveToken(res.data.token);
            }

            return res;
        },
    };
}