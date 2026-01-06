<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Berita 1 - Mahasiswa Polibatam Raih Prestasi</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />

  <style>
    body {
      margin: 0;
      font-family: "Poppins", sans-serif;
      background-color: #fff5dc; /* Warna krem lembut */
      color: #222;
    }

    /* Navbar */
    .navbar-custom {
      background: linear-gradient(to right, #f3b63a, #f5cd6f);
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
      padding: 0.7rem 2rem;
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000;
    }

    .navbar-brand img {
      height: 55px;
      transition: transform 0.3s ease;
    }
    .navbar-brand img:hover {
      transform: scale(1.08);
    }

    .nav-link {
      color: #2c2c2c !important;
      padding: 0.6rem 1.4rem !important;
      transition: 0.3s;
    }
    .nav-link:hover {
      color: #fff !important;
      text-shadow: 0 0 6px rgba(255, 255, 255, 0.4);
    }

    /* Kontainer utama */
    .content-container {
      padding: 130px 20px 80px; /* ruang di atas untuk navbar */
      display: flex;
      justify-content: center;
    }

    /* Layout dua kolom */
    .main-content {
      display: flex;
      flex-wrap: wrap;
      align-items: flex-start;
      gap: 40px;
      max-width: 1100px;
      width: 100%;
    }

    .main-content img {
      flex: 1;
      width: 50%;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      object-fit: cover;
    }

    .content-text {
      flex: 1;
      padding: 10px 20px;
    }

    .content-text h1 {
      font-size: 2rem;
      font-weight: 700;
      color: #2c2c2c;
      margin-bottom: 20px;
    }

    .content-text p {
      font-size: 1rem;
      line-height: 1.7;
      color: #333;
      margin-bottom: 15px;
    }

    .content-text ul {
      padding-left: 20px;
      color: #333;
    }

    .content-text li {
      margin-bottom: 10px;
    }

      /* ======== Tombol bawah ======== */
        .floating-toolbar {
        position: fixed;
        bottom: 25px;
        left: 25px;
        background-color: #e6a730;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 14px;
        border-radius: 25px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        z-index: 1000;
        }

        .floating-toolbar i {
        color: #000;
        font-size: 16px;
        cursor: pointer;
        transition: transform 0.2s ease;
        }

        .floating-toolbar i:hover {
        transform: scale(1.2);
        }


    @media (max-width: 768px) {
      .main-content {
        flex-direction: column;
        text-align: justify;
      }
      .main-content img {
        width: 100%;
      }
      .content-text {
        padding: 0;
      }
    }
  </style>
</head>

<body>
  <!-- Navbar -->
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
                <li class="nav-item"><a class="nav-link" href="../admin/index.php">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="../admin/index.php#kontak">Kontak</a></li>
                <li class="nav-item"><a class="nav-link" href="../admin/Tentang.php">Tentang</a></li>
                <li class="nav-item"><a class="nav-link" href="../admin/tampilanadmin.php">Admin</a></li>
                <li class="nav-item"><a class="nav-link" href="../login.php">Login</a></li>
            </ul>>
      </div>
    </div>
  </nav>

  <!-- Konten -->
  <div class="content-container">
    <div class="main-content">
      <img src="img1.jpeg" alt="Mahasiswa Polibatam di POMNAS" />
      <div class="content-text">
        <h1>Mahasiswa Polibatam Menorehkan Prestasi di Ajang Pekan Olahraga Mahasiswa Nasional (POMNAS) 2025</h1>

        <p>
          SEMARANG - Mahasiswa Politeknik Negeri Batam (Polibatam) kembali menorehkan prestasi membanggakan pada ajang
          Pekan Olahraga Mahasiswa Nasional (POMNAS) XIX Tahun 2025. Dua mahasiswa berhasil meraih medali perunggu pada
          cabang olahraga panahan dan taekwondo (29/09/2025).
        </p>

        <p>Adapun mahasiswa peraih prestasi tersebut adalah:</p>
        <ul>
          <li>
            <b>Syahdan Syahruddin</b> (Program Studi D4 Teknologi Rekayasa Pengelasan & Fabrikasi) – Juara 3 Panahan,
            Eliminasi Nasional Individu Putra
          </li>
          <li>
            <b>Wendy Setiawan</b> (Program Studi Teknologi Rekayasa Konstruksi Perkapalan) – Juara 3 Taekwondo, Kyorugi
            Putra U-58 KG
          </li>
        </ul>

        <p>
          Prestasi ini membuktikan komitmen Polibatam tidak hanya di bidang akademik, tetapi juga dalam pengembangan
          bakat dan minat mahasiswa di bidang non-akademik. Selamat kepada para mahasiswa yang telah berjuang dan
          mengharumkan nama Polibatam di kancah nasional.
        </p>
      </div>
    </div>
        <div class="floating-toolbar">
            <i class="fa-solid fa-message"></i>
            <i class="fa-solid fa-pen"></i>
            <i class="fa-solid fa-ellipsis-vertical"></i>
        </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
