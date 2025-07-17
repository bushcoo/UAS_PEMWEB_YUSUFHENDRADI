<?php
session_start();
// Koneksi DB
$host = "localhost";
$user = "root";
$pass = "";
$db   = "todo_app";
mysqli_report(MYSQLI_REPORT_OFF); // Nonaktifkan exception MySQLi
$conn = @new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    $_SESSION['notif'] = ['type' => 'danger', 'msg' => 'Koneksi ke database gagal!'];
}

// Tambah tugas
if (isset($_POST['add'])) {
    $judul = trim($_POST['judul'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $status = $_POST['status'] ?? 'Belum Selesai';
    if ($judul === '') {
        $_SESSION['notif'] = ['type' => 'danger', 'msg' => 'Judul tidak boleh kosong!'];
    } else {
        $stmt = $conn->prepare("INSERT INTO todos (judul, deskripsi, status) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sss", $judul, $deskripsi, $status);
            $stmt->execute();
            $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Tugas berhasil ditambahkan!'];
        } else {
            $_SESSION['notif'] = ['type' => 'danger', 'msg' => 'Gagal menambah tugas.'];
        }
    }
    header("Location: index.php");
    exit;
}

// Update tugas
if (isset($_POST['update'])) {
    $id = $_POST['id'] ?? '';
    $judul = trim($_POST['judul'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $status = $_POST['status'] ?? 'Belum Selesai';
    if ($judul === '' || !$id) {
        $_SESSION['notif'] = ['type' => 'danger', 'msg' => 'Judul tidak boleh kosong!'];
    } else {
        $stmt = $conn->prepare("UPDATE todos SET judul=?, deskripsi=?, status=? WHERE id=?");
        if ($stmt) {
            $stmt->bind_param("sssi", $judul, $deskripsi, $status, $id);
            $stmt->execute();
            $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Tugas berhasil diupdate!'];
        } else {
            $_SESSION['notif'] = ['type' => 'danger', 'msg' => 'Gagal update tugas.'];
        }
    }
    header("Location: index.php");
    exit;
}

// Hapus tugas
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM todos WHERE id=?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Tugas berhasil dihapus!'];
    } else {
        $_SESSION['notif'] = ['type' => 'danger', 'msg' => 'Gagal menghapus tugas.'];
    }
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>To-Do List - Satu Halaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <div class="container py-5">
        <div class="main-card mx-auto" style="max-width: 900px;">
            <div class="header text-center mb-4">
                <h2 class="mb-1"><i class="fas fa-list-check me-2"></i>To-Do List</h2>
                <div style="font-size:1.1em;">Kelola tugas harianmu dengan mudah</div>
            </div>

            <!-- Notifikasi -->
            <?php if (!empty($_SESSION['notif'])): ?>
                <div class="alert alert-<?= $_SESSION['notif']['type'] ?> notif-anim alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['notif']['msg']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['notif']); ?>
            <?php endif; ?>

            <!-- Form Tambah -->
            <form method="POST" class="card p-3 mb-4 border-0 shadow-sm" style="border-radius: 12px;">
                <h5 class="mb-3"><i class="fas fa-plus-circle me-2 text-primary"></i>Tambah Tugas</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="judul" class="form-control" placeholder="Judul" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="Belum Selesai">Belum Selesai</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" name="add" class="btn btn-primary w-100">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                    </div>
                </div>
            </form>

            <!-- Daftar Tugas -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0 align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Judul</th>
                                <th>Deskripsi</th>
                                <th width="170">Status</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $editId = $_GET['edit'] ?? null;
                            $result = @$conn->query("SELECT * FROM todos ORDER BY id DESC");
                            if ($result && $result->num_rows > 0):
                                while ($row = $result->fetch_assoc()):
                                    if ($editId == $row['id']):
                            ?>
                                        <!-- Form Edit -->
                                        <tr>
                                            <form method="POST">
                                                <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                                                <td><input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($row['judul']) ?>" required></td>
                                                <td><input type="text" name="deskripsi" class="form-control" value="<?= htmlspecialchars($row['deskripsi']) ?>"></td>
                                                <td>
                                                    <select name="status" class="form-select">
                                                        <option value="Belum Selesai" <?= $row['status'] == 'Belum Selesai' ? 'selected' : '' ?>>Belum Selesai</option>
                                                        <option value="Selesai" <?= $row['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                    <button type="submit" name="update" class="btn btn-success btn-sm"><i class="fas fa-save"></i></button>
                                                    <a href="index.php" class="btn btn-secondary btn-sm"><i class="fas fa-times"></i></a>
                                                </td>
                                            </form>
                                        </tr>
                                    <?php else: ?>
                                        <!-- Tampilan Normal -->
                                        <tr>
                                            <td><?= htmlspecialchars($row['judul']) ?></td>
                                            <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                                            <td class="text-center">
                                                <span class="badge badge-status bg-<?= $row['status'] == 'Selesai' ? 'success' : 'secondary' ?>">
                                                    <?= htmlspecialchars($row['status']) ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="?edit=<?= (int)$row['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-pen"></i></a>
                                                <a href="?delete=<?= (int)$row['id'] ?>" onclick="return confirm('Yakin hapus tugas ini?')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                <?php endif;
                                endwhile;
                            elseif ($result): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Tidak ada data.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="footer pb-3">
                &copy; <?= date('Y') ?> To-Do List App &middot; Dibuat dengan <i class="fas fa-heart text-danger"></i> di Indonesia
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>