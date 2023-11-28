/* global AshcorpApp, ConnectionService */
var UserWebService = (function () {
    function UserWebService(ConnectionService) {
        this.ConnectionService = ConnectionService;
    }

    UserWebService.prototype.Login = function (user) {
        var url = "api/login";
        return this.ConnectionService.postRequest(url, user);
    };

    return UserWebService;
})();

AshcorpApp.service("UserWebService", ["ConnectionService", UserWebService]);