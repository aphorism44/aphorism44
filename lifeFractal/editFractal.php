<?php 
    include('lifeHeader.php');
    include('fractalFormHandler.php');
?>
<script src="http://code.jquery.com/jquery-2.1.4.js"></script>
<script src="//tinymce.cachefly.net/4.2/tinymce.min.js"></script>
<script type="text/javascript" src="fractalApp.js"></script>
<body>
<header>
 <!-- below to test current session data -->
<?php
    echo "<h1>" . $_SESSION['username'] . "'s Life Fractal</h1>";
?>
</header>
<main>  
    <div class="container">
        <div class="tabs">
            <a href=""><span id = "1" class="active">Edit Entry</span></a>
            <a href=""><span id = "2">Switch Entry</span></a>
            <a href=""><span id="3">Keywords</span></a>
        </div>
        <div class="content">
        </div>
    </div> 
</main>
   
<?php 
    include('lifeFooter.php');
?>