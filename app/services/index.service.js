(function() {
    'use strict';

    angular
        .module(appInfo.module)
        .factory('IndexService', IndexService);

    IndexService.$inject = ['$http', '$q', 'appConfig'];

    /* @ngInject */
    function IndexService($http, $q, appConfig) {
        var serviceUrl = appConfig.baseUrl = '/api/restaurants/{searchString}';
        var service = {
            getRestaurants: _getRestaurants
        };
        return service;

        ////////////////

        function _getRestaurants(searchString) {
            var deferred = $q.defer();
            var url = serviceUrl.replace('{searchString}',searchString);
            $http({
                    method: 'GET',
                    url: url
                })
                .success(function(data, status) {
                    console.log(data, status);
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
