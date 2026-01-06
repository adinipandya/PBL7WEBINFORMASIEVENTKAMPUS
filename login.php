<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | Event Polibatam</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    /* ====== Gaya Umum ====== */
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
    }

    /* ===== Navbar ===== */
    .navbar-custom {
      background: linear-gradient(to right, #f3b63a, #f5cd6f);
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
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
      letter-spacing: 0.3px;
      font-family: "Poppins", sans-serif;
      font-size: 1.05rem;
      position: relative;
      transition: all 0.3s ease;
    }

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

    .nav-link:hover::after {
      transform: translateX(-50%) scaleX(1);
    }

    .nav-link:hover {
      color: #ffffff !important;
      text-shadow: 0 0 6px rgba(255, 255, 255, 0.4);
    }

    /* ===== Login Container ===== */
    .login-container {
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding-top: 100px; /* jarak dari navbar */
    }

    .login-box {
      background: rgba(233, 161, 35, 0.9);
      padding: 40px 50px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.25);
      width: 340px;
      text-align: center;
    }

    .login-box h2 {
      color: #000;
      margin-bottom: 25px;
      font-weight: 700;
    }

    .login-box label {
      display: block;
      text-align: left;
      font-weight: 600;
      color: #000;
      margin-bottom: 6px;
    }

    .login-box input {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 6px;
      margin-bottom: 15px;
      outline: none;
    }

    .login-box button {
      width: 100%;
      background-color: #e67e00;
      color: #fff;
      border: none;
      padding: 10px;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .login-box button:hover {
      background-color: #cc6f00;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container-fluid px-4">
      <a class="navbar-brand" href="#">
        <img src="logopolibatam.png" alt="Logo Polibatam" />
      </a>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
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

  <!-- Form Login -->
  <div class="login-container">
    <div class="login-box">
      <h2>Login</h2>
      <form action="auth/login_process.php" method="POST">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Masukkan username" required />

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Masukkan password" required />

        <button type="submit">Login</button>

        <!-- pesan error dari backend (opsional) -->
        <?php if (isset($_GET["err"])): ?>
          <div class="alert alert-danger mt-3 mb-0">
            <?php
              $err = $_GET["err"];
              if ($err === "empty") echo "Username dan password wajib diisi.";
              elseif ($err === "invalid") echo "Username atau password salah!";
              elseif ($err === "inactive") echo "Akun nonaktif.";
              else echo "Terjadi kesalahan.";
            ?>
          </div>
        <?php endif; ?>
      </form>

    </div>
  </div>

  <script>
    // Login statis (admin/admin)
    document.getElementById("loginForm").addEventListener("submit", function (e) {
      e.preventDefault();
      const username = document.getElementById("username").value.trim();
      const password = document.getElementById("password").value.trim();

      if (username === "admin" && password === "admin") {
        window.location.href = "./admin/index.php"; // ✅ redirect ke beranda
      } else {
        alert("Username atau password salah!");
      }
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
