<?php
require(__DIR__ . "/config.php");

header('Content-Type: application/json'); // Ensure correct content type

if (!isset($db)) {
    die(json_encode(array("error" => true, "message" => "Database connection not established. Check config.php.")));
}

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user by email
    $query = "SELECT id, fullname, username, email, usertype, password FROM users WHERE email = :email";
    $query_params = array(':email' => $email);

    try {
        $stmt = $db->prepare($query);
        $stmt->execute($query_params);
    } catch (PDOException $ex) {
        $response["error"] = TRUE;
        $response["message"] = "Database error: " . $ex->getMessage();
        die(json_encode($response));
    }

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($password, $row['password'])) {
        $response["error"] = FALSE;
        $response["message"] = "Login successful!";
        $response["user"] = array(
            "id" => intval($row['id']), // Ensure ID is an integer
            "fullname" => $row['fullname'],
            "username" => $row['username'],
            "email" => $row['email'],
            "usertype" => $row['usertype'] // Donor or Recipient
        );
        echo json_encode($response);
        exit();
    } else {
        $response["error"] = TRUE;
        $response["message"] = "Invalid email or password.";
        echo json_encode($response);
        exit();
    }
} else {
    $response = array("error" => true, "message" => "Email and password are required.");
    echo json_encode($response);
    exit();
}
?>