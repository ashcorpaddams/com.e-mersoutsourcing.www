/* global angular */
var AshcorpApp = angular.module("AshcorpApp", ["ngAnimate", "ngSweetAlert"]);

var ConnectionService = (function () {
    function ConnectionService($http, $window, $q, SweetAlert) {
        this.$q = $q;
        this.http = $http;
        this.window = $window;
        this.q = $q;
        this.SweetAlert = SweetAlert;
    }

    ConnectionService.prototype.postRequest = function (url, data) {
        return this.loadRequest("POST", url, data);
    };

    ConnectionService.prototype.loadRequest = function (method, url, data) {
        var self = this;
        var deferred = this.q.defer();
        this.http({
            method: method,
            url: "/" + url,
            dataType: "json",
            contentType: "application/json",
            headers: {
                "content-type": "application/json",
                "cache-control": "no-cache"
            },
            responseType: "application/json",
            timeout: 600000,
            data: data
        }).then(function (response) {
            var result = response.data;
            if (result.title !== "" && result.title !== null && result.message !== "" && result.message !== null)
            {
                self.SweetAlert.swal(result.title, result.message, result.status);
            }
            if (result.redirect !== null && result.redirect !== "") {
                setTimeout(function () {
                    window.location.href = result.redirect;
                }, 3000);
            }
            if (result.payload === undefined) {
                self.SweetAlert.swal(
                        "Error",
                        "Server respond contains error.",
                        "error"
                        );
                deferred.reject(result);
            }
            deferred.resolve(result.payload);
        }).catch(function (err) {
            if (err.status === 400) {
                self.SweetAlert.swal(
                        "Error",
                        "Please connect to proceed.",
                        "error"
                        );
            } else {
                self.SweetAlert.swal(
                        "Error",
                        "An error occured during the operation.",
                        "error"
                        );
            }
            deferred.reject(err);
        }).finally(function () {});
        return deferred.promise;
    };
    return ConnectionService;
})();

var CoreWebService = (function () {
    function CoreWebService(ConnectionService) {
        this.ConnectionService = ConnectionService;
    }

    CoreWebService.prototype.page = function (u) {
        var url = "api/page";
        return this.ConnectionService.postRequest(url, {"url": u});
    };

    CoreWebService.prototype.savepage = function (content, u, title, description) {
        var url = "api/save-page";
        return this.ConnectionService.postRequest(url, {"content": content, "url": u, "title": title, "description": description});
    };

    CoreWebService.prototype.deletepage = function (u) {
        var url = "api/delete-page";
        return this.ConnectionService.postRequest(url, {"url": u});
    };

    CoreWebService.prototype.Logout = function () {
        var url = "api/logout";
        return this.ConnectionService.postRequest(url, null);
    };
    return CoreWebService;
})();

AshcorpApp.service("ConnectionService", ["$http", "$window", "$q", "SweetAlert", ConnectionService]);
AshcorpApp.service("CoreWebService", ["ConnectionService", CoreWebService]);