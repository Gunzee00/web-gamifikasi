<!DOCTYPE html>
<html lang ="en">

    <!-- Head berfungsi menyimpan metadata -->
    <!-- Menyimpan link relation seperti css dan javascript -->
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Ray Siagian">
        <meta name="description" content="Saya sedang belajar html">
        <title>
            Gamifikasi dengan Metode VAK
        </title>
        <link rel="icon" href="../../images/cartoonpfpcircle.png" type="image/x-icon">
        <script src="../../js/script.js"></script>
        <!-- memanggil css dari file lain -->
        <!-- ada beberapa cara untuk membuat css -->
        <!-- inline stylesheet -->
        <!-- internal stylesheet -->
        <!-- external stylesheet -->
        <!-- apa yg ditampilkan adalah apa yang dibaca oleh browser terakhir -->
        <!-- jadi ketika kamu membuat css internal dibawah link ini maka yg dibaca terakhir adalah link tersebut -->
        <!-- hindari inline css sebisamu untuk meningkatkan keterbacaan -->
        <link rel="stylesheet" href="../../css/main.css" type="text/css">
        <link rel="stylesheet" href="../../css/color.css" type="text/css">
        <link rel="stylesheet" href="../../css/section.css" type="text/css">
        <link rel="stylesheet" href="../../css/margin.css" type="text/css">
        <link rel="stylesheet" href="../../css/navbar.css" type="text/css">
        <link rel="stylesheet" href="../../css/footer.css" type="text/css">
    </head>

    <!-- body menyimpan data yg ditampilkan -->
    <!-- Webpage sejatinya seperti koran atapun media berbasis text -->
    <body>
        <header>
            <nav id="nav-header">
                <img src="../../images/cartoonpfpcircle.png" alt="image profile" title="image_pfp" loading="eager">
                <div class="landing-menu">
                    <ul>
                        <li>
                            <a class="btn-primary" href="{{ url('/login') }}">Masuk</a>
                        </li>
                        <li>
                            <a class="btn-white" href="../auth/register/register.html">Daftar</a>
                        </li>
                    </ul>
                </div>
                <!-- kode belum selesai -->
                <div class="landing-hamburger-menu">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>  
            </nav>
        </header>
        <main>
            <!-- Saat kita inspect element pada browser kita dapat melihat bahwa ada margin dari element tanpa di set -->
            <section class="main-padding-1">
                <article class="section-type-1 bc-white mrgn-vertical-1 centered-content">
                    <section class="section-type-1-content">
                         <section class="section-type-1-image">
                             <img class="img-sz-section-type-1" src="../../images/testimage.png" alt="mathplaygasing" title="mathplaygasing" loading="lazy">
                         </section>
                         <section class="section-type-1-text">
                             <h2 class="section-title color-black txt-algn-center">Belajar dan Bermain</h2>
                             <p class="color-black txt-algn-center">
                                Belajar yang seru dan asyik, disesuaikan untuk anak-anak dengan bimbingan orang tua ataupun orang dewasa.
                             </p>
                         </section>
                    </section>
                    <!-- <a href="projects/mathplaygasing.html" class="section-type-2-link"></a> -->
                 </article>
                 <article class="mrgn-vertical-1 centered-content">
                     <h1 class="title-extralarge">
                         Visual, Audiotori, Kinestetik.
                     </h1>
                 </article>
                 <article class="section-type-1 bc-white mrgn-vertical-1 centered-content">
                     <section class="section-type-1-content">
                          <section class="section-type-1-text">
                              <h2 class="section-title color-primary txt-algn-left">Visual</h2>
                              <p class="color-black txt-algn-left">
                                 Dengan visual, anak berfikir kreatif dalam memecahkan masalah
                              </p>
                          </section>
                          <section class="section-type-1-image">
                             <img class="img-sz-section-type-1" src="../../images/testimage.png" alt="mathplaygasing" title="mathplaygasing" loading="lazy">
                         </section>
                     </section>
                  </article>
                  <article class="section-type-1 bc-white mrgn-vertical-1">
                     <section class="section-type-1-content">
                         <section class="section-type-1-image">
                             <img class="img-sz-section-type-1" src="../../images/testimage.png" alt="mathplaygasing" title="mathplaygasing" loading="lazy">
                         </section>
                          <section class="section-type-1-text">
                              <h2 class="section-title color-primary txt-algn-left">Audiotori</h2>
                              <p class="color-black txt-algn-left">
                                 Dengan audio melatih otak dan indera pendengaran, motorik kasar dan halus.
                              </p>
                          </section>
                     </section>
                  </article>
            </section>
        </main>
        <div class="footerupper">
            <img src="../../images/footerupper.svg" alt="">
        </div>
        <footer>
            <div class="footer">
                <div class="container">
                    <div class="footer-content">
                        <!-- Kolom 1: Tentang Perusahaan -->
                        <div class="footer-section about">
                            <h2>Tentang Kami</h2>
                            <p>Belajar sambil bermain, dengan menggunakan Metode VAK melatih motorik kasr dan halus anak.</p>
                            <p>Alamat: Jl. Contoh No. 123, Jakarta</p>
                            <p>Email: support@contoh.com</p>
                            <p>Telepon: +62 812-3456-7890</p>
                        </div>
            
                        <!-- Kolom 2: Layanan -->
                        <div class="footer-section services">
                            <h3>Layanan</h2>
                            <ul>
                                <li><a href="#">Jasa Desain</a></li>
                                <li><a href="#">Pengembangan Web</a></li>
                                <li><a href="#">Aplikasi Mobile</a></li>
                                <li><a href="#">SEO & Digital Marketing</a></li>
                            </ul>
                        </div>
            
                        <!-- Kolom 3: Tautan Cepat -->
                        <div class="footer-section quick-links">
                            <h3>Tautan Cepat</h2>
                            <ul>
                                <li><a href="#">Beranda</a></li>
                                <li><a href="#">Tentang Kami</a></li>
                                <li><a href="#">Layanan</a></li>
                                <li><a href="#">Kontak</a></li>
                            </ul>
                        </div>
            
                        <!-- Kolom 4: Media Sosial -->
                        <div class="footer-section social">
                            <h2>Ikuti Kami</h2>
                            <div class="social-icons">
                                <a href="#"><img src="facebook-icon.png" alt="Facebook"></a>
                                <a href="#"><img src="twitter-icon.png" alt="Twitter"></a>
                                <a href="#"><img src="instagram-icon.png" alt="Instagram"></a>
                                <a href="#"><img src="linkedin-icon.png" alt="LinkedIn"></a>
                            </div>
                        </div>
                    </div>
            
                    <!-- Copyright -->
                    <div class="footer-bottom">
                        <p>&copy; 2025 Nama Perusahaan. Semua Hak Dilindungi.</p>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>