<!-- Header for navigations -->
<header>
  <div class="shown-menu">
    <nav>
      <ul class="navbar">
        <li>
          <a href="index.php">
            <img id="logo" src="images/smartglow_header.png" alt="SmartGlow Logo" /></a>
        </li>
        <li><a class="links" href="product.php">Product</a></li>
        <li><a class="links" href="enquire.php">Enquire</a></li>
        <li><a class="links" href="about.php">About</a></li>
        <li><a class="links" href="enhancements.php">Enhancements</a></li>
        <li><a class="links" href="enhancements2.php">Enhancements 2</a></li>
        <li><a class="links" href="enhancements3.php">Enhancements 3</a></li>
        <?php

        if (isset($_SESSION["user_type"])) {
          if (strtolower($_SESSION["user_type"]) == "manager") {
            echo "<li><a class='links' href='manager.php'>Manager</a></li>";
          }
        }
        ?>
      </ul>

      <div class="auth-link">
        <?php if (!isset($_SESSION["user_id"])): ?>
          <a class="links" href="login.php">Login</a>
        <?php else: ?>
          <a class="links" href="logout.php">Logout</a>
        <?php endif; ?>
      </div>
    </nav>
  </div>
  <!-- A hidden menu that shows when the media reaches 768px (tablet size) -->
  <nav class="hidden-menu">
    <ul class="dropdown">
      <li><a class="hidden-links" href="product.php">Product</a></li>
      <li><a class="hidden-links" href="enquire.php">Enquire</a></li>
      <li><a class="hidden-links" href="about.php">About</a></li>
      <li><a class="hidden-links" href="enhancements.php">Enhancements</a></li>
      <li>
        <a class="hidden-links" href="enhancements2.php">Enhancements 2</a>
      </li>
      <li><a class="hidden-links" href="enhancements3.php">Enhancements 3</a></li>
      <?php
      if (isset($_SESSION["user_type"])) {
        if (strtolower($_SESSION["user_type"]) == "manager") {
          echo "<li><a class='hidden-links' href='manager.php'>Manager</a></li>";
        }
      }
      ?>

      <?php if (!isset($_SESSION["user_id"])): ?>
        <li><a class="hidden-links" href="login.php">Login</a></li>
      <?php else: ?>
        <li><a class="hidden-links" href="logout.php">Logout</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>