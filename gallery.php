<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

include 'db.php';

// Get search value if provided
$search = isset($_GET['search']) ? $conn->real_escape_string(trim($_GET['search'])) : '';

// Pagination settings
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
  $page = 1;
}

// Change the number of artworks per page to 4
$perPage = 4;
$offset = ($page - 1) * $perPage;

$whereClause = "";
if ($search !== "") {
  $whereClause = "WHERE title LIKE '%$search%' OR description LIKE '%$search%'";
}

// Count total artworks matching search
$countSql = "SELECT COUNT(*) as total FROM artworks $whereClause";
$countResult = $conn->query($countSql);
$totalRows = 0;
if ($row = $countResult->fetch_assoc()) {
  $totalRows = $row['total'];
}
$totalPages = ($totalRows > 0) ? ceil($totalRows / $perPage) : 1;

// Fetch the artworks for the current page
$sql = "SELECT * FROM artworks $whereClause ORDER BY id DESC LIMIT $perPage OFFSET $offset";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Aakrit's Art Gallery - Gallery</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <div class="navbar">
      <div class="logo"><a href="index.php">Aakrit's Art Gallery</a></div>
      <nav>
        <ul>
          <li><a href="gallery.php" class="active">Gallery</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
    </div>
  </header>
  <main>
    <div class="search-bar">
      <form method="GET" action="gallery.php">
        <input type="text" name="search" placeholder="Search artworks..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
      </form>
    </div>
    <div class="gallery-container">
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="artwork">
            <h2><?php echo htmlspecialchars($row['title']); ?></h2>
            <p><?php echo htmlspecialchars($row['description']); ?></p>
            <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p style="text-align:center;">No artworks found.</p>
      <?php endif; ?>
    </div>
    <div class="pagination">
      <?php if ($page > 1): ?>
        <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo ($page - 1); ?>">Prev</a>
      <?php endif; ?>
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <?php if ($i == $page): ?>
          <span class="current"><?php echo $i; ?></span>
        <?php else: ?>
          <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
        <?php endif; ?>
      <?php endfor; ?>
      <?php if ($page < $totalPages): ?>
        <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo ($page + 1); ?>">Next</a>
      <?php endif; ?>
    </div>
  </main>
  <footer>
    <p>&copy; 2025 Aakrit's Art Gallery. All rights reserved.</p>
  </footer>
</body>
</html>