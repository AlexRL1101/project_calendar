<?php
 session_start();
 $idrol = $_SESSION['idrol'];
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Eventos</title>
<!-- jQuery UI CSS -->
<link rel="stylesheet" type="text/css" href="../css/jquery-ui.min.css">

<!-- jQuery Library -->
<script src="../js/jquery-3.6.0.min.js"></script>

<!-- jQuery UI JS -->
<script type="text/javascript" src="../js/jquery-ui.min.js"></script>

<!-- Datatable JS -->
<script src="../js/jquery.dataTables.min.js"></script>

<!-- Datatable Boostrap5 -->
<script src="../js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" href=" ../css/bootstrap.min.css">
<link rel="stylesheet" href="../css/dataTables.bootstrap5.min.css">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-gradient" id="topNavBar">
        <div class="container">
			<a class="navbar-link" style="color: #FCF3CF;" href="../login/logout.php">Cerrar Sesión</a>
            <a class="navbar-brand" href="../index.php">Calendario</a>
            <a class="navbar-brand" href="index.php">Eventos</a>
            <?php 
            if($_SESSION['idrol']== 2){
            echo '<a class="navbar-brand" href="../users/index.php">Usuarios</a>';
        }
        ?>
            <div>
                <b class="text-light">Eventos</b>
            </div>
        </div>
    </nav>
<div class="container">
   <div class="row">
      <div class="col-md-12">
      <h4></h4><br><br>
   </div>
          </div>
   <!-- Date Filter -->
   <form id="formFecha">
   <table>
     <tr>
       <td>
          <input type='text' readonly id='buscar_inicio' class="datepicker" placeholder='Desde:'class="form-control form-control-sm">
       </td>
       <td>
          <input type='text' readonly id='buscar_fin' class="datepicker" placeholder='Hasta:' class="form-control form-control-sm">
       </td>
       <td>
          <input type='button' id="btn_search" value="Buscar" class="btn btn-primary btn-sm">
       </td>
       <td>
          <input type='button' id="btnLimpiar" value="Cancelar" class="btn btn-danger btn-sm">
       </td>
     </tr>
   </table>
</form>
<hr>
   <!-- Table -->
   <table id='Tabla_personal' class="table table-striped" style="width:100%">
     <thead>
       <tr>
         <th>Titulo</th>
         <th>Descripción</th>
         <th>Fecha</th>
         <th>Departamento</th>
       </tr>
     </thead>

   </table>
</div>
   <script  src="../js/lista.js"></script>
</body>
</html>