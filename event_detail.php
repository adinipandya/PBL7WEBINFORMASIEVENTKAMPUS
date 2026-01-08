<?php
require_once __DIR__ . "/config/db.php";

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($id <= 0) {
  header("Location: index.php");
  exit;
}

/** ambil event utama */
$sql = "
  SELECT
    e.id, e.category_id, e.title, e.description, e.event_date, e.start_time, e.end_time,
    e.location, e.contact, e.attachment,
    c.slug AS category_slug, c.name AS category_name
  FROM events e
  JOIN event_categories c ON c.id = e.category_id
  WHERE e.id = ? AND e.status = 'published'
  LIMIT 1
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();

if (!$event) {
  header("Location: index.php");
  exit;
}

/** helper tanggal Indonesia */
function tanggalIndo($dateYmd) {
  $dateReadable = date("l, d F Y", strtotime($dateYmd));
  $hariMap = [
    "Sunday"=>"Minggu","Monday"=>"Senin","Tuesday"=>"Selasa","Wednesday"=>"Rabu",
    "Thursday"=>"Kamis","Friday"=>"Jumat","Saturday"=>"Sabtu"
  ];
  $bulanMap = [
    "January"=>"Januari","February"=>"Februari","March"=>"Maret","April"=>"April",
    "May"=>"Mei","June"=>"Juni","July"=>"Juli","August"=>"Agustus",
    "September"=>"September","October"=>"Oktober","November"=>"November","December"=>"Desember"
  ];
  $dateReadable = strtr($dateReadable, $hariMap);
  $dateReadable = strtr($dateReadable, $bulanMap);
  return $dateReadable;
}

function jamRange($start, $end) {
  if (empty($start) && empty($end)) return "";
  $s = $start ? substr($start, 0, 5) : "";
  $e = $end ? substr($end, 0, 5) : "";
  if ($s && $e) return $s . " - " . $e . " WIB";
  if ($s) return $s . " WIB";
  if ($e) return "sampai " . $e . " WIB";
  return "";
}

$catSlug = $event["category_slug"];

/** ambil detail sesuai kategori */
$detail = [];
$listA = []; // speakers/instructors/judges/lineups
$listB = []; // requirements/rundowns

if ($catSlug === "seminar") {
  $q = $conn->prepare("SELECT theme, quota, registration_link FROM event_seminar_details WHERE event_id = ?");
  $q->bind_param("i", $id);
  $q->execute();
  $detail = $q->get_result()->fetch_assoc() ?: [];

  $q = $conn->prepare("SELECT name, title_role FROM event_speakers WHERE event_id = ? ORDER BY sort_order ASC, id ASC");
  $q->bind_param("i", $id);
  $q->execute();
  $listA = $q->get_result()->fetch_all(MYSQLI_ASSOC);

} elseif ($catSlug === "lokakarya") {
  $q = $conn->prepare("SELECT theme, tools_required, quota, registration_link FROM event_workshop_details WHERE event_id = ?");
  $q->bind_param("i", $id);
  $q->execute();
  $detail = $q->get_result()->fetch_assoc() ?: [];

  $q = $conn->prepare("SELECT name, title_role FROM event_instructors WHERE event_id = ? ORDER BY sort_order ASC, id ASC");
  $q->bind_param("i", $id);
  $q->execute();
  $listA = $q->get_result()->fetch_all(MYSQLI_ASSOC);

} elseif ($catSlug === "kompetisi") {
  $q = $conn->prepare("SELECT theme, prize, registration_deadline, rules FROM event_competition_details WHERE event_id = ?");
  $q->bind_param("i", $id);
  $q->execute();
  $detail = $q->get_result()->fetch_assoc() ?: [];

  $q = $conn->prepare("SELECT name, title_role FROM event_judges WHERE event_id = ? ORDER BY sort_order ASC, id ASC");
  $q->bind_param("i", $id);
  $q->execute();
  $listA = $q->get_result()->fetch_all(MYSQLI_ASSOC);

  $q = $conn->prepare("SELECT requirement_text FROM event_competition_requirements WHERE event_id = ? ORDER BY sort_order ASC, id ASC");
  $q->bind_param("i", $id);
  $q->execute();
  $listB = $q->get_result()->fetch_all(MYSQLI_ASSOC);

} elseif ($catSlug === "festival") {
  $q = $conn->prepare("SELECT theme, is_ticketed, ticket_price, area_note FROM event_festival_details WHERE event_id = ?");
  $q->bind_param("i", $id);
  $q->execute();
  $detail = $q->get_result()->fetch_assoc() ?: [];

  $q = $conn->prepare("SELECT item_name, item_note FROM event_festival_lineups WHERE event_id = ? ORDER BY sort_order ASC, id ASC");
  $q->bind_param("i", $id);
  $q->execute();
  $listA = $q->get_result()->fetch_all(MYSQLI_ASSOC);

  $q = $conn->prepare("SELECT start_time, end_time, activity FROM event_festival_rundowns WHERE event_id = ? ORDER BY sort_order ASC, id ASC");
  $q->bind_param("i", $id);
  $q->execute();
  $listB = $q->get_result()->fetch_all(MYSQLI_ASSOC);
}

