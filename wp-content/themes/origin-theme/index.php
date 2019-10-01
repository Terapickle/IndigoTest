<?php get_header(); ?>

    <div class="band band--md band--gray">
        <div class="container">

            <?php

            if (!is_singular()) :
                the_archive_title('<h1>', '</h1>');
                the_archive_description();
            endif;

            if (have_posts()) : ?>

                <div class="row cards m--b-sm">
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="column column--sm-6 column--md-4">

                            <div class="card">
                                <a href="<?php the_permalink(); ?>" class="card__media">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('card-thumb'); ?>
                                    <?php else : ?>
                                        <svg viewBox="0 0 350 210" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="0" y="0" width="400" height="400" style="fill:#555555;"/>
                                            <text x="50%" y="50%" font-size="18" text-anchor="middle" alignment-baseline="middle" font-family="monospace, sans-serif" fill="#eeeeee">Placeholder</text>
                                        </svg>
                                    <?php endif; ?>
                                </a>
                                <div class="card__body">
                                    <div class="card__header">
                                        <span class="card__meta"><?= get_the_date(); ?></span>
                                        <?php the_title('<h2 class="card__title">', '</h2>'); ?>
                                    </div>
                                    <div class="card__content">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    <div class="card__footer">
                                        <a href="<?php the_permalink(); ?>" class="button button--primary">
                                            Read More <span class="sr">about <?php the_title(); ?></span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    <?php endwhile; ?>
                </div>

                <?php origin_archive_pagination(); ?>

            <?php else : ?>

                <p>Sorry, no items matched your criteria.</p>

            <?php endif; ?>
            
        </div>
    </div>

<?php get_footer();
