<?php
session_start();
if (isset($_SESSION['username'])) {
  header("Location: gallery.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Aakrit's Art Gallery - Home</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <div class="navbar">
      <div class="logo">
        <a href="index.php">Aakrit's Art Gallery</a>
      </div>
      <nav>
        <ul>
          <li><a href="index.php" class="active">Home</a></li>
          <li><a href="login.php">Login</a></li>
          <li><a href="register.php">Register</a></li>
        </ul>
      </nav>
    </div>
  </header>
  <main>
    <div class="form-container" style="text-align: center;">
      <h2>Welcome to Aakrit's Art Gallery</h2>
      <p>Please login or register to view the gallery.</p>
      <div style="margin-top: 20px;">
        <a href="login.php"><button style="width: 150px; margin-right:10px;">Login</button></a>
        <a href="register.php"><button style="width: 150px;">Register</button></a>
      </div>
    </div>
  </main>
  <footer>
    <p>&copy; 2025 Aakrit's Art Gallery. All rights reserved.</p>
  </footer>
</body>
</html>