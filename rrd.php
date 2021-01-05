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
    <title>COE Tools - Lab RRD</title>
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="node_modules/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
    <div id="preloader">
        <img src="assets/img/loader.gif" id="loaderOSU"/>
    </div>
    <?php include "menu.php"; navbar("labs"); ?>
    <main role="main" class="container">
        <div class="jumbotron rrd">
            <h2>Labmap Historical Graphing</h2>
            <form id="rrdform" action="https://order.engr.oregonstate.edu/cgi-bin/labmap" method="post" target="frame">
                <select class="selectpicker m-1" multiple data-selected-text-format="count" id="hostselect" name="hosts" required>
                    <option value="XD-CCE_pc.rrd">XD-CCE pc</option>
                    <option value="XD-CCE_xd.rrd">XD-CCE xd</option>
                    <option value="XD-GPU_pc.rrd">XD-GPU pc</option>
                    <option value="XD-GPU_ts.rrd">XD-GPU ts</option>
                    <option value="XD-GPU_xd.rrd">XD-GPU xd</option>
                    <option value="XD-MIME_pc.rrd">XD-MIME pc</option>
                    <option value="XD-MIME_xd.rrd">XD-MIME xd</option>
                    <option value="bat041_pc.rrd">bat041 pc</option>
                    <option value="bat045_pc.rrd">bat045 pc</option>
                    <option value="dear115_mac.rrd">dear115 mac</option>
                    <option value="dear115_pc.rrd">dear115 pc</option>
                    <option value="dear119_linux.rrd">dear119 linux</option>
                    <option value="dear120_pc.rrd">dear120 pc</option>
                    <option value="dear203_pc.rrd">dear203 pc</option>
                    <option value="dear208_pc.rrd">dear208 pc</option>
                    <option value="glsn02_pc.rrd">glsn02 pc</option>
                    <option value="graf202_pc.rrd">graf202 pc</option>
                    <option value="graf210_pc.rrd">graf210 pc</option>
                    <option value="kear302_pc.rrd">kear302 pc</option>
                    <option value="kec1130_linux.rrd">kec1130 linux</option>
                    <option value="kec1130_mac.rrd">kec1130 mac</option>
                    <option value="kec1130_pc.rrd">kec1130 pc</option>
                    <option value="mfd107b_pc.rrd">mfd107b pc</option>
                    <option value="owen237_pc.rrd">owen237 pc</option>
                    <option value="owen241_pc.rrd">owen241 pc</option>
                    <option value="rad_a124_linux.rrd">rad_a124 linux</option>
                    <option value="rad_a124_pc.rrd">rad_a124 pc</option>
                    <option value="rog336_pc.rrd">rog336 pc</option>
                    <option value="rog338_pc.rrd">rog338 pc</option>
                    <option value="rog340_pc.rrd">rog340 pc</option>
                    <option value="windows_ts.rrd">windows ts</option>
                    <option value="xendesktop_xd.rrd">xendesktop xd</option>
                </select>
                <select class="selectpicker" name="period" id="periodselect" required>
                    <option value="day">Day</option>
                    <option value="week" selected>Week</option>
                    <option value="2week">2 Weeks</option>
                    <option value="month">Month</option>
                    <option value="3month">3 Months</option>
                    <option value="year">Year</option>
                    <option value="2year">2 Years</option>
                </select>
                <button type="button" id="formreset" onclick="$('#hostselect').val('default');$('#hostselect').selectpicker('refresh');" class="btn btn-danger m-1">Clear Selection</button>
            </form>
            <iframe src="blank.html" onload="$('#preloader').fadeOut();" name="frame" id="rrdiframe"></iframe>
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
    <script src="node_modules/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
    <script>
    $("select").on("changed.bs.select",
        function(e, clickedIndex, newValue, oldValue) {
            if ($('#hostselect').val() != '') {
                document.getElementById('rrdform').submit();
            }
        });
    $("#formbuttondh").click(function() {
        var hosts = $('#hostselect').val();
        var period = $('#periodselect').val();
        hosts.forEach((host) => {
            $('table tbody').append(
                "<tr><td><img src='https://order.engr.oregonstate.edu/rrd/labmap_history/" + host +
                "_" + period + ".png' /></td></tr>");
        })
    });
    </script>
</body>

</html>