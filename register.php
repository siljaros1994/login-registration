<?php
ob_start();

require(__DIR__ . "/config.php");

if (isset($db)) {
    error_log("config.php included successfully, \$db is set.");
} else {
    error_log("config.php NOT included correctly, or \$db is NOT set.");
    die("config.php NOT included correctly, or \$db is NOT set.");
}

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = array("error" => FALSE);

    $required_fields = array('fullname', 'username', 'password', 'email', 'usertype');
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $response["error"] = TRUE;
            $response["message"] = "Required parameter " . $field . " is missing or empty!";
            error_log("Missing parameter: " . $field);
            die(json_encode($response));
        }
    }

    $query = "SELECT 1 FROM users WHERE email = :email";
    $query_params = array(':email' => $_POST['email']);

    try {
        $stmt = $db->prepare($query);
        $stmt->execute($query_params);
    } catch (PDOException $ex) {
        $response["error"] = TRUE;
        $response["message"] = "Database error (email check): " . $ex->getMessage();
        error_log("PDOException (email check): " . $ex->getMessage());
        die(json_encode($response));
    }

    if ($stmt->fetch()) {
        $response["error"] = TRUE;
        $response["message"] = "Email is already in use.";
        die(json_encode($response));
    }

    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO users (fullname, username, password, email, usertype)
              VALUES (:fullname, :username, :password, :email, :usertype)";

    $query_params = array(
        ':fullname' => $_POST['fullname'],
        ':username' => $_POST['username'],
        ':password' => $hashedPassword,
        ':email' => $_POST['email'],
        ':usertype' => $_POST['usertype']
    );

    try {
        $stmt = $db->prepare($query);
        $stmt->execute($query_params);

        $response["error"] = FALSE;
        $response["message"] = "Registration successful!";
        //Debugging: Log success
        error_log("Registration Successful");


    } catch (PDOException $ex) {
        $response["error"] = TRUE;
        $response["message"] = "Database error (insert): " . $ex->getMessage();
        // Debugging: Log the PDOException
        error_log("PDOException (insert): " . $ex->getMessage());

    }

    ob_end_clean();
    echo json_encode($response);
    exit();

} elseif ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
} else {
    http_response_code(405);
    $response = array("message" => "Method not supported! (Only POST allowed)");
    echo json_encode($response);
}
?>