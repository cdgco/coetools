<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">
    <link rel="icon" type="image/x-icon" href="assets/favicon/favicon.ico" />
    <link rel="manifest" href="assets/favicon/site.webmanifest">
    <link rel="mask-icon" href="assets/favicon/safari-pinned-tab.svg" color="#ff6700">
    <meta name="msapplication-TileColor" content="#dddddd">
    <meta name="theme-color" content="#ffffff">
    <title>COE Tools - Inventory</title>
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
    <div id="preloader">
        <img src="assets/img/loader.gif" id="loaderOSU"/>
    </div>
    <?php include "menu.php"; navbar("inventory"); ?>

    <main role="main" class="container">
        <div class="jumbotron cdg">
            <center>
                <iframe src="https://tools.engr.oregonstate.edu/coetools/inventory/" onload="$('#preloader').fadeOut();" id="labiframe"></iframe>
            </center>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <span class="text-muted">By <a href="mailto:roeserc@oregonstate.edu">Carter Roeser</a>. <?php echo "Updated: ".date( "M d, Y h:i A.", getlastmod() ); ?></span>
        </div>
    </footer>
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script src="node_modules/popper.js/dist/umd/popper.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
</body>

</html>