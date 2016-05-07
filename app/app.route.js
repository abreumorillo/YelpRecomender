/**
 * This script handles the routing part of the client application
 * it dependes on UI router to accomplish it tasks
 */
(function() {
    'use strict';
    angular
        .module(appInfo.module)
        .config(['$stateProvider', '$urlRouterProvider', '$locationProvider',
            function($stateProvider, $urlRouterProvider, $locationProvider) {
                // For any unmatched url, redirect to /index
                $urlRouterProvider.otherwise("/statistics");
                //$locationProvider.html5Mode(true).hashPrefix('!');
                // Now set up the states
                $stateProvider
                    .state('index', {
                        url: '/index',
                        controller: 'IndexController',
                        controllerAs: 'vm',
                        templateUrl: 'app/views/index.html'
                    })
                    .state('statistics', {
                        url: '/statistics',
                        controller: 'StatisticsController',
                        controllerAs: 'vm',
                        templateUrl: 'app/views/statistics.html'
                    });
            }
        ]);
})();
