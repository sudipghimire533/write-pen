<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>

    <link rel="stylesheet" href="home.css" />
    <link rel="stylesheet" href="/index.css" />
</head>
<body>
    <div id='Main'>
        <div class='postContainer'>
            <a href='#' class='post'>
                <div class='coverContainer'>
                    <img src='/cat.png' load='lazy' width='200' class='postCover' />
                </div>
                <div class='postMeta'>
                    <h2 class='post_title'>This is some of that title you may see in the coming future...</h2>
                    <span class='post_date'>
                        2020-03-45
                    </span>
                </div>
                <p class='postSummary'>
                    This is something that you can call summary and this is awesome and this is what all about and that is great too. and this is to be the one of the internetthings and that is being super cool and i am listigning to the song by the uniq poet. oh am i really putring the fullstop like this.
                </p>
            </a>
        </div>
    </div>
</body>
<script>
    let sample = document.getElementsByClassName('post')[0];
    for(let i = 0; i < 10; i++){
        sample.parentElement.appendChild(sample.cloneNode(true));
    }
</script>
</html>