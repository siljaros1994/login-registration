<!DOCTYPE html>
<html>
<head>
    <title>Registration Form Test</title>
</head>
<body>
<h1>Registration Form</h1>
<form method="POST" action="http://192.168.101.4/login-registration/register.php">
    <label for="fullname">Full Name:</label><br>
    <input type="text" id="fullname" name="fullname" required><br><br>

    <label for="username">Username:</label><br>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required><br><br>

    <label for="usertype">User Type:</label><br>
    <select id="usertype" name="usertype" required>
        <option value="Donor">Donor</option>
        <option value="Recipient">Recipient</option>
    </select><br><br>

    <input type="submit" value="Register">
</form>
</body>
</html>