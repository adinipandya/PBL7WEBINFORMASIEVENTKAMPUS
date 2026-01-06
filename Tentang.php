<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tentang | Event Polibatam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKx36l/4sfa96hLyw7bA5y3JdGNg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    /* ===== Gaya Umum ===== */
    body {
      margin: 0;
      font-family: "Poppins", sans-serif;
      background: url("gedungpolibatam.jpg") no-repeat center center fixed;
      background-size: cover;
      color: #222;
    }

    /* ===== Overlay Latar Belakang ===== */
    body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.5);
      backdrop-filter: blur(3px);
      z-index: -1;
    }

    /* ===== Navbar ===== */
    .navbar-custom {
            background: linear-gradient(to right, #f3b63a, #f5cd6f);
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
            padding: 0.7rem 2rem; /* tambah ruang atas-bawah */
            transition: all 0.3s ease-in-out;
        }

        /* Brand logo */
        .navbar-brand img {
            height: 55px;
            transition: transform 0.3s ease;
        }

        .navbar-brand img:hover {
            transform: scale(1.08); /* efek hover lembut di logo */
        }

        /* Menu link */
        .nav-link {
            color: #2c2c2c !important;
            padding: 0.6rem 1.4rem !important;
            letter-spacing: 0.3px;
            font-family: "Poppins", sans-serif;
            font-size: 1.05rem;
            position: relative;
            transition: all 0.3s ease;
        }

        /* Efek garis bawah animasi */
        .nav-link::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%) scaleX(0);
            width: 60%;
            height: 2px;
            background-color: #ffffff;
            border-radius: 2px;
            transition: transform 0.3s ease;
        }

        /* Hover dan aktif */
        .nav-link:hover::after,
        .nav-link:focus::after {
            transform: translateX(-50%) scaleX(1);
        }

        .nav-link:hover {
            color: #ffffff !important;
            text-shadow: 0 0 6px rgba(255,255,255,0.4);
        }

        /* Responsif */
        .navbar-toggler {
            border: none;
            outline: none;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar-nav {
            gap: 5px;
        }

    /* ===== Kontainer Utama ===== */
    .content-container {
      position: relative;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      min-height: 100vh;
      padding: 120px 20px 60px; /* ✅ tambahkan jarak atas agar tidak ketutupan navbar */
      z-index: 1;
      box-sizing: border-box;
    }

    /* ===== Card Utama ===== */
    .content-card {
      position: relative;
      background: rgba(233, 161, 35, 0.85); /* Warna #E9A123 dengan opacity */
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.25);
      padding: 50px 60px;
      max-width: 900px;
      color: #000;
      text-align: justify;
      overflow: hidden;
    }

    /* ===== Logo Samar bacground ===== */
    .content-container::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url("gedung_polibatam.jpg") no-repeat center center;
      background-size: cover; /* agar penuh */
      opacity: 0.25; /* sesuaikan transparansi */
      filter: grayscale(30%) brightness(95%); /* opsional efek lembut */
      z-index: 0;
    }

    .content-card h1, .content-card p {
      position: relative;
      z-index: 1;
    }

    .content-card h1 {
      text-align: center;
      font-size: 2.3rem;
      font-weight: 700;
      margin-bottom: 25px;
    }

    .content-card p {
      line-height: 1.7;
      font-size: 1.05rem;
      margin-bottom: 18px;
    }
  </style>
</head>
<body>

  <!-- Header -->
   <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container-fluid px-4">
      <a class="navbar-brand" href="index.php">
        <img src="logopolibatam.png" alt="Logo Polibatam" class="d-inline-block align-text-top">
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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

  <!-- Konten Tentang -->
  <div class="content-container">
    <div class="content-card">
      <h1>Tentang</h1>
      <p>
        Website Event Polibatam dibuat sebagai pusat informasi kegiatan kampus Politeknik Negeri Batam (Polibatam).
        Platform ini hadir untuk memudahkan mahasiswa, dosen, dan masyarakat dalam mengetahui berbagai kegiatan kampus
        seperti seminar, lokakarya, kompetisi, bazar, hingga festival yang diselenggarakan sepanjang tahun.
        Melalui website ini, Polibatam ingin menghadirkan wadah informasi yang cepat, mudah diakses, dan menarik secara visual.
      </p>
      <p>
        Sebagai institusi pendidikan vokasi, Polibatam berkomitmen menciptakan lingkungan belajar yang aktif, kolaboratif, dan inovatif.
        Setiap kegiatan yang ditampilkan di website ini menjadi bagian penting dalam pengembangan potensi mahasiswa,
        mendorong kreativitas, serta memperkuat hubungan antara dunia akademik dan industri.
        Website ini juga dilengkapi fitur pencarian dan filter acara agar pengguna dapat dengan mudah menemukan kegiatan sesuai minatnya.
      </p>
      <p>
        Website Event Polibatam bukan hanya media informasi, tetapi juga simbol semangat kampus dalam memanfaatkan teknologi digital
        untuk memperluas akses pengetahuan dan kolaborasi. Melalui inisiatif ini, diharapkan setiap mahasiswa dapat terlibat aktif
        dalam berbagai kegiatan kampus, memperkaya pengalaman belajar, serta berkontribusi dalam mewujudkan kampus yang dinamis, kreatif, dan inspiratif.
      </p>
    </div>
  </div>

</body>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</html>
