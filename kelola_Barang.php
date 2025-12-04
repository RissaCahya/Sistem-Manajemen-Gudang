<?php
include "koneksi.php";

/* ==============================
    CREATE (Tambah Data)
============================== */
if (isset($_POST['tambah'])) {
    $id           = $_POST['id'];
    $nama         = $_POST['nama'];
    $kategori     = $_POST['kategori'];
    $stok         = $_POST['stok'];
    $satuan       = $_POST['satuan'];
    $supplier     = $_POST['supplier'];
    $waktu_keluar = $_POST['waktu_keluar'];

    if ($waktu_keluar == "") {
        $waktu_keluar = "0000-00-00 00:00:00";
    }

    mysqli_query($conn, "INSERT INTO kelola_barang
        (id, nama, kategori, stok, satuan, supplier, waktu_keluar)
        VALUES ('$id', '$nama', '$kategori', '$stok', '$satuan', '$supplier', '$waktu_keluar')");

    header("Location: admin_kelola_barang.php");
    exit;
}

/* ==============================
    DELETE
============================== */
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query($conn, "DELETE FROM kelola_barang WHERE id='$id'");
    header("Location: admin_kelola_barang.php");
    exit;
}

/* ==============================
    UPDATE
============================== */
if (isset($_POST['update'])) {
    $id           = $_POST['id'];
    $nama         = $_POST['nama'];
    $kategori     = $_POST['kategori'];
    $stok         = $_POST['stok'];
    $satuan       = $_POST['satuan'];
    $supplier     = $_POST['supplier'];
    $waktu_keluar = $_POST['waktu_keluar'];

    mysqli_query($conn, "UPDATE kelola_barang SET
        nama='$nama',
        kategori='$kategori',
        stok='$stok',
        satuan='$satuan',
        supplier='$supplier',
        waktu_keluar='$waktu_keluar'
        WHERE id='$id'");

    header("Location: admin_kelola_barang.php");
    exit;
}
?>

<!-- ==============================
        HTML + CSS
============================== -->

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Kelola Barang</title>

<style>
/* GLOBAL */
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f7f7f7;
    background-color: #f5f5dc;
}

.container {
    width: 90%;
    margin: 20px auto;
}

/* SECTION TITLE */
.section-title {
    font-size: 22px;
    font-weight: bold;
    color: #783f04;
    margin-top: 30px;
    border-bottom: 3px solid #c9832c;
    display: inline-block;
    padding-bottom: 5px;
}

/* CARD */
.card {
    background: #fff;
    padding: 25px;
    margin-top: 15px;
    border-radius: 12px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.12);
}

/* FORM */
form label {
    display: block;
    font-weight: bold;
    margin-top: 12px;
    color: #4b2d0d;
}

form input {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    margin-top: 5px;
}

.btn {
    padding: 10px 20px;
    margin-top: 15px;
    border: none;
    background: #ad6823;
    color: white;
    border-radius: 6px;
    cursor: pointer;
}

.btn:hover {
    background: #8c541b;
}

/* TABLE */
.table-crud {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.table-crud th {
    background: #783f04;
    color: white;
    padding: 12px;
    text-align: left;
}

.table-crud td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

/* ACTION BUTTONS */
.actions a {
    padding: 7px 12px;
    border-radius: 6px;
    color: #fff;
    text-decoration: none;
    font-size: 14px;
    margin-right: 5px;
}

.edit { background: #4285f4; }
.delete { background: #d9534f; }
.edit:hover { background: #3367d6; }
.delete:hover { background: #b52b27; }

.update-btn {
    background: #ffc107;
    color: #000;
    border-radius: 6px;
    padding: 10px 20px;
    margin-top: 15px;
}

.update-btn:hover {
    background: #e0a800;
}
</style>

</head>
<body>

<div class="container">

<!-- ==========================================
               TAMBAH BARANG
=========================================== -->
<h2 class="section-title">Tambah Barang</h2>

<div class="card">
    <form method="POST">

        <label>ID</label>
        <input type="text" name="id" required>

        <label>Nama</label>
        <input type="text" name="nama" required>

        <label>Kategori</label>
        <input type="text" name="kategori">

        <label>Stok</label>
        <input type="number" name="stok">

        <label>Satuan</label>
        <input type="text" name="satuan">

        <label>Supplier</label>
        <input type="text" name="supplier">

        <label>Waktu Keluar (opsional)</label>
        <input type="datetime-local" name="waktu_keluar">

        <button type="submit" class="btn" name="tambah">Tambah</button>
    </form>
</div>

<!-- ==========================================
               TABEL DATA BARANG
=========================================== -->
<h2 class="section-title">Data Barang</h2>

<div class="card">
<table class="table-crud">
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

    <?php
    $result = mysqli_query($conn, "SELECT * FROM kelola_barang");

    while ($row = mysqli_fetch_assoc($result)) {
        echo "
        <tr>
            <td>{$row['id']}</td>
            <td>{$row['nama']}</td>
            <td>{$row['kategori']}</td>
            <td>{$row['stok']}</td>
            <td>{$row['satuan']}</td>
            <td>{$row['supplier']}</td>
            <td>{$row['waktu_masuk']}</td>
            <td>{$row['waktu_keluar']}</td>
            <td class='actions'>
                <a class='edit' href='?edit={$row['id']}'>Edit</a>
                <a class='delete' href='?hapus={$row['id']}' onclick='return confirm(\"Yakin hapus?\")'>Hapus</a>
            </td>
        </tr>";
    }
    ?>
</table>
</div>

<!-- ==========================================
               FORM EDIT DATA
=========================================== -->
<?php
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit = mysqli_query($conn, "SELECT * FROM kelola_barang WHERE id='$id'");
    $data = mysqli_fetch_assoc($edit);
?>

<h2 class="section-title">Edit Data Barang</h2>

<div class="card">
    <form method="POST">

        <input type="hidden" name="id" value="<?= $data['id']; ?>">

        <label>Nama</label>
        <input type="text" name="nama" value="<?= $data['nama']; ?>">

        <label>Kategori</label>
        <input type="text" name="kategori" value="<?= $data['kategori']; ?>">

        <label>Stok</label>
        <input type="number" name="stok" value="<?= $data['stok']; ?>">

        <label>Satuan</label>
        <input type="text" name="satuan" value="<?= $data['satuan']; ?>">

        <label>Supplier</label>
        <input type="text" name="supplier" value="<?= $data['supplier']; ?>">

        <label>Waktu Masuk</label>
        <input type="text" readonly value="<?= $data['waktu_masuk']; ?>">

        <label>Waktu Keluar</label>
        <input type="datetime-local" 
               name="waktu_keluar"
               value="<?= ($data['waktu_keluar'] != '0000-00-00 00:00:00') 
                    ? date('Y-m-d\TH:i', strtotime($data['waktu_keluar'])) 
                    : '' ?>">

        <button type="submit" class="update-btn" name="update">Update</button>

    </form>
</div>

<?php } ?>

</div>

</body>
</html>
