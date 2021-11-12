<?php

/* Set site time for localized modified date in footer */
date_default_timezone_set("America/Los_Angeles");
/* Include DB creditials for all calls */
require_once('db.php');

$ldapconn = ldap_connect("ldaps://ldap2", 389) or die("Could not connect to LDAP server.");
$ldapbind = ldap_bind($ldapconn, 'uid='. $_SERVER['PHP_AUTH_USER']. ',ou=groups,dc=example,dc=com', $_SERVER['PHP_AUTH_PW']) or die ("Error trying to bind: ".ldap_error($ldapconn));

$result = ldap_search($ldapconn, 'dc=example,dc=com', '(&(cn=*)(memberUid='.$_SERVER['PHP_AUTH_USER'].'))') or exit("Unable to search LDAP server");
$groups = ldap_get_entries($ldapconn, $result);
$userGroups = [];
foreach ($groups as &$group) {
    if ($group['cn'][0])
        array_push($userGroups, $group['cn'][0]);
}
//if(in_array('support', $userGroups))
if(in_array('support', $userGroups) || strpos($_SERVER['PHP_AUTH_USER'], 'coe_') !== false || ($_SERVER['PHP_AUTH_USER'] == 'roeserc' && $_GET['admin'] != ''))
    $currStaff = 1;
else
    $currStaff = 0;

ldap_close($ldapconn);

/* Connect to DB, create associative array of tools */
$con=mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_name);
$coe_tools = array(); $result=mysqli_query($con,"SELECT * FROM `tool_dir`");
while($line = mysqli_fetch_assoc($result)){ 
	if(!($currStaff == 0 && strpos(addslashes($line['staffOnly']), 'check')))
		$coe_tools[] = $line; 
}
mysqli_free_result($result); 
mysqli_close($con);
$con=mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_name);
$ldapuser = mysqli_real_escape_string($con, $_SERVER['PHP_AUTH_USER']);
$result = mysqli_query($con, "SELECT * FROM `user_layouts` WHERE `user` = '".$ldapuser."'");
$userData = mysqli_fetch_assoc($result);
mysqli_free_result($result);
mysqli_close($con);

$con=mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_name);
$ldapuser = mysqli_real_escape_string($con, $_SERVER['PHP_AUTH_USER']);
$result = mysqli_query($con, "SELECT * FROM `user_recentbackup` WHERE `user` = '".$ldapuser."'");
$userRecents = mysqli_fetch_assoc($result);
mysqli_free_result($result);
mysqli_close($con);

$userLayout = $userData['layout'];
if($userData['nightmode'] != '0' && $userData['nightmode'] != '1') {
    $userData['nightmode'] = '0';
}
$hiddenElements = explode(',', $userData['hidden']);
$favorites = explode(',', $userData['favorites']);
$recents = explode(',', $userRecents['recents']);
/* Create array of all categories found in tool array */
$categories = array();
foreach ($coe_tools as $arr) {
	if(!in_array($arr['category'], $categories)) {
		array_push($categories, $arr['category']);
	}
}
sort($categories); // Sort categories alphabetically


/* Function to sort associative array by name value */
function compareByName($a, $b) {
    return strcmp($a["name"], $b["name"]);
}

