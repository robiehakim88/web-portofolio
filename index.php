<?php
require_once 'config.php';

// Ambil tema yang dipilih
 $theme_query = "SELECT theme_name FROM settings ORDER BY id DESC LIMIT 1";
 $theme_result = mysqli_query($conn, $theme_query);
 $theme_row = mysqli_fetch_assoc($theme_result);
 $current_theme = $theme_row ? $theme_row['theme_name'] : 'theme1';

// Ambil data profil
 $profile_query = "SELECT * FROM profile ORDER BY id DESC LIMIT 1";
 $profile_result = mysqli_query($conn, $profile_query);
 $profile = mysqli_fetch_assoc($profile_result);

// Ambil variabel nama dan lokasi
 $full_name = $profile ? $profile['full_name'] : 'Mohammad Najmudin';
 $location = $profile ? $profile['location'] : 'SMK Negeri 1 Tanjunganom';
 $title = $profile ? $profile['title'] : 'S.Kom., M.Pd';
 $description = $profile ? $profile['description'] : 'Guru Teknik Komputer dan Jaringan di SMK Negeri 1 Tanjunganom — menghadirkan pembelajaran mendalam, proyek nyata, dan asesmen kompetensi berbasis skill passport.';

// Ambil data keahlian
 $skills_query = "SELECT * FROM skills ORDER BY id ASC";
 $skills_result = mysqli_query($conn, $skills_query);

// Ambil data pencapaian
 $achievements_query = "SELECT * FROM achievements ORDER BY year DESC";
 $achievements_result = mysqli_query($conn, $achievements_query);

// Ambil data proyek
 $projects_query = "SELECT * FROM projects ORDER BY year DESC";
 $projects_result = mysqli_query($conn, $projects_query);

// Ambil data mentoring
 $mentoring_query = "SELECT * FROM mentoring ORDER BY year DESC";
 $mentoring_result = mysqli_query($conn, $mentoring_query);

// Ambil data organisasi
 $organizations_query = "SELECT * FROM organizations ORDER BY id ASC";
 $organizations_result = mysqli_query($conn, $organizations_query);

