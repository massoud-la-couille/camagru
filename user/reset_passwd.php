<?php
include('../config/connection.php');
include('valid_passwd.php');
date_default_timezone_set(UTC);
if (isset($_GET["key"]) && isset($_GET["email"]) && isset($_GET["action"])
&& ($_GET["action"]=="reset") && !isset($_POST["action"])) {
    $key = $_GET["key"];
    $mail = $_GET["email"];
    $curDate = date("Y-m-d H:i:s");
    try {
        $query = $pdo->prepare("SELECT * FROM `password_reset` WHERE `key`= ? AND `mail`= ?");
        $query->execute([$key, $mail]);
        $check = $query->fetchAll();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    if ($check == null) {
        $error .= '<h2>LIEN INVALIDE</h2>
<p>Le lien est non valide ou a expire</p>';
    } else {
        $expDate = $check[0]['expDate'];
        if ($expDate >= $curDate) {
            ?>
            <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Camagru</title>
  <link rel = "stylesheet"
    type = "text/css"
    href = "../style.css" />
    </head>
<body>
  <div class="form">
  <form class ="login-form" method="post" action="" name="update">
  <input type="hidden" name="action" value="update" />
  <br /><br />
  <label>Veuillez entrer un nouveau mot de passe :</label><br />
  <input type="password" name="pass1" maxlength="15" required />
  <br /><br />
  <label>Veuillez re-entrer votre nouveau mot de passe :</label><br />
  <input type="password" name="pass2" maxlength="15" required/>
  <br /><br />
  <input type="hidden" name="email" value="<?php echo $mail; ?>"/>
  <input id="login" type="submit" value="Reinitialiser le mot de passe" />
  </form></div>
        </body>
    </html>
<?php
        } else {
            $error .= "<h2>LIEN EXPIRE</h2>
<p>Le lien a expire. Tu as 24h pour utiliser ton lien<br /><br /></p>";
        }
    }

    if ($error!="") {
        echo "<div class='error'>".$error."</div><br />";
    }
}
 
 
if (isset($_POST["email"]) && isset($_POST["action"]) &&
 ($_POST["action"]=="update")) {
    $error="";
    $pass1 = strip_tags($_POST["pass1"]);
    $pass2 = strip_tags($_POST["pass2"]);
    $mail = $_POST["email"];
    $curDate = date("Y-m-d H:i:s");
    if (!is_valid_password($pass1)){
        echo "Le mot de passe doit contenir 2 caracteres speciaux et/ou majuscules et avoir 8 caracteres";
        exit();
    }
    if ($pass1!=$pass2) {
        $error.= "<p>Les mots de passe ne sont pas les memes.<br /><br /></p>";
    }
  
    if ($error!="") {
        echo "<div class='error'>".$error."</div><br />";
    } else {
        $query = $pdo->prepare("UPDATE users
SET password=PASSWORD('$pass1')
WHERE mail= ?");
        $query2 = $pdo->prepare("DELETE FROM `password_reset` WHERE `mail`= ?");
        try {
            $query->execute([$mail]);
            
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        try {
            $query2->execute([$mail]);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    echo("<div>Le mot de passe a bien ete change\n<br> <a href=\"//localhost:8100/login.php\">
            Connecte toi ici !</a></div><br /></div>");
}
?>