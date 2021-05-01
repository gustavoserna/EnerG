<?php
$dashboard = "";
$suscripciones = "";
$clases = "";
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
        <a class="nav-link" href="./index.php">
          <i class="material-icons">dashboard</i>
          <p>Dashboard</p>
        </a>
      </li>
      <li class="nav-item <?php echo  $suscripciones; ?>">
        <a class="nav-link" href="./suscripciones.php">
          <i class="material-icons">shield</i>
          <p>Suscripciones</p>
        </a>
      </li>
      <li class="nav-item <?php echo  $clases; ?>">
        <a class="nav-link" href="./clases.php">
          <i class="material-icons">fitness_center</i>
          <p>Lista de clases</p>
        </a>
      </li>
      <li class="nav-item <?php echo  $instructores; ?>">
        <a class="nav-link" href="./instructores.php">
          <i class="material-icons">school</i>
          <p>Instructores</p>
        </a>
      </li>
      <li class="nav-item <?php echo  $usuarios; ?>">
        <a class="nav-link" href="./usuarios.php">
          <i class="material-icons">face</i>
          <p>Usuarios</p>
        </a>
      </li>
    </ul>
  </div>
</div>