export default function ($http) {
    var arc = this;
    $http.get('/api/artist.php').
    then(function(response) {
        arc.artists = response.data;
    });
}