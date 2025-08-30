<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Social media app</title>
</head>
<body>

<h1>WELCOME TO OpenThreads</h1>

    <form method="POST">
        <button type="submit" name="login" id="login">Log In</button>
    </form>
    <form method="POST">
        <button type="submit" name="signup" id="signup">Sign Up</button>
    </form>

    
    
</body>
</html>

<?php
 
if (isset($_POST['login'])) {
    header("Location: login.php");  
    exit();
}
if (isset($_POST['signup'])) {
    header("Location: signup.php");  
    exit();
}


?>