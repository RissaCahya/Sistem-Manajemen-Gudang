<?php
include "koneksi.php";

// CREATE
if (isset($_POST['tambah'])) {
    $id         = $_POST['id'];
    $nama       = $_POST['nama'];
    $kategori   = $_POST['kategori'];
    $stok       = $_POST['stok'];
    $satuan     = $_POST['satuan'];
    $supplier   = $_POST['supplier'];

    mysqli_query($conn, "INSERT INTO kelola_barang (id, nama, kategori, stok, satuan, supplier)
                         VALUES ('$id', '$nama', '$kategori', '$stok', '$satuan', '$supplier')");
    header("Location: admin_kelola_barang.php");
}

// DELETE
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM kelola_barang WHERE id='$id'");
    header("Location: admin_kelola_barang.php");
}

// UPDATE
if (isset($_POST['update'])) {
    $id         = $_POST['id'];
    $nama       = $_POST['nama'];
    $kategori   = $_POST['kategori'];
    $stok       = $_POST['stok'];
    $satuan     = $_POST['satuan'];
    $supplier   = $_POST['supplier'];

    mysqli_query($conn, "UPDATE kelola_barang SET
                nama='$nama',
                kategori='$kategori',
                stok='$stok',
                satuan='$satuan',
                supplier='$supplier'
            WHERE id='$id'");

    header("Location: admin_kelola_barang.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Kelola Barang</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f6f9;
        padding: 20px;
    }
    h2 {
        background: #007bff;
        color: white;
        padding: 10px;
        width: fit-content;
        border-radius: 5px;
    }
    form {
        background: white;
        padding: 20px;
        border-radius: 5px;
        width: 400px;
        box-shadow: 0px 0px 5px #ccc;
        margin-bottom: 25px;
    }
    input {
        width: 100%;
        padding: 8px;
        margin-bottom: 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    button {
        padding: 10px 15px;
        border: none;
        background: #28a745;
        color: white;
        cursor: pointer;
        border-radius: 4px;
    }
    button:hover {
        opacity: 0.8;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        box-shadow: 0px 0px 5px #ccc;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }
    th {
        background: #007bff;
        color: white;
    }
    a {
        text-decoration: none;
        padding: 6px 10px;
        border-radius: 4px;
        color: white;
    }
    .edit { background: #ffc107; }
    .hapus { background: #dc3545; }
</style>
</head>

<body>

<h2>Tambah Barang</h2>
<form method="POST">
    <label>ID</label>
    <input type="text" name="id" required>

    <label>Nama</label>
    <input type="text" name="nama">

    <label>Kategori</label>
    <input type="text" name="kategori">

    <label>Stok</label>
    <input type="number" name="stok">

    <label>Satuan</label>
    <input type="text" name="satuan">

    <label>Supplier</label>
    <input type="text" name="supplier">

    <button type="submit" name="tambah">Tambah</button>
</form>

<!-- TABEL DATA -->
<h2>Data Barang</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Kategori</th>
        <th>Stok</th>
        <th>Satuan</th>
        <th>Supplier</th>
        <th>Aksi</th>
    </tr>

    <?php
    $result = mysqli_query($conn, "SELECT * FROM kelola_barang");

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['nama']}</td>
            <td>{$row['kategori']}</td>
            <td>{$row['stok']}</td>
            <td>{$row['satuan']}</td>
            <td>{$row['supplier']}</td>
            <td>
                <a class='edit' href='?edit={$row['id']}'>Edit</a>
                <a class='hapus' href='?hapus={$row['id']}' onclick='return confirm(\"Yakin hapus?\")'>Hapus</a>
            </td>
        </tr>";
    }
    ?>
</table>

<!-- FORM EDIT -->
<?php
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit = mysqli_query($conn, "SELECT * FROM kelola_barang WHERE id='$id'");
    $data = mysqli_fetch_assoc($edit);
?>
<br><br>
<h2>Edit Data Barang</h2>
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

    <button type="submit" name="update" style="background:#ffc107;">Update</button>
</form>
<?php } ?>

</body>
</html>
