/* global AshcorpApp, tinymce */

var CoreController = (function () {
    function CoreController($scope, SweetAlert, CoreWebService) {
        this.scope = $scope;
        this.CoreWebService = CoreWebService;
        this.Math = Math;
        this.window = window;
        this.SweetAlert = SweetAlert;
        this.scope.CoreController = this;
        this.scope.Controller = this;
    }

    CoreController.prototype.Alert = function (Type, Title, Text) {
        if (Text === void 0) {
            Text = "";
        }
        this.SweetAlert.swal(Title, Text, Type);
    };
    CoreController.prototype.GenerateGUID = function () {
        return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g, function (
                c
                ) {
            var r = (Math.random() * 16) | 0,
                    v = c === "x" ? r : (r & 0x3) | 0x8;
            return v.toString(16);
        });
    };
    CoreController.prototype.IsNullUndefinedOrBlank = function (value, null_value) {
        return value === "" || value === null || value === undefined ? null_value : value;
    };
    CoreController.prototype.StringToJson = function (string) {
        return JSON.parse(string);
    };
    CoreController.prototype.JsonToString = function (string) {
        return JSON.stringify(string);
    };
    CoreController.prototype.JsonCopy = function (object) {
        var target = {};
        Object.assign(target, object);
        return target;
    };
    CoreController.prototype.GoToUrl = function (url) {
        if (url !== null && url !== "" && url !== "#") {
            window.location.href = url;
        }
    };
    CoreController.prototype.Logout = function () {
        this.CoreWebService.Logout();
    };

    CoreController.prototype.Init = function () {
    };
    CoreController.prototype.InitEditor = function (selector) {
        window.tinymce.init({
            selector: "." + selector,
            max_height: 425,
            max_width: 200,
            min_height: 425,
            min_width: 200,
            menubar: false,
            statusbar: true,
            browser_spellcheck: true,
            schema: "html5",
            content_css: [
                "/assets/mbr-switch-arrow/mbr-switch-arrow.css"
            ],
            plugins: "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking table contextmenu directionality emoticons template textcolor paste fullpage textcolor colorpicker textpattern imagetools",
            toolbar1: "fontselect fontsizeselect | bold italic underline | backcolor forecolor | alignleft aligncenter alignright alignjustify | outdent indent | subscript superscript | bullist numlist | removeformat",
            toolbar2: "code | cut copy paste | searchreplace | link unlink image media table hr | insertdatetime charmap emoticons | undo redo | preview fullscreen",
            fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt 48pt 72pt",
            images_upload_handler: function (blobInfo, success, failure) {
                success("data:image/jpeg;base64," + blobInfo.base64());
            },
            file_picker_callback: function (cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/* audio/* video/*');
                input.onchange = function () {
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = function () {
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);
                        cb(blobInfo.blobUri(), {
                            title: file.name
                        });
                    };
                };
                input.click();
            }
        });
    };
    CoreController.prototype.SetTextEditorContent = function (content) {
        if (content !== null) {
            setTimeout(function () {
                window.tinymce.activeEditor.setContent(content);
            }, 2000);
        }
    };
    CoreController.prototype.GetTextEditorContent = function () {
        return window.tinymce.activeEditor.getContent();
    };


    CoreController.prototype.Save = function () {
        var self = this;
        self.CoreWebService.savepage(self.scope.CoreController.GetTextEditorContent(), self.page.url, self.page.title, self.page.description).then(
                function (response) {},
                function (error) {}
        );
    };

    CoreController.prototype.Delete = function (url) {
        var self = this;
        self.CoreWebService.deletepage(url).then(
                function (response) {},
                function (error) {}
        );
    };

    CoreController.prototype.New = function () {
        var self = this;
        self.GoToUrl("/edit/" + self.page.url);
    };





    CoreController.prototype.GoToSearch = function () {
        if (
                this.search.text === "" &&
                this.search.region === "*" &&
                this.search.category === "*"
                ) {
            this.Alert(
                    "error",
                    "Recherche",
                    "Veillez remplir les critères de recherche."
                    );
            return;
        }
        var url =
                "/" +
                this.search.category +
                "/" +
                this.search.region +
                (this.search.text === ""
                        ? ""
                        : "/" + this.search.text.split(" ").join("-"));

        this.GoToUrl(url);
    };



    CoreController.prototype.Likes = function (id) {
        var self = this;
        self.annonce_like[id] = 1;
        self.CoreWebService.Likes({id: id});
    };

    return CoreController;
})();

AshcorpApp.controller("CoreController", ["$scope", "SweetAlert", "CoreWebService", CoreController]);
