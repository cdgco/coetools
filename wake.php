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
    <title>COE Tools - Wake on LAN</title>
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body onload="$('#preloader').fadeOut();">
    <div id="preloader">
        <img src="assets/img/loader.gif" id="loaderOSU"/>
    </div>
    <?php include "menu.php"; navbar("wake"); ?>
    <main role="main" class="container">
        <div class="jumbotron">
            <h2>Wake on LAN</h2><br>
            <p class="lead">Please provide the hostname of the machine (or group of machines) you want to wake up. Don't provide the domain name.<br><br>
                Examples:<br><br>
                dear115 (any host that starts with 'dear115')<br>
                kec1130-22 (the specific host)
            </p><br>
            <form class="form-inline" action="https://tools.engr.oregonstate.edu/coetools/inventory/wake_on_lan.php"
                method="POST">
                <div class="col-auto p-1">
                    <input type="text" class="form-control" name="hostname" placeholder="Machine Name">
                </div>
                <div class="col-auto p-1">
                    <button type="submit" class="btn btn-primary">Wake</button>
                </div>
            </form>
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