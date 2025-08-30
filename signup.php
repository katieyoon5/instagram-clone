<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h2>Create a new account</h2>

    <?php
// define variables and set to empty values
$nameErr = $emailErr = $passwordErr = $usernameErr= "";
$fname = $lname = $email = $username = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["fname"])) {
    $nameErr = "Name is required";
  } else {
    $fname = test_input($_POST["fname"]);
  }

  if (empty($_POST["lname"])) {
    $nameErr = "Name is required";
  } else {
    $lname = test_input($_POST["lname"]);
  }

  
  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
  } else {
    $email = test_input($_POST["email"]);
  }

  if (empty($_POST["username"])) {
    $usernameErr = "username is required";
  } else {
    $username = test_input($_POST["username"]);
  }
    
  if (empty($_POST["password"])) {
    $passwordErr = "Name is required";
  } else {
    $password = test_input($_POST["password"]);
  }

}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if (
    !empty($fname) && !empty($lname) && !empty($email) &&
    !empty($username) && !empty($password)
  ) {
      require("php.php");
  
      $stmt = $conn->prepare("INSERT INTO users (fname, lname, email, username, pwd) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param("sssss", $fname, $lname, $email, $username, $password);
  
      if ($stmt->execute()) {
          echo "✅ Account created successfully!";
          header("Location: welcome.php");  
          exit();
      } else {
          echo "❌ Error: " . $stmt->error;
      }
  
      $stmt->close();
      $conn->close();
  }
?>

<p><span class="error">* required field</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  First Name: <input type="text" name="fname">
  <span class="error">* <?php echo $nameErr;?></span>
  <br><br>
  Last Name: <input type="text" name="lname">
  <span class="error">* <?php echo $nameErr;?></span>
  <br><br>
  E-mail: <input type="text" name="email">
  <span class="error">* <?php echo $emailErr;?></span>
  <br><br>

  Username: <input type="text" name="username">
  <span class="error">* <?php echo $usernameErr;?></span>
  <br><br>
  Password: <input type="password" name="password">
  <span class="error">* <?php echo $passwordErr;?></span>
  <br><br>
  
  <input type="submit" name="submit" value="Submit">  
</form>

    
</body>
</html>