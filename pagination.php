<?php 

include_once('comments/get_likes.php');
include_once('config/connection.php');
try {
$query = $pdo->prepare("SELECT
COUNT(*)
FROM
photos");
$query->execute();
$total = $query->fetchAll();
} catch (PDOException $e) {
    echo $e->getMessage();
}

if ($total) {
    $limit = 5;
    $pages = ceil($total[0]['COUNT(*)'] / $limit);
    $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
    'options' => array(
        'default'   => 1,
        'min_range' => 1,
    ),
)));

    $offset = ($page - 1)  * $limit;

    $start = $offset + 1;

    $end = min(($offset + $limit), $total);

    $prevlink = ($page > 1) ? '<a href="?page=1" title="First page">&laquo;</a> <a href="?page=' . ($page - 1) . '" title="Previous page">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';

    $nextlink = ($page < $pages) ? '<a href="?page=' . ($page + 1) . '" title="Next page">&rsaquo;</a> <a href="?page=' . $pages . '" title="Last page">&raquo;</a>' : '<span class="disabled">&rsaquo;</span> <span class="disabled">&raquo;</span>';

    

    $query = $pdo->prepare('
SELECT
    *
FROM
    `photos`
ORDER BY
    id DESC
LIMIT
    :limit
OFFSET
    :offset');
    $query->bindParam(':limit', $limit, PDO::PARAM_INT);
    $query->bindParam(':offset', $offset, PDO::PARAM_INT);
    try {
        $query->execute();
        $check = $query->fetchAll();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    $i = 0;
    if ($query->rowCount() > 0) {
        echo '<iframe name="hiddenFrame" class="hide"></iframe>';
        echo '<div class="row">';
        foreach ($check as $k => $val) {
            echo '<div class = "col'.$i.'"><img class ="img_galerie" src="'.($val['photo']).'">';
            if ($_SESSION['login']){
            echo '<div class="block_com"><form  action="comments/com_and_like.php" method="POST" target="hiddenFrame">
            <input class="insert_com" type="submit" name="submit" value="Commenter"><br><input id="commentbox" type="text" name="com"><br>
            <input type="hidden" name="id" value="'.$val['id'].'">
            <input type="hidden" name="login" value="'.$val['login'].'">
            
    </form>';
    
            echo '<form style="display:inline-block;position:absolute;margin-left:7vw;margin-top:-6.5vh;" action="comments/com_and_like.php" method="POST" target="hiddenFrame">
            <div id="get_likes">'.get_likes($val['id']).'</div><input id ="like" type="image" src="../pics/like.png"></a>
            <input type="hidden" name="id" value="'.$val['id'].'">
            <input type="hidden" name="submit" value="like">

        </form></div>';
            }
        echo '</div>';
            $i++;
        }
        echo '</div>';
        echo '<br><br><div id="paging"><p>', $prevlink, ' Page ', $page, ' of ', $pages, ' pages, displaying ', $start, '-', $end, ' of ', $total, ' results ', $nextlink, ' </p></div>';
    }
}

?>