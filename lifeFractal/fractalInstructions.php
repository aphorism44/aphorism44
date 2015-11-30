<?php 
    include('lifeHeader.php');
?>
<script src="http://code.jquery.com/jquery-2.1.4.js"></script>
<script src="//tinymce.cachefly.net/4.2/tinymce.min.js"></script>
<body>
<header>
 <!-- below to test current session data -->
<?php
    echo "<h1>" . $_SESSION['username'] . "'s Life Fractal</h1>";
?>
</header>
<main> 
       <div class="content" id="instructions">
           <u>Overview</u>
            <br><br><p>The Life Fractal web application allows you to create a personalized life story organized in a fractal format. Instead of a standard linear or chronological story, this app allows you to type in incidents and facts at random, then connect them to each other with a series of keywords.</p>
            <br><br><p>For example: suppose you wrote the following entry:</p>
            <pre>I’m sitting at my computer, trying out the new Life Fractal app. Outside, it’s cloudy, windy, and looks like it’s about to rain.</pre>
            <br><p>The flow reminds you of a rainstorm you witnessed years ago. You would then:</p>
            <ol>
            <li>Highlight the word “rain” and make it into a keyword using the button on the entry page.</li>
            <li>Click the <em>Switch Entry</em> tab and create a new entry</li>
            <li>Click back to <em>Edit Entry</em> and type something like the following:
            <br><pre>The largest storm I remember took place in the summer of ’92. I was staying with my grandfather, and the television announced a tornado watch. Grandpa herded me and my cousins into the basement…</pre></li>
            <li>After saving the entry, click on the <em>Keywords</em> tab. Choose “rain” from the unconnected keyword list, type something like “the largest storm I was ever in” in the description box, and connect it to the entry</li>
            </ol>
            <br><p>When finished, you’ve recalled a specific memory and organized it by keyword. If you click on the <em>Current Life Fractal</em> button at the bottom of the screen, you can see your current life story. Keep adding random entries and more keywords, and you’ll have a life fractal!</p>
            <br><br><u>Edit Entry Tab</u>
            <br><br><p>To add an entry to your life fractal, you need to type in a title and text in the entry text. If desired, you may also add a date.</p>
            <br><br><p>You should save your text often to avoid losing it. The <em>Save</em> button at the bottom of the page will save your current entry, as will clicking one of the other two tabs. However, navigating away from the page may result in lost data.</p>
            <br><br><p>In addition, you can add a new keyword by simply highlighting a word (or group of words), then pressing the <em>Turn Select Text into Keyword</em> button. However, this keyword will not be attached to your entry (or any other entry) until you connect it using the <em>Keywords</em> tab, as described below.</p>
            <br><br><u>Switch Entry Tab</u>
            <br><br><p>Here, you can create a new entry by clicking the <em>Create New Entry</em> button. After doing this, you should return to the <em>Edit Entry</em> tab, which will now be blank, to create a new entry.</p>
            <br><br><p>You can edit existing entries by choosing one from the list, then clicking the <em>Switch to Selected Entry</em> button.</p>
            <br><br><p>You can also delete an existing entry by choosing one from the box and clicking the <em>Delete Selected Entry</em> button. Please be careful when doing this, as deleting an entry will remove all text and keyword references to this entry.</p>
            <br><br><u>Keywords Tab</u>
            <br><br><p>Here, you manage your keywords and their links to your entries. When you click this tab, you will see lists of all your keywords, and have the option to attach any of them to your current entry.</p>
            <br><br><p>To add a new keyword to your list of keywords, type it in the box under the <em>Add New Keyword</em> button, then click the button. The keyword will not initially be connected to any entries.</p>
            <br><br><p>To connect an entry to a keyword, first pick one from the unconnected list, then in the large textbox on the right, type in how this particular keyword relates to the entry. Click the <em>Save Keyword Entry Description</em> to attach the word and description.</p>
            <br><br><p>To remove a keyword connection, highlight it in the attached list, then click the <em>Disconnect Selected Keyword from Entry</em> button. Be careful when doing this, as it will delete not only the connection, but any text you attached to this connection (but not the entry text itself).</p>
            <br><br><p>Please note: your entry text must contain the keyword before you can attach it. Also, even if your entry isn’t connected to a particular keyword, if that keyword appears in the entry text, it will be highlighted in your Life Fractal. For this reason, you should try to connect any keywords that appear in your entry text.</p>
            
                       
           
        </div>
</main>
   
<?php 
    include('lifeFooter.php');
?>