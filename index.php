<?php
session_start();

// Verificar si la variable de sesión 'nombre_usuario' y 'rol' existen
if (!isset($_SESSION['nombre_usuario']) || !isset($_SESSION['rol'])) {
    // Si no está logueado, redirigir al login
    header('Location: login.html');
    exit();
}

// Lógica para redirigir según el rol del usuario al hacer clic en "Tabla"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_SESSION['rol'] === 'admin') {
        header('Location: admin.php');
        exit();
    } elseif ($_SESSION['rol'] === 'normal') {
        header('Location: top10.php');
        exit();
    } else {
        // Si el rol no es válido
        echo 'No tienes permisos para acceder a esta sección.';
    }
}
?>

<!doctype html>
<html class="no-js" lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Wanderer's Ascent</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/main.css">
  <meta name="description" content="">

  <link rel="icon" href="/favicon.ico" sizes="any">
  <link rel="icon" href="/icon.svg" type="image/svg+xml">
  <link rel="apple-touch-icon" href="icon.png">

  <link rel="manifest" href="site.webmanifest">
  <meta name="theme-color" content="#fafafa">
</head>

<body>

  <!-- Header -->
  <header class="site-header">
    <img src="img/logo.png" alt="Logo" class="logo">
    <nav class="navbar">
      <a href="index.php" class="nav-btn">Inicio</a>
      <a href="inicioJuego.html" class="nav-btn">Jugar</a>
      <a href="login.php" class="nav-btn">Login</a>
      <a href="logout.php" class="nav-btn">Logout</a>
      <!-- Botón "Tabla" que envía la solicitud POST -->
      <form method="POST" action="">
        <button type="submit" class="nav-btn">Tabla</button>
      </form>
    </nav>
  </header>

  <!-- Imagen Grande -->
  <section class="hero">
    <div class="overlay"></div>
    <div class="hero-content">
      <h1>Wanderer's Ascent</h1>
      <p>Acompáñanos en esta maravillosa travesía</p>
    </div>
  </section>

  <!-- Sección de información -->
  <section class="info-section">
    <h2>Descubre información sobre nuestro juego</h2>
    <div class="info-container">
      <!-- Primer cuadro -->
      <div class="info-box">
        <img src="img/image 4.png" alt="Personaje" style="width: 343px; height: 289px; object-fit: cover;">
        <div class="info-description">
          <p>Acompaña al Errante en su viaje para descubrir los secretos de un mundo olvidado.</p>
        </div>
        <a href="historia.html" class="info-btn">Descubre más</a>
      </div>
      <!-- Segundo cuadro -->
      <div class="info-box">
        <img src="img/keyboard.png" alt="Teclado" style="width: 576px; height: 289px; object-fit: cover;">
        <div class="info-description">
          <p>Aprende cómo jugar y domina los desafíos que te esperan.</p>
        </div>
        <a href="comoJugar.html" class="info-btn">Descubre más</a>
      </div>
      <!-- Tercer cuadro -->
      <div class="info-box">
        <img src="img/estrella 1.png" alt="Estrella" style="width: 289px; height: 289px; object-fit: cover;">
        <div class="info-description">
          <p>Conoce todo lo utilizable en este videojuego.</p>
        </div>
        <a href="utilizables.html" class="info-btn">Descubre más</a>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="site-footer">
    <div class="footer-content">
        <img src="img/logo.png" alt="Footer Logo" class="footer-logo">
        <nav class="social-navbar">
            <a href="#"><img src="img/you.png" alt="YouTube" class="social-icon"></a>
            <a href="#"><img src="img/instagram.png" alt="Instagram" class="social-icon"></a>
            <a href="#"><img src="img/facebook.png" alt="Facebook" class="social-icon"></a>
        </nav>
    </div>
  </footer>

</body>
</html>
