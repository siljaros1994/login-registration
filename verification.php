<?php
require(__DIR__ . "/config.php");

if (!isset($db)) {
    die("Database connection not established. Check config.php.");
}

if (isset($_POST['tag']) && $_POST['tag'] != '') {
    $tag = $_POST['tag'];
    $response = array("tag" => $tag, "error" => FALSE);

    // Fetch user by email
    $query = "SELECT * FROM users WHERE email = :email";
    $query_params = array(':email' => $_POST['email']);

    try {
        $stmt = $db->prepare($query);
        $stmt->execute($query_params);
    } catch (PDOException $ex) {
        $response["error"] = TRUE;
        $response["message"] = "Database error: " . $ex->getMessage();
        die(json_encode($response));
    }

    $row = $stmt->fetch();

    if ($tag == 'verify_code') {
        // Add OTP verification logic here if needed
        $response["error"] = FALSE;
        $response["message"] = "Verification logic not implemented yet.";
        die(json_encode($response));
    }
} else {
    echo json_encode(array("message" => "Method not supported!"));
}
?>