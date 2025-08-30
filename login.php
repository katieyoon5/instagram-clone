<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <style>
        html,body{
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            background-image: url("loginbackground.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;

            display: flex; 
            flex-direction: column;
            justify-content: center; 
            align-items: center;     
            height: 100vh;
        }

        .border {
            border: 2px solid white;
            border-radius: 20px;
            width: 30%;
            height: 50%; 
            padding: 30px;
            background-color: rgba(180, 160, 190, 0.3);
            color: white;
            text-align: center; 
        }


    </style>
</head>
<body>

<?php
session_start(); 
$error = "";
$username = $password =  "";
 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["username"]) or empty($_POST["password"])) {
      $error = "Please fill in both fields";
    } else {
      $username = test_input($_POST["username"]);
      $password = test_input($_POST["password"]);

      
      require("php.php");   
      $stmt = $conn->prepare("SELECT * FROM `users` WHERE username = ? AND pwd = ?");
      $stmt->bind_param("ss", $username, $password);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows >= 1) {
          echo "✅ Login successful!";
          $row = $result->fetch_assoc(); 
          $_SESSION['id'] = $row['id'];
          header("Location: posts.php");  
          
          exit();
      } else {
          $error = "❌ Invalid username or password. Please try again ";
      }

      $stmt->close();
      $conn->close();
    }
    
    
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
?>

<h1>OpenThreads</h1> <br><br>
<div class="border">
    <h1>Login</h1> 
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
        Username: <input type="text" name="username">
        <br><br>
        Password: <input type="password" name="password"> 
        <br><br>
        <input type="submit" name="submit" value="Submit">  
        <span class="error">* <?php echo $error;?></span>
    </form>
</div> 


</body>
</html>