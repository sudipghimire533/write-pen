<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(!isset($_GET['url']) || empty($_GET['url'])){
    echo "No url...";
    exit;
}
require_once('../server/serve.php');

$thisPost = get_feed($_GET['url']);
if($thisPost == 404){
    echo "Post not fond...";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $thisPost['title']; ?></title>

    <link rel="stylesheet" href="/blogs/blog.css" />
    <link rel="stylesheet" href="/index.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div id='Main'>
        <div id='contentWrapper'>
            <div id='postCover' style="background: url('<?php echo $thisPost['cover']; ?>');">
            </div>

            <main id='article'>
                <h1 id='postHeading'>
                <?php
                    echo $thisPost['title'];
                ?>
                </h1>
                <div class='metaInfo'>
                    <div class='meta_author'>
                        <img src='../person.jpeg' alt='sudip Ghimire..' class='author_avatar' load='lazy' height='80' width='80' />
                        <div class='author_name'>
                            Sudip <br />Ghmire
                        </div>
                    </div>
                    
                    <div class='meta_date'>
                        <i class='fa fa-calendar'></i>
                        <span>
                        <?php 
                            echo $thisPost['created'];
                        ?>
                        </span>
                    </div>
                    <div class='meta_time'>
                        <i class='fa fa-star'></i>
                        <span>4</span> min read
                    </div>

                    <div class='socialIcons' onclick="this.getElementsByClassName('link_share')[0].classList.toggle('active')">
                        <i class='fa fa-link'></i>
                        <span class='link_share'>
                            <input type='text' readonly='true' class='link_share_url' value='https://www.artal.org/article/2020/how-to-do-things-that-is-good-and-let-it-go' />
                            <i class='fa  fa-clipboard icon'></i>
                        </span>
                        <i class='fa fa-facebook-square'></i>
                        <i class='fa fa-twitter'></i>
                        <i class='fa fa-snapchat'></i>
                        <i class='fa fa-github'></i>
                        <i class='fa fa-whatsapp'></i>
                    </div>
                </div>
                <article id='Content'>
                <?php
                    echo $thisPost['info'];
                ?>
                </article>

                <div id='articleBottom'>
                    <span id='tagContainer'>
                        <!--a href='#' class='tag'>c++</a-->
                        <?php
                            foreach (explode(',', $thisPost['tags']) as $key => &$value) {
                                echo "<a class='tag' href='/taggedfor/$value'>$value</a>\n";
                            }
                        ?>
                    </span>
                    <!--Add .socialIcons fromjavascript-->
                </div>
            </main>

            <div id='aboutAuthor'>
                <div class='meta_author'>
                    <img src='../person.jpeg' alt='sudip Ghimire..' class='author_avatar' load='lazy' height='80' width='80' />
                    <div class='author_name'>Sudip Ghmire</div>
                </div>
                <div id='authorAbout'>
                    I am sudip ghimire from pokhara, Nepal. I share my technical knowledge through writings. For me sharing is the best thing i can offer. I mostly write about programming and techniques in this blog. You can also visit my pesonal portfolio or hire me.
                    <div class='btnContainer'>
                        <a href='#' class='author_btn'>Visit me</a>
                        <a href='#' class='author_btn'>Hire me</a>
                    </div>
                </div>
            </div>

            <div id='relatedContainer'>
                <a href='#' class='related_post'>
                    <img src='' load='lazy' class='related_cover' />
                    <h3 class='related_title'></h3>
                </a>
            </div>

        </div>
    </div>
    <script type='text/javascript'>
    var postId = <?php echo json_encode($thisPost['id']); ?>;
    (function(){
        document.getElementById('articleBottom').appendChild(document.getElementsByClassName('socialIcons')[0].cloneNode(true));

        let relatedContainer = document.getElementById('relatedContainer');
        let sampleRelated = relatedContainer.firstElementChild;

        let handler = new XMLHttpRequest;
        handler.onerror = function(){
            relatedContainer.innerHTML = 'Failed to get relatedd posts...';
        };
        handler.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                let response = JSON.parse(this.responseText);
                if(response == 1){
                    relatedContainer.innerHTML = 'Failed to get related posts...';
                    return;
                }

                let related;
                response.forEach(post => {
                    related = sampleRelated.cloneNode(true);
                    related.setAttribute('href', post);
                    related.getElementsByClassName('related_cover')[0].setAttribute('src', post.cover);
                    related.getElementsByClassName('related_title')[0].textContent = post.title;
                    relatedContainer.appendChild(related);
                });
                sampleRelated.remove();
            }
        };
        handler.open('POST', '/server/serve.php', true);
        handler.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        handler.send('action=getRelatedPost&id='+postId);
    })();
    </script>
</body>
</html>