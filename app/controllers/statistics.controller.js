/**
 * This Controller is used for displaying general statistics to the user
 * @return {mix}
 */
(function() {
    'use strict';

    angular
        .module(appInfo.module)
        .controller('StatisticsController', StatisticsController);

    StatisticsController.$inject = ['StatisticService', 'toastr', '$timeout', 'CommonService'];

    function StatisticsController(StatisticService, toastr, $timeout, CommonService) {
        var vm = this;

        vm.isAppInitialized = false;
        vm.statistics = {};

        activate();

        /**
         * Initialize the Application on loading
         * @return {mix}
         */
        function activate() {
            StatisticService.get().then(function(successResponse) {
                if (CommonService.isValidResponse(successResponse)) {
                    vm.statistics = successResponse.data;
                    //Some delay
                    $timeout(function() {
                        vm.isAppInitialized = true;
                        toastr.info('Information loaded Successfully!');
                    }, 2000);
                    // console.log(vm.statistics);
                }

            }, function(errorResponse) {
                console.log(errorResponse);
                toastr.error('An error has occurred', errorResponse.status);
            });
        }
    }
})();
