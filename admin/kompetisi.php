<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seminar Polibatam - Slideshow Tiga Seminar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
               body {
            background: linear-gradient(to bottom right, #fff6da, #fff1c1);
            font-family: 'Poppins', Arial, sans-serif;
            color: #2c2c2c;
            margin: 0;
            padding: 0;
        }

        /* ======== NAVBAR ======== */
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

        /* ======== SEMINAR CARD ======== */
        .seminar-card {
            max-width: 900px;
            margin: 50px auto;
            padding: 40px 60px;
            border-radius: 10px;
            background: none; /* tidak ada background putih */
            box-shadow: none;
        }

        .seminar-card:hover {
            transform: translateY(-3px);
        }

        /* ======== DETAIL ITEM ======== */
        .detail-item {
            margin-bottom: 0.5rem;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
        }

        .detail-item i {
            color: #e89b22;
            font-size: 1.2rem;
            margin-right: 10px;
        }

        /* ======== THEME QUOTE ======== */
        .theme-quote {
            display: block;
            font-size: 1.15rem;
            font-weight: 600;
            margin: 20px 0 15px 0;
            color: #3a2e1f;
            border-left: 4px solid #f1b83b;
            padding-left: 10px;
        }

        /* ======== CAROUSEL CONTROL ======== */
        .carousel-control-prev,
        .carousel-control-next {
            width: 45px;
            height: 45px;
            background-color: rgba(255,255,255,0.85);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            transition: background 0.3s ease;
            position: absolute;
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            background-color: rgba(255, 230, 180, 0.95);
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            filter: invert(30%) sepia(20%) saturate(600%) hue-rotate(180deg);
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

        /* ======== RESPONSIVE ======== */
        @media (max-width: 768px) {
            .seminar-card {
                padding: 30px 25px;
            }
            h1 {
                font-size: 1.6rem;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="logopolibatam.png" alt="Logo Politeknik Negeri Batam" height="60" class="d-inline-block align-text-top">
            </a>
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
    
    <div id="seminarCarousel" class="carousel slide" data-bs-ride="false">
        <div class="carousel-inner">
        
            <div class="carousel-item active">
                <div class="container seminar-card">
                    <h1 class="text-center mb-4 seminar-title fw-bold">Kompetisi Wawasan Bisnis</h1>

                    <p class="mb-3">
                        <strong>Diselenggarakan oleh:</strong> Politeknik Negeri Batam
                    </p>

                    <div class="detail-item align-items-center">
                        <i class="fa-solid fa-calendar-alt"></i>
                        <span>Minggu, 26 Oktober 2025</span>
                    </div>
                    <div class="detail-item align-items-center">
                        <i class="fa-solid fa-clock"></i>
                        <span>08.00 - 16.00 WIB</span>
                    </div>
                    <div class="detail-item align-items-center mb-4">
                        <i class="fa-solid fa-map-marker-alt"></i>
                        <span>Auditorium Politeknik Negeri Batam</span>
                    </div>

                    <em class="theme-quote fw-bold">
                        Tema : “Membangun Jiwa Wirausaha Kreatif dan Kompetitif di Era Digital”
                    </em>

                    <p class="text-justify">
                        Kompetisi Wawasan Bisnis 2025 merupakan ajang tahunan yang dirancang untuk mengasah kemampuan analisis, strategi, dan kreativitas mahasiswa dalam
                        menghadapi tantangan dunia bisnis modern. Peserta akan berkompetisi dalam tim untuk merancang ide bisnis inovatif yang tidak hanya menguntungkan secara ekonomi,
                        tetapi juga memberikan dampak sosial yang positif.
                        Selama dua hari pelaksanaan, peserta akan mengikuti sesi business case challenge, pitching presentation, dan mentoring bersama para profesional di bidang kewirausahaan dan industri kreatif. 
                        Setiap tim akan mempresentasikan ide mereka di depan dewan juri yang terdiri dari dosen, pelaku bisnis, dan investor muda.
                    </p>

                    <p class="mt-4">
                        <strong>Lokakarya ini terbuka bagi seluruh Mahasiswa Polibatam dan peserta umum.</strong>
                    </p>
                </div>
            </div>
        
            <div class="carousel-item">
                <div class="container seminar-card">
                    <h1 class="text-center mb-4 seminar-title fw-bold">Kompetisi Inovasi Teknologi Mahasiswa</h1>

                    <p class="mb-3">
                        <strong>Diselenggarakan oleh:</strong> Politeknik Negeri Batam
                    </p>

                    <div class="detail-item align-items-center">
                        <i class="fa-solid fa-calendar-alt"></i>
                        <span>Sabtu, 15 November 2025</span>
                    </div>
                    <div class="detail-item align-items-center">
                        <i class="fa-solid fa-clock"></i>
                        <span>08.00 - 17.00 WIB</span>
                    </div>
                    <div class="detail-item align-items-center mb-4">
                        <i class="fa-solid fa-map-marker-alt"></i>
                        <span>Auditorium Politeknik Negeri Batam</span>
                    </div>

                    <em class="theme-quote fw-bold">
                        Tema : “Inovasi Digital untuk Solusi Berkelanjutan di Era Modern”
                    </em>

                    <p class="text-justify">
                        Kompetisi Inovasi Teknologi Mahasiswa 2025 merupakan ajang untuk menumbuhkan semangat inovasi dan kreativitas mahasiswa
                        dalam menciptakan solusi teknologi yang berorientasi pada keberlanjutan dan efisiensi. Peserta akan ditantang untuk merancang
                        dan mempresentasikan produk atau aplikasi berbasis teknologi yang dapat memberikan manfaat nyata bagi masyarakat. 
                        Selain kompetisi utama, peserta juga akan mendapatkan sesi workshop dan mentoring dari praktisi industri dan dosen berpengalaman. Karya terbaik akan dipamerkan
                        pada sesi Tech Expo dan dinilai oleh panel juri dari kalangan akademisi dan profesional teknologi.
                    </p>

                    <p class="mt-4">
                        <strong>Kompetisi ini terbuka bagi seluruh Mahasiswa Polibatam dan peserta umum..</strong>
                    </p>
                </div>
            </div>
            
            <div class="carousel-item">
                <div class="container seminar-card">
                    <h1 class="text-center mb-4 seminar-title fw-bold">Kompetisi Inovasi Mahasiswa Polibatam 2025</h1>

                    <p class="mb-3">
                        <strong>Diselenggarakan oleh:</strong> Politeknik Negeri Batam
                    </p>

                    <div class="detail-item align-items-center">
                        <i class="fa-solid fa-calendar-alt"></i>
                        <span>Sabtu, 16 November 2025</span>
                    </div>
                    <div class="detail-item align-items-center">
                        <i class="fa-solid fa-clock"></i>
                        <span>08.00 - 17.00 WIB</span>
                    </div>
                    <div class="detail-item align-items-center mb-4">
                        <i class="fa-solid fa-map-marker-alt"></i>
                        <span>Gedung Utama Politeknik Negeri Batam</span>
                    </div>

                    <h4 class="mt-4 mb-3 fw-bold">Juri:</h4>
                    
                    <p class="mb-1"><strong>Prof. Dr. Ir. Siti Aisyah, M.Sc.</strong></p>
                    <p class="mb-3">Pakar Inovasi Teknologi Nasional</p>
                    
                    <p class="mb-1"><strong>Ir. Heru Susanto, M.Kom.</strong></p>
                    <p class="mb-4">Ketua Jurusan Teknik Informatika Polibatam</p>

                    <em class="theme-quote fw-bold">
                        Tema: "Menciptakan Solusi Digital Berkelanjutan untuk Industri 4.0"
                    </em>

                    <p class="text-justify">
                        Kompetisi ini adalah ajang tahunan bagi mahasiswa Polibatam untuk memamerkan proyek inovatif 
                        mereka di bidang teknologi dan rekayasa. Kategori yang dilombakan meliputi aplikasi mobile, IoT (Internet of Things), dan embedded system.
                    </p>
                    <p>
                    Peserta akan mempresentasikan karya mereka di hadapan juri ahli dan berkesempatan memenangkan hadiah menarik, serta pendanaan untuk pengembangan proyek lebih lanjut.
                    </p>
                    <p class="mt-4">
                        <strong>Terbuka untuk tim mahasiswa Polibatam. Pendaftaran ditutup 10 November 2025.</strong>
                    </p>
                </div>
            </div>

        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#seminarCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        
        <button class="carousel-control-next" type="button" data-bs-target="#seminarCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
        
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#seminarCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#seminarCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#seminarCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>

        <div class="floating-toolbar">
            <i class="fa-solid fa-message"></i>
            <i class="fa-solid fa-pen"></i>
            <i class="fa-solid fa-ellipsis-vertical"></i>
        </div>

        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>