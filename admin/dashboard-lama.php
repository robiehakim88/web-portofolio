<?php
// admin/dashboard.php
require_once '../config.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Hitung jumlah data
 $profile_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM profile"));
 $skills_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM skills"));
 $achievements_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM achievements"));
 $projects_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM projects"));
 $mentoring_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM mentoring"));
 $organizations_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM organizations"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Portfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #03A9F4 0%, #0288D1 100%);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            transition: all 0.3s ease;
            width: 250px;
            transform: translateX(-100%);
        }
        .sidebar.active {
            transform: translateX(0);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            transition: all 0.3s;
            border-radius: 8px;
            margin: 5px 10px;
            padding: 10px 15px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }
        .sidebar-header {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-header h4 {
            margin: 0;
            font-weight: 600;
        }
        .close-sidebar {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }
        .content {
            padding: 20px;
            margin-left: 0;
            transition: all 0.3s ease;
        }
        .page-title {
            font-weight: 700;
            margin-bottom: 30px;
            color: #0b2533;
        }
        .card-stat {
            border-radius: 16px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
        }
        .card-stat:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .card-stat .card-body {
            padding: 25px;
        }
        .card-stat .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }
        .card-stat .stat-icon.blue {
            background: rgba(3, 169, 244, 0.1);
            color: #03A9F4;
        }
        .card-stat .stat-icon.green {
            background: rgba(76, 175, 80, 0.1);
            color: #4CAF50;
        }
        .card-stat .stat-icon.orange {
            background: rgba(255, 152, 0, 0.1);
            color: #FF9800;
        }
        .card-stat .stat-icon.purple {
            background: rgba(156, 39, 176, 0.1);
            color: #9C27B0;
        }
        .card-stat .stat-icon.red {
            background: rgba(244, 67, 54, 0.1);
            color: #F44336;
        }
        .card-stat .stat-icon.teal {
            background: rgba(0, 150, 136, 0.1);
            color: #009688;
        }
        .top-bar {
            background: white;
            padding: 15px 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 99;
        }
        .menu-toggle {
            background: none;
            border: none;
            font-size: 24px;
            color: #03A9F4;
            cursor: pointer;
        }
        .user-info {
            display: flex;
            align-items: center;
            color: #555;
        }
        .user-info i {
            margin-right: 8px;
            color: #03A9F4;
        }
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 99;
            display: none;
        }
        .overlay.active {
            display: block;
        }
        /* Media queries untuk responsivitas */
        @media (min-width: 992px) {
            .sidebar {
                transform: translateX(0);
                width: 250px;
            }
            .content {
                margin-left: 250px;
            }
            .close-sidebar {
                display: none !important;
            }
            .menu-toggle {
                display: none !important;
            }
            .overlay {
                display: none !important;
            }
        }
        @media (max-width: 991px) {
            .close-sidebar {
                display: block;
            }
        }
        @media (max-width: 576px) {
            .content {
                padding: 15px;
            }
            .card-stat .card-body {
                padding: 20px;
            }
            .card-stat .stat-icon {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Overlay untuk mobile -->
    <div class="overlay" id="overlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4>Admin Panel</h4>
            <button class="close-sidebar" id="closeSidebar">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="d-flex flex-column p-3">
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link active">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="profile.php" class="nav-link">
                        <i class="bi bi-person me-2"></i> Profil
                    </a>
                </li>
                <li class="nav-item">
                    <a href="skills.php" class="nav-link">
                        <i class="bi bi-graph-up me-2"></i> Keahlian
                    </a>
                </li>
                <li class="nav-item">
                    <a href="achievements.php" class="nav-link">
                        <i class="bi bi-trophy me-2"></i> Pencapaian
                    </a>
                </li>
                <li class="nav-item">
                    <a href="projects.php" class="nav-link">
                        <i class="bi bi-folder me-2"></i> Proyek
                    </a>
                </li>
                <li class="nav-item">
                    <a href="mentoring.php" class="nav-link">
                        <i class="bi bi-people me-2"></i> Mentoring
                    </a>
                </li>
                <li class="nav-item">
                    <a href="organizations.php" class="nav-link">
                        <i class="bi bi-building me-2"></i> Organisasi
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <a href="../logout.php" class="nav-link">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Top Bar -->
    <div class="top-bar">
        <button class="menu-toggle" id="menuToggle">
            <i class="bi bi-list"></i>
        </button>
        <div class="user-info">
            <i class="bi bi-person-circle"></i>
            <?php echo $_SESSION['username']; ?>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1 class="page-title">Dashboard</h1>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card card-stat">
                    <div class="card-body">
                        <div class="stat-icon blue">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <h5 class="card-title">Profil</h5>
                        <h3 class="mb-0"><?php echo $profile_count; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card card-stat">
                    <div class="card-body">
                        <div class="stat-icon green">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h5 class="card-title">Keahlian</h5>
                        <h3 class="mb-0"><?php echo $skills_count; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card card-stat">
                    <div class="card-body">
                        <div class="stat-icon orange">
                            <i class="bi bi-trophy"></i>
                        </div>
                        <h5 class="card-title">Pencapaian</h5>
                        <h3 class="mb-0"><?php echo $achievements_count; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card card-stat">
                    <div class="card-body">
                        <div class="stat-icon purple">
                            <i class="bi bi-folder"></i>
                        </div>
                        <h5 class="card-title">Proyek</h5>
                        <h3 class="mb-0"><?php echo $projects_count; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card card-stat">
                    <div class="card-body">
                        <div class="stat-icon red">
                            <i class="bi bi-people"></i>
                        </div>
                        <h5 class="card-title">Mentoring</h5>
                        <h3 class="mb-0"><?php echo $mentoring_count; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card card-stat">
                    <div class="card-body">
                        <div class="stat-icon teal">
                            <i class="bi bi-building"></i>
                        </div>
                        <h5 class="card-title">Organisasi</h5>
                        <h3 class="mb-0"><?php echo $organizations_count; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-6 mb-3">
                        <a href="profile.php" class="btn btn-outline-primary w-100">
                            <i class="bi bi-person me-2"></i> Edit Profil
                        </a>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <a href="skills.php?action=add" class="btn btn-outline-success w-100">
                            <i class="bi bi-plus-circle me-2"></i> Tambah Keahlian
                        </a>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <a href="achievements.php?action=add" class="btn btn-outline-warning w-100">
                            <i class="bi bi-plus-circle me-2"></i> Tambah Pencapaian
                        </a>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <a href="projects.php?action=add" class="btn btn-outline-info w-100">
                            <i class="bi bi-plus-circle me-2"></i> Tambah Proyek
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menuToggle');
            const closeSidebar = document.getElementById('closeSidebar');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            
            // Buka sidebar di mobile
            menuToggle.addEventListener('click', function() {
                sidebar.classList.add('active');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden'; // Mencegah scroll background
            });
            
            // Tutup sidebar di mobile
            closeSidebar.addEventListener('click', function() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = 'auto'; // Aktifkan kembali scroll
            });
            
            // Tutup sidebar saat klik overlay
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = 'auto'; // Aktifkan kembali scroll
            });
            
            // Deteksi ukuran layar dan sesuaikan sidebar
            function checkScreenSize() {
                if (window.innerWidth >= 992) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            }
            
            // Jalankan fungsi saat load dan resize
            window.addEventListener('load', checkScreenSize);
            window.addEventListener('resize', checkScreenSize);
        });
    </script>
</body>
</html>