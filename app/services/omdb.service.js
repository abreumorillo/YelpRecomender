/**
 * 
 * Access the Odmb API
 */
(function () {
    'use strict';

    angular
        .module('app')
        .factory('OmdbService', OmdbService);

    OmdbService.$inject = ['$http', '$q'];

    function OmdbService($http, $q) {

        var service = {
            getData: _getData
        };

        return service;

        /**
         * Query the Omdb API for information about the movies/series
         * @param {string} searchTerm
         * @param {int} year
         * @returns {$q@call;defer.promise}
         */
        function _getData(searchTerm, year) {
            
            var path = "";
            var deferred = $q.defer();
            if (year > 0) {
                path = "http://www.omdbapi.com/?t={searchTerm}&y={year}&plot=full&r=json".replace('{searchTerm}', searchTerm).replace('{year}', year);
            } else {
                path = "http://www.omdbapi.com/?t={searchTerm}&y=&plot=full&r=json".replace('{searchTerm}', searchTerm);
            }
            $http.get(path)
                .success(function (data, status) {
                    deferred.resolve({ data: data, status: status });
                })
                .error(function (error, status) {
                    deferred.reject({ error: error, status: status });
                });
            return deferred.promise;
        }
    }
})();


