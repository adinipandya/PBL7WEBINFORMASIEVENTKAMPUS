<?php
require_once __DIR__ . "/config/db.php";

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($id <= 0) { header("Location: index.php"); exit; }

$stmt = $conn->prepare("SELECT id, title, content FROM news WHERE id=? AND is_active=1 LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$news = $stmt->get_result()->fetch_assoc();
if (!$news) { header("Location: index.php"); exit; }

$sqlGallery = "
  SELECT 
    n.id,
    n.title,
    COALESCE(
      (SELECT ni.image_path
       FROM news_images ni
       WHERE ni.news_id = n.id
       ORDER BY ni.sort_order ASC, ni.id ASC
       LIMIT 1
      ),
      n.cover_image
    ) AS cover
  FROM news n
  WHERE n.is_active = 1
  ORDER BY n.published_at DESC, n.id DESC
  LIMIT 4
";
$newsGallery = $conn->query($sqlGallery)->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($news["title"]) ?></title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />

  <style>
    body { margin:0; font-family:"Poppins",sans-serif; background:#fff5dc; color:#222; }
    .navbar-custom {
      background: linear-gradient(to right, #f3b63a, #f5cd6f);
      box-shadow: 0 3px 8px rgba(0,0,0,0.1);
      padding: 0.7rem 2rem;
      position: fixed; top:0; width:100%; z-index:1000;
    }
    .navbar-brand img { height:55px; transition:transform .3s ease; }
    .navbar-brand img:hover { transform:scale(1.08); }
    .nav-link { color:#2c2c2c !important; padding:0.6rem 1.4rem !important; transition:.3s; }
    .nav-link:hover { color:#fff !important; text-shadow:0 0 6px rgba(255,255,255,0.4); }

    .content-container { padding:130px 20px 80px; display:flex; justify-content:center; }
    .main-content { display:flex; flex-wrap:wrap; gap:40px; max-width:1100px; width:100%; }
    .image-column { display:flex; flex-direction:column; gap:20px; flex:1; }
    .image-column img { width:100%; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1); object-fit:cover; }
    .content-text { flex:1; padding:10px 20px; }
    .content-text h1 { font-size:2rem; font-weight:700; margin-bottom:20px; }
    .content-text p, .content-text li { font-size:1rem; line-height:1.7; color:#333; }
    .content-text ul { padding-left:20px; }

    @media (max-width:768px) {
      .main-content { flex-direction:column; }
      .image-column { flex-direction:row; gap:10px; }
      .image-column img { width:50%; }
      .content-text { padding:0; }
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
      <a class="navbar-brand" href="./index.php">
        <img src="logopolibatam.png" alt="Logo Polibatam" />
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="./index.php">Beranda</a></li>
          <li class="nav-item"><a class="nav-link" href="./index.php#kontak">Kontak</a></li>
          <li class="nav-item"><a class="nav-link" href="./Tentang.php">Tentang</a></li>
          <li class="nav-item"><a class="nav-link" href="./login.php">Login</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="content-container">
    <div class="main-content">
      <div class="image-column">
        <?php foreach ($newsGallery as $n): ?>
          <img src="<?= htmlspecialchars(($n["cover"] ? "assets/images/" . basename($n["cover"]) : "placeholder.jpg")) ?>" alt="<?= htmlspecialchars($n["title"]) ?>">
          <?php endforeach; ?>
      </div>

      <div class="content-text">
        <h1><?= htmlspecialchars($news["title"]) ?></h1>
        <div style="white-space:pre-line;"><?= htmlspecialchars($news["content"]) ?></div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
