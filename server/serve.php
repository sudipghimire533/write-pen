<?php
require_once('global.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function get_post($url, $response = null){
    $conn = get_connection();

    $url = $conn->real_escape_string(trim($url));
    
    $res = $conn->query("SELECT
                blog.Title AS title,
                blog.Id AS id,
                blog.CreatedOn AS created,
                blog.UpdatedOn AS updated,
                blog.Content AS info,
                blog.Summary AS summary,
                blog.Cover AS cover,
                blog.CoverThumb AS cover_thumb,
                GROUP_CONCAT(bt.Tag SEPARATOR ' ' ) AS tags
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

function getFeed($notIn, $type='recent'){
    $conn = get_connection();
    $notIn = $conn->real_escape_string($notIn);

    $ids = "-1";
    if($type == 'bytag'){
        // change $query accordongly
    } else {
        $res = $conn->query("SELECT
                GROUP_CONCAT(Id)
                FROM Blog
                WHERE Id NOT IN($notIn)
            ;") or die($conn->error . " in line ". __LINE__);
        if($res->num_rows == 0){
            return 1;
        }
        $res = $res->fetch_array(MYSQLI_NUM);
        $ids = $res[0];
    }
    $res = $conn->query("SELECT
                blog.Id AS id,
                blog.Url AS url,
                blog.Title AS title,
                blog.Summary AS summary,
                blog.CoverThumb AS cover,
                blog.UpdatedOn AS updated
                FROM Blog blog
                WHERE Id IN ($ids)
                LIMIT 10
            ;") or die($conn->error . " in line " . __LINE__ );
    if($res->num_rows == 0){
        return 404;
    }
    $res = $res->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $res;
}

if(isset($_POST['action'])){
    if(isset($_POST['id']) && $_POST['action'] == 'getRelatedPost'){
        print_r(json_encode(get_related($_POST['id'])));
    } else if(isset($_POST['notIn']) && $_POST['action'] == 'getFeed' && isset($_POST['type'])){
        print_r(json_encode(getFeed($_POST['notIn'], $_POST['type'])));
    }
}
?>