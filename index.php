<?php

require_once __DIR__ . "/config/db.php";



$cat = $_GET["cat"] ?? ""; // seminar/lokakarya/kompetisi/festival
$cat = preg_replace('/[^a-z]/', '', strtolower($cat));
$validCats = ["seminar","lokakarya","kompetisi","festival"];
if (!in_array($cat, $validCats, true)) $cat = "";

/** =========================
 *  3) BULAN & TAHUN KALENDER
 *  ========================= */
$month = isset($_GET["m"]) ? (int)$_GET["m"] : (int)date("n");
$year  = isset($_GET["y"]) ? (int)$_GET["y"] : (int)date("Y");

if ($month < 1 || $month > 12) $month = (int)date("n");
if ($year < 2000 || $year > 2100) $year = (int)date("Y");

$firstDay = sprintf("%04d-%02d-01", $year, $month);
$lastDay  = date("Y-m-t", strtotime($firstDay));

$monthName = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"][$month];

$prevMonth = $month - 1; $prevYear = $year;
if ($prevMonth < 1) { $prevMonth = 12; $prevYear--; }

$nextMonth = $month + 1; $nextYear = $year;
if ($nextMonth > 12) { $nextMonth = 1; $nextYear++; }

/** =========================
 *  4) EVENT BULAN INI (DOT + AGENDA)
 *  ========================= */
$sqlMonth = "
  SELECT e.id, e.title, e.event_date, e.location, c.slug AS category_slug
  FROM events e
  JOIN event_categories c ON c.id = e.category_id
  WHERE e.status = 'published'
    AND e.event_date BETWEEN ? AND ?
    " . ($cat ? " AND c.slug = ? " : "") . "
  ORDER BY e.event_date ASC, e.id ASC
";
$stmt = $conn->prepare($sqlMonth);
if ($cat) $stmt->bind_param("sss", $firstDay, $lastDay, $cat);
else      $stmt->bind_param("ss",  $firstDay, $lastDay);
$stmt->execute();
$rowsMonth = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

/** Kelompokkan event per tanggal */
$eventsByDate = [];
foreach ($rowsMonth as $r) {
  $d = $r["event_date"];
  if (!isset($eventsByDate[$d])) $eventsByDate[$d] = [];
  $eventsByDate[$d][] = $r;
}

/** =========================
 *  5) EVENT CARDS (3 TERDEKAT)
 *  ========================= */
/** =========================
 *  5) EVENT CARDS + PAGINATION
 *  ========================= */
$today = date("Y-m-d");
$perPage = 3;
$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $perPage;

/* hitung total data (future) */
$sqlCount = "
  SELECT COUNT(*) AS total
  FROM events e
  JOIN event_categories c ON c.id = e.category_id
  WHERE e.status = 'published'
    AND e.event_date >= ?
    " . ($cat ? " AND c.slug = ? " : "") . "
";
$stmtCount = $conn->prepare($sqlCount);
if ($cat) $stmtCount->bind_param("ss", $today, $cat);
else      $stmtCount->bind_param("s",  $today);
$stmtCount->execute();
$totalFuture = (int)($stmtCount->get_result()->fetch_assoc()["total"] ?? 0);

/* ambil list future sesuai halaman */
$sqlCards = "
  SELECT e.id, e.title, e.event_date, e.location, c.slug AS category_slug
  FROM events e
  JOIN event_categories c ON c.id = e.category_id
  WHERE e.status = 'published'
    AND e.event_date >= ?
    " . ($cat ? " AND c.slug = ? " : "") . "
  ORDER BY e.event_date ASC, e.id ASC
  LIMIT ? OFFSET ?
";
$stmt2 = $conn->prepare($sqlCards);
if ($cat) $stmt2->bind_param("ssii", $today, $cat, $perPage, $offset);
else      $stmt2->bind_param("sii",  $today,       $perPage, $offset);
$stmt2->execute();
$eventsCards = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

$totalPages = max(1, (int)ceil($totalFuture / $perPage));

