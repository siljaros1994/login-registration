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

    if ($tag == 'forgot_pass') {
        // Generate a new random password
        $newPassword = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update user's password
        $query = "UPDATE users SET password = :password WHERE email = :email";
        $query_params = array(
            ':password' => $hashedPassword,
            ':email' => $_POST['email']
        );

        try {
            $stmt = $db->prepare($query);
            $stmt->execute($query_params);

            // Send email with new password (for demonstration, we'll just return it in the response)
            $response["error"] = FALSE;
            $response["message"] = "Password reset successful!";
            $response["new_password"] = $newPassword; // For testing purposes only
            die(json_encode($response));
        } catch (PDOException $ex) {
            $response["error"] = TRUE;
            $response["message"] = "Password reset failed: " . $ex->getMessage();
            die(json_encode($response));
        }
    }
} else {
    echo json_encode(array("message" => "Method not supported!"));
}
?>