// Definisi tema
 $themes = [
    'theme1' => [
        'primary-900' => '#0277BD',
        'primary-700' => '#0288D1',
        'primary-500' => '#03A9F4',
        'primary-300' => '#4FC3F7',
        'primary-100' => '#B3E5FC',
        'accent' => '#26C6DA',
        'muted' => '#607D8B',
        'bg' => '#E1F5FE',
        'gradient-primary' => 'linear-gradient(135deg, #03A9F4 0%, #0288D1 100%)',
        'gradient-accent' => 'linear-gradient(135deg, #26C6DA 0%, #03A9F4 100%)',
        'hero-style' => 'classic',
        'card-style' => 'rounded'
    ],
    'theme2' => [
        'primary-900' => '#6A1B9A',
        'primary-700' => '#8E24AA',
        'primary-500' => '#AB47BC',
        'primary-300' => '#CE93D8',
        'primary-100' => '#F3E5F5',
        'accent' => '#EC407A',
        'muted' => '#7B1FA2',
        'bg' => '#FCE4EC',
        'gradient-primary' => 'linear-gradient(135deg, #AB47BC 0%, #8E24AA 100%)',
        'gradient-accent' => 'linear-gradient(135deg, #EC407A 0%, #AB47BC 100%)',
        'hero-style' => 'split',
        'card-style' => 'elevated'
    ],
    'theme3' => [
        'primary-900' => '#00695C',
        'primary-700' => '#00897B',
        'primary-500' => '#009688',
        'primary-300' => '#4DB6AC',
        'primary-100' => '#E0F2F1',
        'accent' => '#26A69A',
        'muted' => '#546E7A',
        'bg' => '#E8F5E9',
        'gradient-primary' => 'linear-gradient(135deg, #009688 0%, #00897B 100%)',
        'gradient-accent' => 'linear-gradient(135deg, #26A69A 0%, #009688 100%)',
        'hero-style' => 'modern',
        'card-style' => 'minimal'
    ]
];

 $theme = $themes[$current_theme];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Portofolio <?php echo $full_name; ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root{
            --primary-900: <?php echo $theme['primary-900']; ?>;
            --primary-700: <?php echo $theme['primary-700']; ?>;
            --primary-500: <?php echo $theme['primary-500']; ?>;
            --primary-300: <?php echo $theme['primary-300']; ?>;
            --primary-100: <?php echo $theme['primary-100']; ?>;
            --accent: <?php echo $theme['accent']; ?>;
            --muted: <?php echo $theme['muted']; ?>;
            --bg: <?php echo $theme['bg']; ?>;
            --card-bg: #ffffff;
            --glass: rgba(255,255,255,0.65);
            --gradient-primary: <?php echo $theme['gradient-primary']; ?>;
            --gradient-accent: <?php echo $theme['gradient-accent']; ?>;
        }

        html, body { 
            font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; 
            background: var(--bg); 
            color:#0b2533; 
            scroll-behavior: smooth;
            overflow-x: hidden;
        }
        
        @font-face {
            font-family: 'Simple Nathalie';
            src: url('font/Simple Nathalie.otf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        /* Typography */
        h1, h2, h3, h4, h5, h6 {
            font-family: "Poppins", sans-serif;
            font-weight: 600;
            line-height: 1.3;
        }

        .display-5 {
            font-weight: 700;
        }

        /* Navigation */
        .navbar {
            padding: 15px 0;
            transition: all 0.3s ease;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.2rem;
        }

        .nav-link {
            font-weight: 500;
            position: relative;
            transition: all 0.3s ease;
            margin: 0 5px;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--primary-500);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 80%;
        }

        /* Hero Section - Classic Style */
        <?php if ($theme['hero-style'] == 'classic'): ?>
        .hero {
            background: var(--gradient-primary);
            color: #fff;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="none"/><circle cx="50" cy="50" r="40" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></svg>');
            background-size: 100px 100px;
            opacity: 0.3;
        }

        .profile-thumb {
            width:480px; 
            height:680px; 
            object-fit:cover; 
            border-radius: 0px; 
            transition: all 0.3s ease;
        }
        <?php endif; ?>

        /* Hero Section - Split Style */
        <?php if ($theme['hero-style'] == 'split'): ?>
        .hero {
            background: linear-gradient(135deg, <?php echo $theme['primary-500']; ?> 50%, white 50%);
            color: <?php echo $theme['primary-500']; ?>;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .hero .col-md-7 {
            color: white;
        }

        .hero .col-md-5 {
            color: <?php echo $theme['primary-900']; ?>;
        }

        .profile-thumb {
            width:400px; 
            height:400px; 
            object-fit:cover; 
            border-radius: 50%;
            border: 8px solid white;
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }

        .hero-text {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
        }
        <?php endif; ?>

        /* Hero Section - Modern Style */
        <?php if ($theme['hero-style'] == 'modern'): ?>
        .hero {
            background: linear-gradient(135deg, var(--primary-500) 0%, var(--primary-700) 100%);
            color: #fff;
            padding: 120px 0;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 400px;
            height: 400px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .profile-thumb {
            width:350px; 
            height:350px; 
            object-fit:cover; 
            border-radius: 20px;
            transform: rotate(3deg);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
            border: 4px solid white;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            padding: 8px 20px;
            border-radius: 30px;
            margin: 5px;
            font-size: 14px;
        }
        <?php endif; ?>

        .profile-thumb:hover {
            transform: <?php echo $theme['hero-style'] == 'modern' ? 'rotate(0deg) scale(1.05)' : 'scale(1.05)'; ?>;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .cta-btn {
            border-radius: 30px;
            padding: 12px 28px;
            font-weight:600;
            box-shadow: 0 8px 20px rgba(2,136,209,0.15);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .cta-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(2,136,209,0.25);
        }

        .btn-primary {
            background: var(--gradient-primary);
            border: none;
        }

        .btn-primary:hover {
            background: var(--gradient-accent);
        }

        /* Cards - Rounded Style */
        <?php if ($theme['card-style'] == 'rounded'): ?>
        .card-project { 
            border-radius: 16px; 
            overflow: hidden;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .card-project:hover { 
            transform: translateY(-8px); 
            box-shadow: 0 15px 30px rgba(2,136,209,0.15); 
        }
        <?php endif; ?>

        /* Cards - Elevated Style */
        <?php if ($theme['card-style'] == 'elevated'): ?>
        .card-project { 
            border-radius: 8px; 
            overflow: hidden;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            transform: translateY(0);
        }
        
        .card-project:hover { 
            transform: translateY(-10px) scale(1.02); 
            box-shadow: 0 20px 40px rgba(0,0,0,0.25); 
        }

        .card-project .card-img-top {
            filter: brightness(0.9);
            transition: all 0.5s ease;
        }

        .card-project:hover .card-img-top {
            filter: brightness(1);
        }
        <?php endif; ?>

        /* Cards - Minimal Style */
        <?php if ($theme['card-style'] == 'minimal'): ?>
        .card-project { 
            border-radius: 4px; 
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.1);
            box-shadow: none;
            background: white;
        }
        
        .card-project:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 5px 15px rgba(0,0,0,0.1); 
            border-color: var(--primary-300);
        }

        .card-project .card-img-top {
            filter: grayscale(20%);
            transition: all 0.5s ease;
        }

        .card-project:hover .card-img-top {
            filter: grayscale(0%);
        }
        <?php endif; ?>

        .card-project .card-img-top {
            height: 200px;
            object-fit: cover;
            transition: all 0.5s ease;
        }

        <?php if ($theme['card-style'] != 'minimal'): ?>
        .card-project:hover .card-img-top {
            transform: scale(1.05);
        }
        <?php endif; ?>

        .section-title { 
            font-weight:700; 
            margin-bottom:24px;
            position: relative;
            padding-bottom: 10px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--gradient-primary);
            border-radius: 3px;
        }

        .muted { 
            color:var(--muted); 
            text-align: justify;    
        }
        
        .testimonial { 
            border-radius: 16px; 
            background: #fff; 
            box-shadow:0 8px 25px rgba(2,136,209,0.08); 
            padding:24px;
            transition: all 0.3s ease;
            height: 100%;
        }

        .testimonial:hover {
            transform: translateY(-5px);
            box-shadow:0 12px 30px rgba(2,136,209,0.12);
        }

        .pill { 
            background: var(--gradient-primary);
            color: white;
            padding:8px 16px; 
            border-radius:30px; 
            font-weight:600; 
            font-size:.9rem;
            display: inline-block;
            box-shadow: 0 4px 10px rgba(3,169,244,0.2);
        }

        /* Animations */
        .reveal { 
            opacity:0; 
            transform: translateY(20px); 
            transition: all 0.8s ease; 
        }
        
        .reveal.show { 
            opacity:1; 
            transform: translateY(0); 
        }

        /* Icons */
        .icon-round { 
            width:50px; 
            height:50px; 
            display:inline-flex; 
            align-items:center; 
            justify-content:center; 
            background: var(--primary-100);
            border-radius:15px;
            transition: all 0.3s ease;
        }

        .icon-round:hover {
            transform: scale(1.1);
            background: var(--primary-300);
            color: white;
        }

        /* Footer */
        footer { 
            padding:40px 0; 
            background: var(--primary-900); 
            color:#e6f7ff;
            position: relative;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--gradient-primary);
        }

        /* Section backgrounds */
        .section-accent {
            background: var(--primary-100);
            position: relative;
        }

        .section-accent::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="none"/><circle cx="20" cy="20" r="5" fill="rgba(3,169,244,0.05)"/><circle cx="80" cy="80" r="5" fill="rgba(3,169,244,0.05)"/><circle cx="80" cy="20" r="5" fill="rgba(3,169,244,0.05)"/><circle cx="20" cy="80" r="5" fill="rgba(3,169,244,0.05)"/></svg>');
            background-size: 100px 100px;
            opacity: 0.5;
        }

        /* Timeline for plan section */
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 2px;
            background: var(--gradient-primary);
        }

        .timeline-item {
            position: relative;
            padding-bottom: 30px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -34px;
            top: 5px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--primary-500);
            border: 2px solid white;
            box-shadow: 0 0 0 4px rgba(3,169,244,0.2);
        }

        /* Achievement Cards */
        .achievement-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(2,136,209,0.08);
            transition: all 0.3s ease;
            height: 100%;
            border-left: 4px solid var(--primary-500);
        }

        .achievement-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(2,136,209,0.15);
        }

        .achievement-year {
            background: var(--gradient-primary);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 10px;
        }

        /* Organization Cards */
        .org-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(2,136,209,0.08);
            transition: all 0.3s ease;
            text-align: center;
        }

        .org-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(2,136,209,0.15);
        }

        .org-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--primary-100);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 30px;
            color: var(--primary-500);
        }

        /* Skill bars - FIXED */
        .skill-item {
            margin-bottom: 20px;
        }

        .skill-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .skill-name {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
        }

        .skill-percentage {
            font-weight: 600;
            color: var(--primary-500);
            font-size: 14px;
        }

        .skill-bar {
            height: 10px;
            background: rgba(3, 169, 244, 0.1);
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .skill-progress {
            height: 100%;
            border-radius: 10px;
            background: var(--gradient-primary);
            width: 0;
            transition: width 1.5s ease;
            position: relative;
        }

        /* Initial state for skill bars */
        .skill-progress.animated {
            width: var(--skill-width);
        }

        /* Responsive */
        @media (max-width:767px){
            .hero { padding:60px 0;}
            .profile-thumb{width:150px; height:220px;}
            .section-title::after { width: 40px; }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-500);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-700);
        }

        /* Floating action button */
        .fab {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--gradient-primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(3,169,244,0.2);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .fab:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 20px rgba(3,169,244,0.3);
        }

        .hero .overlay-shape {
            position: absolute; 
            right: -120px; 
            top: -40px; 
            opacity: 0.12; 
            transform: rotate(15deg);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0) rotate(15deg); }
            50% { transform: translateY(-20px) rotate(15deg); }
            100% { transform: translateY(0) rotate(15deg); }
        }
    </style>

