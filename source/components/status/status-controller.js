export default function ($http) {
    let sc = this;

    $http.get('/api/status').
    then(function(response) {
        sc.status = response.data;
    });
}