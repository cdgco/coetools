<!doctype html>
<html lang="en">
	<head>
		<?php require_once("frontend/includes/head.html"); require_once("frontend/includes/menu.php"); ?>
		<title>COE Tools - AMT</title>
	</head>
	<body onload="$('#preloader').fadeOut();" <?php if($userData['nightmode'] == '1') { echo 'class="bg-dark text-white"'; } ?> >
		<div style="min-height:calc(100vh - 60px);">
			<div id="preloader"> 
				<img src="frontend/assets/img/loader.gif" id="loaderOSU" />
			</div>
			<?php navbar("directory"); ?>
			<main role="main" class="container">
                <script>
                    function invalidRemoveFavorite(id) {
                            removeFavorite(id);
                        }
                </script>
                <div class="jumbotron<?php if($userData['nightmode'] == '1') { echo ' bg-dark'; } ?>" >
                    <h2>Intel® Active Management Technology</h2><br>
                    <p class="lead">Please provide the hostname of the machine you want to access.</p><br>
                    <form class="form-inline">
                        <div class="col-auto p-2">
                            <input type="text" class="form-control" id="host" placeholder="Machine Name">
                        </div>
                        <div class="col-auto p-2">
                            <button type="submit" onclick="event.preventDefault();querySearch();" class="btn btn-primary">Submit</button>
                        </div>
                    </form>	
				</div>
			</main>
		</div>
		<?php require_once("frontend/includes/dynamicPage.php"); ?>
	</body>
    <script>
    function querySearch() {
        var host = document.getElementById('host').value;
        var start = atob("aHR0cDovL2FkbWluOmEkcDFEZTkhQA==");
        var win = window.open(start + host + ":16992/index.htm", '_blank');
        win.focus();
    }
    </script>
</html>