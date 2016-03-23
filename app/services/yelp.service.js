(function() {
    'use strict';
    angular.module(appInfo.module)
        .factory('YelpService', yelpService);

    yelpService.$inject = ['$http', '$q'];

    function yelpService($http, $q) {

        var service = {
            getRestaurants: _getRestaurants
        };
        return service;

        function _randomString(length, chars) {
            var result = '';
            for (var i = length; i > 0; --i) result += chars[Math.round(Math.random() * (chars.length - 1))];
            return result;
        }
//http://stackoverflow.com/questions/23716264/yelp-api-and-angularjs
        function _getRestaurants(restaurant, location) {
            var method = 'GET';
            var url = 'http://api.yelp.com/v2/search';
            var params = {
                callback: 'angular.callbacks._0',
                location: location,
                limit:1,
                oauth_consumer_key: 'RMYRv43d5ynnmxOqZ5x61g', //Consumer Key
                oauth_token: 'dMWcn5yCF5A1Xz56YZeAW-kpKYyw9nAy', //Token
                oauth_signature_method: "HMAC-SHA1",
                oauth_timestamp: new Date().getTime(),
                oauth_nonce: _randomString(32, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'),
                term: restaurant
            };
            var consumerSecret = 'LHoi79QzUVfEalk2ZyQ2hjA71CI'; //Consumer Secret
            var tokenSecret = 'dee4VqJl64kBu4m8RX4IFPrxkYI'; //Token Secret
            var signature = oauthSignature.generate(method, url, params, consumerSecret, tokenSecret, { encodeSignature: false });
            params['oauth_signature'] = signature;
            
            $http.jsonp(url, { params: params }).success(function (data, status) {
                console.log(data);
            });
        }

    }

})();