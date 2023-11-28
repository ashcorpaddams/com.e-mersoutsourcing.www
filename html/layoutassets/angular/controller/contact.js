/* global AshcorpApp */
var ContactController = (function () {
    function ContactController($scope, ContactWebService) {
        this.scope = $scope;
        this.ContactWebService = ContactWebService;
        this.Math = Math;
        this.window = window;
        this.CoreController = $scope.$parent.CoreController;
        this.scope.Controller = this;
    }

    ContactController.prototype.Init = function () {
        var self = this;
        self.contact = {
            name: "",
            email: "",
            phone: "",
            message: ""
        };
    };

    ContactController.prototype.Contact = function () {
        var self = this;
        if (self.contact.name === "" || (self.contact.email === "" && self.contact.phone === "") || self.contact.message === "") {
            return;
        }
        self.ContactWebService.Post(this.contact);
    };

    return ContactController;
})();

AshcorpApp.controller("ContactController", ["$scope", "ContactWebService", ContactController]);