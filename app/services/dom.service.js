/**
 * purpose        : This service allows to focus an element programatically from controllers
 *                  Reference: http://plnkr.co/edit/vJQXtsZiX4EJ6Uvw9xtG?p=preview
 * @return {mix}
 */
(function() {
    // body...
    var app = angular.module(appInfo.module);
        app.factory('focus', ['$timeout', '$window',function($timeout, $window) {
            return function(id) {
                // timeout makes sure that is invoked after any other event has been triggered.
                // e.g. click events that need to run before the focus or
                // inputs elements that are in a disabled state but are enabled when those events
                // are triggered.
                $timeout(function() {
                    var element = $window.document.getElementById(id);
                    if (element)
                        element.focus();
                });
            };
        }]);
})();
