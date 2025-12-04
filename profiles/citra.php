<?php
include "../koneksi.php";?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../profile.css">
    <title>Profile Citra Dewi Cahya Nanda Zulfani</title>
</head>
<body>
    <div class="container">
         <header>
            <div class="left-header">
                <img id="fotoku" src="../assets/citra.jpg" alt="Foto Citra">
                <span id="nama">Citra Dewi Cahya Nanda Zulfani</span>

        <!-- Dropdown nama -->
                <ul class="dropdown-name">
                    <li><a href="rissa.php">Rissa</a></li>
                    <li><a href="verynna.php">Verynna</a></li>
                    <li><a href="../index.php">Dashboard</a></li>
                </ul>
            </div>

    <!-- INI BAGIAN TAHAP 3 -->
            <div class="right-header">
                <a href="http://localhost/Sistem-Manajemen-Gudang/kelola_Barang.php" class="admin-btn">Admin</a>
            </div>
        </header>

        <main>
            <nav>
                <ul>
                    <li><a href="#biodata" onclick="showContent('biodata'); return false;">Biodata</a></li>
                    <li><a href="#pendidikan" onclick="showContent('pendidikan'); return false;">Pendidikan</a></li>
                    <li><a href="#pengalaman" onclick="showContent('pengalaman'); return false;">Pengalaman</a></li>
                    <li><a href="#keahlian" onclick="showContent('keahlian'); return false;">Keahlian</a></li>
                    <li><a href="#publikasi" onclick="showContent('publikasi'); return false;">Publikasi</a></li>
                </ul>
            </nav>

            <article>
                <section id="biodata" class="content active">
                    <h2>Biodata</h2>
                    <table>
                        <tr>
                            <td>NIM</td>
                            <td>:</td>
                            <td>2024081002</td>
                        </tr>
                        <tr>
                            <td>Nama Lengkap</td>
                            <td>:</td>
                            <td>Citra Dewi Cahya Nanda Zulfani</td>
                        </tr>
                        <tr>
                            <td>Agama</td>
                            <td>:</td>
                            <td>Islam</td>
                        </tr>
                        <tr>
                            <td>Tanggal Lahir</td>
                            <td>:</td>
                            <td>09 Juni 2006</td>
                        </tr>
                        <tr>
                            <td>Tempat Lahir</td>
                            <td>:</td>
                            <td>Jakarta</td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>:</td>
                            <td>Perempuan</td>
                        </tr>
                    </table>
                </section>

                <section id="pendidikan" class="content">
                    <h2>Pendidikan</h2>
                    <div class="timeline">
                        <div class="timeline-item">
                            <h3>Universitas Pembangunan Jaya</h3>
                            <p class="periode">2024 - Sekarang</p>
                            <p>S1 Sistem Informasi</p>
                        </div>
                        <div class="timeline-item">
                            <h3>SMA Negeri Jumapolo</h3>
                            <p class="periode">2021 - 2024</p>
                            <p>IPA</p>
                        </div>
                        <div class="timeline-item">
                            <h3>SMP Negeri 2 Jatipuro</h3>
                            <p class="periode">2018 - 2021</p>
                        </div>
                        <div class="timeline-item">
                            <h3>SD Negeri 13 Jatiwarno</h3>
                            <p class="periode">2012 - 2018</p>
                        </div>
                    </div>
                </section>

                <section id="pengalaman" class="content">
                    <h2>Pengalaman</h2>
                    <div class="timeline">
                        <div class="timeline-item">
                            <h3>Pelatihan Python 2025</h3>
                            <p class="periode">Universitas Pembangunan Jaya</p>
                            <ul>
                                <li>Menjadi Instruktur Pelatihan Python 2025</li>
                                <li>Melakukan bimbingan peserta untuk melatih skill python</li>
                            </ul>
                        </div>
                        <div class="timeline-item">
                            <h3>Prodi Gathering Sistem Informasi 2025</h3>
                            <p class="periode">Universitas Pembangunan Jaya</p>
                            <ul>
                                <li>Menjadi Sekretaris Prodi Gathering Sistem Informasi 2025</li>
                                <li>Mencatat perkembangan setiap divisi</li>
                                <li>Membuat proposal</li>
                                <li>Membuat surat menyurat</li>
                                <li>Membuat surat kerja</li>
                            </ul>
                        </div>
                        <div class="timeline-item">
                            <h3>Badan Pengurus Harian UKM</h3>
                            <p class="periode">Universitas Pembangunan Jaya</p>
                            <ul>
                                <li>Menjadi Bendahara salah satu UKM Universitas Pembangunan Jaya</li>
                                <li>Mencatat kas per bulan</li>
                                <li>Mencatat pengeluaran dan pemasukan</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <section id="keahlian" class="content">
                    <h2>Keahlian</h2>
                    <div class="skill-container">
                        <div class="skill-item">
                            <h4>Programming</h4>
                            <ul>
                                <li>HTML & CSS</li>
                                <li>JavaScript</li>
                                <li>PHP</li>
                                <li>Python</li>
                            </ul>
                        </div>
                        <div class="skill-item">
                            <h4>Framework & Tools</h4>
                            <ul>
                                <li>Git & GitHub</li>
                                <li>VS Code</li>
                                <li>Figma</li>
                            </ul>
                        </div>
                        <div class="skill-item">
                            <h4>Database</h4>
                            <ul>
                                <li>MySQL</li>
                            </ul>
                        </div>
                        <div class="skill-item">
                            <h4>Soft Skills</h4>
                            <ul>
                                <li>Teamwork</li>
                                <li>Problem Solving</li>
                                <li>Time Management</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <section id="publikasi" class="content">
                    <h2>Publikasi & Portofolio</h2>
                    <div class="timeline">
                        <div class="timeline-item">
                            Tidak ada hehe
                        </div>
                    </div>
                </section>
            </article>

            <aside>
                <h3>Tentang Saya</h3>
                <p>Mahasiswa Sistem Informasi di Universitas Pembangunan Jaya angkatan 2025.</p>
                
                <h3>Hobi</h3>
                <ul>
                    <li>Renang</li>
                    <li>Nonton Film</li>
                    <li>Traveling</li>
                    <li>Main Game</li>
                </ul>

                <h3>Kontak</h3>
                <p>📧 cahyapuspita06@email.com</p>
                <p>📱 +62 852-8189-5564</p>
                <p>📍 Jl. Haji Rean Graha Indah 2 Pamulang Blok E2/1</p>
            </aside>
        </main>

        <footer>
            <div class="wadah-footer">
                <div class="footer-left">
                    <p>Twitter: -</p>
                    <p>FB: -</p>
                    <p>Instagram: @rissachyp</p>
                </div>
                <div class="footer-center">
                    <p>&copy; 2025 Rissa Cahya Puspita. All Rights Reserved.</p>
                </div>
                <div class="footer-right">
                    <p>rissachy | kita jalanin dulu aja ya.</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Function untuk menampilkan konten yang dipilih
        function showContent(contentId) {
            // Sembunyikan semua konten
            const contents = document.querySelectorAll('.content');
            contents.forEach(content => {
                content.classList.remove('active');
            });

            // Tampilkan konten yang dipilih
            const selectedContent = document.getElementById(contentId);
            if (selectedContent) {
                selectedContent.classList.add('active');
            }

            // Update active state di navigasi
            const navLinks = document.querySelectorAll('nav a');
            navLinks.forEach(link => {
                link.classList.remove('active');
            });
            event.target.classList.add('active');
        }

        // Set biodata sebagai konten default saat halaman load
        window.addEventListener('DOMContentLoaded', function() {
            showContent('biodata');
        });
    </script>
</body>
</html>