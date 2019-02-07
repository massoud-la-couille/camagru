<?php
function create_user(){
    //connection to SQL through PDO
    session_start();
    if (!isset($_SESSION['crea']))
        header('Location: ../my_account.php');
    else
        header('Location: ../create.php');
    include('../config/connection.php');
    include('valid_passwd.php');
    include('valid_mail.php');
    include('verify_user.php');
    // check Post correct


    if ($_POST["submit"] == "Creer un compte" && $_POST["login"] && $_POST["password"] && $_POST["email"]) {
        // create var of user and pass and mail
        list($login, $password, $mail, $hash) = array($_POST["login"], $_POST["password"], $_POST["email"], md5(rand(0,1000)));
        if (!is_valid_password($_POST['password'])){
            // echo "<div><h1>Le mot de passe doit contenir 2 caracteres speciaux et/ou majuscules et avoir 8 caracteres</div>";
            $_SESSION['crea'] = 1;
            exit();
        }
        if (!valid_mail($_POST['email'])){
            // echo "L'addresse email est non-valide";
            $_SESSION['crea'] = 2;
            exit();
        }
        try {
            // Prepare and query SQL for check
            $query = $pdo->prepare("SELECT * FROM users WHERE login='$login'");
            $query->execute();
            $check = $query->fetchAll();
            $query = $pdo->prepare("SELECT * FROM users WHERE mail='$mail'");
            $query->execute();
            $check2 = $query->fetchAll();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        // check if user exist
        if ($check == null && $check2 == null) {
            // create user on SQL line
            $query = $pdo->prepare("INSERT INTO users (login, password, mail, hash)
								VALUES ('$login', PASSWORD('$password'), '$mail', '$hash')");
            try {
                // apply SQL line on database
                $query->execute();
                // echo("L'utilisateur a bien été crée. Vous allez recevoir un email de confirmation à l'adresse indiquee\n");
                verify_user($mail, $hash);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            if (isset($_SESSION['crea']))
                unset($_SESSION['crea']);
        } elseif ($check != null) {
            $_SESSION['crea'] = 3;
            // echo("Ce nom d'utilisateur est deja utilise\n");
        } elseif ($check2 != null) {
            $_SESSION['crea'] = 4;
            // echo("Cette adresse e-mail est deja utilisee\n");
        }
    }
    $pdo = null;
}
create_user();

?>