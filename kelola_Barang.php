<?php
include "koneksi.php";

$msg = "";
$err = "";

/* -----------------------------
   HELPERS: sanitize & fetch
------------------------------*/
function esc($conn, $s) {
    return mysqli_real_escape_string($conn, trim($s));
}

// fetch all items (for listing and select)
function get_items($conn) {
    $out = [];
    $res = mysqli_query($conn, "SELECT * FROM kelola_barang ORDER BY nama ASC");
    if ($res) {
        while ($r = mysqli_fetch_assoc($res)) $out[] = $r;
    }
    return $out;
}

/* ==============================
   ACTION: Tambah Barang Baru
   Nama form button: tambah_barang
   ============================== */
if (isset($_POST['tambah_barang'])) {
    $id       = esc($conn, $_POST['id']);
    $nama     = esc($conn, $_POST['nama']);
    $kategori = esc($conn, $_POST['kategori']);
    $stok     = (int)$_POST['stok'];
    $satuan   = esc($conn, $_POST['satuan']);
    $supplier = esc($conn, $_POST['supplier']);

    if ($id == "" || $nama == "") {
        $err = "ID dan Nama wajib diisi.";
    } else {
        // check duplicate id
        $stmt = mysqli_prepare($conn, "SELECT id FROM kelola_barang WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $err = "ID sudah ada. Gunakan ID lain atau hapus data yang lama.";
        } else {
            mysqli_stmt_close($stmt);

            // waktu_masuk set NOW() jika stok > 0, else NULL
            $waktu_masuk_sql = $stok > 0 ? "NOW()" : "NULL";

            // prepared insert
            $sql = "INSERT INTO kelola_barang (id, nama, kategori, stok, satuan, supplier, waktu_masuk, waktu_keluar)
                    VALUES (?,?,?,?,?,?," . $waktu_masuk_sql . ", NULL)";

            $stmt2 = mysqli_prepare($conn, $sql);
            if ($stmt2 === false) {
                $err = "Kesalahan query: " . mysqli_error($conn);
            } else {
                mysqli_stmt_bind_param($stmt2, "sssiss", $id, $nama, $kategori, $stok, $satuan, $supplier);
                if (mysqli_stmt_execute($stmt2)) {
                    $msg = "Barang berhasil ditambahkan.";
                } else {
                    $err = "Gagal menambahkan barang: " . mysqli_stmt_error($stmt2);
                }
                mysqli_stmt_close($stmt2);
            }
        }
    }
    // redirect agar tidak resubmit jika reload
    if ($msg || $err) {
        header("Location: kelola_Barang.php?msg=" . urlencode($msg) . "&err=" . urlencode($err));
        exit;
    }
}

/* ==============================
   ACTION: Barang Masuk (tambah stok)
   Nama form button: barang_masuk
   ============================== */
