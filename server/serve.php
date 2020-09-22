<?php
require_once('global.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function get_feed($url, $response = null){
    $conn = get_connection();

    $url = $conn->real_escape_string(trim($url));
    
    $res = $conn->query("SELECT
                blog.Title AS title,
                blog.Id AS id,
                blog.CreatedOn AS created,
                blog.UpdatedOn AS updated,
                blog.Content AS info,
                blog.Cover AS cover,
                GROUP_CONCAT(bt.Tag) AS tags
                FROM
                Blog AS blog
                LEFT JOIN
                BlogTag AS bt
                ON bt.Blog=blog.Id
                WHERE blog.Url='$url'
            ;") or die($conn->error ." in line ". __LINE__);
    $res = $res->fetch_assoc();
    if($res['title'] == null){
        $conn->close();
        $response = null;
        return 404;
    }
    $response = $res;
    return $response;
}

function get_related($id, $count = 6){
    $conn = get_connection();

    $id = $conn->real_escape_string($id);

    $tags = $conn->query("SELECT
            GROUP_CONCAT(Tag)
            FROM BlogTag 
            WHERE Blog=$id
        ;") or die($conn->error);
    $tags = $tags->fetch_array(MYSQLI_NUM);
    if($tags == null){
        return 404;
    }
    $tags = $tags[0];
    if(strlen($tags) == 0){
        return 404;
    }

    $posts = null;
    $tag = '';
    $ids = "$id";
    $idMap = null;

    $stmt = $conn->prepare("SELECT
            blog.Title as title,
            blog.Id as id,
            blog.CoverThumb as cover,
            blog.Url as url
            FROM Blog blog
            LEFT JOIN BlogTag
            bt ON bt.Blog=blog.Id
            WHERE
            (Tag=?) AND
            (blog.Id NOT IN (?))
        ;") or die($conn->error . " in line ".__LINE__);
    $stmt->bind_param('ss', $tag, $ids);

    foreach (explode(',', $tags) as $key => &$value) {
        $tag = $value;
        $stmt->execute() or die($conn->error . " in line ".__LINE__);
        $res = $stmt->get_result();
        while($row = $res->fetch_array(MYSQLI_ASSOC)){
            if(isset($idMap[$row['id']])){
                continue;
            }
            $idMap[$row['id']] = true;
            $posts[] = $row;
            $ids .= ",".$row['id'];
        }
    }
    if(($remaining = $count-count($posts)) != 0){
        $res = $conn->query("SELECT
                blog.Title as title,
                blog.Url as url,
                blog.CoverThumb as cover
                FROM Blog blog
                WHERE blog.Id NOT IN ($ids)
                LIMIT $remaining
            ;") or die($conn->error);
        $res = $res->fetch_all(MYSQLI_ASSOC);
        $posts = array_merge($posts, $res);
    }
    $conn->close();
    return $posts;
}

if(isset($_POST['action']) && isset($_POST['id'])){
    if($_POST['action'] == 'getRelatedPost'){
        echo json_encode(get_related($_POST['id']));
    }
}
?>