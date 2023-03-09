<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Editar usuarios</title>
<link type="text/css" href="bootstrap.min.css" rel="stylesheet">
<link type="text/css" href="http://fontawesome.io/assets/font-awesome/css/font-awesome.css" rel="stylesheet">
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
	background-color:#FFFFFF ;
    /*background-image: url("../img/LogoVessel2.png");
    background-repeat: no-repeat;
    background-position: top left;
    background-size: 130px 45px;*/
}
table {
    border-collapse: collapse;
    width: 100%;
}
th, td {
    text-align: left;
    padding: 4px;
}
tr:nth-child(even){background-color: #FFFFFF}
th {
    background-color: #4CAF50;
    color: white;
}
.main-wrapper{
	width:50%;
	
	
	padding:25px;
	background: linear-gradient(#D7EBFF   ,15%  ,#FFFFFF );
    color:black;
	margin: 6em auto;
			
}
hr {
    margin-top: 5px;
    margin-bottom: 5px;
    border: 0;
    border-top: 1px solid #eee;
}


</style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-gradient" id="topNavBar">
        <div class="container">
		<a class="navbar-link" style="color: #FCF3CF;" href="index.php">Atrás</a>

    </nav>
	<div class="main-wrapper">
		<font face="perpetua" size=5>Introduza los nuevos datos a cambiar del usuario.</font>
		<h5>Una vez hecho lo anterior haga click en "Enviar".</h5>
		<br><br>
		<?php 
			include("function.php");
			$id = $_GET['id'];
			select_id('users','id',$id);
			?>
			<center>
				<form action="" method="post" >
					<div class="form-group">
						<p align ="left"><label for="nombres">Nombre:</label>
							<input type="text" value="<?php echo $row->nombres;?>" name="nombres" placeholder="Nombre y apellido"></p>
					</div>
					<div >
						<p align ="left"><label for="dpto">Departamento:</label>
						<input type="text" value="<?php echo $row->dpto;?>" name="dpto" placeholder="XYZ"></p>
					</div>
					<div>
						<p align ="left"><label for="username">Usuario:</label>
						<input type="text" value="<?php echo $row->username;?>" name="username" placeholder="username"></p>
					</div>
					<div>
						<p align ="left"><label for="password">Contraseña:</label>
						<input type="text" value="<?php echo $row->password;?>" name="password" placeholder="pass"></p>
					</div>
					<div>
					
						<p align="left"><input type="submit" name="submit"></p>
					</div>
				</form>
			</center>

		<?php
			
			if(isset($_POST['submit'])){
				$field = array("nombres"=>$_POST['nombres'], "dpto"=>$_POST['dpto'], "username"=>$_POST['username'], "password"=>$_POST['password']);
				$tbl = "users";
				edit($tbl,$field,'id',$id);
				header("location:index.php");
			}
		?>
	</div>
</body>
</html>