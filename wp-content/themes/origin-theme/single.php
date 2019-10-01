<?php get_header(); ?>
    
    <?php while (have_posts()) : the_post(); ?>

        <article>
            <div class="band band--md">
                <div class="container">
                
                    <div class="row">
                        <div class="column column--sm-8">

                            <header class="article__header">
                                <?php the_title('<h1 class="article__title">', '</h1>'); ?>
                                <div class="article__meta">
                                    <?php
                                    $cat = get_the_category_list(', ');
                                    printf(
                                        'Posted on <time datetime="%s">%s</time>%s',
                                        get_the_date('c'),
                                        get_the_date(),
                                        !empty($cat) ? ' in ' . $cat : ''
                                    ); ?>
                                </div>
                            </header>

                            <div class="article__content">
                                <?php the_content(); ?>
                            </div>

                        </div>

                        <aside class="sidebar column column--sm-4">
                            <?php dynamic_sidebar('primary'); ?>
                        </aside>

                    </div>
            
                </div>
            </div>
        </article>

    <?php endwhile; ?>

<?php get_footer();
