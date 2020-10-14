<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if($_SERVER['REQUEST_METHOD'] != 'POST'){
    header("index.php");
    exit;
}
if(!(
    isset($_POST['content']) &&
    isset($_POST['tags']) &&
    isset($_POST['url']) &&
    isset($_POST['title']) &&
    isset($_POST['cover']) &&
    isset($_POST['cover_thumbnail']) &&
    isset($_POST['summary']) &&
    isset($_POST['userid']) &&
    isset($_POST['password'])
)){
    echo "<h1>incomplete data...</h1><hr />";
    echo json_encode($_POST);
}

require_once '../server/global.php';

$conn = get_connection();

$userid = $conn->real_escape_string(trim($_POST['userid']));
$password = $conn->real_escape_string(trim($_POST['password']));

$user = $conn->query("SELECT 
                password
                FROM Writers
                WHERE Id=$userid
            ") or die($conn->error . " in line " . __LINE__);
if($user->num_rows == 0){
    die("Unauthorized user id");
    exit;
}
$hash = $user->fetch_array(MYSQLI_NUM)[0];
if(!password_verify($password, $hash)){
    die("invalid login...");
    exit;
}

$title = $conn->real_escape_string(trim($_POST['title']));
$cover = $conn->real_escape_string(trim($_POST['cover']));
$cover_thumbnail = $conn->real_escape_string(trim($_POST['cover_thumbnail']));
$url = $conn->real_escape_string(trim(trim($_POST['url'])));
$content = $conn->real_escape_string(trim($_POST['content']));
$tags = trim($_POST['tags']);
$summary = $conn->real_escape_string(trim($_POST['summary']));

$conn->autocommit(false);
$conn->query("INSERT
            INTO Blog
            (Title, Url, Content, Cover, CoverThumb, Summary, Author)
            VALUES
            ('$title', '$url', '$content', '$cover', '$cover_thumbnail', '$summary', $userid) 
            ;") or die($conn->error . " at line ".__LINE__);

$id = $conn->insert_id;

$tags = explode(',', $tags);
$stmt = $conn->prepare("INSERT
            INTO BlogTag
            (Blog, Tag)
            VALUES
            ($id, ?)
        ;") or die($conn->error."at line ".__LINE__);
$tag = "null";
$stmt->bind_param('s', $tag) or die($stmt->error . " in line " . __LINE__);

for($i = 0; $i < count($tags); $i++){
    $tag = trim($tags[$i]);
    if(empty($tag)){
        continue;
    }
    $stmt->execute() or die($stmt->error . " in line " . __LINE__);
}

$conn->commit();
echo "Sucess...";
header("Location: /post/".$url);
exit;
?>