<div id='NavBar'>
    <div class='Container'>
        <div id='nav_left'>
            <a class='icon-home' href='/home'> Home</a>
        </div>
        <div id='nav_middle'>
            <form class='topSearchContainer' method='GET'>
                <input name='query' id='searchQuery' placeholder='What you want to know today?' onfocus="this.placeholder=''" onblur="this.placeholder='What you want to know today..'"/>
                <i class='icon-search'></i>
            </form>
        </div>
        <div id='nav_right'>
        </div>
    </div>
</div>
<style>
    #NavBar{
        background: var(--LightDark);
        color: var(--White);
        margin-bottom: 40px;
        padding: 5px 0;
        z-index: 3;
    }
    #NavBar > .Container{
        display: flex;
        flex-flow: row nowrap;
        align-items: center;
        justify-content: space-around;
    }
    #NavBar > .Container > *{
        flex-grow: 1;
        margin: 0 10px;
        position: relative;
    }
    #searchQuery{
        margin: 0;
        height: 37px;
        box-sizing: border-box;
        border: 1px solid rgba(255, 255, 255, .5);
        border-radius: 5px;
        outline: none;
        padding: 10px 20px;
        padding-right: 30px;
        box-sizing: border-box;
        background: transparent;
        color: inherit;
        font-weight: bold;
        text-align: center;
        font-family: Rubik;
        font-size: calc( var(--fontLarge) + var(--fontSmall) / 32ss );
        margin: 3px 0;
        width: 100%;
        letter-spacing: 1.7px;
    }
    #NavBar .icon-search{
        position: absolute;
        right: 8px;
        font-size: 20px;
        bottom: 10px;
        cursor: pointer;
        opacity:  .6;
        transition: opacity .2s;
    }
    #NavBar .icon-home{
        color: var(--White);
        text-decoration: none;
        opacity: .6;
        transition: opacty .2s;
    }
    #NavBar .icon-home:before{
        font-size: calc(var(--fontPrimary) * 1.3)
    }
    #NavBar .icon-home:hover,
    #NavBar .icon-search:hover{
        opacity: 1;
    }
</style>