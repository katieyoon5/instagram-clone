<?php
session_start();
require("php.php"); // Your DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['id'];
    $caption = $_POST['caption'];

    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true); 
    }

    $originalName = basename($_FILES['file']['name']);
    $uniqueName = uniqid() . "_" . $originalName;
    $imagePath = $uploadDir . $uniqueName;

    move_uploaded_file($_FILES['file']['tmp_name'], $imagePath);

    $stmt = $conn->prepare("INSERT INTO posts (user_id, image, caption, date) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $userId, $imagePath, $caption);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: posts.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upload</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
        crossorigin="anonymous">
</head>
<body>

<div id="drop-area">
  <div class="content">
    <p>Drag & drop your image here</p>
    <input type="file" id="fileElem" accept="image/*" onchange="getImg()">
    <label for="fileElem">or click to select</label>
  </div>
</div>

<div id="imgPrev" style="display: none;">
  <button type="button" class="btn-close" aria-label="Close" onclick="closeImage()">×</button>
  <img class="preview-image" id="preview" src="" alt="Uploaded image preview" />
</div>

<div class="mb-3">
  <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Add a caption..."></textarea>
</div>

<div style="text-align: center;">
  <button class="btn btn-primary" type="button" onclick="share()">Share</button>
</div>

<script>
function share() {
    const fileInput = document.getElementById('fileElem');
    const file = fileInput.files[0];
    const caption = document.getElementById('exampleFormControlTextarea1').value;

    if (!file) {
        alert("Please upload an image before sharing.");
        return;
    }

    const formData = new FormData();
    formData.append("file", file);
    formData.append("caption", caption);

    fetch("upload.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        alert(data);
        if (data.includes("✅")) {
            window.location.href = "posts.php";
        }
    })
    .catch(err => {
        console.error(err);
        alert("Error uploading post.");
    });
}

function getImg() {
    const fileInput = document.getElementById('fileElem');
    const file = fileInput.files[0];
    const dropArea = document.getElementById('drop-area');
    const preview = document.getElementById('preview');
    const imgPrev = document.getElementById('imgPrev');

    if (file && file.type.startsWith("image/")) {
        preview.src = URL.createObjectURL(file);
        dropArea.style.display = "none";
        imgPrev.style.display = "block";
    }
}

function closeImage() {
    const preview = document.getElementById('preview');
    const dropArea = document.getElementById('drop-area');
    const imgPrev = document.getElementById('imgPrev');
    const fileInput = document.getElementById('fileElem');

    preview.src = "";
    imgPrev.style.display = "none";
    dropArea.style.display = "block";
    fileInput.value = "";
}
</script>

</body>
</html>
