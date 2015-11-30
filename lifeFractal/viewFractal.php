<?php 
    include('lifeHeader.php');
    include('viewFractalHandler.php');
?>
<script src="http://code.jquery.com/jquery-2.1.4.js"></script>
<script src="//tinymce.cachefly.net/4.2/tinymce.min.js"></script>
<script type="text/javascript" src="viewFractalApp.js"></script>
<body>
<header>
 <!-- below to test current session data -->
<?php
    echo "<h1>" . $_SESSION['username'] . "'s Life Fractal</h1>";
?>
</header>
<main> 
       <div class="content" id="fractalBox">
        </div>
        
       <br><br>
       <div id="fractalButtonHolder">
            <button type='button' id='previousEntry'>Previous Entry</button> 
            <button type='button' id='nextEntry'>Next Entry</button> 
        </div>
</main>
   
<?php 
    include('lifeFooter.php');
?>