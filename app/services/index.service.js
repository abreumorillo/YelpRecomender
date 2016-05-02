/**
 * @purpose         : This service is used to query the index built using vector space model.
 * @course          : Knowledge Processing Technologies.
 * @return {mix}
 */
(function() {
    'use strict';

    angular
        .module(appInfo.module)
        .factory('IndexService', IndexService);

    IndexService.$inject = ['$http', '$q', 'appConfig'];

    /* @ngInject */
    function IndexService($http, $q, appConfig) {
        var baseUrl = appConfig.baseUrl;
        var serviceUrl = baseUrl + 'api/restaurants/{searchString}';

        var URL = {
            RESTAURANT_SEARCH: baseUrl + 'api/restaurants/{searchString}',
            SPELLCHECKER: baseUrl + 'api/spellchecker/{term}'
        };
        var service = {
            getRestaurants: _getRestaurants,
            spellcheck: _spellCheck
        };
        return service;

        ////////////////

        /**
         * Search for restaurant
         * @param  {string} searchString query
         * @return {promise}
         */
        function _getRestaurants(searchString) {
            var deferred = $q.defer();
            var url = URL.RESTAURANT_SEARCH.replace('{searchString}', searchString).replace('html', 'php');
            $http.get(url)
                .success(function(data, status) {
                    deferred.resolve({
                        data: data,
                        status: status
                    });
                })
                .error(function(error, status) {
                    deferred.reject({
                        error: error,
                        status: status
                    });
                });

            return deferred.promise;
        }

        /**
         * Check for spelling correction
         * @param  {string} word
         * @return {promise}
         */
        function _spellCheck(word) {
            var deferred = $q.defer();
            var url = URL.SPELLCHECKER.replace('{term}', word).replace('html', 'php');
            $http.get(url)
                .success(function(data, status) {
                    deferred.resolve({
                        data: data,
                        status: status
                    });
                })
                .error(function(error, status) {
                    deferred.reject({
                        error: error,
                        status: status
                    });
                });
            return deferred.promise;
        }
    }
})();
