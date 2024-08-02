<?php

$debug = true;

require_once('config.php');
require_once('user.php');

///////////////////////////////
// Database Helper Functions //
///////////////////////////////

$dbType = $dbSettings['type'];

function convertEmptyArrayToObject($data) {
    return (is_array($data) && empty($data)) ? new stdClass() : $data;
}

function decodeJsonOrDefault($data, $key, $default = []) {
    return $data && !empty($data[$key]) ? json_decode(urldecode($data[$key]), true) : $default;
}

function connectToDB() {
    global $dbSettings, $dbType;

    if ($dbType === 'mysql') {
        return mysqli_connect($dbSettings['host'], $dbSettings['user'], $dbSettings['password'], $dbSettings['db']);
    } else {
        return pg_connect("host=" . $dbSettings['host'] . " dbname=" . $dbSettings['db'] . " user=" . $dbSettings['user'] . " password=" . $dbSettings['password']);
    }
}

function closeDB($con) {
    global $dbType;

    if ($dbType === 'mysql') {
        mysqli_close($con);
    } else {
        pg_close($con);
    }
}

function createTable($con, $tableName, $query) {
    global $dbType;

    $tableCheck = false;

    try {
        if ($dbType === 'mysql') {
            $result = @mysqli_query($con, "SELECT 1 FROM $tableName LIMIT 1");
        } else {
            $result = @pg_query($con, "SELECT 1 FROM $tableName LIMIT 1");
        }
    } catch (Exception $e) {
        $result = false;
    }

    if ($result !== false) {
        $tableCheck = true;
    }

    if (!$tableCheck) {
        if ($dbType === 'mysql') {
            $query = $query . " ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
            return mysqli_query($con, $query);
        } else {
            return pg_query($con, $query);
        }
    }
}

function fetchAssocArray($con, $query) {
    global $dbType;

    if ($dbType === 'mysql') {
        $result = mysqli_query($con, $query);
        $data = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
    } else {
        $result = pg_query($con, $query);
        $data = pg_fetch_assoc($result);
        pg_free_result($result);
    }
    return $data;
}

function executeUpdate($con, $queryTemplate, $requiredFields, $data, $includeUser = false, $pgQuery = null) {
    global $username, $dbType;
    
    $response = ['success' => false, 'code' => 2, 'message' => ''];

    // Decode JSON input
    $inputData = json_decode($data, true);

    // Check required fields
    foreach ($requiredFields as $field) {
        if (!isset($inputData[$field])) {
            $response['message'] = "Error: Required field '$field' is missing.";
            return json_encode($response); // Return JSON response
        }
    }

    // Sanitize user input
    $sanitizedValues = [];
    foreach ($inputData as $key => $value) {
        // If data is an empty array, convert it to an empty object
        if (is_array($value) && empty($value)) {
            $value = json_encode(new stdClass());
        } elseif (is_array($value)) {
            $value = json_encode($value);
        }

        if ($dbType === 'mysql') {
            $sanitizedValues[$key] = mysqli_real_escape_string($con, $value);
        } else {
            $sanitizedValues[$key] = pg_escape_string($con, $value);
        }
    }

    // If user data is to be included, check for the existence of the username
    if ($includeUser) {
        if (!isset($username)) {
            $response['message'] = "Error: User authentication failed.";
            $response['code'] = 403; // Forbidden or any appropriate error code
            $response['username'] = $username;
            return json_encode($response);
        }
        if ($dbType === 'mysql') {
            $sanitizedValues['user_id'] = mysqli_real_escape_string($con, $username);
        } else {
            $sanitizedValues['user_id'] = pg_escape_string($con, $username);
        }
    }

    // Replace placeholders in the query with sanitized values
    $query = $dbType === 'postgresql' && $pgQuery !== null ? $pgQuery : $queryTemplate;
    foreach ($sanitizedValues as $key => $value) {
        $query = str_replace(':'.$key, $value, $query);
    }

    // Attempt to execute the query
    if ($dbType === 'mysql') {
        if (mysqli_query($con, $query)) {
            $response['success'] = true;
            $response['code'] = 0;
            $response['message'] = 'Update successful.';
        } else {
            $response['code'] = 1;
            $response['message'] = 'Error updating record: ' . mysqli_error($con);
        }
    } else {
        if (pg_query($con, $query)) {
            $response['success'] = true;
            $response['code'] = 0;
            $response['message'] = 'Update successful.';
        } else {
            $response['code'] = 1;
            $response['message'] = 'Error updating record: ' . pg_last_error($con);
        }
    }

    return json_encode($response); // Return JSON response
}

function jsonApiEndpoint($query, $fields, $includeUser = false, $pgQuery = null) {
    global $dbType;

    header('Content-Type: application/json');

    $con = connectToDB();
    
    if ($con === false) {
        $response = ['success' => false, 'code' => 1, 'message' => 'Error connecting to the database.'];
        return json_encode($response);
    }

    $data = file_get_contents('php://input');
    $response = executeUpdate($con, $query, $fields, $data, $includeUser, $pgQuery);

    closeDB($con);

    echo $response;
    
    exit();
}

?>