if (isset($_POST['barang_masuk'])) {
    $id_barang = esc($conn, $_POST['id_barang_masuk']);
    $jumlah    = (int)$_POST['jumlah_masuk'];

    if ($id_barang == "" || $jumlah <= 0) {
        $err = "Pilih barang dan masukkan jumlah masuk yang valid.";
    } else {
        // update stok: stok = stok + jumlah, waktu_masuk = NOW()
        $stmt = mysqli_prepare($conn, "UPDATE kelola_barang SET stok = stok + ?, waktu_masuk = NOW() WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "is", $jumlah, $id_barang);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $msg = "Stok berhasil ditambah.";
            } else {
                $err = "Barang tidak ditemukan.";
            }
        } else {
            $err = "Gagal update stok: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    }

    if ($msg || $err) {
        header("Location: kelola_Barang.php?msg=" . urlencode($msg) . "&err=" . urlencode($err));
        exit;
    }
}

/* ==============================
   ACTION: Barang Keluar (kurangi stok)
   Nama form button: barang_keluar
   ============================== */
if (isset($_POST['barang_keluar'])) {
    $id_barang = esc($conn, $_POST['id_barang_keluar']);
    $jumlah    = (int)$_POST['jumlah_keluar'];

    if ($id_barang == "" || $jumlah <= 0) {
        $err = "Pilih barang dan masukkan jumlah keluar yang valid.";
    } else {
        // cek stok sekarang
        $stmt = mysqli_prepare($conn, "SELECT stok FROM kelola_barang WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "s", $id_barang);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $stok_saat_ini);
        if (mysqli_stmt_fetch($stmt)) {
            mysqli_stmt_close($stmt);
            if ($stok_saat_ini < $jumlah) {
                $err = "Stok tidak cukup. Stok saat ini: {$stok_saat_ini}.";
            } else {
                // kurangi stok dan set waktu_keluar = NOW()
                $stmt2 = mysqli_prepare($conn, "UPDATE kelola_barang SET stok = stok - ?, waktu_keluar = NOW() WHERE id = ?");
                mysqli_stmt_bind_param($stmt2, "is", $jumlah, $id_barang);
                if (mysqli_stmt_execute($stmt2)) {
                    if (mysqli_stmt_affected_rows($stmt2) > 0) {
                        $msg = "Transaksi keluar berhasil. Stok dikurangi {$jumlah}.";
                    } else {
                        $err = "Barang tidak ditemukan saat update.";
                    }
                } else {
                    $err = "Gagal update stok keluar: " . mysqli_stmt_error($stmt2);
                }
                mysqli_stmt_close($stmt2);
            }
        } else {
            $err = "Barang tidak ditemukan.";
            mysqli_stmt_close($stmt);
        }
    }

    if ($msg || $err) {
        header("Location: kelola_Barang.php?msg=" . urlencode($msg) . "&err=" . urlencode($err));
        exit;
    }
}

/* ==============================
   ACTION: Hapus & Edit (via GET/POST)
============================== */
if (isset($_GET['hapus'])) {
    $id = esc($conn, $_GET['hapus']);
    $stmt = mysqli_prepare($conn, "DELETE FROM kelola_barang WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "s", $id);
    if (mysqli_stmt_execute($stmt)) {
        $msg = "Data dihapus.";
    } else {
        $err = "Gagal hapus: " . mysqli_stmt_error($stmt);
    }
    mysqli_stmt_close($stmt);
    header("Location: kelola_Barang.php?msg=" . urlencode($msg) . "&err=" . urlencode($err));
    exit;
}

// Update via Edit form handled by existing 'update' action earlier; keep compatibility
if (isset($_POST['update'])) {
    $id           = esc($conn, $_POST['id']);
    $nama         = esc($conn, $_POST['nama']);
    $kategori     = esc($conn, $_POST['kategori']);
    $stok         = (int)$_POST['stok'];
    $satuan       = esc($conn, $_POST['satuan']);
    $supplier     = esc($conn, $_POST['supplier']);
    $waktu_keluar = isset($_POST['waktu_keluar']) && $_POST['waktu_keluar'] !== "" ? esc($conn, $_POST['waktu_keluar']) : NULL;

    // prepare update - set waktu_keluar to provided datetime or NULL
    if ($waktu_keluar === NULL) {
        $sql = "UPDATE kelola_barang SET nama=?, kategori=?, stok=?, satuan=?, supplier=?, waktu_keluar = NULL WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sisiss", $nama, $kategori, $stok, $satuan, $supplier, $id);
    } else {
        $sql = "UPDATE kelola_barang SET nama=?, kategori=?, stok=?, satuan=?, supplier=?, waktu_keluar = ? WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sisisss", $nama, $kategori, $stok, $satuan, $supplier, $waktu_keluar, $id);
    }

    if ($stmt) {
        if (mysqli_stmt_execute($stmt)) {
            $msg = "Data berhasil diperbarui.";
        } else {
            $err = "Gagal update: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $err = "Kesalahan query update: " . mysqli_error($conn);
    }

    header("Location: kelola_Barang.php?msg=" . urlencode($msg) . "&err=" . urlencode($err));
    exit;
}

/* show messages from redirect */
if (isset($_GET['msg']) && $_GET['msg'] !== "") $msg = esc($conn, $_GET['msg']);
if (isset($_GET['err']) && $_GET['err'] !== "") $err = esc($conn, $_GET['err']);

$items = get_items($conn);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Kelola Barang (Masuk & Keluar) </title>

<style>
:root {
    --brown-dark: #783f04;
    --brown-mid: #ad6823;
    --cream: #f3f0d5;
    --blue: #4285f4;
    --red: #d9534f;
    --card-bg: #fff;
}

* { box-sizing: border-box; }

body {
    margin: 0;
    font-family: Inter, "Segoe UI", Roboto, Arial, sans-serif;
    background: #f7f7f3;
    color: #222;
    font-size: 16px;
    line-height: 1.4;
}

.header {
    background: linear-gradient(90deg, var(--brown-dark), var(--brown-mid));
    color: white;
    padding: 18px 28px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header h1 { margin:0; font-size: 20px; letter-spacing: .2px; }
.header small { opacity: .9; font-weight: 400; }

.btn-kembali {
    display: inline-block;
    background: #ad6823;
    color: white;
    padding: 10px 18px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    margin-bottom: 8px;
    margin-top: 5px;
    transition: background 0.2s;
}

.btn-kembali:hover {
    background: #8c541b;
}

.container {
    width: 95%;
    max-width: 1200px;
    margin: 22px auto;
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 22px;
}

/* main column */
.main {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* card */
.card {
    background: var(--card-bg);
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.06);
}

/* title */
.section-title {
    font-size: 22px;
    color: var(--brown-dark);
    margin: 0 0 12px 0;
    font-weight: 700;
    border-bottom: 3px solid #c9832c;
    display: inline-block;
    padding-bottom: 6px;
}

/* form fields */
label {
    display: block;
    font-weight: 600;
    color: #4b2d0d;
    margin-top: 12px;
    font-size: 15px;
}
input[type="text"],
input[type="number"],
input[type="datetime-local"],
select {
    width: 100%;
    padding: 12px 14px;
    margin-top: 6px;
    border-radius: 8px;
    border: 1px solid #d1cfc9;
    background: #fff;
    font-size: 15px;
}

/* buttons */
.row { display:flex; gap:12px; align-items:center; flex-wrap:wrap; margin-top:12px; }
.btn {
    padding: 12px 18px;
    border-radius: 9px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    font-size: 15px;
}
.btn-primary { background: var(--brown-mid); color: #fff; }
.btn-primary:hover { background: #8c541b; }
.btn-secondary { background: var(--blue); color: #fff; }
.btn-danger { background: var(--red); color:#fff; }
.btn-warning { background: #ffc107; color: #000; }

/* table */
.table-crud {
    width:100%;
    border-collapse: collapse;
    margin-top: 14px;
    font-size: 15px;
}
.table-crud th {
    background: var(--brown-dark);
    color: white;
    text-align: left;
    padding: 12px;
    font-size: 15px;
}
.table-crud td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}

/* actions */
.actions a {
    display:inline-block;
    padding: 8px 12px;
    border-radius: 8px;
    color:#fff;
    text-decoration:none;
    font-weight:600;
    font-size: 14px;
}
.actions .edit { background: var(--blue); }
.actions .delete { background: var(--red); }

/* right sidebar */
.sidebar {
    display:flex;
    flex-direction:column;
    gap:18px;
}
.panel {
    background: linear-gradient(180deg, #e8f2ff, #ffffff);
    padding: 18px;
    border-radius: 12px;
}

/* messages */
.alert {
    padding: 12px 14px;
    border-radius: 9px;
    margin-bottom: 6px;
    font-weight:600;
}
.alert-success { background: #e6f7ea; color: #0b6b26; }
.alert-error { background: #ffe6e6; color: #b70000; }

/* responsive */
@media (max-width: 920px) {
    .container { grid-template-columns: 1fr; padding: 12px; }
    .sidebar { order: 2; }
}
</style>
</head>
<body>

<header class="header">
    <a href="index.php" class="btn-kembali"> ◀ Kembali ke Beranda</a>
    <div>
        <h1>Admin - Kelola Barang</h1>
        <small>Tambah barang / Barang masuk / Barang keluar</small>
    </div>
    <div>
        <small>Selamat datang, Admin</small>
    </div>
</header>

<div class="container">

    <div class="main">

        <!-- messages -->
        <?php if ($msg): ?>
            <div class="card">
                <div class="alert alert-success"><?= htmlspecialchars($msg); ?></div>
            </div>
        <?php endif; ?>

        <?php if ($err): ?>
            <div class="card">
                <div class="alert alert-error"><?= htmlspecialchars($err); ?></div>
            </div>
        <?php endif; ?>

        <!-- ADD ITEM -->
        <div class="card">
            <h2 class="section-title">Tambah Barang Baru</h2>
            <form method="POST">
                <label for="id">ID (unik)</label>
                <input id="id" name="id" type="text" placeholder="Masukkan ID barang (unik)" required>

                <label for="nama">Nama Barang</label>
                <input id="nama" name="nama" type="text" placeholder="Nama barang" required>

                <label for="kategori">Kategori</label>
                <select id="kategori" name="kategori" required>
                    <option value="Bahan Baku">Bahan Baku</option>
                    <option value="Barang Jadi">Barang Jadi</option>
                </select>

                <label for="stok">Stok Awal</label>
                <input id="stok" name="stok" type="number" value="0" min="0">

                <label for="satuan">Satuan</label>
                <input id="satuan" name="satuan" type="text" placeholder="Misal: Pcs, Kg">

                <label for="supplier">Supplier</label>
                <input id="supplier" name="supplier" type="text" placeholder="Nama supplier (opsional)">

                <div class="row">
                    <button class="btn btn-primary" name="tambah_barang" type="submit">Tambah Barang</button>
                </div>
            </form>
        </div>

        <!-- STOK (TABLE) -->
        <div class="card">
            <h2 class="section-title">Daftar Barang</h2>
            <table class="table-crud">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                        <th>Supplier</th>
                        <th>Waktu Masuk</th>
                        <th>Waktu Keluar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($items) == 0): ?>
                        <tr><td colspan="9" style="padding:18px;">Belum ada data.</td></tr>
                    <?php else: ?>
                        <?php foreach ($items as $it): ?>
                            <tr>
                                <td><?= htmlspecialchars($it['id']); ?></td>
                                <td><?= htmlspecialchars($it['nama']); ?></td>
                                <td><?= htmlspecialchars($it['kategori']); ?></td>
                                <td><?= (int)$it['stok']; ?></td>
                                <td><?= htmlspecialchars($it['satuan']); ?></td>
                                <td><?= htmlspecialchars($it['supplier']); ?></td>
                                <td><?= $it['waktu_masuk'] ? htmlspecialchars($it['waktu_masuk']) : '-'; ?></td>
                                <td><?= $it['waktu_keluar'] ? htmlspecialchars($it['waktu_keluar']) : '-'; ?></td>
                                <td class="actions">
                                    <a class="edit" href="?edit=<?= urlencode($it['id']); ?>">Edit</a>
                                    <a class="delete" href="?hapus=<?= urlencode($it['id']); ?>" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- EDIT FORM (if chosen) -->
        <?php if (isset($_GET['edit'])):
            $edit_id = esc($conn, $_GET['edit']);
            $stmt = mysqli_prepare($conn, "SELECT * FROM kelola_barang WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "s", $edit_id);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            $d = mysqli_fetch_assoc($res);
            mysqli_stmt_close($stmt);
            if ($d):
        ?>
        <div class="card">
            <h2 class="section-title">Edit Barang (ID: <?= htmlspecialchars($d['id']); ?>)</h2>
            <form method="POST">
                <input type="hidden" name="id" value="<?= htmlspecialchars($d['id']); ?>">

                <label>Nama</label>
                <input name="nama" type="text" value="<?= htmlspecialchars($d['nama']); ?>" required>

                <label>Kategori</label>
                <select name="kategori" required>
                    <option value="Bahan Baku" <?= $d['kategori'] == "Bahan Baku" ? "selected" : ""; ?>>Bahan Baku</option>
                    <option value="Barang Jadi" <?= $d['kategori'] == "Barang Jadi" ? "selected" : ""; ?>>Barang Jadi</option>
                </select>

                <label>Stok</label>
                <input name="stok" type="number" min="0" value="<?= (int)$d['stok']; ?>">

                <label>Satuan</label>
                <input name="satuan" type="text" value="<?= htmlspecialchars($d['satuan']); ?>">

                <label>Supplier</label>
                <input name="supplier" type="text" value="<?= htmlspecialchars($d['supplier']); ?>">

                <label>Waktu Masuk</label>
                <input type="text" readonly value="<?= $d['waktu_masuk'] ? htmlspecialchars($d['waktu_masuk']) : '-'; ?>">

                <label>Waktu Keluar (opsional)</label>
                <input name="waktu_keluar" type="datetime-local" value="<?= ($d['waktu_keluar'] && $d['waktu_keluar'] != '0000-00-00 00:00:00') ? date('Y-m-d\TH:i', strtotime($d['waktu_keluar'])) : ''; ?>">

                <div class="row">
                    <button class="btn btn-warning" name="update" type="submit">Update</button>
                    <a class="btn btn-secondary" href="admin_kelola_barang.php" style="text-decoration:none;color:white;">Batal</a>
                </div>
            </form>
        </div>
        <?php
            endif;
        endif;
        ?>

    </div> 

    <!-- RIGHT SIDEBAR: Barang Masuk & Keluar -->
    <aside class="sidebar">

        <div class="panel card">
            <h3 style="margin:0 0 8px 0; color:var(--brown-dark);">Form Barang Masuk</h3>
            <form method="POST">
                <label>Pilih Barang</label>
                <select name="id_barang_masuk" required>
                    <option value="">-- Pilih barang --</option>
                    <?php foreach ($items as $it): ?>
                        <option value="<?= htmlspecialchars($it['id']); ?>"><?= htmlspecialchars($it['nama'] . " — [" . $it['id'] . "] (stok: " . (int)$it['stok'] . ")"); ?></option>
                    <?php endforeach; ?>
                </select>

                <label>Jumlah Masuk</label>
                <input name="jumlah_masuk" type="number" min="1" value="1" required>

                <div class="row">
                    <button class="btn btn-primary" name="barang_masuk" type="submit">Proses Masuk</button>
                </div>
            </form>
        </div>

        <div class="panel card">
            <h3 style="margin:0 0 8px 0; color:var(--brown-dark);">Form Barang Keluar</h3>
            <form method="POST">
                <label>Pilih Barang</label>
                <select name="id_barang_keluar" required>
                    <option value="">-- Pilih barang --</option>
                    <?php foreach ($items as $it): ?>
                        <option value="<?= htmlspecialchars($it['id']); ?>"><?= htmlspecialchars($it['nama'] . " — [" . $it['id'] . "] (stok: " . (int)$it['stok'] . ")"); ?></option>
                    <?php endforeach; ?>
                </select>

                <label>Jumlah Keluar</label>
                <input name="jumlah_keluar" type="number" min="1" value="1" required>

                <div class="row">
                    <button class="btn btn-danger" name="barang_keluar" type="submit">Proses Keluar</button>
                </div>
            </form>
        </div>

        <div class="panel card">
            <h4 style="margin:0 0 6px 0; color:var(--brown-dark);">Petunjuk Singkat</h4>
            <ul style="margin:8px 0 0 18px; padding:0; color:#333;">
                <li>Tambah barang untuk membuat entri baru.</li>
                <li>Gunakan <strong>Barang Masuk</strong> untuk menambah stok.</li>
                <li>Gunakan <strong>Barang Keluar</strong> untuk mengurangi stok (cek sisa).</li>
            </ul>
        </div>

    </aside>

</div> <!-- container -->

        <footer>
            <div class="wadah-footer">
                <div class="footer-center">
                    <p>&copy; 2025 Sistem Manajemen Gudang Roti. All Rights Reserved.</p>
                </div>
            </div>
        </footer>
        
</body>
</html>
