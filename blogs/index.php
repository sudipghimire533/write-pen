<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(!isset($_GET['url']) || empty($_GET['url'])){
    echo "No url...";
    exit;
}
require_once('../server/serve.php');

$thisPost = get_post($_GET['url']);
if($thisPost == 404){
    echo "Post not fond...";
    exit;
}
$summary = substr($thisPost['summary'], 0, 160);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $thisPost['title']; ?></title>

    <meta name="Descrption" content="<?php echo $summary; ?>">
    <meta name="Keywords" content="sudip-ghimire <?php echo $thisPost['tags']; ?> learn-programming programming-blog">
    
    <meta property="og:title" content="<?php echo $thisPost['title']; ?>">
    <meta property="og:description" content="<?php echo $summary; ?>">
    <meta property="og:image" content="<?php echo $thisPost['cover_thumb']; ?>">

    <link rel="stylesheet" href="/blogs/blog.css" />
    <link rel="stylesheet" href="/assest/index.css" />
    <link rel="stylesheet" href="/assest/fontello/css/fontello.css" />
</head>
<body>
    <?php
        include_once('../assest/navbar.php');
    ?>
    <div id='Main'>
        <div id='contentWrapper'>
            <div id='postCover' style="background-image: url('<?php echo $thisPost['cover']; ?>');">
            </div>

            <main id='article'>
                <h1 id='postHeading'>
                <?php
                    echo $thisPost['title'];
                ?>
                </h1>
                <div class='metaInfo'>
                    <div class='meta_author'>
                        <img src='/assest/author.jpeg' alt='sudip Ghimire..' class='author_avatar' load='lazy' height='80' width='80' />
                        <div class='author_name'>
                            Sudip <br />Ghmire
                        </div>
                    </div>
                    
                    <div class='meta_date'>
                        <i class='icon-calendar'></i>
                        <span>
                        <?php 
                            echo explode(' ',$thisPost['created'])[0];
                        ?>
                        </span>
                    </div>
                    <div class='meta_time'>
                        <i class='icon-fire'></i>
                        <span>4</span> min read
                    </div>

                    <div class='socialIcons' onclick="this.getElementsByClassName('link_share')[0].classList.toggle('active')">
                        <i class='icon-link'></i>
                        <span class='link_share' onclick="">
                            <input type='text' readonly='true' class='link_share_url' value='https://www.artal.org/article/2020/how-to-do-things-that-is-good-and-let-it-go' />
                            <i class='icon-clipboard'></i>
                        </span>
                        <i class='icon-facebook'></i>
                        <i class='icon-twitter'></i>
                        <i class='icon-github'></i>
                        <i class='icon-whatsapp'></i>
                    </div>
                </div>
                <article id='Content'>
                <?php
                    echo $thisPost['info'];
                ?>
                </article>

                <div id='articleBottom'>
                    <span id='tagContainer'>
                        <?php
                            foreach (explode(' ', $thisPost['tags']) as $key => &$value) {
                                echo "<a class='tag' href='/taggedfor/$value'>$value</a>\n";
                            }
                        ?>
                    </span>
                    <!--Add .socialIcons fromjavascript-->
                </div>
            </main>

            <div id='aboutAuthor'>
                <div class='meta_author'>
                    <img src='/assest/author.jpeg' alt='sudip Ghimire..' class='author_avatar' load='lazy' height='80' width='80' />
                    <div class='author_name'>Sudip Ghmire</div>
                </div>
                <div id='authorAbout'>
                    I am sudip ghimire from pokhara, Nepal. I share my technical knowledge through writings. For me sharing is the best thing i can offer. I mostly write about programming and techniques in this blog. You can also visit my pesonal portfolio or hire me.
                    <div class='btnContainer'>
                        <a href='#' class='author_btn'>Visit my profile</a>
                        <a href='#' class='author_btn'>Buy me a coffee</a>
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
        let sampleRelated = relatedContainer.getElementsByClassName('related_post')[0];

        let handler = new XMLHttpRequest;
        handler.onerror = function(){
            relatedContainer.innerHTML = 'Failed to get relatedd posts...';
        };
        handler.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                let response = JSON.parse(this.responseText);
                if(response == 1){
                    return this.onerror();
                }

                let related;
                response.forEach(post => {
                    related = sampleRelated.cloneNode(true);
                    related.setAttribute('href', post.url);
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