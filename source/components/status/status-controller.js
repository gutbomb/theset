export default function ($http, appConfig) {
    let sc = this;

    $http.get(appConfig.apiUrl+'/status').
    then(function(response) {
        sc.status = response.data;
    });
}