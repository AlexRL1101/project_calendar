

<!doctype html>
<html lang="es-MX">
	<head>
	<meta charset="utf-8">
		<link type="text/css" href="bootstrap.min.css" rel="stylesheet">
		<link type="text/css" href="http://fontawesome.io/assets/font-awesome/css/font-awesome.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.css"/>  
    	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <meta charset='utf-8' />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.6.2/fullcalendar.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.2/moment.js"></script>
        <title>Usuarios</title>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/personalizado.css" rel="stylesheet">
        

<style>
body {
    margin: 0px 0px;
    padding: 0;
    font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
    font-size: 14px;
}
table.dataTable thead {
            background: linear-gradient(to right, #000000, #ff9900,#fffb00);
            color:white;
}
</style>
<script src='../js/jquery.min.js'></script>
		<script src='../js/bootstrap.min.js'></script>
		<script src='../js/moment.min.js'></script>
		<script src='../js/fullcalendar.min.js'></script>
		<script src='../locale/es-es.js'></script>

  </head> 
      <body>
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-gradient" id="topNavBar">
        <div class="container">
		<a class="navbar-link" style="color: #FCF3CF;" href="../login/logout.php">cerrar sesion</a>
            <a class="navbar-brand" href="../index.php">Calendario</a>
            <a class="navbar-brand" href="../events/index.php">Eventos</a>
            <a class="navbar-brand" href="index.php">Usuarios</a>

            <div>
                <b class="text-light">Usuarios</b>
            </div>
        </div>
    </nav>

		<div class="container">
          <div class="row">
                      <div class="col-md-12">
                        <h4></h4><br>
                      </div>
          </div>
		<div class="main-wrapper">
			<br><br>
					<form action="" method="post">
					<div class="col-xs-3">
					<!--    <input class="form-control" name="username" type="text" placeholder="Username">
					</div>
					<div class="col-xs-3">
						<input class="form-control" name="email" type="text" placeholder="Email">
					</div>  
						<input type="submit" name="submit" class="btn btn-primary" value="Insertar">-->
					</form>
					<br>

					<?php
						include("function.php");
						if(isset($_POST['submit'])){
							$field = array("name"=>$_POST['name']);
							$tbl = "users";
							insert($tbl,$field);
							
						}
					?>
					<table FRAME="void" align="center" RULES="rows" width="75%" height="200px" bordercolor="#8C00FF">
						<tr>
							<th style ="color:#FFFFFF" bgcolor="#2079FF" class="text-center" width="41%" height="15%">Nombre´s</th>
							<th style ="color:#FFFFFF" bgcolor="#2079FF" class="text-center" width="46%">Departamento</th>
							<th style ="color:#FFFFFF" bgcolor="#1C77FF " class="text-center" width="13%">Opcion</th>
						</tr>
					<?php  
						$sql = "select * from users";
						$result = db_query($sql);
						while($row = mysqli_fetch_object($result)){
						?>
						<tr>
							<td align="center"><?php echo $row->nombres;?></td>
							<td align="center"><?php echo $row->dpto;?></td>
							<td><a class="btn-sm btn-primary" href="editar.php?id=<?php echo $row->id; ?>"><i class="fa fa-pencil fa-lg" aria-hidden="true"></i></a>
								<a class="btn-sm btn-danger" href="borrar.php?id=<?php echo $row->id;?>"><i class="fa fa-trash-o fa-lg" aria-hidden="true"></i></a></td>
					
						</tr>
				<?php } ?>
			</div>
			</table>
		</div>
	</body>
</html>