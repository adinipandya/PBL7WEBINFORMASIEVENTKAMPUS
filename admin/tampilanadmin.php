<?php
session_start();
require_once __DIR__ . "/../config/db.php";


/** =========================
 *  1) AUTH STATE (navbar)
 *  ========================= */
$isLoggedIn = !empty($_SESSION["logged_in"]);
$isAdmin    = $isLoggedIn && (($_SESSION["role"] ?? "") === "admin");
$username   = $isLoggedIn ? ($_SESSION["username"] ?? "") : "";

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, "UTF-8"); }

function require_admin() {
  if (empty($_SESSION["logged_in"]) || (($_SESSION["role"] ?? "") !== "admin")) {
    header("Location: ../login.php");
    exit;
  }
}
require_admin();

$tab = $_GET["tab"] ?? "event";
$tab = preg_replace('/[^a-z]/', '', strtolower($tab));
if (!in_array($tab, ["event","berita","laporan"], true)) $tab = "event";

$msg = "";
$err = "";

$cats = [];
$resCats = $conn->query("SELECT id, name, slug FROM event_categories ORDER BY id ASC");
if ($resCats) $cats = $resCats->fetch_all(MYSQLI_ASSOC);

$report = [];
$sqlReport = "
  SELECT c.name AS category_name, COUNT(*) AS total
  FROM events e
  JOIN event_categories c ON c.id = e.category_id
  GROUP BY c.id
  ORDER BY total DESC, c.name ASC
";
$resReport = $conn->query($sqlReport);
if ($resReport) $report = $resReport->fetch_all(MYSQLI_ASSOC);

$events = [];
$sqlEvents = "
  SELECT e.id, e.title, e.event_date, e.location, e.category_id, c.name AS category_name
  FROM events e
  JOIN event_categories c ON c.id = e.category_id
  ORDER BY e.event_date DESC, e.id DESC
  LIMIT 50
";
$resEvents = $conn->query($sqlEvents);
if ($resEvents) $events = $resEvents->fetch_all(MYSQLI_ASSOC);

function parse_people_lines($text) {
  $lines = preg_split("/\r\n|\n|\r/", trim((string)$text));
  $out = [];
  foreach ($lines as $ln) {
    $ln = trim($ln);
    if ($ln === "") continue;
    $parts = array_map("trim", explode("|", $ln, 2));
    $name = $parts[0] ?? "";
    $role = $parts[1] ?? null;
    if ($name !== "") $out[] = ["name"=>$name, "title_role"=>$role];
  }
  return $out;
}
function parse_simple_lines($text) {
  $lines = preg_split("/\r\n|\n|\r/", trim((string)$text));
  $out = [];
  foreach ($lines as $ln) {
    $ln = trim($ln);
    if ($ln === "") continue;
    $out[] = $ln;
  }
  return $out;
}
function parse_lineups($text) {
  $lines = preg_split("/\r\n|\n|\r/", trim((string)$text));
  $out = [];
  foreach ($lines as $ln) {
    $ln = trim($ln);
    if ($ln === "") continue;
    $parts = array_map("trim", explode("|", $ln, 2));
    $item = $parts[0] ?? "";
    $note = $parts[1] ?? null;
    if ($item !== "") $out[] = ["item_name"=>$item, "item_note"=>$note];
  }
  return $out;
}
function parse_rundown($text) {
  $lines = preg_split("/\r\n|\n|\r/", trim((string)$text));
  $out = [];
  foreach ($lines as $ln) {
    $ln = trim($ln);
    if ($ln === "") continue;
    $parts = array_map("trim", explode("|", $ln, 2));
    $timePart = $parts[0] ?? "";
    $activity = $parts[1] ?? "";
    if ($activity === "") $activity = $timePart;
    $start = null; $end = null;
    if (strpos($timePart, "-") !== false) {
      [$a,$b] = array_map("trim", explode("-", $timePart, 2));
      if (preg_match("/^\d{2}:\d{2}$/", $a)) $start = $a . ":00";
      if (preg_match("/^\d{2}:\d{2}$/", $b)) $end   = $b . ":00";
    }
    $out[] = ["start_time"=>$start, "end_time"=>$end, "activity"=>$activity];
  }
  return $out;
}

function ensure_dir($path) {
  if (!is_dir($path)) {
    if (!mkdir($path, 0755, true) && !is_dir($path)) {
      throw new Exception("Gagal membuat folder: " . $path);
    }
  }
}

function is_allowed_image_ext($ext) {
  $ext = strtolower($ext);
  return in_array($ext, ["jpg","jpeg","png","webp","gif"], true);
}

