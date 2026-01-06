<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Politeknik Negeri Batam (Polibatam) Raih Prestasi di KMIPN VII 2025</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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
                <li class="nav-item"><a class="nav-link" href="../admin/index.php">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="../admin/index.php#kontak">Kontak</a></li>
                <li class="nav-item"><a class="nav-link" href="../admin/Tentang.php">Tentang</a></li>
                <li class="nav-item"><a class="nav-link" href="../admin/tampilanadmin.php">Admin</a></li>
                <li class="nav-item"><a class="nav-link" href="../login.php">Login</a></li>
            </ul>
      </div>
    </div>
  </nav>

  <!-- Konten -->
  <div class="content-container">
    <div class="main-content">
      <div class="image-column">
        <img src="img3.jpg" alt="Prestasi Tim Polibatam di KMIPN VII" />
        <img src="img3.1.png" alt="Suasana Penghargaan KMIPN VII" />
      </div>

      <div class="content-text">
        <h1>Politeknik Negeri Batam (Polibatam) kembali menorehkan prestasi membanggakan di ajang Kompetisi Mahasiswa Informatika Politeknik Nasional (KMIPN) VII tahun 2025</h1>

        <p>Daftar Peraih Juara KMIPN VII dari Polibatam:</p>
        <ul>
          <li>Tim Digidou – Juara 1 Kategori Animasi (Pembimbing: Amirul Mu’minin, S.Ds., M.Ds.)</li>
          <li>Tim Titik Tengah – Juara 2 Kategori Animasi (Pembimbing: Amirul Mu’minin, S.Ds., M.Ds.)</li>
          <li>Tim Fii Amanillah – Juara 2 Kategori Hackathon (Pembimbing: Supardianto)</li>
          <li>Tim Pocket Lite – Juara 2 Kategori Aplikasi Permainan (Pembimbing: Amirul Mu’minin, S.Ds., M.Ds.)</li>
          <li>Tim PBL-IF-11 – Juara 1 Art & Visualization (Pembimbing: Liony Lumombo, S.ST., M.Ides.)</li>
          <li>Tim 404’s Not Found – Juara 1 Prototype (Pembimbing: Dwi Amalia Purnamasari, S.T., M.Cs.)</li>
          <li>Tim Sintaks Juang – Juara 2 Dampak Layanan Publik (Pembimbing: Dwi Amalia Purnamasari, S.T., M.Cs.)</li>
          <li>Tim hahaha – Juara 3 Team Collaboration (Pembimbing: Festy Winda Sari, S.Tr.Kom., M.Sc.)</li>
        </ul>

        <p>Keberhasilan ini menegaskan kualitas akademik dan kreativitas mahasiswa Polibatam serta menjadi inspirasi bagi mahasiswa lain untuk terus berprestasi dalam bidang informatika dan teknologi.</p>
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
