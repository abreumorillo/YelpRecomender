(function() {
    angular
        .module(appInfo.module)
        .factory('StatisticService', StatisticService);

    StatisticService.$inject = ['$http', '$q', 'appConfig'];

    function StatisticService($http, $q, appConfig) {

        var serviceUrl = appConfig.baseUrl + '/api/getStatistics';
        var service = {
            get: _get
        };
        return service;

        /**
         * Get the project general statistics
         * @return {promise}
         */
        function _get() {
            var url = serviceUrl.replace('html', 'php');
            var deferred = $q.defer();
            $http.get(url)
            .success(function (data, status) {
                deferred.resolve({data: data, status:status});
            })
            .error(function (error, status) {
                deferred.reject({data: error, status: status});
            });
            return deferred.promise;
        }
    }
})();
