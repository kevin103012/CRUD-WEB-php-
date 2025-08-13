<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>	
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ENTREGABLE :u</title>
</head>
<body>

<?php
require_once 'model/db/dbconnect.php';

session_start();
if(isset($_SESSION["director_login"]))	//Condicion admin
{
	header("location: view_director/director_view.php");	
}

if(isset($_SESSION["alumno_login"]))	//Condicion Usuarios
{
	header("location: view_alumno/alumno_view.php");
}

if(isset($_REQUEST['btn_login']))	
{
	$email		=$_REQUEST["email"];	//textbox nombre "txt_email"
	$password	=$_REQUEST["password"];	//textbox nombre "txt_password"
	$role		=$_REQUEST["rol"];		//select opcion nombre "txt_role"
		
	if(empty($email)){						
		$errorMsg[]="Por favor ingrese Email";	//Revisar email
	}
	else if(empty($password)){
		$errorMsg[]="Por favor ingrese Password";	//Revisar password vacio
	}
	else if(empty($role)){
		$errorMsg[]="Por favor seleccione rol ";	//Revisar rol vacio
	}
	else if($email AND $password AND $role)
	{
		try
		{
			$select_stmt=$db->prepare("SELECT email,password,rol FROM users
										WHERE
										email=? AND password=? AND rol=?"); 
			$select_stmt->bindValue(1,$email);
			$select_stmt->bindValue(2,$password);
			$select_stmt->bindValue(3,$role);
			$select_stmt->execute();	
					
			while($row=$select_stmt->fetch(PDO::FETCH_ASSOC))	
			{
				$dbemail	=$row["email"];
				$dbpassword	=$row["password"];
				$dbrole		=$row["rol"];
			}
			if($email!=null AND $password!=null AND $role!=null)	
			{
				if($select_stmt->rowCount()>0)
				{
					if($email==$dbemail and $password==$dbpassword and $role==$dbrole)
					{
						switch($dbrole)		//inicio de sesión de usuario base de roles
						{
							case "director":
								$_SESSION["director_login"]=$email;			
								$loginMsg="Admin: Inicio sesión con éxito";	
								header("refresh:1;view_director/director_view.php");	
								break;
								
					
							case "alumno":
								$_SESSION["alumno_login"]=$email;				
								$loginMsg="Usuario: Inicio sesión con éxito";	
								header("refresh:1;view_alumno/alumno_view.php");		
								break;
								
							default:
								$errorMsg[]="correo electrónico o contraseña o rol incorrectos";
						}
					}
					else
					{
						$errorMsg[]="correo electrónico o contraseña o rol incorrectos";
					}
				}
				else
				{
					$errorMsg[]="correo electrónico o contraseña o rol incorrectos";
				}
			}
			else
			{
				$errorMsg[]="correo electrónico o contraseña o rol incorrectos";
			}
		}
		catch(PDOException $e)
		{
			$e->getMessage();
		}		
	}
	else
	{
		$errorMsg[]="correo electrónico o contraseña o rol incorrectos";
	}
}

?>
    
    <div class="container_form">
      
            <form method="post" class="formulario_index">
                <div class="div_form_email">
                    <label for="">INGRESE SU EMAIL INSTITUCIONAL</label>
                <input type="text" name="email" class="input_email" id="email" placeholder="Ingrese su email institucional" required>
                </div>
                
                <div class="div_form_password">
                    <label for="">INGRESE SU CONTRASEÑA</label>
                    <input type="password" class="input_password" name="password" id="password" placeholder="Ingrese su contraseña" required>
                
                </div>
                
                <div class="div_form_users">
                    <label for="">SELECIONE SU ROL</label>
                <select name="rol" id="rol" required>
                    <option value="" disabled selected>Seleccione su rol</option>
                    <option value="director">Director</option>
                    <option value="alumno">Alumno</option>
                </select>
                </div>
                <div class="button_container">
                    <input type="submit" value="SUBIR" name="btn_login" class="button_f">
                </div>
                
                
                
            </form>
    <div>
               <div class="p_container">
                 <p><a href="https://youtu.be/jQGqtCalg9Y?si=WvV2ckYLkpLpUaz-" target="_blank">Dificultades con su inicio de sesion?</a></p>
               </div>
            

        
	<a href="close_sesion.php"><button>wa</button></a>
    </div>    
</body>
</html>