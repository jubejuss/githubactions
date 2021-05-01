<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top"> <!-- fixed-top  -->
    <a class="navbar-brand" href="page.php">PHP koolitööd</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="page.php">Esileht <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="show_news.php">Uudised</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Pildid</a>
        </li>
        <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Seadistamine
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item text-dark" href="add_user.php">Lisa kasutaja</a>
          <a class="dropdown-item text-dark" href="add_news.php">Lisa uudis</a>
          <a class="dropdown-item text-dark" href="upload_photo.php">Lae pilt üles</a>
          <a class="dropdown-item text-dark" 
            <?PHP if(!isset($_SESSION["user_id"])): ?>
              href="page.php">Logi sisse
            <?php else: ?>
              href="home.php?logout=1">Logi välja
            <?php endif ?>
          </a>
        </div>
      </li>
      </ul>
      <?PHP 
      if(!isset($_SESSION["user_id"])){ 
        echo '<a class="d-flex align-items-center" href="page.php">Logi sisse<i class="fas fa-poo h2 ml-3"></i></a>';
      } else {
        echo '<a class="d-flex align-items-center" href="home.php?logout=1">logi välja<span class="h3 ml-3 mb-0">X</span><i class="fas fa-sign-out-alt h2 ml-3"></i></a>';
      }
      ?>
    </div>
  </nav>
  