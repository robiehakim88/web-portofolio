<?php
// admin/profile.php
require_once '../config.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Ambil data profil
 $profile_query = "SELECT * FROM profile ORDER BY id DESC LIMIT 1";
 $profile_result = mysqli_query($conn, $profile_query);
 $profile = mysqli_fetch_assoc($profile_result);

// Proses form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    
    // Handle upload gambar
    $image = $profile['image']; // Default to existing image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_name = time() . '_' . $_FILES['image']['name'];
        $file_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
            $image = 'uploads/' . $file_name;
        }
    }
    
    if ($profile) {
        // Update existing profile
        $query = "UPDATE profile SET full_name='$full_name', title='$title', description='$description', location='$location', image='$image' WHERE id={$profile['id']}";
    } else {
        // Insert new profile
        $query = "INSERT INTO profile (full_name, title, description, location, image) VALUES ('$full_name', '$title', '$description', '$location', '$image')";
    }
    
    if (mysqli_query($conn, $query)) {
        $success = "Profil berhasil diperbarui!";
        // Refresh data
        $profile_result = mysqli_query($conn, $profile_query);
        $profile = mysqli_fetch_assoc($profile_result);
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Admin Dashboard</title>
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
        .card {
            border-radius: 16px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: none;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 12px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #03A9F4 0%, #0288D1 100%);
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #0288D1 0%, #0277BD 100%);
        }
        .preview-image {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            margin-top: 10px;
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
            .preview-image {
                max-width: 150px;
                max-height: 150px;
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
                    <a href="dashboard.php" class="nav-link">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="profile.php" class="nav-link active">
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
        <h1 class="page-title">Profil</h1>

        <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Profil</h5>
            </div>
            <div class="card-body">
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="full_name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $profile ? $profile['full_name'] : ''; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">Judul/Profesi</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo $profile ? $profile['title'] : ''; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required><?php echo $profile ? $profile['description'] : ''; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Lokasi</label>
                        <input type="text" class="form-control" id="location" name="location" value="<?php echo $profile ? $profile['location'] : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Foto Profil</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <?php if ($profile && $profile['image']): ?>
                            <div class="mt-2">
                                <small class="text-muted">Gambar saat ini:</small>
                                <img src="../<?php echo $profile['image']; ?>" alt="Profil" class="preview-image">
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-end flex-wrap gap-2">
                        <a href="dashboard.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
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
                document.body.style.overflow = 'hidden';
            });
            
            // Tutup sidebar di mobile
            closeSidebar.addEventListener('click', function() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = 'auto';
            });
            
            // Tutup sidebar saat klik overlay
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = 'auto';
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