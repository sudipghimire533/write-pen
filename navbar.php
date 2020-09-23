<div id='NavBar'>
    <div class='Container'>
        <div id='nav_left'>
        </div>
        <div id='nav_middle'>
            <form class='topSearchContainer' method='GET'>
                <input name='query' id='searchQuery' placeholder='What you want to know today?' onfocus="this.placeholder=''" onblur="this.placeholder='What you want to know today..'"/>
                <i class='search_icon fa fa-search'></i>
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
        border: 1px solid var(--White);
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
    .search_icon{
        position: absolute;
        right: 8px;
        font-size: 20px;
        bottom: 10px;
        cursor: pointer;
    }

</style>