<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

<h1 style="text-align: center;" >OPEN TREADS</h1>
<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['id'];

if (isset($_POST['uploadbutton'])){
    header("Location: upload.php");
    //SEND THE USER ID SO ITS IN THE URL OF UPLOAD.PHP
 
    header("Location: upload.php");
    exit(); 
}

require("php.php");   
$stmt = $conn->prepare("SELECT * FROM `posts`");
$stmt->execute();
$posts = $stmt->get_result();


while ($post = $posts->fetch_assoc()) {
    $user_id = $post['user_id'];
    $user_stmt = $conn->prepare("SELECT username FROM `users` WHERE id = ?");
    $user_stmt->bind_param("i", $user_id);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    $user = $user_result->fetch_assoc();


    echo "<div class='post'>";
    echo "<img src='" . htmlspecialchars($post["image"]) . "' alt='Post image' class='image' ><br>";
    echo "<p>".htmlspecialchars($user["username"])."</p>";
    echo "<p>" . htmlspecialchars($post["caption"]) . "</p>";
    echo "</div>";

    $user_stmt->close(); 
}

$stmt->close();

?>

<div class='fix'>
    <form method="POST">
        <button type="submit" name="uploadbutton" id="uploadbutton">Opload pic</button>
    </form> 
</div>
    
</body>
</html>

