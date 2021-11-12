<!doctype html>
<html lang="en">
	<head>
		<?php require_once("frontend/includes/head.html"); require_once("frontend/includes/menu.php"); ?>
		<title>COE Tools</title>
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
					<h1 class="pb-2">COE Tools</h1>
					<p class="lead">Hello, <b><?php echo $_SERVER['PHP_AUTH_USER']; ?></b>. Welcome to the new COE Tools Homepage!</p>
					<h4 class="pb-2">Recent Tools</h4>
					<span id="recentstable">
						<?php 
							if($userRecents['recents'][0] == '') { 
								echo '<p class="lead">It looks like you don\'t have any recent tools. Once you open a tool it will be added to your recents.</p>'; 
							}
							else {
								echo '<table class="table table-sm table-hover';
								if($userData['nightmode'] == '1') { echo ' table-dark'; }
								echo '">
								<col style="width:80%" />
								<col style="width:20%" />
								<thead class="';
								if($userData['nightmode'] == '0') { echo 'thead-light'; }
								echo '">
								<tr>
									<th scope="col" class="th-lg">Tool</th>
									<th class="th-sm">Action</th>
								</tr>
								</thead>
								<tbody>';
								foreach($recents as $recent) { 
									if ($recent != '') {
										$curTool = $coe_tools[array_search($recent, array_column($coe_tools, 'id'))];
										if ($recent != $curTool['id']) {
											if (($key = array_search($recent, $recents)) !== false) {
												unset($recents[$key]);
											}
											removeRecent(implode(',', $recents));
										}
										else {
											echo '<tr><td class="align-middle">'.$curTool['name'].'</td><td>';
											if ($curTool['tab'] == '2') {
												/* Print out items that were directly placed in header */
												echo '<a href="frame.php?coeFrameID='.$curTool['id'].'" onclick="updateRecents(\''.$curTool['id'].'\')"><button type="button" class="btn btn-outline-primary"><i class="fa fa-external-link" aria-hidden="true"></i></button></a>';
											}
											else {
												/* Print out items that were directly placed in header */
												echo '<a href="'.$curTool['link'].'" onclick="updateRecents(\''.$curTool['id'].'\')"';
												if($curTool['tab'] == '1') {
													echo ' target="_blank" ';
												}
												echo '><button type="button" class="btn btn-outline-primary"><i class="fa fa-external-link" aria-hidden="true"></i></button></a>';
											}
											echo '</td></tr>';
										}
									}
								}
								echo '</tbody></table>';
							} 
						?>
					</span>
					<h4 class="pb-2">Favorite Tools</h4>
					<span id="favoritestable">
						<?php 
							if($userData['favorites'][0] == '') { 
								echo '<p class="lead">It looks like you don\'t have any favorites. Enter "Editing Mode," right click a tool, and select "Favorite" to add it to your favorites list.</p>'; 
							}
							else {
								echo '<table class="table table-sm table-hover';
								if($userData['nightmode'] == '1') { echo ' table-dark'; }
								echo '">
								<col style="width:80%" />
								<col style="width:20%" />
								<thead class="';
								if($userData['nightmode'] == '0') { echo 'thead-light'; }
								echo '">
								<tr>
									<th scope="col" class="th-lg">Tool</th>
									<th class="th-sm">Action</th>
								</tr>
								</thead>
								<tbody>';
								foreach($favorites as $favorite) { 
									if ($favorite != '') {
										$curTool = $coe_tools[array_search($favorite, array_column($coe_tools, 'id'))];
										if ($favorite != $curTool['id']) {
											if (($key = array_search($favorite, $favorites)) !== false) {
												unset($favorites[$key]);
											}
											removeRecent(implode(',', $favorites));
										}
										else {
											echo '<tr><td class="align-middle">'.$curTool['name'].'</td><td>';
											if ($curTool['tab'] == '2') {
												/* Print out items that were directly placed in header */
												echo '<a href="frame.php?coeFrameID='.$curTool['id'].'" onclick="updateRecents(\''.$curTool['id'].'\')"><button type="button" class="btn btn-outline-primary"><i class="fa fa-external-link" aria-hidden="true"></i></button></a>';
											}
											else {
												/* Print out items that were directly placed in header */
												echo '<a href="'.$curTool['link'].'" onclick="updateRecents(\''.$curTool['id'].'\')"';
												if($curTool['tab'] == '1') {
													echo ' target="_blank" ';
												}
												echo '><button type="button" class="btn btn-outline-primary"><i class="fa fa-external-link" aria-hidden="true"></i></button></a>';
											}
											echo '</td></tr>';
										}
									}
								}
								echo '</tbody></table>';
							} 
						?>
						</span>
				</div>
			</main>
		</div>
		<?php require_once("frontend/includes/dynamicPage.php"); ?>
	</body>
</html>