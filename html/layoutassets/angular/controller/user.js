/* global AshcorpApp */
var UserController = (function () {
    function UserController($scope, UserWebService) {
        this.scope = $scope;
        this.UserWebService = UserWebService;
        this.Math = Math;
        this.window = window;
        this.CoreController = $scope.$parent.CoreController;
        this.scope.Controller = this;
    }

    UserController.prototype.InitLogin = function () {
        var self = this;
        self.user = {
            email: "",
            password: ""
        };
    };

    UserController.prototype.Login = function () {
        var self = this;
        if (self.user.name === "" || self.user.password === "") {
            return;
        }
        self.UserWebService.Login(this.user).then(
                function (response) {
                    self.user = response;
                },
                function (error) {}
        );
    };

    return UserController;
})();

AshcorpApp.controller("UserController", ["$scope", "UserWebService", UserController]);