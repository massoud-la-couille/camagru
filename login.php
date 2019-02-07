<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title>Me connecter</title>
        <link rel = "stylesheet"
        type = "text/css"
        href = "style.css" />
	</head>
	<body>
	<div id="top_bar">
    <?php include ('top_bar.php');?>
</div>
		<div class="login-page">
			<div class="form">
				<form class="login-form" action="user/login_user.php" method="POST">
					<p>Nom d'utilisateur : </p><input type="text" name="login"/>
					<p>Mot de passe : </p><input type="password" name="password"/>
					<input id = "login" type="submit" name="submit" value ="Se connecter">
					<p class="message">Mot de passe oublié ? <a href="new_passwd.php">Réinitialiser le mot de passe</a></p>
					<p class="message">Pas encore de compte ? <a href="create.php">Creer un compte</a></p>
				</form>
			</div>
		</div>
	</body>
</html>
