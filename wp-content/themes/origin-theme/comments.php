<?php

if (post_password_required() || (!comments_open() && !have_comments())) {
    return;
}

?><section id="comments" class="comments">

    <?php if (comments_open() && have_comments()) : ?>
        <h2 class="comments__title">Leave A Comment</h2>
    <?php endif;

    if (have_comments()) : ?>

        <ol class="comments__list"><?php

            wp_list_comments([
                'type' => 'comment',
                'style' => 'ol',
                'avatar_size' => 70,
            ]);

        ?></ol>

        <?php

        the_comments_navigation();

        if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
            <p class="comments__no-comments">Comments are closed.</p>
        <?php endif;

    endif;

    comment_form([
        'comment_notes_after' => ''
    ]);

    ?>

</section>