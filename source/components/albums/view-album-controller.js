export default function ($http, $routeParams, $rootScope, $location) {
    let vac = this;

    vac.editMode = 0;

    function getRatings () {
        $http.get('/api/rating?album_id='+$routeParams.id+'&rating_source='+$rootScope.userId).
        then(function(response) {
            vac.ratings = response.data;
        });
    }

    function getAlbum () {
        $http.get('/api/year/active').
        then(function(response) {
            vac.currentYear = response.data[0];
            $http.get('/api/album/'+$routeParams.id).
            then(function(response) {
                vac.album = response.data[0];
                if (vac.album.year === vac.currentYear.year) {
                    if ($rootScope.isLoggedIn===true) {
                        vac.editMode = 1;
                        getRatings();
                        vac.reviewCount=0;
                        if (typeof(vac.album.reviews)!=='undefined') {
                            for (let i = 0; i < vac.album.reviews.length; i++) {
                                if (vac.album.reviews[i].review_source_id===$rootScope.userId) {
                                    vac.reviewCount++;
                                }
                            }
                        }
                    } else {
                        vac.hideRatings=true;
                    }
                }
            });
        });
    }

    function getTracks () {
        $http.get('/api/track?album_id='+$routeParams.id).
        then(function(response) {
            vac.tracks = response.data;
        });
    }

    function saveRating(trackId) {
        vac.newRatings[trackId].failed=false;
        vac.newRatings[trackId].saving=true;
        $http({
            method: 'POST',
            url: '/api/rating',
            data: {track_id: trackId, track_rating: vac.newRatings[trackId].rating_score, track_album: $routeParams.id, action: 'save'},
            headers : {'Content-Type': 'application/json'}
        })
        .then(function() {
            vac.newRatings[trackId].saving=false;
            vac.newRatings[trackId].saved=true;
            getTracks();
            getRatings();
        },
        function() {
            vac.newRatings[trackId].saving=false;
            vac.newRatings[trackId].failed=true;
        });
    }

    function saveReview() {
        $http({
            method: 'POST',
            url: '/api/review',
            data: {review_album: $routeParams.id, review_text: vac.newReview, action: 'save'},
            headers : {'Content-Type': 'application/json'}
        })
        .then(function() {
            getAlbum();
        },
        function() {
            $location.path('/login');
        });
    }

    function updateRating(ratingId, trackId) {
        vac.ratings[trackId].failed=false;
        vac.ratings[trackId].updating=true;
        $http({
            method: 'PUT',
            url: '/api/rating/'+ratingId,
            data: {track_rating: vac.ratings[trackId].rating_score},
            headers : {'Content-Type': 'application/json'}
        })
        .then(function() {
            vac.ratings[trackId].updating=false;
            vac.ratings[trackId].updated=true;
            getRatings();
        },
        function() {
            vac.ratings[trackId].updating=false;
            vac.ratings[trackId].false=true;
        });
    }

    function updateReview(reviewIndex) {
        $http({
            method: 'PUT',
            url: '/api/review/'+vac.album.reviews[reviewIndex].review_id,
            data: {review_text: vac.album.reviews[reviewIndex].review_text},
            headers : {'Content-Type': 'application/json'}
        })
        .then(function() {
            getAlbum();
        },
        function() {
            $location.path('/login');
        });
    }

    function getComments() {
        $http.get('/api/comment?album_id='+$routeParams.id).
        then(function(response) {
            vac.comments = response.data;
        });
    }

    getAlbum();
    getTracks();

    if (vac.editMode===0) {
        getComments();
    } else {
        getRatings();
    }

    function postComment() {
        $http({
            method: 'POST',
            url: '/api/comment',
            data: {album_id: $routeParams.id, comment_text: vac.newComment},
            headers : {'Content-Type': 'application/json'}
        })
        .then(function() {
            getComments();
            vac.newComment='';
        },
        function() {
            $location.path('/login');
        });
    }



    vac.getAlbum = getAlbum;
    vac.saveRating = saveRating;
    vac.getTracks = getTracks;
    vac.getRatings = getRatings;
    vac.updateRating = updateRating;
    vac.saveReview = saveReview;
    vac.updateReview = updateReview;
    vac.postComment = postComment;
    vac.getComments = getComments;
}