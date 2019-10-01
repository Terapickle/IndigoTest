<?php

$content = 'Sorry, but the page you were looking for doesn\'t exist anymore or it may have been moved.';

get_header(); ?>

    <div class="band band--md">
        <div class="container">

            <h1><?= apply_filters('the_title', (get_field('page_404_title', 'option') ?: 'Page Not Found'), get_the_ID()); ?></h1>
            
            <?= apply_filters('the_content', get_field('page_404_content', 'option') ?: $content); ?>

        </div>
    </div>
    
<?php get_footer();