$dateReadable = tanggalIndo($event["event_date"]);
$timeReadable = jamRange($event["start_time"], $event["end_time"]);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($event["title"]) ?> | Event Polibatam</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  <style>
    body{
      background: linear-gradient(to bottom right, #fff6da, #fff1c1);
      font-family: 'Poppins', Arial, sans-serif;
      color: #2c2c2c;
      margin:0; padding:0;
    }
    .navbar-custom{
      background: linear-gradient(to right, #f3b63a, #f5cd6f);
      box-shadow: 0 3px 8px rgba(0,0,0,0.1);
      padding: 0.7rem 2rem;
    }
    .navbar-brand img{ height:55px; transition:transform .3s ease; }
    .navbar-brand img:hover{ transform:scale(1.08); }
    .nav-link{
      color:#2c2c2c !important;
      padding:0.6rem 1.4rem !important;
      font-size:1.05rem;
      position:relative;
      transition:.3s;
    }
    .nav-link::after{
      content:"";
      position:absolute;
      bottom:0; left:50%;
      transform:translateX(-50%) scaleX(0);
      width:60%; height:2px;
      background:#fff;
      border-radius:2px;
      transition:.3s;
    }
    .nav-link:hover::after{ transform:translateX(-50%) scaleX(1); }
    .nav-link:hover{ color:#fff !important; text-shadow:0 0 6px rgba(255,255,255,0.4); }

    .event-card{
      max-width: 980px;
      margin: 34px auto;
      padding: 34px 44px;
      border-radius: 14px;
      background: rgba(255,255,255,0.38);
      box-shadow: 0 10px 28px rgba(0,0,0,0.12);
      backdrop-filter: blur(7px);
    }
    .badge-cat{
      display:inline-block;
      background: rgba(230,126,0,0.15);
      border: 1px solid rgba(230,126,0,0.25);
      color:#7a3f00;
      padding:6px 10px;
      border-radius:999px;
      font-weight:700;
      font-size:.9rem;
    }
    .detail-item{
      margin-bottom:.55rem;
      font-size:1.02rem;
      display:flex;
      align-items:flex-start;
      gap:10px;
    }
    .detail-item i{
      color:#e89b22;
      font-size:1.15rem;
      width:22px;
      text-align:center;
      padding-top:2px;
    }
    .section-title{
      margin-top:18px;
      font-weight:800;
      font-size:1.1rem;
      color:#3a2e1f;
      border-left: 4px solid #f1b83b;
      padding-left:10px;
    }
    .soft-box{
      background: rgba(255,255,255,0.65);
      border: 1px solid rgba(0,0,0,0.06);
      border-radius: 12px;
      padding: 14px 14px;
      margin-top:10px;
    }
    .list-clean{ margin:0; padding-left:18px; }
    .list-clean li{ margin:6px 0; }
    .pill{
      display:inline-block;
      padding:5px 10px;
      border-radius:999px;
      background:rgba(0,0,0,0.05);
      font-size:.9rem;
      font-weight:700;
    }
    @media (max-width:768px){
      .event-card{ padding: 24px 18px; margin: 18px auto; }
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
      <a class="navbar-brand" href="./index.php">
        <img src="logopolibatam.png" alt="Logo Polibatam">
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

  <div class="container event-card">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
      <span class="badge-cat"><?= htmlspecialchars($event["category_name"]) ?></span>
      <a class="btn btn-sm btn-outline-dark" href="index.php">
        <i class="fa-solid fa-arrow-left"></i> Kembali
      </a>
    </div>

    <h1 class="text-center mb-4 fw-bold"><?= htmlspecialchars($event["title"]) ?></h1>

    <div class="row g-3">
      <div class="col-lg-6">
        <div class="detail-item">
          <i class="fa-solid fa-calendar-days"></i>
          <div>
            <div><?= htmlspecialchars($dateReadable) ?></div>
            <?php if ($timeReadable): ?>
              <div class="text-muted"><?= htmlspecialchars($timeReadable) ?></div>
            <?php endif; ?>
          </div>
        </div>

        <div class="detail-item">
          <i class="fa-solid fa-location-dot"></i>
          <div><?= htmlspecialchars($event["location"]) ?></div>
        </div>

        <?php if (!empty($event["contact"])): ?>
          <div class="detail-item">
            <i class="fa-solid fa-phone"></i>
            <div><?= htmlspecialchars($event["contact"]) ?></div>
          </div>
        <?php endif; ?>

        <?php if (!empty($event["attachment"])): ?>
          <div class="detail-item">
            <i class="fa-solid fa-paperclip"></i>
            <div style="white-space:pre-line;"><?= htmlspecialchars($event["attachment"]) ?></div>
          </div>
        <?php endif; ?>
      </div>

      <div class="col-lg-6">
        <?php if (!empty($detail["theme"])): ?>
          <div class="soft-box">
            <div class="pill mb-2"><i class="fa-solid fa-bolt"></i> Tema</div>
            <div class="fw-bold"><?= htmlspecialchars($detail["theme"]) ?></div>
          </div>
        <?php endif; ?>

        <?php if (isset($detail["quota"]) && $detail["quota"] !== null && $detail["quota"] !== ""): ?>
          <div class="soft-box">
            <div class="pill mb-2"><i class="fa-solid fa-users"></i> Kuota</div>
            <div class="fw-bold"><?= (int)$detail["quota"] ?> peserta</div>
          </div>
        <?php endif; ?>

        <?php if (!empty($detail["registration_link"])): ?>
          <div class="soft-box">
            <div class="pill mb-2"><i class="fa-solid fa-link"></i> Pendaftaran</div>
            <a class="fw-bold" href="<?= htmlspecialchars($detail["registration_link"]) ?>" target="_blank" rel="noopener">
              <?= htmlspecialchars($detail["registration_link"]) ?>
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- SECTION: kategori spesifik -->
    <?php if ($catSlug === "seminar"): ?>
      <?php if (!empty($listA)): ?>
        <div class="section-title mt-4">Pembicara</div>
        <div class="soft-box">
          <ul class="list-clean">
            <?php foreach ($listA as $sp): ?>
              <li>
                <b><?= htmlspecialchars($sp["name"]) ?></b>
                <?php if (!empty($sp["title_role"])): ?>
                  <div class="text-muted"><?= htmlspecialchars($sp["title_role"]) ?></div>
                <?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

    <?php elseif ($catSlug === "lokakarya"): ?>
      <?php if (!empty($detail["tools_required"])): ?>
        <div class="section-title mt-4">Peralatan yang dibutuhkan</div>
        <div class="soft-box" style="white-space:pre-line;"><?= htmlspecialchars($detail["tools_required"]) ?></div>
      <?php endif; ?>

      <?php if (!empty($listA)): ?>
        <div class="section-title mt-4">Instruktur</div>
        <div class="soft-box">
          <ul class="list-clean">
            <?php foreach ($listA as $ins): ?>
              <li>
                <b><?= htmlspecialchars($ins["name"]) ?></b>
                <?php if (!empty($ins["title_role"])): ?>
                  <div class="text-muted"><?= htmlspecialchars($ins["title_role"]) ?></div>
                <?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

    <?php elseif ($catSlug === "kompetisi"): ?>
      <?php if (!empty($detail["prize"])): ?>
        <div class="section-title mt-4">Hadiah</div>
        <div class="soft-box"><?= htmlspecialchars($detail["prize"]) ?></div>
      <?php endif; ?>

      <?php if (!empty($detail["registration_deadline"])): ?>
        <div class="section-title mt-4">Batas pendaftaran</div>
        <div class="soft-box"><?= htmlspecialchars(tanggalIndo($detail["registration_deadline"])) ?></div>
      <?php endif; ?>

      <?php if (!empty($listA)): ?>
        <div class="section-title mt-4">Juri</div>
        <div class="soft-box">
          <ul class="list-clean">
            <?php foreach ($listA as $j): ?>
              <li>
                <b><?= htmlspecialchars($j["name"]) ?></b>
                <?php if (!empty($j["title_role"])): ?>
                  <div class="text-muted"><?= htmlspecialchars($j["title_role"]) ?></div>
                <?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if (!empty($listB)): ?>
        <div class="section-title mt-4">Syarat</div>
        <div class="soft-box">
          <ul class="list-clean">
            <?php foreach ($listB as $req): ?>
              <li><?= htmlspecialchars($req["requirement_text"]) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if (!empty($detail["rules"])): ?>
        <div class="section-title mt-4">Aturan</div>
        <div class="soft-box" style="white-space:pre-line;"><?= htmlspecialchars($detail["rules"]) ?></div>
      <?php endif; ?>

    <?php elseif ($catSlug === "festival"): ?>
      <?php if (isset($detail["is_ticketed"])): ?>
        <div class="section-title mt-4">Tiket</div>
        <div class="soft-box">
          <?php if ((int)$detail["is_ticketed"] === 1): ?>
            <b>Berbayar</b>
            <?php if (!empty($detail["ticket_price"])): ?>
              <div class="text-muted">Harga: Rp <?= number_format((float)$detail["ticket_price"], 0, ",", ".") ?></div>
            <?php endif; ?>
          <?php else: ?>
            <b>Gratis</b>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($detail["area_note"])): ?>
        <div class="section-title mt-4">Catatan</div>
        <div class="soft-box"><?= htmlspecialchars($detail["area_note"]) ?></div>
      <?php endif; ?>

      <?php if (!empty($listA)): ?>
        <div class="section-title mt-4">Lineup / Kegiatan</div>
        <div class="soft-box">
          <ul class="list-clean">
            <?php foreach ($listA as $li): ?>
              <li>
                <b><?= htmlspecialchars($li["item_name"]) ?></b>
                <?php if (!empty($li["item_note"])): ?>
                  <div class="text-muted"><?= htmlspecialchars($li["item_note"]) ?></div>
                <?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if (!empty($listB)): ?>
        <div class="section-title mt-4">Rundown</div>
        <div class="soft-box">
          <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
              <thead>
                <tr>
                  <th style="width:150px;">Waktu</th>
                  <th>Kegiatan</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($listB as $rd): ?>
                  <?php
                    $s = $rd["start_time"] ? substr($rd["start_time"],0,5) : "";
                    $e = $rd["end_time"] ? substr($rd["end_time"],0,5) : "";
                    $w = trim(($s ? $s : "") . ($s && $e ? " - " : "") . ($e ? $e : ""));
                  ?>
                  <tr>
                    <td><?= htmlspecialchars($w ?: "-") ?></td>
                    <td><?= htmlspecialchars($rd["activity"]) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <!-- DESKRIPSI UMUM -->
    <div class="section-title mt-4">Deskripsi</div>
    <div class="soft-box" style="white-space:pre-line;"><?= htmlspecialchars($event["description"]) ?></div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
