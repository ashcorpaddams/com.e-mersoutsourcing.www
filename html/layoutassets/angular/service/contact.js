/* global AshcorpApp, ConnectionService */
var ContactWebService = (function () {
    function ContactWebService(ConnectionService) {
        this.ConnectionService = ConnectionService;
    }

    ContactWebService.prototype.Post = function (data) {
        var url = "api/contact-us";
        return this.ConnectionService.postRequest(url, data);
    };

    return ContactWebService;
})();

AshcorpApp.service("ContactWebService", ["ConnectionService", ContactWebService]);