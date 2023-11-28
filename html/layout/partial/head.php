<title><?php echo Config::SiteName(); ?> - <?php echo $title; ?></title>

<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta property="og:image" content="assets/images/<?php echo $image; ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
<link rel="shortcut icon" href="assets/images/logo.png" type="image/x-icon">
<meta name="description" content="">

<?php
View::PathCSS(
        "assets",
        "tether/tether.min.css",
        "bootstrap/css/bootstrap.min.css",
        "bootstrap/css/bootstrap-grid.min.css",
        "bootstrap/css/bootstrap-reboot.min.css",
        "dropdown/css/style.css",
        "animatecss/animate.css",
        "socicon/css/styles.css",
        "theme/css/style.css",
        "mobirise/css/mbr-additional.css"
);
