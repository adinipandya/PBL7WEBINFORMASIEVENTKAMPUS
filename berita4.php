<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Politeknik Negeri Batam (Polibatam) Sukses Menyelenggarakan Polibatam Fair 2025</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />

  <style>
    body {
      margin: 0;
      font-family: "Poppins", sans-serif;
      background-color: #fff5dc;
      color: #222;
    }

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

    .content-container {
      padding: 130px 20px 80px;
      display: flex;
      justify-content: center;
    }

    .main-content {
      display: flex;
      flex-wrap: wrap;
      gap: 40px;
      max-width: 1100px;
      width: 100%;
    }

    .image-column {
      display: flex;
      flex-direction: column;
      gap: 20px;
      flex: 1;
    }

    .image-column img {
      width: 100%;
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

    @media (max-width: 768px) {
      .main-content {
        flex-direction: column;
        text-align: justify;
      }
      .image-column {
        flex-direction: row;
        gap: 10px;
      }
      .image-column img {
        width: 50%;
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
          <li class="nav-item"><a class="nav-link" href="./index.php">Beranda</a></li>
          <li class="nav-item"><a class="nav-link" href="./index.php#kontak">Kontak</a></li>
          <li class="nav-item"><a class="nav-link" href="./Tentang.php">Tentang</a></li>
          <li class="nav-item"><a class="nav-link" href="./login.php">Login</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Konten -->
  <div class="content-container">
    <div class="main-content">
      <div class="image-column">
        <img src="img4.jpg" alt="Suasana Polibatam Fair 2025" />
        <img src="img4.1.png" alt="Kegiatan Polibatam Fair 2025" />
      </div>

      <div class="content-text">
        <h1>Politeknik Negeri Batam (Polibatam) sukses menyelenggarakan Polibatam Fair 2025 pada 3–21 Agustus 2025 dengan tema “Createch Ignite Innovation” dan judul “Unlocking Creativity & Technology Together.”</h1>

        <p>Ajang tahunan ini menjadi wadah mahasiswa untuk menunjukkan bakat, kreativitas, dan inovasi sekaligus memperkuat sinergi antar Organisasi Mahasiswa (Ormawa).</p>

        <p>Tahun ini, Polibatam Fair menghadirkan puluhan perlombaan akademik dan non-akademik, diikuti ratusan peserta SMA/SMK dan mahasiswa dari berbagai daerah. Rangkaian kegiatan meliputi Ormawa Expo, Mini Stage hiburan, Bazar UMKM, serta berbagai kompetisi unggulan hingga Malam Puncak yang meriah dengan bintang tamu nasional.</p>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
