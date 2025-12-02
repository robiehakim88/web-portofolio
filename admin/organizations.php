<?php
// admin/organizations.php
require_once '../config.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Proses form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $period = mysqli_real_escape_string($conn, $_POST['period']);
    $icon = mysqli_real_escape_string($conn, $_POST['icon']);
    
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update existing organization
        $id = $_POST['id'];
        $query = "UPDATE organizations SET name='$name', position='$position', period='$period', icon='$icon' WHERE id=$id";
    } else {
        // Add new organization
        $query = "INSERT INTO organizations (name, position, period, icon) VALUES ('$name', '$position', '$period', '$icon')";
    }
    
    if (mysqli_query($conn, $query)) {
        $success = "Organisasi berhasil disimpan!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Delete organization
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM organizations WHERE id=$id";
    
    if (mysqli_query($conn, $query)) {
        $success = "Organisasi berhasil dihapus!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Get organization for editing
 $edit_organization = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM organizations WHERE id=$id");
    $edit_organization = mysqli_fetch_assoc($result);
}

// Get all organizations
 $organizations_result = mysqli_query($conn, "SELECT * FROM organizations ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organisasi - Admin Dashboard</title>
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
        .organization-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(2,136,209,0.08);
            transition: all 0.3s ease;
            text-align: center;
            height: 100%;
        }
        .organization-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(2,136,209,0.15);
        }
        .org-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(3, 169, 244, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 30px;
            color: #03A9F4;
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
        .icon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
            gap: 10px;
            max-height: 200px;
            overflow-y: auto;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .icon-option {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            border: 2px solid transparent;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 24px;
            color: #555;
        }
        .icon-option:hover {
            background: rgba(3, 169, 244, 0.1);
            border-color: #03A9F4;
        }
        .icon-option.selected {
            background: rgba(3, 169, 244, 0.2);
            border-color: #03A9F4;
            color: #03A9F4;
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
        @media (max-width: 768px) {
            .organization-card {
                margin-bottom: 15px;
            }
        }
        @media (max-width: 576px) {
            .content {
                padding: 15px;
            }
            .page-title {
                font-size: 24px;
            }
            .icon-grid {
                grid-template-columns: repeat(auto-fill, minmax(50px, 1fr));
            }
            .icon-option {
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
                    <a href="dashboard.php" class="nav-link">
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
                    <a href="organizations.php" class="nav-link active">
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
        <h1 class="page-title">Organisasi</h1>

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

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><?php echo $edit_organization ? 'Edit' : 'Tambah'; ?> Organisasi</h5>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <?php if ($edit_organization): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_organization['id']; ?>">
                    <?php endif; ?>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Organisasi</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $edit_organization ? $edit_organization['name'] : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="position" class="form-label">Posisi/Jabatan</label>
                        <input type="text" class="form-control" id="position" name="position" value="<?php echo $edit_organization ? $edit_organization['position'] : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="period" class="form-label">Periode</label>
                        <input type="text" class="form-control" id="period" name="period" value="<?php echo $edit_organization ? $edit_organization['period'] : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pilih Icon</label>
                        <div class="icon-grid">
                            <div class="icon-option <?php echo ($edit_organization && $edit_organization['icon'] == 'mortarboard-fill') ? 'selected' : ''; ?>" data-icon="mortarboard-fill">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>
                            <div class="icon-option <?php echo ($edit_organization && $edit_organization['icon'] == 'award-fill') ? 'selected' : ''; ?>" data-icon="award-fill">
                                <i class="bi bi-award-fill"></i>
                            </div>
                            <div class="icon-option <?php echo ($edit_organization && $edit_organization['icon'] == 'building') ? 'selected' : ''; ?>" data-icon="building">
                                <i class="bi bi-building"></i>
                            </div>
                            <div class="icon-option <?php echo ($edit_organization && $edit_organization['icon'] == 'people-fill') ? 'selected' : ''; ?>" data-icon="people-fill">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div class="icon-option <?php echo ($edit_organization && $edit_organization['icon'] == 'briefcase-fill') ? 'selected' : ''; ?>" data-icon="briefcase-fill">
                                <i class="bi bi-briefcase-fill"></i>
                            </div>
                            <div class="icon-option <?php echo ($edit_organization && $edit_organization['icon'] == 'star-fill') ? 'selected' : ''; ?>" data-icon="star-fill">
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <div class="icon-option <?php echo ($edit_organization && $edit_organization['icon'] == 'shield-fill') ? 'selected' : ''; ?>" data-icon="shield-fill">
                                <i class="bi bi-shield-fill"></i>
                            </div>
                            <div class="icon-option <?php echo ($edit_organization && $edit_organization['icon'] == 'trophy-fill') ? 'selected' : ''; ?>" data-icon="trophy-fill">
                                <i class="bi bi-trophy-fill"></i>
                            </div>
                        </div>
                        <input type="hidden" id="icon" name="icon" value="<?php echo $edit_organization ? $edit_organization['icon'] : 'mortarboard-fill'; ?>" required>
                    </div>
                    <div class="d-flex justify-content-end flex-wrap gap-2">
                        <a href="organizations.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary"><?php echo $edit_organization ? 'Update' : 'Simpan'; ?></button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Daftar Organisasi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php if (mysqli_num_rows($organizations_result) > 0): ?>
                        <?php while($organization = mysqli_fetch_assoc($organizations_result)) { ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="organization-card">
                                    <div class="org-logo">
                                        <i class="bi bi-<?php echo $organization['icon']; ?>"></i>
                                    </div>
                                    <h6 class="fw-bold"><?php echo $organization['name']; ?></h6>
                                    <p class="muted small"><?php echo $organization['position']; ?></p>
                                    <p class="small"><?php echo $organization['period']; ?></p>
                                    <div class="action-buttons mt-3">
                                        <a href="organizations.php?action=edit&id=<?php echo $organization['id']; ?>" class="btn btn-sm btn-warning me-2">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="organizations.php?action=delete&id=<?php echo $organization['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus organisasi ini?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-building" style="font-size: 48px; color: #ccc;"></i>
                            <h5 class="mt-3 text-muted">Belum ada organisasi</h5>
                            <p class="text-muted">Tambahkan organisasi pertama Anda menggunakan form di atas.</p>
                        </div>
                    <?php endif; ?>
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
            
            // Icon selection
            const iconOptions = document.querySelectorAll('.icon-option');
            const iconInput = document.getElementById('icon');
            
            iconOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Remove selected class from all options
                    iconOptions.forEach(opt => opt.classList.remove('selected'));
                    // Add selected class to clicked option
                    this.classList.add('selected');
                    // Update hidden input
                    iconInput.value = this.dataset.icon;
                });
            });
        });
    </script>
</body>
</html>