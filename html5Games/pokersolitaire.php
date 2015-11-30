<?php 
    include('../include/config.php');
    include('../include/header.html');
?>
    <body>
        <header>
            <h1>Aphorism 44 - Poker Solitaire</h1>
        </header>
        <div id="main">
            
            <table>
                <th><strong>Poker Solitaire</strong></th>
                <tr><td><strong>Engine :</strong>
                    Basic features, enchant.js engine.
                </td></tr>
                <tr><td><strong>Graphics :</strong>
                    All self-made; Adobe Photoshop Elements 4.0.
                </td></tr>
                <tr><td><strong>Gameplay :</strong>
                    Simple solitaire game where player shifts around cards (5 rows of
                    5 cards each) in order to create five pat hands (4-of-a-kind, Full House,
                    Flush, or Straight). Contains algorithm to ensure that all dealt hands are winnable.</td></tr>
                <tr><td><strong>Formats :</strong>
                    Android (primary target); all major web browsers.
                </td></tr>
                <tr><td><strong>Locations :
                    <ul>
                        <li><a href="https://play.google.com/store/apps/details?id=com.aphorism44.pokersolitaire&hl=en" target="_blank">Android app</a></li>
                        <li><a href="http://pokersolitaire.clay.io/" target="_blank">Clay.io</a></li>
                    </ul>
                    </strong></td></tr>
                <tr><td><strong>Relevant Blog Entries :</strong>
                    <ul>
                        <li><a href="<?php echo ROOT_URL; ?>/blog/blogEntry.php?entryId=9" target="_blank">Poker Solitaire I – A Proof for Losing</a></li>
                        <li><a href="<?php echo ROOT_URL; ?>/blog/blogEntry.php?entryId=10" target="_blank">Poker Solitaire II - No (Quick) Program for Winning</a></li>
                        <li><a href="<?php echo ROOT_URL; ?>/blog/blogEntry.php?entryId=11" target="_blank">Poker Solitaire III – A Backend Heuristic</a></li>
                    </ul>
                </td></tr>
                <tr><td><strong>Notes :</strong></td></tr>
            </table>
        </div>
        <div id="sidebar">
            <p>Read my blog for more info on these games, upcoming games, and how I make them.
                </p>    
        </div>
<?php 
    include('../include/footer.html');
?>