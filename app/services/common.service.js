/**
 * @purpose: This service is used provide commong functionalities accross the application
 * @return {mix}
 */
(function() {
    'use strict';

    angular
        .module(appInfo.module)
        .factory('CommonService', CommonService);

    CommonService.$inject = ['$log', '$state', '$sanitize'];

    /* @ngInject */
    function CommonService($log, $state, $sanitize) {
        var statusCode = {
            'HTTP_OK': 200,
            'HTTP_NO_CONTENT': 204,
            'HTTP_NOT_FOUND': 404,
            'HTTP_VALIDATION_ERROR': 422,
            'HTTP_CREATED': 201
        };
        var service = {
            isValidResponse: _isValidResponse,
            getResponse: _getResponse,
            statusCode: statusCode,
            isInvalidFormElement: _isInvalidFormElement,
            goToUrl: _goToUrl,
            getSanitizeObject: _getSanitizeObject,
            sanitize: _sanitize
        };
        return service;

        ////////////////

        /**
         * This function validades if the response is OK (200)
         */
        function _isValidResponse(response) {
            return (response.status === statusCode.HTTP_OK && (angular.isObject(response.data) || angular.isArray(response.data)));
        }

        /**
         * The purpose of this method is to format the result as an array for further client side
         * processing.
         */
        function _getResponse(response) {
            var result = [];
            if (angular.isArray(response.data)) {
                result = response.data;
            } else {
                result.push(response.data);
            }
            return result;
        }


        /**
         * This function is used for validation purpose. It evaluates if a given form element is dirty and invalid
         * @param formElement
         * @returns {rd.$dirty|*|dg.$dirty|$dirty|rd.$invalid|b.ctrl.$invalid} boolean
         */
        function _isInvalidFormElement(formElement) {
            return formElement.$dirty && formElement.$invalid;
        }

        /**
         * Go back to a particular state
         * @param  {string} url area to navigate
         * @return {mix}
         */
        function _goToUrl(url) {
            $state.go(url);
        }

        /**
         * Sanitize the information of an object
         * // console.log("obj." + prop + " = " + data[prop]);
         * @param  {[type]} data [description]
         * @return {[type]}      [description]
         */
        function _getSanitizeObject(data) {
            var obj = {};
            for (var prop in data) {
                if (data.hasOwnProperty(prop)) {
                    obj[prop] = $sanitize(data[prop]);
                }
            }
            return obj;
        }

        /**
         * Sanitize  a given value
         * @param  {mix} input
         * @return {sanitized}
         */
        function _sanitize(input) {
            return $sanitize(input);
        }

    }
})();