<!-- Jetpack Open Graph Tags -->
<meta property="og:type" content="website" />
<meta property="og:title" content="<?php echo $full_name; ?>" />
<meta property="og:description" content="<?php echo $description; ?>" />
<meta property="og:image:secure_url" content="https://paknajgurusmk.my.id/paknaj2.png" />
<meta property="og:image:type" content="image/png" />
<meta property="og:site_name" content="<?php echo $full_name; ?>" />
<meta property="og:image" content="https://paknajgurusmk.my.id/paknaj2.png" />
<meta property="og:image:width" content="1000" />
<meta property="og:image:height" content="673" />
<meta property="og:image:alt" content="<?php echo $full_name; ?>" />
<meta property="og:locale" content="id_ID" />
<meta name="twitter:site" content="@mohammadnajmudin" />

<!-- End Jetpack Open Graph Tags -->
<link rel="icon" href="https://paknajgurusmk.my.id/paknaj2.png" sizes="32x32" />
<link rel="icon" href="https://paknajgurusmk.my.id/paknaj2.png" sizes="192x192" />
<link rel="apple-touch-icon" href="https://paknajgurusmk.my.id/paknaj2.png" />
<meta name="msapplication-TileImage" content="https://paknajgurusmk.my.id/paknaj2.png" />
</head>