/* Function to print out dynamic menu bar */
function navbar() {
    global $categories; global $coe_tools; global $hiddenElements; global $favorites; global $recents;
    echo '
    <nav class="navbar navbar-expand-xl navbar-dark bg-dark mb-4 add-context-menu3 navbar-fixed" id="navx">
        <a class="navbar-brand" id="headertext" href=".">OSU COE Tools</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav mr-auto" id="id01">';
		  /* Redundant sorting algorithm, replaced by JS sorting in pages using data-sort attributes in <li> tags below */
          $sortedtools = $coe_tools;
          usort($sortedtools, 'compareByName');
          foreach ($sortedtools as $tool) {
            $toolhidden = ''; $toolfavorite = '';
            foreach($hiddenElements as $element) {
                if($element == $tool['id']) {
                    $toolhidden = 'style="display:none;"';
                }
            }
            foreach($favorites as $element) {
                if($element == $tool['id']) {
                    $toolfavorite = ' favorite ';
                }
            }
            if($tool['display'] == '1'){
                if ($tool['tab'] == '2') {
                    /* Print out items that were directly placed in header */
                    echo '<li class="nav-item" data-sort="'.$tool['name'].'"><a '.$toolhidden.' data-coe-id="'.$tool['id'].'" onclick="updateRecents(\''.$tool['id'].'\')" class="nav-link add-context-menu'.$toolfavorite.'" href="frame.php?coeFrameID='.$tool['id'].'">'.$tool['name'].'</a></li>';
                }
                else {
                    /* Print out items that were directly placed in header */
                    echo '<li class="nav-item" data-sort="'.$tool['name'].'"><a '.$toolhidden.' data-coe-id="'.$tool['id'].'" onclick="updateRecents(\''.$tool['id'].'\')" class="nav-link add-context-menu'.$toolfavorite.'" href="'.$tool['link'].'"';
                    if($tool['tab'] == '1') {
                        echo ' target="_blank" ';
                    }
                    echo '>'.$tool['name'].'</a></li>';
                }
            }
          }
          if($categories[0] != '') { // If at least one category is defined
              $x1 = 0;
              do {
				/* Check if category has any children */
				$toolcounter = 0;
				foreach ($coe_tools as $tool) {
					if($tool['category'] == $categories[$x1] && $tool['display'] != '1' && $tool['display'] != '2'){
						$toolcounter++;
					}
                    $categoryhidden = '';
                    foreach ($hiddenElements as $element) {
                        if($element == $categories[$x1]) {
                            $categoryhidden = 'style="display:none;"';
                        }
                    }
				}
				if($toolcounter > 0) { // if at least one child tool create category dropdown
				/* data-sort attribute used in JS to sort all li elements alphabetically */
				echo '<li '.$categoryhidden.' class="nav-item dropdown add-context-menu2" data-sort="'.$categories[$x1].'"> 
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$categories[$x1].'</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">';
                        foreach ($coe_tools as $tool) { // Check if all tools are child of parent category, if so and not hidden or displayed directly in navbar, add to dropdown
                            $toolhidden = ''; $toolfavorite = '';
                            foreach ($hiddenElements as $element) {
                                if($element == $tool['id']) {
                                    $toolhidden = 'style="display:none;"';
                                }
                            }
                            foreach($favorites as $element) {
                                if($element == $tool['id']) {
                                    $toolfavorite = ' favorite ';
                                }
                            }
                            if($tool['category'] == $categories[$x1] && $tool['display'] != '1' && $tool['display'] != '2'){
                                if ($tool['tab'] == 2) {
                                    echo '<a '.$toolhidden.' href="frame.php?coeFrameID='.$tool['id'].'" data-coe-id="'.$tool['id'].'" onclick="updateRecents(\''.$tool['id'].'\')" class="dropdown-item add-context-menu'.$toolfavorite.'">'.$tool['name'].'</a>';
                                }
                                else {
                                    echo '<a '.$toolhidden.' href="'.$tool['link'].'" data-coe-id="'.$tool['id'].'" onclick="updateRecents(\''.$tool['id'].'\')" class="dropdown-item add-context-menu'.$toolfavorite.'"';
                                    if($tool['tab'] == '1') {
                                        echo ' target="_blank" ';
                                    }
                                    echo '>'.$tool['name'].'</a>';
                                }
                            }
                        }
                        echo '</div></li>';
				}
              $x1++;
          } while ($categories[$x1] != ''); }
          echo '<li class="nav-item" data-sort="~">
                <a class="nav-link" href="directory.php">Tool Directory</a>
            </li></ul>
        </div>
      </nav>';
}

function removeRecent($recent) {
    global $mysql_host; global $mysql_user; global $mysql_password; global $mysql_name; global $_SERVER;

    $con=mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_name);
    $user = mysqli_real_escape_string($con, $_SERVER['PHP_AUTH_USER']);
    $recents = mysqli_real_escape_string($con, $recent);
    $sql1 = "UPDATE `user_recents` SET recents = '".$recents."' WHERE user = '".$user."';";
    $result = mysqli_query($con, $sql1);
    mysqli_close($con);

}

function removeFavorite($favorite) {
    global $mysql_host; global $mysql_user; global $mysql_password; global $mysql_name; global $_SERVER;

    $con=mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_name);
    $user = mysqli_real_escape_string($con, $_SERVER['PHP_AUTH_USER']);
    $favorites = mysqli_real_escape_string($con, $favorite);
    $sql1 = "UPDATE `user_layouts` SET favorites = '".$favorites."' WHERE user = '".$user."';";
    $result = mysqli_query($con, $sql1);
    mysqli_close($con);

}