<?php

$time_start = microtime(true); 

// Include DB credentials for all calls
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('db.php');
require_once('user.php');

/* This block of code is used to fetch all tools and user data from the database. It connects to the
    database using the credentials provided in the db.php file. It then fetches all tools from the 
    tool_dir table and stores them in the $tools array. It also fetches the current user's layout
    from the user_layouts table and stores it in the $userData array. It also fetches
    extension data from the global_data table and stores it in the $globalData array. All of this
    data is then stored in the $result array and returned as a json object at the end of the file.
*/

// Connect to DB
$con = connectToDB();

createTable(
    $con, 'tool_dir',
    "CREATE TABLE tool_dir (
        id VARCHAR(36) PRIMARY KEY,
        tool_name VARCHAR(255) NOT NULL,
        tool_description VARCHAR(2048) NOT NULL,
        category VARCHAR(255) NOT NULL,
        link VARCHAR(2048) NOT NULL,
        staff_only INT NOT NULL,
        display INT NOT NULL,
        tab INT NOT NULL
    )"
);

createTable(
    $con, 'user_layouts',
    "CREATE TABLE user_layouts (
        user_id VARCHAR(255) PRIMARY KEY,
        layout VARCHAR(2048) NOT NULL DEFAULT '',
        night_mode INT NOT NULL DEFAULT 0,
        hidden_elements VARCHAR(2048) NOT NULL DEFAULT '',
        favorites VARCHAR(2048) NOT NULL DEFAULT '',
        recents VARCHAR(2048) NOT NULL DEFAULT '',
        direct_links INT NOT NULL DEFAULT 0,
        real_name VARCHAR(128) NOT NULL DEFAULT '',
        status_cards VARCHAR(2048) NOT NULL DEFAULT '',
        extension_data JSON NOT NULL DEFAULT '{}'
    )"
);

createTable(
    $con, 'global_data',
    "CREATE TABLE global_data (
        extension_name VARCHAR(255) PRIMARY KEY,
        extension_data JSON NOT NULL DEFAULT '{}'
    )"
);

// Create associative array of tools
$tools = array();
$toolQuery = "SELECT * FROM tool_dir";

if ($dbType === 'mysql') {
    $toolResult = mysqli_query($con, $toolQuery);
    while ($line = mysqli_fetch_assoc($toolResult)) {
        if (!($currStaff == 0 && $line['staff_only'] === 1)) {
            $tools[] = $line;
        }
    }
    mysqli_free_result($toolResult);
} else {
    $toolResult = pg_query($con, $toolQuery);
    while ($line = pg_fetch_assoc($toolResult)) {
        if (!($currStaff == 0 && $line['staff_only'] === 1)) {
        $tools[] = $line;
        }
    }
    pg_free_result($toolResult);
}

// Fetching user data
if ($dbType === 'mysql') {
    $escapedUser = mysqli_real_escape_string($con, $username);
} else {
    $escapedUser = pg_escape_string($con, $username);
}

$userData = fetchAssocArray($con, "SELECT * FROM user_layouts WHERE user_id = '$escapedUser'");
$globalData = array();
$globalQuery = "SELECT * FROM global_data";

// For each row, use the extensionName as the key and the extensionData as the value
if ($dbType === 'mysql') {
    $globalResult = mysqli_query($con, $globalQuery);
    while ($line = mysqli_fetch_assoc($globalResult)) {
        $globalData[$line['extension_name']] = $line['extension_data'];
    }
    mysqli_free_result($globalResult);
} else {
    $globalResult = pg_query($con, $globalQuery);
    while ($line = pg_fetch_assoc($globalResult)) {
        $globalData[$line['extension_name']] = $line['extension_data'];
    }
    pg_free_result($globalResult);
}

// If user is not in user_layouts table, insert them
if (empty($userData)) {
    $insertQuery = "INSERT INTO user_layouts (user_id) VALUES ('$escapedUser')";
    if ($dbType === 'mysql') {
        mysqli_query($con, $insertQuery);
    } else {
        pg_query($con, $insertQuery);
    }
    $userData = fetchAssocArray($con, "SELECT * FROM user_layouts WHERE user_id = '$escapedUser'");
}

// Close connection to DB
closeDB($con);

/* This block of code is used to validate the user data fetched from the database. If the data is invalid
    it is set to a default value. The user's status cards and extension data are fetched from the database
    and stored in the $statusCards and $extensionData arrays respectively. If the data is invalid it is set
    to an empty string. All of this data is then stored in the  $result array and returned as a json object 
    at the end of the file.
*/

$name = $userData['realName'] ?? '';

// Validate JSON data
$statusCards = decodeJsonOrDefault($userData, 'status_cards');
$extensionData = convertEmptyArrayToObject($userData['extension_data'] && !empty($userData['extension_data']) ? json_decode($userData['extension_data'], true) : []);

// Validate single values
$userLayout = $userData['layout'] ?? '';
$nightmode = ($userData['night_mode'] ?? '0') === '1' ? true : false;
$directLinks = ($userData['direct_links'] ?? '0') === '1' ? true : false;

// Validate comma separated values
$hiddenElements = !empty($userData['hidden_elements']) ? explode(',', $userData['hidden_elements']) : [];
$favorites = !empty($userData['favorites']) ? explode(',', $userData['favorites']) : [];
$recents = !empty($userData['recents']) ? explode(',', $userData['recents']) : [];

/* Create array of all categories found in tool array */
$categories = array_values(array_unique(array_column($tools, 'category')));

/* This block of code is used to create the json object that is returned at the end of the file. It contains
    the current user's username, name, staff status, layout, recents, favorites, nightmode, directLinks, hidden
    elements, status cards, shipping notifications, printer subscriptions, categories, tools, and last package
    refresh time. It also contains the raw data that was fetched from the database. This data is then returned
    as a json object at the end of the file.
*/

$result = array(
    "user" => array(
        "username" => $username,
        "name" => empty($name) ? $realName : $name,
        "staff" => $currStaff,
        "layout" => $userLayout,
        "recents" => $recents,
        "favorites" => $favorites,
        "night_mode" => $nightmode,
        "direct_links" => $directLinks,
        "hidden_elements" => $hiddenElements,
        "status_cards" => $statusCards,
        "extension_data" => $extensionData
    ),
    "tools" => array(
        "categories" => $categories,
        "tools" => $tools
    ),
    "global_data" => $globalData,
);

$time_end = microtime(true);

if (isset($debug) && $debug) {
    $result['debug'] = array(
        "dbSettings" => array(
            "host" => $dbSettings['host'],
            "db" => $dbSettings['db'],
            "user" => $dbSettings['user'],
            "password" => "********",
            "type" => $dbType
        ),
        "userData" => $userData,
        "authProviderData" => $authProvierData,
        "executionTime" => ($time_end - $time_start)
    );
}

header("Content-Type: application/json");
echo json_encode($result);
exit();