<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="#">
        <div class="pill">MN</div>
        <div>
          <div style="font-weight:700"><?php echo $full_name; ?></div>
          <small class="text-muted"><?php echo $location; ?></small>
        </div>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
          <li class="nav-item"><a class="nav-link" href="#profil">Profil</a></li>
          <li class="nav-item"><a class="nav-link" href="#karya">Karya</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Admin</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- HERO -->
  <header class="hero">
    <div class="container position-relative">
      <?php if ($theme['hero-style'] == 'split'): ?>
      <div class="row align-items-center">
        <div class="col-md-7 hero-content" data-aos="fade-right">
          <div class="hero-text">
            <h1 class="display-3 fw-bold mb-2">Portofolio Digital</h1>
            <h2 class="fw-bold mb-3"><?php echo $full_name; ?></h2>
            <p class="lead mb-4"><?php echo $description; ?></p>

            <div class="d-flex gap-3 mb-4">
              <a href="#karya" class="btn btn-light cta-btn">Lihat Karya <i class="bi bi-arrow-right ms-2"></i></a>
              <button class="btn btn-outline-light cta-btn" data-bs-toggle="modal" data-bs-target="#contactModal">Hubungi Saya</button>
            </div>

            <div class="d-flex flex-wrap gap-3">
              <div class="hero-badge"><i class="bi bi-geo-alt me-2"></i><?php echo $location; ?></div>
              <div class="hero-badge"><i class="bi bi-award me-2"></i>Guru & Fasilitator Pembelajaran Mendalam</div>
            </div>
          </div>
        </div>

        <div class="col-md-5 text-center" data-aos="fade-left">
          <img src="<?php echo $profile ? $profile['image'] : 'paknaj5.png'; ?>" alt="Foto Profil" class="profile-thumb mb-3">
          <div><?php echo $full_name; ?></div>
        </div>
      </div>
      <?php elseif ($theme['hero-style'] == 'modern'): ?>
      <div class="row align-items-center">
        <div class="col-lg-6 hero-content" data-aos="fade-right">
          <div class="mb-4">
            <span class="hero-badge">👋 Halo, Saya</span>
          </div>
          <h1 class="display-2 fw-bold mb-3"><?php echo $full_name; ?></h1>
          <h3 class="fw-light mb-4"><?php echo $title; ?></h3>
          <p class="lead mb-4"><?php echo $description; ?></p>

          <div class="d-flex gap-3 mb-4">
            <a href="#karya" class="btn btn-light cta-btn">Lihat Karya <i class="bi bi-arrow-right ms-2"></i></a>
            <button class="btn btn-outline-light cta-btn" data-bs-toggle="modal" data-bs-target="#contactModal">Hubungi Saya</button>
          </div>

          <div class="d-flex flex-wrap gap-2">
            <span class="hero-badge"><i class="bi bi-geo-alt me-2"></i><?php echo $location; ?></span>
            <span class="hero-badge"><i class="bi bi-award me-2"></i>Fasilitator Pembelajaran Mendalam</span>
            <span class="hero-badge"><i class="bi bi-heart me-2"></i>Passionate Educator</span>
          </div>
        </div>

        <div class="col-lg-6 text-center" data-aos="fade-left">
          <img src="<?php echo $profile ? $profile['image'] : 'paknaj5.png'; ?>" alt="Foto Profil" class="profile-thumb">
        </div>
      </div>
      <?php else: ?>
      <div class="row align-items-center">
        <div class="col-md-7" data-aos="fade-right">
          <h1 class="display-3 fw-bold mb-2">Portofolio Digital  <span style="color:#fffa07;font-size:30px;"><?php echo $full_name; ?></span></h1>
          <p class="lead text-yellow-50 mb-4"><?php echo $description; ?></p>

          <div class="d-flex gap-3 mb-4">
            <a href="#karya" class="btn btn-light cta-btn">Lihat Karya <i class="bi bi-arrow-right ms-2"></i></a>
            <button class="btn btn-outline-light cta-btn" data-bs-toggle="modal" data-bs-target="#contactModal">Hubungi Saya</button>
          </div>

          <div class="d-flex flex-wrap gap-3">
            <div class="text-yellow-50 small"><i class="bi bi-geo-alt me-2"></i><?php echo $location; ?></div>
            <div class="text-yellow-50 small"><i class="bi bi-award me-2"></i>Guru & Fasilitator Pembelajaran Mendalam</div>

            <div class="mt-5">
              <h4 class="fw-light" style="font-family: 'Simple Nathalie', cursive; color:#ffffff; font-size:45px;align=center;">
                Belajar  Berkarya   Menginspirasi
              </h4>
            </div>
          </div>
        </div>

        <div class="col-md-5 text-center" data-aos="fade-left">
          <img src="<?php echo $profile ? $profile['image'] : 'paknaj5.png'; ?>" alt="Foto Profil" class="profile-thumb mb-3">
          <div class="text-yellow-50"><?php echo $full_name; ?></div>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <svg class="overlay-shape" width="420" height="420" viewBox="0 0 420 420" fill="none" xmlns="http://www.w3.org/2000/svg">
      <circle cx="210" cy="210" r="210" fill="#ffffff"></circle>
    </svg>
  </header> 
  
  <main class="pt-5">

    <!-- PROFIL SECTION -->
    <section id="profil" class="container py-5">
      <div class="row g-4 align-items-center">
        <div class="col-lg-7" data-aos="fade-right">
          <h2 class="section-title">Profil</h2>
          <p class="muted mb-4"><?php echo $description; ?></p>

          <div class="row mb-4">
            <div class="col-6">
              <?php 
              $skill_count = 0;
              while($skill = mysqli_fetch_assoc($skills_result)) {
                if ($skill_count >= 2) break;
              ?>
              <div class="skill-item">
                <div class="skill-header">
                  <span class="skill-name"><?php echo $skill['name']; ?></span>
                  <span class="skill-percentage"><?php echo $skill['percentage']; ?>%</span>
                </div>
                <div class="skill-bar">
                  <div class="skill-progress" style="--skill-width: <?php echo $skill['percentage']; ?>%"></div>
                </div>
              </div>
              <?php 
                $skill_count++;
              } 
              // Reset result pointer
              mysqli_data_seek($skills_result, 0);
              ?>
            </div>
            <div class="col-6">
              <?php 
              $skill_count = 0;
              // Skip first 2 skills
              mysqli_data_seek($skills_result, 2);
              while($skill = mysqli_fetch_assoc($skills_result)) {
                if ($skill_count >= 2) break;
              ?>
              <div class="skill-item">
                <div class="skill-header">
                  <span class="skill-name"><?php echo $skill['name']; ?></span>
                  <span class="skill-percentage"><?php echo $skill['percentage']; ?>%</span>
                </div>
                <div class="skill-bar">
                  <div class="skill-progress" style="--skill-width: <?php echo $skill['percentage']; ?>%"></div>
                </div>
              </div>
              <?php 
                $skill_count++;
              } 
              // Reset result pointer
              mysqli_data_seek($skills_result, 0);
              ?>
            </div>
          </div>

          <ul class="list-unstyled mt-3">
            <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>Magister Pendidikan (M.Pd)</li>
            <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>S.Kom — Keahlian Komputer & Jaringan</li>
            <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>Narasumber Media Pembelajaran</li>
            <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>Instruktur Kurikulum 13</li>
            <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>Fasilitator Guru Penggerak</li>
            <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>Fasilitator Pembelajaran Mendalam </li>
          </ul>

          <div class="mt-4">
            <a class="btn btn-primary" href="#pengembangan">Lihat Pengembangan Diri</a>
            <a class="btn btn-outline-secondary ms-2" href="CV-NAJMUDIN.pdf" data-bs-toggle="modal" data-bs-target="#cvModal">Unduh CV</a>
          </div>
        </div>

        <div class="col-lg-5" data-aos="fade-left">
          <div class="card p-4 card-project h-100">
            <div class="d-flex gap-3 align-items-center mb-3">
              <div class="icon-round"><i class="bi bi-gear-fill text-primary"></i></div>
              <div>
                <div class="fw-bold">Brand Diri</div>
                <div class="muted small">Guru Inovatif & Reflektif berfokus pada pembelajaran mendalam, diferensiasi, dan proyek nyata.</div>
              </div>
            </div>

            <hr>

            <div class="d-flex gap-3 align-items-center mb-3">
              <div class="icon-round"><i class="bi bi-lightbulb-fill text-warning"></i></div>
              <div>
                <div class="fw-bold">Filosofi Mengajar</div>
                <div class="muted small">Pembelajaran harus autentik, humanis, dan bermakna—memberi ruang bagi siswa untuk berpikir kritis, berkarya, dan berkembang sesuai potensinya.</div>
              </div>
            </div>

            <hr>

            <div class="d-flex gap-3 align-items-center">
              <div class="icon-round"><i class="bi bi-journal-text text-success"></i></div>
              <div>
                <div class="fw-bold">Keahlian Inti</div>
                <div class="muted small">RPP Mendalam · PBL · Asesmen Skill Passport · Media Ajar Digital</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- PENGEMBANGAN DIRI -->
    <section id="pengembangan" class="container py-5">
      <h2 class="section-title">Pengembangan Diri Tahun 2020 - 2025 </h2>
      <div class="row g-4">
        <?php while($achievement = mysqli_fetch_assoc($achievements_result)) { ?>
        <div class="col-md-6" data-aos="fade-up">
          <div class="achievement-card">
            <div class="achievement-year"><?php echo $achievement['year']; ?></div>
            <h5 class="mb-3"><?php echo $achievement['title']; ?></h5>
            <img src="<?php echo $achievement['image']; ?>" alt="<?php echo $achievement['title']; ?>" style="width: 100%; height: 480px; display: block; object-fit: cover;">
          </div>
        </div>
        <?php } ?>
      </div>
    </section>

    <!-- PENDAMPINGAN REKAN SEJAWAT -->
    <section id="pendampingan" class="container py-5 section-accent">
      <h2 class="section-title">Pendampingan Rekan Sejawat 2025</h2>
      
      <div class="row g-4">
        <?php while($mentoring = mysqli_fetch_assoc($mentoring_result)) { ?>
        <div class="col-md-6" data-aos="fade-right">
          <div class="card p-4 card-project h-100">
            <div class="d-flex justify-content-between">
              <div>
                <h5><?php echo $mentoring['title']; ?></h5>
                <p class="muted"><?php echo $mentoring['description']; ?></p>
                <img src="<?php echo $mentoring['image']; ?>" alt="<?php echo $mentoring['title']; ?>" style="width: 100%; height: 480px; display: block; object-fit: cover;">
              </div>
              <div class="icon-round"><i class="bi bi-people-fill text-primary"></i></div>
            </div>
            <hr>
            
            <div class="d-flex justify-content-end align-items-center mt-3 border-top pt-3">
                <span class="small text-muted me-2">Dokumentasi Kegiatan:</span>
                <a href="https://instagram.com/USERNAME_ANDA" target="_blank" class="text-decoration-none text-dark">
                    <i class="bi bi-instagram fs-5"></i>
                </a>
                <a href="https://tiktok.com/@USERNAME_ANDA" target="_blank" class="text-decoration-none text-dark ms-3">
                    <i class="bi bi-tiktok fs-5"></i>
                </a>
                <a href="https://youtube.com/@USERNAME_ANDA" target="_blank" class="text-decoration-none text-danger ms-3">
                    <i class="bi bi-youtube fs-5"></i>
                </a>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
    </section>

    <!-- PENGALAMAN ORGANISASI -->
    <section id="organisasi" class="container py-5">
      <h2 class="section-title">Pengalaman Organisasi</h2>
      
      <div class="row g-4">
        <?php while($organization = mysqli_fetch_assoc($organizations_result)) { ?>
        <div class="col-md-4" data-aos="fade-up">
          <div class="org-card">
            <div class="org-logo">
              <i class="bi bi-<?php echo $organization['icon']; ?>"></i>
            </div>
            <h6 class="fw-bold"><?php echo $organization['name']; ?></h6>
            <p class="muted small"><?php echo $organization['position']; ?></p>
            <p class="small"><?php echo $organization['period']; ?></p>
          </div>
        </div>
        <?php } ?>
      </div>
    </section>

    <!-- KARYA PEMBELAJARAN -->
    <section id="karya" class="container py-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title">Kumpulan Karya Pembelajaran</h2>
        <div class="muted">Klik <span class="fw-semibold">Lihat</span> untuk detail</div>
      </div>

      <div class="row g-4">
        <?php while($project = mysqli_fetch_assoc($projects_result)) { ?>
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
          <div class="card h-100 card-project">
            <img src="<?php echo $project['image']; ?>" class="card-img-top" alt="<?php echo $project['title']; ?>">
            <div class="card-body">
              <h5 class="card-title"><?php echo $project['title']; ?></h5>
              <small class="text-muted"><?php echo $project['year']; ?></small>
              <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#projectModal" data-title="<?php echo $project['title']; ?>" data-desc="<?php echo $project['description']; ?>" data-img="<?php echo $project['image']; ?>">Lihat</button>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer>
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h5>Portofolio Digital</h5>
          <p>&copy; <?php echo date('Y'); ?> <?php echo $full_name; ?>. All rights reserved.</p>
        </div>
        <div class="col-md-6 text-md-end">

        </div>
      </div>
    </div>
  </footer>

  <!-- Contact Modal -->
  <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="contactModalLabel">Hubungi Saya</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label for="name" class="form-label">Nama</label>
              <input type="text" class="form-control" id="name" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" required>
            </div>
            <div class="mb-3">
              <label for="message" class="form-label">Pesan</label>
              <textarea class="form-control" id="message" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Kirim</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Project Modal -->
  <div class="modal fade" id="projectModal" tabindex="-1" aria-labelledby="projectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="projectModalLabel">Detail Karya</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <img id="modalProjectImage" src="" class="img-fluid mb-3" alt="">
          <h5 id="modalProjectTitle"></h5>
          <p id="modalProjectDesc"></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Admin FAB -->
  <?php if (isset($_SESSION['user_id'])) { ?>
  <a href="admin/dashboard.php" class="fab">
    <i class="bi bi-gear-fill"></i>
  </a>
  <?php } ?>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- AOS Animation -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    // Initialize AOS
    AOS.init({
      duration: 800,
      once: true
    });

    // Project Modal
    const projectModal = document.getElementById('projectModal');
    if (projectModal) {
      projectModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const title = button.getAttribute('data-title');
        const desc = button.getAttribute('data-desc');
        const img = button.getAttribute('data-img');
        
        document.getElementById('modalProjectTitle').textContent = title;
        document.getElementById('modalProjectDesc').textContent = desc;
        document.getElementById('modalProjectImage').src = img;
      });
    }

    // Animate skill bars when in viewport
    const observerOptions = {
      root: null,
      rootMargin: '0px',
      threshold: 0.5
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animated');
        }
      });
    }, observerOptions);

    document.querySelectorAll('.skill-progress').forEach(el => {
      observer.observe(el);
    });
  </script>
</body>
</html>