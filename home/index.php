<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $type = 'recent';
    $data = null;

    if(isset($_GET['taggedfor'])){
        $data = htmlspecialchars(urlencode($_GET['taggedfor']));
        $type = 'bytag';
    } else if(isset($_GET['trending'])){
        $type = 'trending';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home Pages</title>

    <link rel="stylesheet" href="/home/home.css" />
    <link rel="stylesheet" href="/assest/index.css" />
    <link rel="stylesheet" href="/assest/fontello/css/fontello.css" />
</head>
<body>
    <?php
        include_once('../assest/navbar.php');
    ?>
    <div id='Main'>
        <div class='filterContainer'>
            <span>
                <a href='/trending' class='filter_option trending'>Trending</a>
                <a href='/recent' class='filter_option recent'>Recent</a>
                <a href='#' class='filter_option bytag' onclick="this.parentElement.getElementsByClassName('tagContainer')[0].classList.toggle('active');">Tag &#9660;</a>
                <div class='tagContainer'>
                    <a href='/taggedfor/c++' class='tag'>c++</a>
                    <a href='/taggedfor/c' class='tag'>c</a>
                    <a href='/taggedfor/design-pattern' class='tag'>design pattern</a>
                    <a href='/taggedfor/idioms' class='tag'>Idioms</a>
                    <a href='/taggedfor/linux' class='tag'>linux</a>
                    <a href='/taggedfor/lifestyle' class='tag'>lifestyle</a>
                </div>
            </span>
        </div>
        <div class='postContainer'>
            <a href='#' class='post'>
                <div class='coverContainer'>
                    <img src='' load='lazy' width='200' height='80' class='postCover' />
                </div>
                <div class='postMeta'>
                    <h2 class='post_title'></h2>
                    <span class='post_date'></span>
                </div>
                <p class='postSummary'></p>
            </a>
        </div>
    </div>
</body>
<script>
    let sample = document.getElementsByClassName('post')[0];
    let appendedPost = new Map;
    let notIn = "0";

    (function(){
        let postContainer = document.getElementsByClassName('postContainer')[0];

        let type = <?php echo json_encode($type); ?>;
        let data = <?php echo json_encode($data); ?>;

        document.querySelector(".filter_option."+type).classList.add('active');

        let handler = new XMLHttpRequest;
        handler.onerror = function(){
            postContainer.innerHTML = "<div><h1>Failed to fetch posts.<a href=''>reload</a></h1><div>";
        };
        handler.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                console.log(this.responseText);
                if(this.responseText == 1 || this.responseText == 404){
                    return this.onerror();
                }
                let allPosts = JSON.parse(this.responseText);
                allPosts.forEach(post => {
                    if(appendedPost.has(post.id)){
                        return;
                    }
                    appendedPost.set(post.id, true);
                    notIn += ","+post.id;
                    
                    let craft = sample.cloneNode(true);
                    craft.setAttribute('href', '/post/'+ post.url);
                    craft.getElementsByClassName('postCover')[0].setAttribute('src', post.cover);
                    craft.getElementsByClassName('post_title')[0].textContent = post.title;
                    craft.getElementsByClassName('post_date')[0].textContent = post.updated.split(' ')[0];
                    craft.getElementsByClassName('postSummary')[0].textContent = post.summary;
                    postContainer.appendChild(craft);
                });
            }
        };
        handler.open('POST', '/server/serve.php', true);
        handler.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        handler.send('action=getFeed&notIn='+notIn+'&type='+type+'&data='+data);
        console.log('action=getFeed&notIn='+notIn+'&type='+type+'&data='+data);
    })();
</script>
</html>
