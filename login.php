<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0){
    $user = $result->fetch_assoc();
    // For development: plain-text password check
    if ($password === $user['password']){
      $_SESSION['username'] = $user['username'];
      $_SESSION['role'] = $user['role'];
      if ($user['role'] === 'admin'){
        header("Location: admin.php");
      } else {
        header("Location: gallery.php");
      }
      exit();
    } else {
      $error_message = "Invalid password.";
    }
  } else {
    $error_message = "User not found.";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Aakrit's Art Gallery - Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Link to the common stylesheet -->
  <link rel="stylesheet" href="styles.css">
  <!-- Page-specific styling for a better-sized form -->
  <style>
    /* Adjust the login form dimensions and appearance */
    .form-container {
      max-width: 450px; /* Increased width */
      margin: 40px auto;
      padding: 30px; /* Extra padding for better spacing */
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px; /* Slight rounded corners */
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .form-container h2 {
      margin-bottom: 25px;
      font-size: 1.8em;
      text-align: center;
    }
    .form-container label {
      display: block;
      margin-top: 12px;
      font-size: 1em;
    }
    .form-container input[type="text"],
    .form-container input[type="password"] {
      font-size: 1em;
      padding: 10px;
      width: 100%;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .form-container button {
      font-size: 1.1em;
      margin-top: 25px;
      padding: 12px;
      width: 100%;
      background-color: #333;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .form-container button:hover {
      background-color: #444;
    }
    .error {
      color: red;
      text-align: center;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <header>
    <div class="navbar">
      <div class="logo">
        <a href="index.php">Aakrit's Art Gallery</a>
      </div>
      <nav>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="login.php" class="active">Login</a></li>
          <li><a href="register.php">Register</a></li>
        </ul>
      </nav>
    </div>
  </header>
  <main>
    <div class="form-container">
      <h2>Login</h2>
      <?php if (isset($error_message)): ?>
        <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
      <?php endif; ?>
      <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        
        <button type="submit">Login</button>
      </form>
    </div>
  </main>
  <footer>
    <p>&copy; 2025 Aakrit's Art Gallery. All rights reserved.</p>
  </footer>
</body>
</html>