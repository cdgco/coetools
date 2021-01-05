<?php

date_default_timezone_set("America/Los_Angeles");

function navbar($nav) {

    echo '
    <nav class="navbar navbar-expand-xl navbar-dark bg-dark mb-4" id="navx">
        <a class="navbar-brand" href="index.php">OSU COE Tools</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link'; if($nav == "amt") { echo " active"; } echo '" href="amt.php">AMT</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="https://osu.workforcehosting.com/workforce/Home.do?action=start" target="_blank">EmpCenter</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle'; if($nav == "grad") { echo " active"; } echo '" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Grad
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="gradassignment.php">Desk Assignments</a>
                <a class="dropdown-item" href="gradmap.php">Desk Map</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle'; if($nav == "inventory") { echo " active"; } echo '" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Inventory
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="inventory.php">Inventory</a>
                <a class="dropdown-item" href="inventory2.php">Inventory 2</a>
                <a class="dropdown-item" href="https://support.engineering.oregonstate.edu/Assets" target="_blank">Jitbit Assets</a>
              </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="https://support.engineering.oregonstate.edu/" target="_blank">Jitbit</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle'; if($nav == "labs") { echo " active"; } echo '" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Labs
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="activity.php">Activity Log</a>
                <a class="dropdown-item" href="labmap.php">Maps</a>
                <a class="dropdown-item" href="laboutage.php">Outage</a>
                <a class="dropdown-item" href="rrd.php">RRD Graphing</a>
                <a class="dropdown-item" href="labstatus.php">Status</a>
                <a class="dropdown-item" href="labstats.php">Utilization Chart</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle'; if($nav == "license") { echo " active"; } echo '" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Licensing
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="license.php">Server Status</a>
                <a class="dropdown-item" href="https://license-stats.engr.oregonstate.edu/" target="_blank">Statistics</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle'; if($nav == "network") { echo " active"; } echo '" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Network
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="ad.php">Active Directory</a>
                <a class="dropdown-item" href="https://cyder.oregonstate.edu/" target="_blank">Cyder</a>
                <a class="dropdown-item" href="mon.php">MON</a>
                <a class="dropdown-item" href="netviewer.php">Netviewer</a>
                <a class="dropdown-item" href="switch.php">Switch Tool New</a>
                <a class="dropdown-item" href="switchold.php">Switch Tool Old</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle'; if($nav == "other") { echo " active"; } echo '" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Other
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="https://tools.engr.oregonstate.edu/coetools/adminer.php" target="_blank">Adminer</a>
                <a class="dropdown-item" href="http://directory.oregonstate.edu/" target="_blank">Directory</a>
                <a class="dropdown-item" href="password.php">Password Manager</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle'; if($nav == "printer") { echo " active"; } echo '" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Printers
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="printernew.php">Status New</a>
                <a class="dropdown-item" href="printer.php">Status Old</a>
              </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="https://app.slack.com/client/TCWLWCNTY/CDLPWRR24" target="_blank">Slack</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="https://trello.com/b/ROgBNyjG/pro-staff-it" target="_blank">Trello</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                vSphere
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="https://vcenter.engr.oregonstate.edu/ui/" target="_blank">vCenter (HTML5)</a>
                <a class="dropdown-item" href="https://vcenter.engr.oregonstate.edu/vsphere-client/?csp" target="_blank">vCenter (Flash)</a>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link'; if($nav == "wake") { echo " active"; } echo '" href="wake.php">Wake</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="https://secure.engr.oregonstate.edu/wiki/support" target="_blank">Wiki</a>
            </li>
          </ul>
        </div>
      </nav>';
}

?>