/** fallback kalau tidak ada event future => paginate dari event terbaru */
if ($totalFuture === 0) {
  $sqlCount2 = "
    SELECT COUNT(*) AS total
    FROM events e
    JOIN event_categories c ON c.id = e.category_id
    WHERE e.status = 'published'
    " . ($cat ? " AND c.slug = ? " : "") . "
  ";
  $stmtCount2 = $conn->prepare($sqlCount2);
  if ($cat) $stmtCount2->bind_param("s", $cat);
  $stmtCount2->execute();
  $totalAll = (int)($stmtCount2->get_result()->fetch_assoc()["total"] ?? 0);

  $sqlCards2 = "
    SELECT e.id, e.title, e.event_date, e.location, c.slug AS category_slug
    FROM events e
    JOIN event_categories c ON c.id = e.category_id
    WHERE e.status = 'published'
    " . ($cat ? " AND c.slug = ? " : "") . "
    ORDER BY e.event_date DESC, e.id DESC
    LIMIT ? OFFSET ?
  ";
  $stmt3 = $conn->prepare($sqlCards2);
  if ($cat) $stmt3->bind_param("sii", $cat, $perPage, $offset);
  else      $stmt3->bind_param("ii",       $perPage, $offset);
  $stmt3->execute();
  $eventsCards = $stmt3->get_result()->fetch_all(MYSQLI_ASSOC);

  $totalPages = max(1, (int)ceil($totalAll / $perPage));
}

/* jaga-jaga page kebesaran */
if ($page > $totalPages) {
  $page = $totalPages;
}


/** =========================
 *  6) KALENDER GRID
 *  ========================= */
$daysInMonth = (int)date("t", strtotime($firstDay));
$firstDowMon = (int)date("N", strtotime($firstDay)); // 1=Mon..7=Sun
$selectedDate = ($year == (int)date("Y") && $month == (int)date("n")) ? $today : $firstDay;

/** =========================
 *  7) GALLERY BERITA (1 gambar per berita)
 *  ========================= */
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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pusat Informasi Acara Polibatam</title>

  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    .calendar-table td { position: relative; cursor: pointer; user-select: none; }
    .calendar-table td.inactive { cursor: default; }
    .calendar-table td.has-event::after {
      content: ""; width: 7px; height: 7px; border-radius: 50%;
      background: #e67e00; position: absolute; bottom: 6px; left: 50%;
      transform: translateX(-50%);
    }
    .calendar-table td.selected { outline: 2px solid rgba(230,126,0,0.6); border-radius: 6px; }
    .agenda-list { padding: 14px 16px; }
    .agenda-item { background: rgba(255,255,255,0.75); border-radius: 10px; padding: 10px 12px; margin-bottom: 10px; }
    .agenda-item h6 { margin: 0 0 4px 0; font-weight: 700; }
    .agenda-item p { margin: 0; font-size: 0.95rem; }
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

      
            <li class="nav-item"><a class="nav-link" href="./login.php">Login</a></li>
     
        </ul>
      </div>
    </div>
  </nav>

  <div class="main-content">
    <div class="left-panel">
      <div class="title-section">
        <h1>WEB INFORMASI EVENT&nbsp;KAMPUS</h1>
      </div>

      <div class="description">
        <p>Temukan jadwal lengkap seminar, lokakarya, kompetisi, dan festival kampus di satu tempat.</p>
        <p>Selalu update dengan kegiatan terbaru di lingkungan akademikmu.</p>
      </div>

      <div class="search-section">
        <select class="category-input" id="kategoriFilter" onchange="applyFilter()">
          <option value="">Semua Kategori</option>
          <option value="seminar"    <?= ($cat==="seminar") ? "selected" : "" ?>>Seminar</option>
          <option value="lokakarya"  <?= ($cat==="lokakarya") ? "selected" : "" ?>>Lokakarya</option>
          <option value="kompetisi"  <?= ($cat==="kompetisi") ? "selected" : "" ?>>Kompetisi</option>
          <option value="festival"   <?= ($cat==="festival") ? "selected" : "" ?>>Festival</option>
        </select>
      </div>
    </div>

    <div class="right-panel">
      <img src="gedung_polibatam.jpg" alt="Gedung Polibatam" class="building-image">
    </div>
  </div>

  <!-- Gallery berita: 1 gambar per berita -->
  <div class="gallery-announcement-section">
    <div class="gallery-container">
      <?php foreach ($newsGallery as $n): ?>
        <div class="gallery-item" onclick="window.location.href='./news_detail.php?id=<?= (int)$n["id"] ?>'">
        <img
            src="<?= htmlspecialchars(($n["cover"] ? "assets/images/" . basename($n["cover"]) : "placeholder.jpg")) ?>"
            alt="<?= htmlspecialchars($n["title"]) ?>"
          >
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="announcement-section" id="hasil-pencarian">
    <h2 class="announcement-title">Pengumuman</h2>
  </div>

  <!-- Cards event dari DB -->
  <div class="event-section">
    <div class="card-container">
      <?php if (empty($eventsCards)): ?>
        <div class="event-card">
          <div>
            <h2>Belum ada event</h2>
            <p>Silakan cek kembali nanti.</p>
            <p>Admin bisa menambahkan event dari halaman admin.</p>
          </div>
        </div>
      <?php else: ?>
        <?php foreach ($eventsCards as $ev): ?>
          <div class="event-card">
            <div>
              <h2><?= htmlspecialchars($ev["title"]) ?></h2>
              <p><?= date("d F Y", strtotime($ev["event_date"])) ?></p>
              <p><?= htmlspecialchars($ev["location"]) ?></p>
            </div>
            <button class="detail-button" onclick="location.href='./event_detail.php?id=<?= (int)$ev["id"] ?>'">
              Lihat Detail
            </button>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <?php
  // build query string untuk pagination (tetap bawa cat, m, y)
  $qs = [];
  if (!empty($cat)) $qs["cat"] = $cat;
  if (!empty($_GET["m"])) $qs["m"] = (int)$_GET["m"];
  if (!empty($_GET["y"])) $qs["y"] = (int)$_GET["y"];

  $baseUrl = "index.php";
  $prevPage = max(1, $page - 1);
  $nextPage = min($totalPages, $page + 1);

  $qsPrev = $qs; $qsPrev["page"] = $prevPage;
  $qsNext = $qs; $qsNext["page"] = $nextPage;

  $prevLink = $baseUrl . "?" . http_build_query($qsPrev);
  $nextLink = $baseUrl . "?" . http_build_query($qsNext);
