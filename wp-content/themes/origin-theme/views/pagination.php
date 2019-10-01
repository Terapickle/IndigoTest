<nav class="pagination" role="navigation">
    <ul class="pagination__menu">
        <?php
        
        foreach ($links as $link) :
            echo "<li>{$link}</li>";
        endforeach;
        
        ?>
    </ul>
</nav>