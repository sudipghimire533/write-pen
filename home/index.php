<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home Pages</title>

    <link rel="stylesheet" href="home.css" />
    <link rel="stylesheet" href="/index.css" />
    <link rel="stylesheet" href="/fontello/css/fontello.css" />
</head>
<body>
    <?php
        include_once('../navbar.php');
    ?>
    <div id='Main'>
        <div class='filterContainer'>
            <span>
                <a href='#' class='filter_option'>Trending</a>
                <a href='#' class='filter_option active'>Recent</a>
                <a href='#' class='filter_option' onclick="document.getElementsByClassName('tagContainer')[0].classList.toggle('active');">Tag &#9660;</a>
                <div class='tagContainer'>
                    <span class='tag active'>all</span>
                    <span class='tag'>programming</span>
                    <span class='tag'>linux</span>
                    <span class='tag'>security</span>
                    <span class='tag'>gnome</span>
                    <span class='tag'>GUI</span>
                    <span class='tag'>c++</span>
                    <span class='tag'>overfloading</span>
                    <span class='tag'>idioms</span>
                    <span class='tag'>electronics</span>
                    <span class='tag'>scription</span>
                    <span class='tag'>javascript</span>
                    <span class='tag'>java</span>
                </div>
            </span>
        </div>
        <div class='postContainer'>
            <a href='#' class='post'>
                <div class='coverContainer'>
                    <img src='' load='lazy' width='200' class='postCover' />
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
    let notInMap = new Map;

    (function(){
        let postContainer = document.getElementsByClassName('postContainer')[0];

        let type = 'recent';

        let handler = new XMLHttpRequest;
        handler.onerror = function(){
            postContainer.innerHTML = "<div>Failed to fetch posts.<a href=''>reload</a><div>";
        };
        handler.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                if(this.responseText == 1){
                    return this.onerror();
                }
                let allPosts = JSON.parse(this.responseText);
                allPosts.forEach(post => {
                    if()
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
        handler.send('action=getFeed&notIn='+notIn+'&type='+type);
    })();
</script>
</html>