?>

<div class="d-flex justify-content-center align-items-center gap-2 mt-3">
  <a class="btn btn-outline-dark btn-sm <?= ($page <= 1) ? "disabled" : "" ?>" href="<?= htmlspecialchars($prevLink) ?>">
    &laquo; Prev
  </a>

  <span class="small fw-semibold">
    Halaman <?= (int)$page ?> / <?= (int)$totalPages ?>
  </span>

  <a class="btn btn-outline-dark btn-sm <?= ($page >= $totalPages) ? "disabled" : "" ?>" href="<?= htmlspecialchars($nextLink) ?>">
    Next &raquo;
  </a>
</div>

  </div>

  <div class="top-header-yellow"></div>

  <!-- Kalender + Agenda -->
  <div class="agenda-section">
    <div class="agenda-card" style="display:flex; gap:18px; align-items:flex-start;">

      <div class="calendar-box" style="flex:0 0 340px;">
        <div class="calendar-header">
          <p class="month-year-display"><?= htmlspecialchars($monthName . " " . $year) ?></p>
          <div class="month-navigation">
            <button class="nav-button"
              onclick="location.href='?m=<?= $prevMonth ?>&y=<?= $prevYear ?><?= $cat ? "&cat=".$cat : "" ?>'">&lt;</button>
            <button class="nav-button"
              onclick="location.href='?m=<?= $nextMonth ?>&y=<?= $nextYear ?><?= $cat ? "&cat=".$cat : "" ?>'">&gt;</button>
          </div>
        </div>

        <table class="calendar-table" id="calendarTable">
          <thead>
            <tr>
              <th>Mo</th><th>Tu</th><th>We</th><th>Th</th><th>Fr</th><th>Sa</th><th>Su</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $day = 1;
              for ($r=0; $r<6; $r++) {
                echo "<tr>";
                for ($c=1; $c<=7; $c++) {
                  $cellIndex = $r*7 + $c;
                  if ($cellIndex < $firstDowMon || $day > $daysInMonth) {
                    echo '<td class="inactive"></td>';
                  } else {
                    $dateStr = sprintf("%04d-%02d-%02d", $year, $month, $day);
                    $hasEvent = isset($eventsByDate[$dateStr]);

                    $classes = [];
                    if ($hasEvent) $classes[] = "has-event";
                    if ($dateStr === $selectedDate) $classes[] = "selected";
                    if ($dateStr === date("Y-m-d")) $classes[] = "today";

                    $classAttr = $classes ? ' class="'.implode(" ", $classes).'"' : "";
                    echo '<td'.$classAttr.' data-date="'.$dateStr.'">'.$day.'</td>';
                    $day++;
                  }
                }
                echo "</tr>";
              }
            ?>
          </tbody>
        </table>
      </div>

      <div style="flex:1;">
        <div class="agenda-title-box">
          <p class="agenda-title-text">Agenda Polibatam</p>
        </div>
        <div class="agenda-list" id="agendaList"></div>
      </div>

    </div>
  </div>

  <div class="contact-section" id="kontak">
    <div class="map-container">
      <a href="https://www.google.com/maps/place/Politeknik+Negeri+Batam/@1.118721,104.048478,17z/data=!4m6!3m5!1s0x31d98921856ddfab:0xf9d9fc65ca00c9d!8m2!3d1.1187205!4d104.0484566!16s%2Fg%2F1hc0g0x3x!5m1!1e4?entry=tts&g_ep=EgoyMDI1MTAxNC4wIPu8ASoASAFQAw%3D%3D&skid=ecfbb60e-8d0d-4fe0-b765-361eb6dafce0" target="_blank">
        <img src="peta_polibatam.png" alt="Peta Lokasi Polibatam" class="map-image">
      </a>
    </div>

    <div class="contact-info">
      <h2>Hubungi Kami</h2>
      <p>Jika memiliki pertanyaan ataupun keperluan, silakan menghubungi kami melalui kontak di bawah ini:</p>
      <p><strong>Alamat:</strong> Jl. Ahmad Yani Batam Kota, Kota Batam, Kepulauan Riau Indonesia</p>
      <p><strong>Phone:</strong> +62-778-469858 Ext. 1017</p>
      <p><strong>Fax:</strong> +62-778-463620</p>
      <p><strong>Email:</strong> info@polibatam.ac.id</p>
    </div>
  </div>

  <div class="footer-bottom">
    <div class="footer-content">
      <div class="footer-social">
        <a href="https://www.instagram.com/polibatamofficial?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw=="><i class="fab fa-instagram"></i></a>
        <a href="https://youtube.com/@polibatamtv?si=1MjmnocfPAumN7qy"><i class="fab fa-youtube"></i></a>
      </div>
      <p>&copy; 2025 Politeknik Negeri Batam. All Rights Reserved.</p>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Filter kategori (reload dengan query string)
    function applyFilter() {
      const cat = document.getElementById("kategoriFilter").value;
      const url = new URL(window.location.href);
        
      // 1. Atur parameter kategori
      if (cat) url.searchParams.set("cat", cat);
      else url.searchParams.delete("cat");
        
      // 2. (Opsional tapi disarankan) Reset ke halaman 1 saat ganti kategori
      // Agar jika user ada di page 5 lalu ganti kategori yang datanya sedikit, tidak error/kosong.
      url.searchParams.set("page", 1); 
        
      // 3. Tambahkan hash/anchor agar scroll otomatis ke elemen dengan id="hasil-pencarian"
      url.hash = "hasil-pencarian";
        
      // 4. Reload halaman
      window.location.href = url.toString();
    }

    // Agenda dari PHP
    const eventsByDate = <?= json_encode($eventsByDate, JSON_UNESCAPED_UNICODE); ?>;
    let selectedDate = "<?= $selectedDate ?>";

    function escapeHtml(str) {
      return String(str).replace(/[&<>"']/g, m => ({
        "&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#039;"
      }[m]));
    }

    function renderAgenda(dateStr) {
      const box = document.getElementById("agendaList");
      const list = eventsByDate[dateStr] || [];

      const readable = new Date(dateStr + "T00:00:00").toLocaleDateString("id-ID", {
        weekday: "long", year: "numeric", month: "long", day: "numeric"
      });

      if (list.length === 0) {
        box.innerHTML = `
          <div class="agenda-item">
            <h6>${readable}</h6>
            <p>Tidak ada event di tanggal ini.</p>
          </div>
        `;
        return;
      }

      box.innerHTML = `
        <div class="agenda-item">
          <h6>${readable}</h6>
          <p>${list.length} event ditemukan.</p>
        </div>
        ${list.map(ev => `
          <div class="agenda-item">
            <h6>${escapeHtml(ev.title)}</h6>
            <p><b>Lokasi:</b> ${escapeHtml(ev.location)}</p>
            <p><b>Kategori:</b> ${escapeHtml(ev.category_slug)}</p>
            <p><a href="event_detail.php?id=${ev.id}">Lihat detail</a></p>
          </div>
        `).join("")}
      `;
    }

    document.getElementById("calendarTable").addEventListener("click", (e) => {
      const td = e.target.closest("td[data-date]");
      if (!td) return;

      document.querySelectorAll("#calendarTable td.selected").forEach(x => x.classList.remove("selected"));
      td.classList.add("selected");

      selectedDate = td.dataset.date;
      renderAgenda(selectedDate);
    });

    renderAgenda(selectedDate);
  </script>
</body>
</html>
