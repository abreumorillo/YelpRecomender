(function() {
    'use strict';
    angular
        .module(appInfo.module)
        .config(['$stateProvider', '$urlRouterProvider', '$locationProvider',
            function($stateProvider, $urlRouterProvider, $locationProvider) {
                // For any unmatched url, redirect to /index
                $urlRouterProvider.otherwise("/index");
                //$locationProvider.html5Mode(true).hashPrefix('!');
                // Now set up the states
                $stateProvider
                    .state('index', {
                        url: '/index',
                        controller: 'IndexController',
                        controllerAs: 'vm',
                        templateUrl: 'app/views/index.html'
                    })
                    .state('details', {
                        url: '/details/:restaurant/:city/:state',
                        controller: 'DetailsController',
                        controllerAs: 'vm',
                        templateUrl: 'app/views/details.html'
                    });
            }
        ]);
})();
