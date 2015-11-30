<nav>
        
        
        <ul id = "ulMenu">
            <?php 
                if (isset( $_SESSION[ 'user_id' ])) echo "
                    <li id='liMenu'><a href='editFractal.php'>Edit Fractal</a></li>
                    <li id='liMenu'><a href='viewFractal.php'>Current Life Fractal</a></li>
                    <li id='liMenu'><a href='fractalInstructions.php'>Instructions</a></li>
                    <li id='liMenu'><a href='logout.php'>Logout</a></li>  "; 
               else echo "
                    <li id='liMenu'><a href='" . ROOT_URL . "/index.php'>Return to Main Page</a></li>";
            ?>
                
        </ul>
        </nav>
        <div id="footer" class="smaller">Copyright 2015 | Aphorism 44</div>
    </body>
</html>
