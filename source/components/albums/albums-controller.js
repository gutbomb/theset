export default function ($http, appConfig) {
    let ac = this;
    $http.get(appConfig.apiUrl+'/album').
    then(function(response) {
        ac.albums = response.data;
    });
}