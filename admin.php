<?php
session_start();
include 'db.php';

// Only allow admin access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header('Location: login.php');
  exit();
}

// Handle artwork upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['artwork_image'])) {
  $title = $_POST['title'];
  $description = $_POST['description'];
  
  // Ensure the folder exists (using assets/images)
  $targetDir = 'assets/images/';
  if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
  }
  $imagePath = $targetDir . basename($_FILES['artwork_image']['name']);
  
  if (move_uploaded_file($_FILES['artwork_image']['tmp_name'], $imagePath)) {
    $stmt = $conn->prepare("INSERT INTO artworks (title, description, image_path) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $description, $imagePath);
    $upload_message = $stmt->execute() ? "Artwork uploaded successfully!" : "Error uploading artwork to database.";
  } else {
    $upload_message = "Failed to upload image.";
  }
}

// Handle artwork deletion
if (isset($_GET['delete_id'])) {
  $deleteId = $_GET['delete_id'];
  $stmt = $conn->prepare("DELETE FROM artworks WHERE id = ?");
  $stmt->bind_param("i", $deleteId);
  $upload_message = $stmt->execute() ? "Artwork deleted successfully!" : "Error deleting artwork.";
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Aakrit's Art Gallery - Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="styles.css">
  <style>
    /* Admin page container styling (optional, overrides can be placed here) */
    .container {
      max-width: 1000px;
      margin: 20px auto;
      padding: 20px;
      background: #fff;
    }
    form input[type="text"],
    form textarea,
    form input[type="file"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
    }
    form button {
      padding: 10px 20px;
      background: #333;
      color: #fff;
      border: none;
      cursor: pointer;
    }
    .message {
      margin-bottom: 20px;
      color: green;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    table, th, td {
      border: 1px solid #ddd;
    }
    th, td {
      padding: 10px;
      text-align: left;
    }
    table img {
      width: 100px;
    }
  </style>
</head>
<body>
  <header>
    <div class="navbar">
      <div class="logo"><a href="index.php">Aakrit's Art Gallery</a></div>
      <nav>
        <ul>
          <li><a href="gallery.php">Gallery</a></li>
          <li><a href="admin.php" class="active">Admin</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
    </div>
  </header>
  <main>
    <div class="container">
      <h1>Admin Panel</h1>
      <?php if (isset($upload_message)): ?>
        <p class="message"><?php echo htmlspecialchars($upload_message); ?></p>
      <?php endif; ?>
      <h2>Upload Artwork</h2>
      <form method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" required>
        
        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>
        
        <label for="artwork_image">Upload Image:</label>
        <input type="file" name="artwork_image" id="artwork_image" required>
        
        <button type="submit">Upload Artwork</button>
      </form>
      <h2>Manage Artworks</h2>
      <table>
        <tr>
          <th>Title</th>
          <th>Description</th>
          <th>Image</th>
          <th>Action</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM artworks ORDER BY id DESC");
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
          <td><?php echo htmlspecialchars($row['title']); ?></td>
          <td><?php echo htmlspecialchars($row['description']); ?></td>
          <td><img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>"></td>
          <td>
            <a href="admin.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this artwork?')">Delete</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </table>
    </div>
  </main>
  <footer>
    <p>&copy; 2025 Aakrit's Art Gallery. All rights reserved.</p>
  </footer>
</body>
</html>