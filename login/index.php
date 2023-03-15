<?php
include('config.php');
session_start();
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    
        $query = $conn->prepare("SELECT * FROM users WHERE USERNAME=:username and PASSWORD=:password");
        $query->bindValue(":username", $username, PDO::PARAM_STR);
        $query->bindValue(":password", $password, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
    
    
    if (!$result) {
        echo '<p class="error">Usuario y/o password son incorrectos!</p>';
    } else {
        if ($username != "" && $password != "") {
            echo '<p class="success">Congratulations, you are logged in!</p>';
            $idusuario = $result['id'];
            $_SESSION['idusuario'] = $result['id'];
            $_SESSION['nombre'] = $result['nombres'];
            $query = $conn->prepare("SELECT idrol FROM user_rol WHERE IDUSUARIO=:idusuario");
            $query->bindValue(":idusuario", $idusuario, PDO::PARAM_STR);
            $query->execute();
            $response = $query->fetch(PDO::FETCH_ASSOC);
            $_SESSION['idrol'] = $response['idrol'];
            header("Location: ../index.php");
        } else {
            echo '<p class="error">Usuario y/o password son incorrectos!</p>';
        }
        
      
    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" 
    integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" 
    integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="estilo-login.css">
    <title>login</title>
</head>
<body>
    <form method="post" action="" name="signin-form">
        <h5></h5><br><br><br>
            <div class="fa-solid fa-user">
                <label>Usuario</label>
                <input type="text" name="username" pattern="[a-zA-Z0-9]+" required placeholder="ves123"/>
            </div>
            <div class="fa-solid fa-unlock">
                <label>Contraseña</label>
                <input type="password" name="password" required placeholder="*******"/>
            </div><br>
            <br><button type="submit" name="login" value="login">Iniciar sesion</button>
    </form>
</body>
</html>


    
