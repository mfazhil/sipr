<?php

if (session_id() === '') session_start();

$loggedIn = false;

if (count($_SESSION) > 0) {
  $loggedIn = true;
  $role = $_SESSION["role"] === "admin" ? "Admin" : "User";
}
function addActiveClass(String $pageName): String
{
  if ($pageName === "index" && substr($_SERVER["REQUEST_URI"], -1) === "/") return "active";
  return strpos($_SERVER["REQUEST_URI"], $pageName) !== false ? "active" : "";
}
?>
<header class="header">
  <div class="header-wrapper">
    <a class="header__brand" href="./">
      <span class="header__title">SI</span>
      <span class="header__title--green">PR </span>
      <?php if ($loggedIn) { ?>
        <span class="header__title">/ </span>
        <span class="header__title--green"> <?= $role ?>
        </span>
      <?php } ?>
    </a>
    <button class="hamburger">
      <span class="hamburger__line"></span>
      <span class="hamburger__line"></span>
      <span class="hamburger__line"></span>
    </button>
    <nav class="nav">
      <ul class="nav__list">
        <li class="nav__item">
          <a href="./" class="nav__link <?= addActiveClass("index") ?>">
            <svg class="nav__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Beranda
          </a>
        </li>
        <li class="nav__item">
          <a href="./pengecekan.php" class="nav__link <?= addActiveClass("pengecekan") ?>">
            <svg class="nav__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Pengecekan
          </a>
        </li>
        <li class="nav__item">
          <a href="./data-ruangan.php" class="nav__link <?= addActiveClass("data-ruangan") ?>">
            <svg class="nav__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Data Ruangan
          </a>
        </li>
        <li class="nav__item">
          <a href="./jenis-ruangan.php" class="nav__link <?= addActiveClass("jenis-ruangan") ?>">
            <svg class="nav__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
            </svg>
            Jenis Ruangan
          </a>
        </li>
        <li class="nav__item">
          <a href="./daftar-prosedur.php" class="nav__link <?= addActiveClass("prosedur") ?>">
            <svg class="nav__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            Daftar Prosedur
          </a>
        </li>
        <?php if ($loggedIn) { ?>
          <li class="nav__item">
            <a href="./petugas.php" class="nav__link <?= addActiveClass("petugas") ?> <?= addActiveClass("admin") ?>">
              <svg class="nav__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
              Petugas
            </a>
          </li>
          <li class="nav__item">
            <a href="./pengaturan.php" class="nav__link <?= addActiveClass("pengaturan") ?>">
              <svg class="nav__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
              </svg>
              Pengaturan
            </a>
          </li>
        <?php } ?>
        <li class="nav__item">
          <?php if ($loggedIn) { ?>
            <a href="./logout.php" class="nav__logout">
              <span>Logout</span>
              <svg class="nav__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
              </svg>
            </a>
          <?php } else { ?>
            <a href="./login.php" class="nav__link <?= addActiveClass("login") ?>">
              <svg class="nav__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
              </svg>
              <span>Login</span>
            </a>
          <?php } ?>
        </li>
      </ul>
    </nav>
  </div>
</header>

<script>
  $(".hamburger").click(function() {
    $(this).toggleClass("active");
    $(".nav__list").toggleClass("active");
  });
</script>