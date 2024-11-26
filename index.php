<?php
session_start();

// Verificar si la variable de sesión 'nombre_usuario' y 'rol' existen
if (!isset($_SESSION['nombre_usuario']) || !isset($_SESSION['rol'])) {
    // Si no está logueado, redirigir al login
    header('Location: login.php');
    exit();
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
  <meta property="og:title" content="">
  <meta property="og:type" content="">
  <meta property="og:url" content="">
  <meta property="og:image" content="">
  <meta property="og:image:alt" content="">

  <link rel="icon" href="/favicon.ico" sizes="any">
  <link rel="icon" href="/icon.svg" type="image/svg+xml">
  <link rel="apple-touch-icon" href="icon.png">

  <link rel="manifest" href="site.webmanifest">
  <meta name="theme-color" content="#fafafa">
</head>



  <!-- Header -->
  <header class="site-header">
    <img src="img/logo.png" alt="Logo" class="logo">
    <nav class="navbar">
      <a href="index.html" class="nav-btn">Inicio</a>
      <a href="inicioJuego.html" class="nav-btn">Jugar</a>
      <a href="login.html" class="nav-btn">login</a>
      <a href="logout.php" class="nav-btn">Logout <output>
      <a href="#" class="nav-btn">Tabla</a>
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
        <img src="img/estrella 1.png" alt="Estrella" style="width: 289px;  height: 289px; object-fit: cover;">
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
<script>
    // Lógica para redirigir al usuario a la sección correspondiente según su rol
    document.getElementById('tabla-btn').addEventListener('click', function() {
      // Obtener el rol del usuario desde la sesión PHP (pasado al script JavaScript)
      <?php if (isset($_SESSION['rol'])): ?>
        var userRole = '<?php echo $_SESSION['rol']; ?>';
        if (userRole === 'admin') {
          window.location.href = 'admin_dashboard.php'; // Redirige al admin
        } else if (userRole === 'normal') {
          window.location.href = 'top_10.php'; // Redirige al usuario normal
        }
      <?php else: ?>
        alert('No tienes permisos para acceder a esta sección.');
      <?php endif; ?>
    });
  </script>
  
</body>
</html>