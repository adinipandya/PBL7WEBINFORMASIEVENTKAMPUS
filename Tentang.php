<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tentang | Event Polibatam</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  <style>
    body {
      margin: 0;
      font-family: "Poppins", sans-serif;
      background: url("gedung_polibatam.jpg") no-repeat center center fixed;
      background-size: cover;
      color: #222;
    }

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
      animation: fadeInBg 1.2s ease forwards;
    }

    .navbar-custom {
      background: linear-gradient(to right, #f3b63a, #f5cd6f);
      box-shadow: 0 3px 8px rgba(0,0,0,0.1);
      padding: 0.7rem 2rem;
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
      font-size: 1.05rem;
      position: relative;
      transition: 0.3s;
    }

    .nav-link::after {
      content: "";
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%) scaleX(0);
      width: 60%;
      height: 2px;
      background: #fff;
      transition: 0.3s;
    }

    .nav-link:hover::after {
      transform: translateX(-50%) scaleX(1);
    }

    .nav-link:hover {
      color: #fff !important;
    }

    .content-container {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 120px 20px 60px;
    }

    .content-card {
      background: rgba(233, 161, 35, 0.88);
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.25);
      padding: 50px 60px;
      max-width: 900px;
      text-align: justify;
      animation: fadeUp 1s ease forwards;
    }

    .content-card h1 {
      text-align: center;
      font-size: 2.3rem;
      font-weight: 700;
      margin-bottom: 25px;
      animation: fadeUp 0.8s ease forwards;
    }

    .content-card p {
      line-height: 1.7;
      font-size: 1.05rem;
      margin-bottom: 18px;
      opacity: 0;
      animation: fadeUp 0.9s ease forwards;
    }

    .content-card p:nth-of-type(1) { animation-delay: 0.3s; }
    .content-card p:nth-of-type(2) { animation-delay: 0.5s; }
    .content-card p:nth-of-type(3) { animation-delay: 0.7s; }

    @keyframes fadeUp {
      from {
        opacity: 0;
        transform: translateY(40px) scale(0.96);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    @keyframes fadeInBg {
      from { opacity: 0; }
      to { opacity: 1; }
    }
  </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
  <div class="container-fluid px-4">
    <a class="navbar-brand" href="index.php">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