function save_uploaded_image_to_assets($file, $baseName = null) {
  if (!isset($file) || !is_array($file)) throw new Exception("File tidak valid.");
  if (($file["error"] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    $code = (int)($file["error"] ?? -1);
    throw new Exception("Upload gagal. Code: " . $code);
  }

  $projectRoot = realpath(__DIR__ . "/.."); // admin/.. => root project
  if (!$projectRoot) throw new Exception("Project root tidak ditemukan.");

  $assetsDirFs = $projectRoot . "/assets";
  $imagesDirFs = $projectRoot . "/assets/images";

  ensure_dir($assetsDirFs);
  ensure_dir($imagesDirFs);

  $origName = (string)($file["name"] ?? "");
  $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
  if (!is_allowed_image_ext($ext)) throw new Exception("Tipe file tidak didukung.");

  $safeBase = $baseName ? $baseName : pathinfo($origName, PATHINFO_FILENAME);
  $safeBase = preg_replace('/[^a-zA-Z0-9_-]+/', '-', $safeBase);
  $safeBase = trim($safeBase, "-");
  if ($safeBase === "") $safeBase = "img";

  $fname = $safeBase . "." . $ext;

  $dstImages = $imagesDirFs . "/" . $fname;

  $i = 1;
  while (file_exists($dstImages)) {
    $fname = $safeBase . "-" . $i . "." . $ext;
    $dstImages = $imagesDirFs . "/" . $fname;
    $i++;
    if ($i > 9999) throw new Exception("Nama file bentrok terlalu banyak.");
  }

  $tmp = $file["tmp_name"];
  if (!is_uploaded_file($tmp)) throw new Exception("File bukan upload valid.");

  if (!move_uploaded_file($tmp, $dstImages)) throw new Exception("Gagal simpan file ke assets/images.");

  // DB tetap simpan assets/<namafile>
  return "assets/" . $fname;
}
function img_url($dbPath){
  $file = basename((string)$dbPath);
  return "../assets/images/" . $file;
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $action = $_POST["action"] ?? "";

  try {
    if ($action === "create_news") {
      $slug      = trim($_POST["slug"] ?? "");
      $title     = trim($_POST["title"] ?? "");
      $content   = trim($_POST["content"] ?? "");
      $published = trim($_POST["published_at"] ?? "");
      $isActive  = isset($_POST["is_active"]) ? 1 : 0;

      if ($title === "") throw new Exception("Judul berita wajib diisi.");
      if ($slug === "") {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $title));
        $slug = trim($slug, "-");
      }

      $coverPath = "";
      if (!empty($_FILES["cover_upload"]["name"] ?? "")) {
        $coverPath = save_uploaded_image_to_assets($_FILES["cover_upload"], "cover-" . $slug);
      } else {
        $coverPath = trim($_POST["cover_image"] ?? "");
      }

      $sql = "INSERT INTO news (slug,title,content,cover_image,published_at,is_active,created_by,created_at)
              VALUES (?,?,?,?,IF(?='',NULL,?),?,?,NOW())";
      $stmt = $conn->prepare($sql);
      $createdBy = (int)($_SESSION["user_id"] ?? 1);
      $stmt->bind_param("ssssssii", $slug, $title, $content, $coverPath, $published, $published, $isActive, $createdBy);
      $stmt->execute();

      $msg = "Berita berhasil ditambahkan.";
      $tab = "berita";
    }

    if ($action === "update_news") {
      $id        = (int)($_POST["id"] ?? 0);
      $slug      = trim($_POST["slug"] ?? "");
      $title     = trim($_POST["title"] ?? "");
      $content   = trim($_POST["content"] ?? "");
      $published = trim($_POST["published_at"] ?? "");
      $isActive  = isset($_POST["is_active"]) ? 1 : 0;

      if ($id <= 0) throw new Exception("ID berita tidak valid.");
      if ($title === "") throw new Exception("Judul berita wajib diisi.");
      if ($slug === "") {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $title));
        $slug = trim($slug, "-");
      }

      $coverPath = trim($_POST["cover_image"] ?? "");
      if (!empty($_FILES["cover_upload"]["name"] ?? "")) {
        $coverPath = save_uploaded_image_to_assets($_FILES["cover_upload"], "cover-" . $slug);
      }

      $sql = "UPDATE news
              SET slug=?, title=?, content=?, cover_image=?, published_at=IF(?='',NULL,?), is_active=?, updated_at=NOW()
              WHERE id=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssssssii", $slug, $title, $content, $coverPath, $published, $published, $isActive, $id);
      $stmt->execute();

      $msg = "Berita berhasil diupdate.";
      $tab = "berita";
    }

    if ($action === "delete_news") {
      $id = (int)($_POST["id"] ?? 0);
      if ($id <= 0) throw new Exception("ID berita tidak valid.");

      $stmtA = $conn->prepare("DELETE FROM news_images WHERE news_id=?");
      $stmtA->bind_param("i", $id);
      $stmtA->execute();

      $stmtB = $conn->prepare("DELETE FROM news WHERE id=?");
      $stmtB->bind_param("i", $id);
      $stmtB->execute();

      $msg = "Berita berhasil dihapus.";
      $tab = "berita";
    }

    if ($action === "add_news_image") {
      $newsId    = (int)($_POST["news_id"] ?? 0);
      $sortOrder = (int)($_POST["sort_order"] ?? 0);

      if ($newsId <= 0) throw new Exception("News ID tidak valid.");

      $imgPath = "";
      if (!empty($_FILES["image_upload"]["name"] ?? "")) {
        $imgPath = save_uploaded_image_to_assets($_FILES["image_upload"], "news-" . $newsId . "-" . $sortOrder);
      } else {
        $imgPath = trim($_POST["image_path"] ?? "");
      }

      if ($imgPath === "") throw new Exception("Gambar wajib diisi.");

      $sql = "INSERT INTO news_images (news_id,image_path,sort_order,created_at)
              VALUES (?,?,?,NOW())";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("isi", $newsId, $imgPath, $sortOrder);
      $stmt->execute();

      $msg = "Gambar berita berhasil ditambahkan.";
      $tab = "berita";
    }

    if ($action === "delete_news_image") {
      $imgId = (int)($_POST["id"] ?? 0);
      if ($imgId <= 0) throw new Exception("ID gambar tidak valid.");

      $stmt = $conn->prepare("DELETE FROM news_images WHERE id=?");
      $stmt->bind_param("i", $imgId);
      $stmt->execute();

      $msg = "Gambar berita berhasil dihapus.";
      $tab = "berita";
    }

    if ($action === "delete_event") {
      $id = (int)($_POST["id"] ?? 0);
      if ($id <= 0) throw new Exception("ID event tidak valid.");
      $stmt = $conn->prepare("DELETE FROM events WHERE id=?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      header("Location: tampilanadmin.php?tab=event&msg=" . urlencode("Event berhasil dihapus"));
      exit;
    }

    if ($action === "create_event" || $action === "update_event") {
      $isUpdate = ($action === "update_event");
      $id = (int)($_POST["id"] ?? 0);

      $category_id = (int)($_POST["category_id"] ?? 0);
      $title = trim($_POST["title"] ?? "");
      $description = trim($_POST["description"] ?? "");
      $event_date = $_POST["event_date"] ?? null;
      $start_time = $_POST["start_time"] ?? null;
      $end_time   = $_POST["end_time"] ?? null;
      $location = trim($_POST["location"] ?? "");
      $contact  = trim($_POST["contact"] ?? "");
      $attachment = trim($_POST["attachment"] ?? "");

      if ($category_id <= 0) throw new Exception("Kategori wajib dipilih.");
      if ($title === "") throw new Exception("Judul wajib diisi.");
      if (!$event_date) throw new Exception("Tanggal wajib diisi.");

      $stmtCat = $conn->prepare("SELECT slug FROM event_categories WHERE id=? LIMIT 1");
      $stmtCat->bind_param("i", $category_id);
      $stmtCat->execute();
      $rowCat = $stmtCat->get_result()->fetch_assoc();
      if (!$rowCat) throw new Exception("Kategori tidak ditemukan.");
      $catSlug = $rowCat["slug"];

      $conn->begin_transaction();

      if (!$isUpdate) {
        $status = "published";
        $stmt = $conn->prepare("
          INSERT INTO events (category_id, title, description, event_date, start_time, end_time, location, contact, attachment, status)
          VALUES (?,?,?,?,?,?,?,?,?,?)
        ");
        $stmt->bind_param("isssssssss", $category_id, $title, $description, $event_date, $start_time, $end_time, $location, $contact, $attachment, $status);
        $stmt->execute();
        $event_id = (int)$conn->insert_id;
      } else {
        if ($id <= 0) throw new Exception("ID event untuk update tidak valid.");
        $event_id = $id;

        $stmt = $conn->prepare("
          UPDATE events
          SET category_id=?, title=?, description=?, event_date=?, start_time=?, end_time=?, location=?, contact=?, attachment=?
          WHERE id=?
        ");
        $stmt->bind_param("issssssssi", $category_id, $title, $description, $event_date, $start_time, $end_time, $location, $contact, $attachment, $event_id);
        $stmt->execute();
      }

      if ($catSlug === "seminar") {
        $theme = trim($_POST["seminar_theme"] ?? "");
        $quota = ($_POST["seminar_quota"] !== "" ? (int)$_POST["seminar_quota"] : null);
        $reg   = trim($_POST["seminar_reg"] ?? "");
        $speakers = parse_people_lines($_POST["seminar_speakers"] ?? "");

        $stmt = $conn->prepare("
          INSERT INTO event_seminar_details (event_id, theme, quota, registration_link)
          VALUES (?,?,?,?)
          ON DUPLICATE KEY UPDATE theme=VALUES(theme), quota=VALUES(quota), registration_link=VALUES(registration_link)
        ");
        $stmt->bind_param("isis", $event_id, $theme, $quota, $reg);
        $stmt->execute();

        $conn->query("DELETE FROM event_speakers WHERE event_id=".(int)$event_id);
        if ($speakers) {
          $ins = $conn->prepare("INSERT INTO event_speakers (event_id, name, title_role, sort_order) VALUES (?,?,?,?)");
          $i=1;
          foreach ($speakers as $sp) {
            $nm = $sp["name"];
            $tr = $sp["title_role"];
            $ins->bind_param("issi", $event_id, $nm, $tr, $i);
            $ins->execute();
            $i++;
          }
        }
      } elseif ($catSlug === "lokakarya") {
        $theme = trim($_POST["workshop_theme"] ?? "");
        $tools = trim($_POST["workshop_tools"] ?? "");
        $quota = ($_POST["workshop_quota"] !== "" ? (int)$_POST["workshop_quota"] : null);
        $reg   = trim($_POST["workshop_reg"] ?? "");
        $instructors = parse_people_lines($_POST["workshop_instructors"] ?? "");

        $stmt = $conn->prepare("
          INSERT INTO event_workshop_details (event_id, theme, tools_required, quota, registration_link)
          VALUES (?,?,?,?,?)
          ON DUPLICATE KEY UPDATE theme=VALUES(theme), tools_required=VALUES(tools_required),
                                  quota=VALUES(quota), registration_link=VALUES(registration_link)
        ");
        $stmt->bind_param("issis", $event_id, $theme, $tools, $quota, $reg);
        $stmt->execute();

        $conn->query("DELETE FROM event_instructors WHERE event_id=".(int)$event_id);
        if ($instructors) {
          $ins = $conn->prepare("INSERT INTO event_instructors (event_id, name, title_role, sort_order) VALUES (?,?,?,?)");
          $i=1;
          foreach ($instructors as $sp) {
            $nm = $sp["name"];
            $tr = $sp["title_role"];
            $ins->bind_param("issi", $event_id, $nm, $tr, $i);
            $ins->execute();
            $i++;
          }
        }
      } elseif ($catSlug === "kompetisi") {
        $theme = trim($_POST["comp_theme"] ?? "");
        $prize = trim($_POST["comp_prize"] ?? "");
        $deadline = ($_POST["comp_deadline"] ?? "") ?: null;
        $rules = trim($_POST["comp_rules"] ?? "");
        $judges = parse_people_lines($_POST["comp_judges"] ?? "");
        $reqs = parse_simple_lines($_POST["comp_requirements"] ?? "");

        $stmt = $conn->prepare("
          INSERT INTO event_competition_details (event_id, theme, prize, registration_deadline, rules)
          VALUES (?,?,?,?,?)
          ON DUPLICATE KEY UPDATE theme=VALUES(theme), prize=VALUES(prize),
                                  registration_deadline=VALUES(registration_deadline), rules=VALUES(rules)
        ");
        $stmt->bind_param("issss", $event_id, $theme, $prize, $deadline, $rules);
        $stmt->execute();

        $conn->query("DELETE FROM event_judges WHERE event_id=".(int)$event_id);
        if ($judges) {
          $ins = $conn->prepare("INSERT INTO event_judges (event_id, name, title_role, sort_order) VALUES (?,?,?,?)");
          $i=1;
          foreach ($judges as $sp) {
            $nm = $sp["name"];
            $tr = $sp["title_role"];
            $ins->bind_param("issi", $event_id, $nm, $tr, $i);
            $ins->execute();
            $i++;
          }
        }

        $conn->query("DELETE FROM event_competition_requirements WHERE event_id=".(int)$event_id);
        if ($reqs) {
          $ins = $conn->prepare("INSERT INTO event_competition_requirements (event_id, requirement_text, sort_order) VALUES (?,?,?)");
          $i=1;
          foreach ($reqs as $rq) {
            $ins->bind_param("isi", $event_id, $rq, $i);
            $ins->execute();
            $i++;
          }
        }
      } elseif ($catSlug === "festival") {
        $theme = trim($_POST["fest_theme"] ?? "");
        $ticketed = (int)($_POST["fest_ticketed"] ?? 0);
        $ticket_price = ($_POST["fest_ticket_price"] !== "" ? (float)$_POST["fest_ticket_price"] : null);
        $area = trim($_POST["fest_area_note"] ?? "");
        $lineups = parse_lineups($_POST["fest_lineups"] ?? "");
        $rundown = parse_rundown($_POST["fest_rundown"] ?? "");

        $stmt = $conn->prepare("
          INSERT INTO event_festival_details (event_id, theme, is_ticketed, ticket_price, area_note)
          VALUES (?,?,?,?,?)
          ON DUPLICATE KEY UPDATE theme=VALUES(theme), is_ticketed=VALUES(is_ticketed),
                                  ticket_price=VALUES(ticket_price), area_note=VALUES(area_note)
        ");
        $stmt->bind_param("isids", $event_id, $theme, $ticketed, $ticket_price, $area);
        $stmt->execute();

        $conn->query("DELETE FROM event_festival_lineups WHERE event_id=".(int)$event_id);
        if ($lineups) {
          $ins = $conn->prepare("INSERT INTO event_festival_lineups (event_id, item_name, item_note, sort_order) VALUES (?,?,?,?)");
          $i=1;
          foreach ($lineups as $it) {
            $nm = $it["item_name"];
            $nt = $it["item_note"];
            $ins->bind_param("issi", $event_id, $nm, $nt, $i);
            $ins->execute();
            $i++;
          }
        }

        $conn->query("DELETE FROM event_festival_rundowns WHERE event_id=".(int)$event_id);
        if ($rundown) {
          $ins = $conn->prepare("INSERT INTO event_festival_rundowns (event_id, start_time, end_time, activity, sort_order) VALUES (?,?,?,?,?)");
          $i=1;
          foreach ($rundown as $rd) {
            $st = $rd["start_time"];
            $et = $rd["end_time"];
            $ac = $rd["activity"];
            $ins->bind_param("isssi", $event_id, $st, $et, $ac, $i);
            $ins->execute();
            $i++;
          }
        }
      }

      $conn->commit();
      $okMsg = $isUpdate ? "Event berhasil diupdate" : "Event berhasil dibuat";
      header("Location: tampilanadmin.php?tab=event&msg=" . urlencode($okMsg));
      exit;
    }

  } catch (Throwable $e) {
    try { $conn->rollback(); } catch (Throwable $x) {}
    $err = $e->getMessage();
  }
}

$news = [];
$resNews = $conn->query("SELECT id, slug, title, content, cover_image, published_at, is_active, created_at FROM news ORDER BY published_at DESC, id DESC LIMIT 50");
if ($resNews) $news = $resNews->fetch_all(MYSQLI_ASSOC);

$newsImagesByNews = [];
$resImgs = $conn->query("SELECT id, news_id, image_path, sort_order, created_at FROM news_images ORDER BY news_id DESC, sort_order ASC, id ASC");
if ($resImgs) {
  $imgs = $resImgs->fetch_all(MYSQLI_ASSOC);
  foreach ($imgs as $im) {
    $nid = (int)$im["news_id"];
    if (!isset($newsImagesByNews[$nid])) $newsImagesByNews[$nid] = [];
    $newsImagesByNews[$nid][] = $im;
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Polibatam</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#fff8e7; font-family:"Poppins",system-ui,Arial; }

    .card { border-radius:15px; box-shadow:0 3px 10px rgba(0,0,0,.1); }
    .hint { color:#6b6b6b; font-size:.9rem; margin-top:.5rem; }
    .mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }
    .img-thumb{ width:80px; height:54px; object-fit:cover; border-radius:8px; border:1px solid rgba(0,0,0,.1); background:#fff; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
      <a class="navbar-brand" href="./index.php">
        <img src="logopolibatam.png" alt="Logo Politeknik Negeri Batam" height="60" class="d-inline-block align-text-top">
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="./index.php">Beranda</a></li>
          <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
          <li class="nav-item"><a class="nav-link" href="./tentang.php">Tentang</a></li>

          <?php if ($isAdmin): ?>
            <li class="nav-item"><a class="nav-link" href="./tampilanadmin.php">Admin</a></li>
            <li class="nav-item"><a class="nav-link" href="./auth/logout.php">Logout</a></li>
          <?php elseif ($isLoggedIn): ?>
            <li class="nav-item">
              <a class="nav-link disabled" href="#" aria-disabled="true">
                Login sebagai: <?= htmlspecialchars($username) ?>
              </a>
            </li>
            <li class="nav-item"><a class="nav-link" href="./auth/logout.php">Logout</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="./login.php">Login</a></li>
          <?php endif; ?>

        </ul>
      </div>
    </div>
  </nav>

<nav class="navbar subnav">
  <div class="container-fluid">
    <ul class="nav">
      <li class="nav-item"><a class="nav-link <?= $tab==="event"?"active":"" ?>" href="?tab=event">Event</a></li>
      <li class="nav-item"><a class="nav-link <?= $tab==="berita"?"active":"" ?>" href="?tab=berita">Konten</a></li>
      <li class="nav-item"><a class="nav-link <?= $tab==="laporan"?"active":"" ?>" href="?tab=laporan">Laporan</a></li>
    </ul>
  </div>
</nav>

<div class="container my-4">
  <?php if ($msg): ?><div class="alert alert-success"><?= h($msg) ?></div><?php endif; ?>
  <?php if ($err): ?><div class="alert alert-danger"><?= h($err) ?></div><?php endif; ?>

  <?php if ($tab === "event"): ?>
    <div class="row g-4">
      <div class="col-lg-7">
        <div class="card p-4">
          <h5 class="mb-3">📅 Daftar Event (50 terbaru)</h5>
          <table class="table table-hover align-middle">
            <thead class="table-warning">
              <tr>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Tanggal</th>
                <th>Lokasi</th>
                <th style="width:210px">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!$events): ?>
                <tr><td colspan="5" class="text-muted">Belum ada event.</td></tr>
              <?php else: ?>
                <?php foreach ($events as $e): ?>
                  <tr>
                    <td><?= h($e["title"]) ?></td>
                    <td><span class="badge text-bg-secondary"><?= h($e["category_name"]) ?></span></td>
                    <td><?= h(date("d/m/Y", strtotime($e["event_date"]))) ?></td>
                    <td><?= h($e["location"]) ?></td>
                    <td class="d-flex gap-2">
                      <a class="btn btn-sm btn-outline-dark" target="_blank" href="../event_detail.php?id=<?= (int)$e["id"] ?>">Detail</a>
                      <button type="button" class="btn btn-sm btn-outline-primary btn-edit" data-id="<?= (int)$e["id"] ?>">Edit</button>
                      <form method="post" onsubmit="return confirm('Yakin hapus event ini?')" class="m-0">
                        <input type="hidden" name="action" value="delete_event">
                        <input type="hidden" name="id" value="<?= (int)$e["id"] ?>">
                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0" id="formTitle">✏️ Tambah Event</h5>
            <button type="button" id="btnCancelEdit" class="btn btn-sm btn-outline-secondary d-none">Batal Edit</button>
          </div>

          <form method="post">
            <input type="hidden" name="action" id="formAction" value="create_event">
            <input type="hidden" name="id" id="eventId" value="">

            <div class="mb-3">
              <label class="form-label">Kategori</label>
              <select class="form-select" name="category_id" id="categorySelect" required>
                <option value="">-- Pilih kategori --</option>
                <?php foreach ($cats as $c): ?>
                  <option value="<?= (int)$c["id"] ?>" data-slug="<?= h($c["slug"]) ?>"><?= h($c["name"]) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Judul</label>
              <input class="form-control" name="title" id="title" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Deskripsi</label>
              <textarea class="form-control" name="description" id="description" rows="3"></textarea>
            </div>

            <div class="row g-2">
              <div class="col-6 mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" class="form-control" name="event_date" id="event_date" required>
              </div>
              <div class="col-3 mb-3">
                <label class="form-label">Mulai</label>
                <input type="time" class="form-control" name="start_time" id="start_time">
              </div>
              <div class="col-3 mb-3">
                <label class="form-label">Selesai</label>
                <input type="time" class="form-control" name="end_time" id="end_time">
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Lokasi</label>
              <input class="form-control" name="location" id="location">
            </div>

            <div class="mb-3">
              <label class="form-label">Kontak</label>
              <input class="form-control" name="contact" id="contact">
            </div>

            <div class="mb-3">
              <label class="form-label">Lampiran</label>
              <textarea class="form-control" name="attachment" id="attachment" rows="2"></textarea>
            </div>

            <div id="form-seminar" class="border rounded p-3 mb-3 d-none">
              <div class="fw-semibold mb-2">Detail Seminar</div>
              <input class="form-control mb-2" name="seminar_theme" id="seminar_theme" placeholder="Tema">
              <div class="row g-2 mb-2">
                <div class="col-4"><input type="number" class="form-control" name="seminar_quota" id="seminar_quota" placeholder="Kuota" min="0"></div>
                <div class="col-8"><input class="form-control" name="seminar_reg" id="seminar_reg" placeholder="Link Pendaftaran"></div>
              </div>
              <textarea class="form-control mono" name="seminar_speakers" id="seminar_speakers" rows="3" placeholder="Nama | Jabatan"></textarea>
            </div>

            <div id="form-lokakarya" class="border rounded p-3 mb-3 d-none">
              <div class="fw-semibold mb-2">Detail Lokakarya</div>
              <input class="form-control mb-2" name="workshop_theme" id="workshop_theme" placeholder="Tema">
              <textarea class="form-control mb-2" name="workshop_tools" id="workshop_tools" rows="2" placeholder="Tools/Peralatan"></textarea>
              <div class="row g-2 mb-2">
                <div class="col-4"><input type="number" class="form-control" name="workshop_quota" id="workshop_quota" placeholder="Kuota" min="0"></div>
                <div class="col-8"><input class="form-control" name="workshop_reg" id="workshop_reg" placeholder="Link Pendaftaran"></div>
              </div>
              <textarea class="form-control mono" name="workshop_instructors" id="workshop_instructors" rows="3" placeholder="Nama | Jabatan"></textarea>
            </div>

            <div id="form-kompetisi" class="border rounded p-3 mb-3 d-none">
              <div class="fw-semibold mb-2">Detail Kompetisi</div>
              <input class="form-control mb-2" name="comp_theme" id="comp_theme" placeholder="Tema">
              <input class="form-control mb-2" name="comp_prize" id="comp_prize" placeholder="Hadiah">
              <input type="date" class="form-control mb-2" name="comp_deadline" id="comp_deadline">
              <textarea class="form-control mb-2" name="comp_rules" id="comp_rules" rows="3" placeholder="Rules"></textarea>
              <textarea class="form-control mono mb-2" name="comp_judges" id="comp_judges" rows="3" placeholder="Nama | Jabatan"></textarea>
              <textarea class="form-control mono" name="comp_requirements" id="comp_requirements" rows="3" placeholder="1 baris = 1 syarat"></textarea>
            </div>

            <div id="form-festival" class="border rounded p-3 mb-3 d-none">
              <div class="fw-semibold mb-2">Detail Festival</div>
              <input class="form-control mb-2" name="fest_theme" id="fest_theme" placeholder="Tema">
              <div class="row g-2 mb-2">
                <div class="col-5">
                  <select class="form-select" name="fest_ticketed" id="fest_ticketed">
                    <option value="0">Tidak</option>
                    <option value="1">Ya</option>
                  </select>
                </div>
                <div class="col-7"><input class="form-control" name="fest_ticket_price" id="fest_ticket_price" placeholder="Harga Tiket"></div>
              </div>
              <input class="form-control mb-2" name="fest_area_note" id="fest_area_note" placeholder="Catatan Area">
              <textarea class="form-control mono mb-2" name="fest_lineups" id="fest_lineups" rows="3" placeholder="Item | Catatan"></textarea>
              <textarea class="form-control mono" name="fest_rundown" id="fest_rundown" rows="3" placeholder="HH:MM-HH:MM | Kegiatan"></textarea>
            </div>

            <button class="btn btn-warning text-white fw-semibold w-100" id="btnSubmit">Simpan Event</button>
          </form>
        </div>
      </div>
    </div>

  <?php elseif ($tab === "berita"): ?>
    <div class="row g-4">
      <div class="col-lg-7">
        <div class="card p-4">
          <h5 class="mb-3">📰 Daftar Berita (50 terbaru)</h5>
          <table class="table table-hover align-middle">
            <thead class="table-warning">
              <tr>
                <th>Judul</th>
                <th>Slug</th>
                <th>Aktif</th>
                <th>Publish</th>
                <th style="width:210px">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!$news): ?>
                <tr><td colspan="5" class="text-muted">Belum ada berita.</td></tr>
              <?php else: ?>
                <?php foreach ($news as $n): ?>
                  <?php
                    $nid = (int)$n["id"];
                    $imgs = $newsImagesByNews[$nid] ?? [];
                    $coverShow = $imgs[0]["image_path"] ?? ($n["cover_image"] ?? "");
                  ?>
                  <tr>
                    <td>
                      <div class="d-flex gap-2 align-items-center">
                        <?php if ($coverShow): ?>
                          <img class="img-thumb" src="<?= h(img_url($coverShow)) ?>" alt="">

                        <?php else: ?>
                          <div class="img-thumb d-flex align-items-center justify-content-center text-muted">no img</div>
                        <?php endif; ?>
                        <div>
                          <div class="fw-semibold"><?= h($n["title"]) ?></div>
                          <div class="text-muted small">ID: <?= $nid ?> · images: <?= count($imgs) ?></div>
                        </div>
                      </div>
                    </td>
                    <td class="mono"><?= h($n["slug"]) ?></td>
                    <td><?= ((int)$n["is_active"]===1) ? "Ya" : "Tidak" ?></td>
                    <td class="small"><?= h($n["published_at"] ?? "-") ?></td>
                    <td class="d-flex flex-wrap gap-2">
                      <a class="btn btn-sm btn-outline-dark" target="_blank" href="../news_detail.php?id=<?= $nid ?>">Detail</a>

                      <button type="button" class="btn btn-sm btn-outline-primary btn-edit-news"
                        data-id="<?= $nid ?>"
                        data-slug="<?= h($n["slug"]) ?>"
                        data-title="<?= h($n["title"]) ?>"
                        data-content="<?= h($n["content"] ?? "") ?>"
                        data-cover="<?= h($n["cover_image"] ?? "") ?>"
                        data-published="<?= h($n["published_at"] ?? "") ?>"
                        data-active="<?= (int)$n["is_active"] ?>"
                      >Edit</button>

                      <form method="post" class="m-0" onsubmit="return confirm('Yakin hapus berita ini? (gambar ikut terhapus)')">
                        <input type="hidden" name="action" value="delete_news">
                        <input type="hidden" name="id" value="<?= $nid ?>">
                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                      </form>
                    </td>
                  </tr>

                  <tr>
                    <td colspan="5" class="bg-light">
                      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="fw-semibold">Gambar Berita</div>
                        <form method="post" class="d-flex flex-wrap gap-2 align-items-center m-0" enctype="multipart/form-data">
                          <input type="hidden" name="action" value="add_news_image">
                          <input type="hidden" name="news_id" value="<?= $nid ?>">
                          <input class="form-control form-control-sm" type="file" name="image_upload" accept=".jpg,.jpeg,.png,.webp,.gif" required>
                          <input class="form-control form-control-sm" type="number" name="sort_order" value="<?= count($imgs)+1 ?>" style="width:110px">
                          <button class="btn btn-sm btn-warning text-white">Upload</button>
                        </form>
                      </div>

                      <div class="mt-2">
                        <?php if (!$imgs): ?>
                          <div class="text-muted">Belum ada gambar untuk berita ini.</div>
                        <?php else: ?>
                          <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0">
                              <thead>
                                <tr class="text-muted">
                                  <th style="width:90px">Preview</th>
                                  <th>image_path</th>
                                  <th style="width:110px">sort_order</th>
                                  <th style="width:160px">created_at</th>
                                  <th style="width:110px">Aksi</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php foreach ($imgs as $im): ?>
                                  <tr>
                                    <td><img class="img-thumb" src="<?= h(img_url($im["image_path"])) ?>" alt="">
                                    </td>
                                    <td class="mono"><?= h($im["image_path"]) ?></td>
                                    <td><?= (int)$im["sort_order"] ?></td>
                                    <td class="small"><?= h($im["created_at"]) ?></td>
                                    <td>
                                      <form method="post" class="m-0" onsubmit="return confirm('Hapus gambar ini?')">
                                        <input type="hidden" name="action" value="delete_news_image">
                                        <input type="hidden" name="id" value="<?= (int)$im["id"] ?>">
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                      </form>
                                    </td>
                                  </tr>
                                <?php endforeach; ?>
                              </tbody>
                            </table>
                          </div>
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>

                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0" id="newsFormTitle">✏️ Tambah Berita</h5>
            <button class="btn btn-sm btn-outline-secondary" type="button" id="btnResetNews">Reset</button>
          </div>

          <form method="post" id="newsForm" class="mt-3" enctype="multipart/form-data">
            <input type="hidden" name="action" id="newsAction" value="create_news">
            <input type="hidden" name="id" id="newsId" value="">

            <div class="mb-2">
              <label class="form-label">Slug</label>
              <input class="form-control mono" name="slug" id="newsSlug">
            </div>

            <div class="mb-2">
              <label class="form-label">Judul</label>
              <input class="form-control" name="title" id="newsTitle" required>
            </div>

            <div class="mb-2">
              <label class="form-label">Konten</label>
              <textarea class="form-control" name="content" id="newsContent" rows="6"></textarea>
            </div>

            <div class="mb-2">
              <label class="form-label">Cover Upload (akan disimpan ke images/ dan DB = assets/namafile)</label>
              <input class="form-control" type="file" name="cover_upload" accept=".jpg,.jpeg,.png,.webp,.gif">
            </div>

            <div class="mb-2">
              <label class="form-label">cover_image (isi manual kalau tidak upload)</label>
              <input class="form-control mono" name="cover_image" id="newsCover" placeholder="assets/nama.jpg">
            </div>

            <div class="mb-2">
              <label class="form-label">published_at</label>
              <input type="datetime-local" class="form-control" name="published_at" id="newsPublished">
            </div>

            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" name="is_active" id="newsActive" checked>
              <label class="form-check-label" for="newsActive">Aktif</label>
            </div>

            <button class="btn btn-warning text-white fw-semibold w-100" id="newsSubmitBtn">Simpan Berita</button>
          </form>
        </div>
      </div>
    </div>

  <?php else: ?>
    <div class="row g-4">
      <div class="col-lg-6">
        <div class="card p-4">
          <h5 class="mb-3">📊 Laporan: Total Event per Kategori</h5>
          <table class="table table-hover align-middle">
            <thead class="table-warning">
              <tr><th>Kategori</th><th>Total</th></tr>
            </thead>
            <tbody>
              <?php foreach ($report as $r): ?>
                <tr>
                  <td><?= h($r["category_name"]) ?></td>
                  <td><?= (int)$r["total"] ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
const sel = document.getElementById("categorySelect");
const blocks = {
  seminar: document.getElementById("form-seminar"),
  lokakarya: document.getElementById("form-lokakarya"),
  kompetisi: document.getElementById("form-kompetisi"),
  festival: document.getElementById("form-festival"),
};
function hideAllBlocks(){ Object.values(blocks).forEach(el => el && el.classList.add("d-none")); }
function showBlockBySlug(slug){ hideAllBlocks(); if (slug && blocks[slug]) blocks[slug].classList.remove("d-none"); }
function getSelectedSlug(){ const opt = sel?.options?.[sel.selectedIndex]; return opt ? opt.getAttribute("data-slug") : ""; }
function onCategoryChange(){ showBlockBySlug(getSelectedSlug()); }
sel?.addEventListener("change", onCategoryChange);

function setVal(id, val){ const el = document.getElementById(id); if (!el) return; el.value = (val ?? ""); }

function setEditMode(isEdit){
  document.getElementById("formAction").value = isEdit ? "update_event" : "create_event";
  document.getElementById("formTitle").textContent = isEdit ? "📝 Edit Event" : "✏️ Tambah Event";
  document.getElementById("btnCancelEdit").classList.toggle("d-none", !isEdit);
  document.getElementById("btnSubmit").textContent = isEdit ? "Update Event" : "Simpan Event";
}
function resetFormToCreate(){
  setEditMode(false);
  setVal("eventId", "");
  ["title","description","event_date","start_time","end_time","location","contact","attachment"].forEach(id => setVal(id, ""));
  ["seminar_theme","seminar_quota","seminar_reg","seminar_speakers","workshop_theme","workshop_tools","workshop_quota","workshop_reg","workshop_instructors","comp_theme","comp_prize","comp_deadline","comp_rules","comp_judges","comp_requirements","fest_theme","fest_ticketed","fest_ticket_price","fest_area_note","fest_lineups","fest_rundown"].forEach(id => setVal(id, ""));
  if (sel) sel.value = "";
  hideAllBlocks();
}
document.getElementById("btnCancelEdit")?.addEventListener("click", resetFormToCreate);

document.querySelectorAll(".btn-edit").forEach(btn => {
  btn.addEventListener("click", async () => {
    const id = btn.dataset.id;
    if (!id) return;
    btn.disabled = true;
    const oldText = btn.textContent;
    btn.textContent = "Loading...";
    try {
      const res = await fetch(`./ajax_event_get.php?id=${encodeURIComponent(id)}`);
      const data = await res.json();
      if (!data.ok) { alert(data.message || "Gagal ambil data event"); return; }
      const ev = data.event;
      const det = data.details || {};
      setVal("eventId", ev.id);
      setVal("title", ev.title);
      setVal("description", ev.description);
      setVal("event_date", ev.event_date);
      setVal("start_time", ev.start_time);
      setVal("end_time", ev.end_time);
      setVal("location", ev.location);
      setVal("contact", ev.contact);
      setVal("attachment", ev.attachment);
      if (sel) sel.value = String(ev.category_id);
      onCategoryChange();
      Object.keys(det).forEach(k => setVal(k, det[k]));
      setEditMode(true);
      document.getElementById("formTitle")?.scrollIntoView({behavior:"smooth", block:"start"});
    } catch (e) {
      alert("Fetch error: " + e.message);
    } finally {
      btn.disabled = false;
      btn.textContent = oldText;
    }
  });
});

const btnsNews = document.querySelectorAll(".btn-edit-news");
const formTitle = document.getElementById("newsFormTitle");
const actionEl  = document.getElementById("newsAction");
const idEl      = document.getElementById("newsId");
const slugEl    = document.getElementById("newsSlug");
const titleEl   = document.getElementById("newsTitle");
const contentEl = document.getElementById("newsContent");
const coverEl   = document.getElementById("newsCover");
const pubEl     = document.getElementById("newsPublished");
const activeEl  = document.getElementById("newsActive");
const submitBtn = document.getElementById("newsSubmitBtn");
const resetBtn  = document.getElementById("btnResetNews");

function toDatetimeLocal(val){
  if (!val) return "";
  const s = String(val).trim().replace(" ", "T");
  return s.slice(0, 16);
}
function resetNewsForm(){
  if (!formTitle) return;
  formTitle.textContent = "✏️ Tambah Berita";
  actionEl.value = "create_news";
  idEl.value = "";
  slugEl.value = "";
  titleEl.value = "";
  contentEl.value = "";
  coverEl.value = "";
  pubEl.value = "";
  activeEl.checked = true;
  submitBtn.textContent = "Simpan Berita";
}
btnsNews.forEach(b => {
  b.addEventListener("click", () => {
    formTitle.textContent = "✏️ Edit Berita";
    actionEl.value = "update_news";
    idEl.value = b.dataset.id || "";
    slugEl.value = b.dataset.slug || "";
    titleEl.value = b.dataset.title || "";
    contentEl.value = b.dataset.content || "";
    coverEl.value = b.dataset.cover || "";
    pubEl.value = toDatetimeLocal(b.dataset.published || "");
    activeEl.checked = (parseInt(b.dataset.active || "0", 10) === 1);
    submitBtn.textContent = "Update Berita";
    document.getElementById("newsForm")?.scrollIntoView({ behavior: "smooth", block: "start" });
    titleEl?.focus();
  });
});
resetBtn?.addEventListener("click", resetNewsForm);

hideAllBlocks();
</script>

</body>
</html>
