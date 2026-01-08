<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | Event Polibatam</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

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
      inset: 0;
      background: rgba(255, 255, 255, 0.5);
      backdrop-filter: blur(3px);
      z-index: -1;
    }

    .navbar-custom {
      background: linear-gradient(to right, #f3b63a, #f5cd6f);
      box-shadow: 0 3px 8px rgba(0,0,0,0.1);
      padding: 0.7rem 2rem;
    }

    .navbar-brand img {
      height: 55px;
      transition: transform .3s ease;
    }

    .navbar-brand img:hover {
      transform: scale(1.08);
    }

    .nav-link {
      color:#2c2c2c !important;
      padding:0.6rem 1.4rem !important;
      font-size:1.05rem;
      position:relative;
      transition:.3s;
    }

    .nav-link::after{
      content:"";
      position:absolute;
      bottom:0;
      left:50%;
      transform:translateX(-50%) scaleX(0);
      width:60%;
      height:2px;
      background:#fff;
      transition:.3s;
    }

    .nav-link:hover::after{
      transform:translateX(-50%) scaleX(1);
    }

    .nav-link:hover{
      color:#fff !important;
      text-shadow:0 0 6px rgba(255,255,255,.4);
    }

    .login-container{
      min-height:100vh;
      display:flex;
      justify-content:center;
      align-items:center;
      padding-top:100px;
    }

    .login-box{
      background: rgba(233, 161, 35, 0.9);
      padding:40px 50px;
      border-radius:15px;
      box-shadow:0 10px 30px rgba(0,0,0,.25);
      width:340px;
      text-align:center;

      opacity:0;
      transform:translateY(40px);
      animation:fadeUp .9s ease forwards;
    }

    @keyframes fadeUp {
      to {
        opacity:1;
        transform:translateY(0);
      }
    }

    .login-box h2{
      font-weight:700;
      margin-bottom:25px;
    }

    .login-box label{
      display:block;
      text-align:left;
      font-weight:600;
      margin-bottom:6px;
    }

    .login-box input{
      width:100%;
      padding:10px;
      border:none;
      border-radius:6px;
      margin-bottom:15px;
      outline:none;
    }

    .login-box button{
      width:100%;
      background:#e67e00;
      color:#fff;
      border:none;
      padding:10px;
      border-radius:6px;
      font-weight:bold;
      transition:.3s;
    }

    .login-box button:hover{
      background:#cc6f00;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container-fluid px-4">
      <a class="navbar-brand" href="./index.php">
        <img src="logopolibatam.png" alt="Logo Polibatam">
      </a>

      <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="./index.php">Beranda</a></li>
          <li class="nav-item"><a class="nav-link" href="./index.php#kontak">Kontak</a></li>
          <li class="nav-item"><a class="nav-link" href="./Tentang.php">Tentang</a></li>
          <li class="nav-item"><a class="nav-link" href="./login.php">Login</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="login-container">
    <div class="login-box">
      <h2>Login</h2>

      <form action="auth/login_process.php" method="POST">
        <label>Username</label>
        <input type="text" name="username" placeholder="Masukkan username" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Masukkan password" required>

        <button type="submit">Login</button>

        <?php if (isset($_GET["err"])): ?>
          <div class="alert alert-danger mt-3 mb-0">
            Username atau password salah
          </div>
        <?php endif; ?>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
