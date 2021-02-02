<?php
function addActiveClass(String $pageName): String
{
  if ($pageName == "index") return "active";
  return strpos($_SERVER["REQUEST_URI"], $pageName) != false ? "active" : "";
}
?>

<header class="header shadow">
  <div class="header-wrapper container">
    <a class="header__brand" href="/">
      <span class="header__title">SI</span>
      <span class="header__title--orange">PR</span>
    </a>
    <div class="hamburger md:hidden">
      <span class="hamburger__line bg-gray-800"></span>
      <span class="hamburger__line bg-gray-800"></span>
      <span class="hamburger__line bg-gray-800"></span>
    </div>
    <nav class="nav">
      <ul class="nav__list shadow-md md:shadow-none">
        <li class="nav__item">
          <a href="./" class="nav__link <?= addActiveClass("index") ?>">
            <svg class="w-5 h-5 nav__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Beranda
          </a>
        </li>
        <li class="nav__item">
          <a href="./" class="nav__link <?= addActiveClass("penilaian") ?>">
            <svg class="w-5 h-5 nav__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Penilaian
          </a>
        </li>
        <li class="nav__item">
          <a href="./" class="nav__link <?= addActiveClass("data_ruangan") ?>">
            <svg class="w-5 h-5 nav__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Data Ruangan
          </a>
        </li>
        <li class="nav__item">
          <a href="./login.php" class="nav__link <?= addActiveClass("login") ?>">
            <svg class="w-5 h-5 nav__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
            </svg>
            <span class="md:font-bold">Login</span>
          </a>
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