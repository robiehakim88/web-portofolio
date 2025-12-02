<?php
// admin/projects.php
require_once '../config.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Proses form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    
    // Handle upload gambar
    $image = '';
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
    
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update existing project
        $id = $_POST['id'];
        
        // Get existing image if not uploading new one
        if (empty($image)) {
            $result = mysqli_query($conn, "SELECT image FROM projects WHERE id=$id");
            $row = mysqli_fetch_assoc($result);
            $image = $row['image'];
        }
        
        $query = "UPDATE projects SET title='$title', description='$description', year='$year', image='$image' WHERE id=$id";
    } else {
        // Add new project
        $query = "INSERT INTO projects (title, description, year, image) VALUES ('$title', '$description', '$year', '$image')";
    }
    
    if (mysqli_query($conn, $query)) {
        $success = "Proyek berhasil disimpan!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Delete project
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Get image path before deleting
    $result = mysqli_query($conn, "SELECT image FROM projects WHERE id=$id");
    $row = mysqli_fetch_assoc($result);
    $image_path = '../' . $row['image'];
    
    // Delete record
    $query = "DELETE FROM projects WHERE id=$id";
    
    if (mysqli_query($conn, $query)) {
        // Delete image file if exists
        if (file_exists($image_path) && !empty($row['image'])) {
            unlink($image_path);
        }
        $success = "Proyek berhasil dihapus!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Get project for editing
 $edit_project = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM projects WHERE id=$id");
    $edit_project = mysqli_fetch_assoc($result);
}

// Get all projects
 $projects_result = mysqli_query($conn, "SELECT * FROM projects ORDER BY year DESC, id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyek - Admin Dashboard</title>
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
        .project-card {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
            background: white;
            margin-bottom: 20px;
        }
        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(2,136,209,0.15);
        }
        .project-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
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
        .action-buttons {
            display: flex;
            gap: 5px;
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
            .project-card {
                margin-bottom: 15px;
            }
            .project-image {
                height: 150px;
            }
        }
        @media (max-width: 576px) {
            .content {
                padding: 15px;
            }
            .page-title {
                font-size: 24px;
            }
            .preview-image {
                max-width: 150px;
                max-height: 150px;
            }
            .action-buttons {
                flex-direction: column;
                gap: 5px;
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
                    <a href="projects.php" class="nav-link active">
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
        <h1 class="page-title">Proyek</h1>

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
                <h5 class="mb-0"><?php echo $edit_project ? 'Edit' : 'Tambah'; ?> Proyek</h5>
            </div>
            <div class="card-body">
                <form method="post" action="" enctype="multipart/form-data">
                    <?php if ($edit_project): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_project['id']; ?>">
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="year" class="form-label">Tahun</label>
                            <input type="text" class="form-control" id="year" name="year" value="<?php echo $edit_project ? $edit_project['year'] : ''; ?>" required>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="title" class="form-label">Judul Proyek</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo $edit_project ? $edit_project['title'] : ''; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="4"><?php echo $edit_project ? $edit_project['description'] : ''; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar Proyek</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <?php if ($edit_project && $edit_project['image']): ?>
                            <div class="mt-2">
                                <small class="text-muted">Gambar saat ini:</small>
                                <img src="../<?php echo $edit_project['image']; ?>" alt="Proyek" class="preview-image">
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-end flex-wrap gap-2">
                        <a href="projects.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary"><?php echo $edit_project ? 'Update' : 'Simpan'; ?></button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Daftar Proyek</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php if (mysqli_num_rows($projects_result) > 0): ?>
                        <?php while($project = mysqli_fetch_assoc($projects_result)) { ?>
                            <div class="col-lg-6 col-xl-4 mb-4">
                                <div class="project-card">
                                    <?php if ($project['image']): ?>
                                        <img src="../<?php echo $project['image']; ?>" alt="<?php echo $project['title']; ?>" class="project-image">
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <small class="text-muted"><?php echo $project['year']; ?></small>
                                        <h5 class="card-title"><?php echo $project['title']; ?></h5>
                                        <p class="card-text text-muted"><?php echo substr($project['description'], 0, 100) . (strlen($project['description']) > 100 ? '...' : ''); ?></p>
                                        <div class="action-buttons">
                                            <a href="projects.php?action=edit&id=<?php echo $project['id']; ?>" class="btn btn-sm btn-warning flex-fill">
                                                <i class="bi bi-pencil me-1"></i> Edit
                                            </a>
                                            <a href="projects.php?action=delete&id=<?php echo $project['id']; ?>" class="btn btn-sm btn-danger flex-fill" onclick="return confirm('Apakah Anda yakin ingin menghapus proyek ini?')">
                                                <i class="bi bi-trash me-1"></i> Hapus
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-folder" style="font-size: 48px; color: #ccc;"></i>
                            <h5 class="mt-3 text-muted">Belum ada proyek</h5>
                            <p class="text-muted">Tambahkan proyek pertama Anda menggunakan form di atas.</p>
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
        });
    </script>
</body>
</html>