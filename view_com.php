<?php
session_start();

if (isset($_GET['id'])) {
    include('config/connection.php');
    try {
        $query = $pdo->prepare("SELECT * FROM `photos` WHERE id = ?");
        $query->execute([$_GET['id']]);
        $total = $query->fetchAll();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    $pic = $total[0]['photo'];

    try {
        $query = $pdo->prepare("SELECT * FROM `comments` WHERE id_photo = ?");
        $query->execute([$_GET['id']]);
        $total_com = $query->fetchAll();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Webcam</title>
    <link rel = "stylesheet"
    type = "text/css"
    href = "style.css" />
</head>
<body>
    <div id="top_bar">
    <?php include('top_bar.php');?>
</div>
<div id="full_body">
    <div id="middle-col">
        <div class ="comz">
    <?php echo '<img class ="img_com" src="'.$pic.'">'; ?>
</div>
    <div class = "display_com">
    <?php include('display_com.php');?>
</div>
    </div>
    <div id="right-col">
        <?php include('get_mini.php');?>
    </div> 
</div>         
    <?php include('footer.php'); ?>
    </body>
</html>
