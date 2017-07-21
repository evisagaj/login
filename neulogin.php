<?php
// Session starten, falls keiner gestartet ist
if(!isset($_SESSION)) {
		session_start();
	}	
	var_dump($_SESSION);
	// Wenn ein Benutzer schon eingeloggt ist, wird er zur Home Seite umaddressiert 
	if(isset($_SESSION["userID"])){
		header("Location: home.php");
	}
		// Aufruf der DBVerbindungsdatei
	require('dbconnect.php');
	?>
<!DOCTYPE html>
<html>
 <head>
    <title> Business Bridge </title>
 </head>
<body>
		<div id="content">
			<h2> Logge Dich ein </h2>
		<?php
		if(isset($_GET['login'])){
			//Daten aus dem Formular in Variablen speichern
			$email = $_POST['email'];
			$passwort = $_POST['passwort'];
			// kontrollieren, ob es diese Addresse in der Datenbank gibt
			$statement = $pdo->prepare("SELECT * FROM users WHERE email = ?");
			$statement ->bindParam(1,$email);
			$result = $statement->execute();
			$res = $statement->fetch();
			$data = $res[1] ."\n" . $res[2] . "\n";
			// Wenn es die Email Addresse gibt, den Passwort überprüfen
			if($statement->rowCount() > 0 ) {
				// Das eingegebene Passwort wird gehasht und mit dem gespeicherten überprüft
				// Wenn beide Hashes passen, werden die Email Addresse, Vorname, userID und userlevel in der Session Variable gespeichert und der Benutzer 
				// wird eingeloggt
				if (password_verify($passwort, $res[2]) && $email == $res[1]){
					$_SESSION['email'] = $_POST['email'];
					$_SESSION['vname'] = $_POST['vname'];
					$_SESSION['userlevel'] = $res[6];
					$_SESSION['userID'] = $res[0];
					// Umaddressierung zur Home Seite
					header('Location: home.php');
				}
				else{
					//Wenn die Hashes nicht passen, ist der Passwort falsch 
					echo "Falsches Passwort, probiere es noch einmal!";
				}
			}else{ 
			// Wenn es keine Resultate zurückgegeben wurden, gibt es die Email Addresse nicht
				echo "Die Email Addresse gibt es nicht!";
			}
			}
		
		?>
		<!-- Login Formular -->
				<form action="?login=1" method="post">
					E-Mail: <br> <input type="email" size="40" maxlength="250" name="email"><br><br>
					Dein Passwort:<br> <input type="password" size="40"  maxlength="250" name="passwort"><br><br>
					<input id="button" type="submit" value="Send">
				</form>
 
		</div>
	</div>
</body>
</html>
<?php
$pdo->connection = null;
?>