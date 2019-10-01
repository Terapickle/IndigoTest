    </main>

    <footer class="footer" role="contentinfo">
    
        <?php if (is_active_sidebar('footer') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3')) : ?>
            <div class="footer__main">
                <div class="container">

                    <div class="row">
                        <div class="column column--sm-4">
                            <?php dynamic_sidebar('footer-1'); ?>
                        </div>
                        <div class="column column--sm-4">
                            <?php dynamic_sidebar('footer-2'); ?>
                        </div>
                        <div class="column column--sm-4">
                            <?php dynamic_sidebar('footer-3'); ?>
                        </div>
                    </div>
                    
                </div>
            </div>
        <?php endif; ?>

        <div class="footer__bottom">
            <div class="container">

                <div class="footer__copyright">
                    <?= origin_theme_copyright(); ?>
                    <?php wp_nav_menu([
                        'theme_location' => 'footer-copyright',
                        'container' => false,
                        'menu_class' => 'footer__copyright-menu',
                        'depth' => 1,
                        'fallback_cb' => '__return_empty_string'
                    ]); ?>
                </div>

                <div class="footer__credit">
                    <?= origin_theme_credit(); ?>
                </div>

            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>

</body>
</html>