<?php if ($_GET['coeFrameID'] == '' || !isset($_GET['coeFrameID'])) { header("Location: index.php"); } 

require_once("frontend/includes/menu.php");

foreach ($coe_tools as $arr) {
	if($arr['id'] == $_GET['coeFrameID']) {
        $currLink = $arr['link'];
        $currName = $arr['name'];
	}
}
if(!isset($currLink)) { header("Location: index.php"); }
?>

<!doctype html>
<html lang="en">
    <head>
        <?php require_once("frontend/includes/head.html"); ?>
        <title>COE Tools - <?php print_r($currName); ?></title>
    </head>
    <body style="background:#FFFFFF">
        <div style="min-height:calc(100vh - 60px);">
            <div id="preloader">
                <img src="frontend/assets/img/loader.gif" id="loaderOSU"/>
            </div>
            <?php navbar("directory"); ?>
            <div id="iframeDiv">
                <iframe src="<?php print_r($currLink); ?>" onload="$('#preloader').fadeOut();" id="fulliframe"></iframe>
            </div>
        </div>
        <?php require_once("frontend/includes/dynamicPage.php"); ?>
        <script>
            var navx = document.getElementById("navx").offsetHeight;
            var vh = Math.max(document.documentElement.clientHeight || 0, window.innerHeight || 0)
            document.getElementById("iframeDiv").setAttribute("style", "height:" + (vh - navx - 60) +
                "px;position:absolute;width:100%;top:" + navx + "px;");
            window.onresize = function(event) {
                var navx = document.getElementById("navx").offsetHeight;
                var vh = Math.max(document.documentElement.clientHeight || 0, window.innerHeight || 0)
                document.getElementById("iframeDiv").setAttribute("style", "height:" + (vh - navx - 60) +
                    "px;position:absolute;width:100%;top:" + navx + "px;");
            }
            $(function() {
                var search = window.location.search;
                $("#fulliframe").attr("src", $("#fulliframe").attr("src")+search);
        });
        </script>
    </body>
</html>