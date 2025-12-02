<?php
// admin/skills.php
require_once '../config.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Proses form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $percentage = mysqli_real_escape_string($conn, $_POST['percentage']);
    
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update existing skill
        $id = $_POST['id'];
        $query = "UPDATE skills SET name='$name', percentage='$percentage' WHERE id=$id";
    } else {
        // Add new skill
        $query = "INSERT INTO skills (name, percentage) VALUES ('$name', '$percentage')";
    }
    
    if (mysqli_query($conn, $query)) {
        $success = "Keahlian berhasil disimpan!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Delete skill
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM skills WHERE id=$id";
    
    if (mysqli_query($conn, $query)) {
        $success = "Keahlian berhasil dihapus!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Get skill for editing
 $edit_skill = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM skills WHERE id=$id");
    $edit_skill = mysqli_fetch_assoc($result);
}

// Get all skills
 $skills_result = mysqli_query($conn, "SELECT * FROM skills ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keahlian - Admin Dashboard</title>
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
        .table {
            border-radius: 8px;
            overflow: hidden;
        }
        .table thead {
            background-color: rgba(3, 169, 244, 0.1);
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
            background: linear-gradient(135deg, #03A9F4 0%, #0288D1 100%);
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
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 14px;
            }
            .btn-sm {
                padding: 5px 10px;
                font-size: 12px;
            }
        }
        @media (max-width: 576px) {
            .content {
                padding: 15px;
            }
            .page-title {
                font-size: 24px;
            }
            .skill-bar {
                height: 8px;
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
                    <a href="skills.php" class="nav-link active">
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
        <h1 class="page-title">Keahlian</h1>

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
                <h5 class="mb-0"><?php echo $edit_skill ? 'Edit' : 'Tambah'; ?> Keahlian</h5>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <?php if ($edit_skill): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_skill['id']; ?>">
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="name" class="form-label">Nama Keahlian</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $edit_skill ? $edit_skill['name'] : ''; ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="percentage" class="form-label">Persentase (%)</label>
                            <input type="number" class="form-control" id="percentage" name="percentage" min="0" max="100" value="<?php echo $edit_skill ? $edit_skill['percentage'] : ''; ?>" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end flex-wrap gap-2">
                        <a href="skills.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary"><?php echo $edit_skill ? 'Update' : 'Simpan'; ?></button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Daftar Keahlian</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Keahlian</th>
                                <th>Persentase</th>
                                <th>Visual</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while($skill = mysqli_fetch_assoc($skills_result)) { ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $skill['name']; ?></td>
                                <td><?php echo $skill['percentage']; ?>%</td>
                                <td>
                                    <div class="skill-bar">
                                        <div class="skill-progress" style="width: <?php echo $skill['percentage']; ?>%"></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="skills.php?action=edit&id=<?php echo $skill['id']; ?>" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="skills.php?action=delete&id=<?php echo $skill['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus keahlian ini?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
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
        });
    </script>
</body>
</html>