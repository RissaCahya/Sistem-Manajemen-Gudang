<?php
include "koneksi.php";?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Team Neon — Interactive Profiles</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <!-- HEADER -->
  <header class="header">
    <div class="left-header">
      <h2>Profile Team</h2>
    </div>
    <div class="right-header">
      <a href="http://localhost/Sistem-Manajemen-Gudang/kelola_Barang.php" class="admin-btn">Admin</a>
    </div>
  </header>

  <main>
    <h2 class="headline">TEAM SISTEM MANAJEMEN GUDANG ROTI</h2>
    <p class="subtext">Klik salah satu profil kami untuk melihat detailnya! </p>

    <div class="carousel-wrapper">
      <button class="arrow left" id="prevBtn">‹</button>

      <div class="carousel">
        <div class="card" data-link="http://localhost/Sistem-Manajemen-Gudang/profiles/citra.php">
          <img src="assets/foto_citra.jpeg" class="avatar">
          <h3>Citra</h3>
        </div>

        <div class="card" data-link="http://localhost/Sistem-Manajemen-Gudang/profiles/verynna.php">
          <img src="assets/foto_verynna.jpeg" class="avatar">
          <h3>Verynna</h3>
        </div>

        <div class="card" data-link="http://localhost/Sistem-Manajemen-Gudang/profiles/rissa.php">
          <img src="assets/foto_rissa.jpeg" class="avatar">
          <h3>Rissa</h3>
        </div>
      </div>

      <button class="arrow right" id="nextBtn">›</button>
    </div>
  </main>
        <footer>
            <div class="wadah-footer">
                <div class="footer-center">
                    <p>&copy; 2025 Sistem Manajemen Gudang Roti. All Rights Reserved.</p>
                </div>
            </div>
        </footer>
  <script src="script.js"></script>
</body>
</html>
