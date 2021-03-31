<?php
$dashboard = "";
$suscripciones = "";
$clases = "";
$alta = "";
$reportes = "";
$usuarios = "";
$instructores = "";
switch ($pag) {
  case 'dashboard':
    $dashboard = "active";
    break;
  case 'suscripciones':
    $suscripciones = "active";
    break;
  case 'clases':
    $clases = "active";
    break;
  case 'alta':
    $alta = "active";
    break;
  case 'reportes':
    $reportes = 'active';
    break;
  case 'usuarios':
    $usuarios = 'active';
    break;
  case 'instructores':
    $instructores = 'active';
    break;
}
?>
<div class="sidebar" data-color="purple" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
  <!--
    Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"
    Tip 2: you can also add an image using data-image tag-->
  <div class="logo" style="text-align:center;">
    <img src="Images/logo.png" width="100px" />
  </div>
  <div class="sidebar-wrapper">
    <ul class="nav">
      <li class="nav-item <?php echo  $dashboard; ?>">
        <a class="nav-link" href="./dashboard.php">
          <i class="material-icons">dashboard</i>
          <p>Dashboard</p>
        </a>
      </li>
      <li class="nav-item <?php echo  $suscripciones; ?>">
        <a class="nav-link" href="./suscripciones.php">
          <i class="material-icons">local_shipping</i>
          <p>Suscripciones</p>
        </a>
      </li>
      <li class="nav-item <?php echo  $clases; ?>">
        <a class="nav-link" href="./clases.php">
          <i class="material-icons">all_inbox</i>
          <p>Lista de clases</p>
        </a>
      </li>
      <li class="nav-item <?php echo  $instructores; ?>">
        <a class="nav-link" href="./instructores.php">
          <i class="material-icons">face</i>
          <p>Instructores</p>
        </a>
      </li>
      <li class="nav-item <?php echo  $alta; ?>">
        <a class="nav-link" href="./alta-clase.php">
          <i class="material-icons">add</i>
          <p>Dar de alta una clase</p>
        </a>
      </li>
      <li class="nav-item <?php echo  $reportes; ?>">
        <a class="nav-link" href="./reportes.php">
          <i class="material-icons">receipt</i>
          <p>Reportes</p>
        </a>
      </li>
      <li class="nav-item <?php echo  $usuarios; ?>">
        <a class="nav-link" href="./usuarios.php">
          <i class="material-icons">face</i>
          <p>Usuarios</p>
        </a>
      </li>
      <!--<li class="nav-item ">
        <a class="nav-link" href="./tables.php">
          <i class="material-icons">content_paste</i>
          <p>Table List</p>
        </a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="./typography.php">
          <i class="material-icons">library_books</i>
          <p>Typography</p>
        </a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="./icons.php">
          <i class="material-icons">bubble_chart</i>
          <p>Icons</p>
        </a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="./map.php">
          <i class="material-icons">location_ons</i>
          <p>Maps</p>
        </a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="./notifications.php">
          <i class="material-icons">notifications</i>
          <p>Notifications</p>
        </a>
      </li>-->
    </ul>
  </div>
</div>