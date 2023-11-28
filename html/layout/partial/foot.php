<?php

View::PathJS(
        "assets"
        , "web/assets/jquery/jquery.min.js"
        , "popper/popper.min.js"
        , "tether/tether.min.js"
        , "bootstrap/js/bootstrap.min.js"
        , "smoothscroll/smooth-scroll.js"
        , "dropdown/js/nav-dropdown.js"
        , "dropdown/js/navbar-dropdown.js"
        , "touchswipe/jquery.touch-swipe.min.js"
        , "viewportchecker/jquery.viewportchecker.js"
        , "parallax/jarallax.min.js"
        , "theme/js/script.js"
);

View::JS(
        "angular-1.6.1.js",
        "angular-animate.js",
        "sweetalert/sweetalert.min.js",
        "sweetalert/ng-sweetalert.min.js"
);
foreach (View::$Modules ?? [] as $ng_m) {
    echo "<script src='/angular/service/{$ng_m}.js'></script>"
    . "<script src='/angular/controller/{$ng_m}.js'></script>";
}