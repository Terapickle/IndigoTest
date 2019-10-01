;(function ($, window, document, undefined) {

    $.fn.originNavbar = function (options) {

        const defaults = {
            navbarToggleClass: `.navbar__toggle`
        };

        options = $.extend({}, defaults, options);
        
        return this.each(function () {
            const navbar = $(this),
                button = navbar.find(options.navbarToggleClass),
                collapse = $(button.data('target'));

            button.on('click', (e) => {
                e.preventDefault();
                if (collapse.hasClass('navbar__collapse--open')) {
                    collapse.removeClass('navbar__collapse--open');
                } else {
                    collapse.addClass('navbar__collapse--open');
                }
            });

            collapse.find('[data-toggle="dropdown"]').on('click', function (e) {
                e.preventDefault();
                const li = $(this).closest('.dropdown');
                if (li.hasClass('dropdown--open')) {
                    li.removeClass('dropdown--open');
                } else {
                    collapse.find('[data-toggle="dropdown"]').closest('.dropdown').removeClass('dropdown--open');
                    li.addClass('dropdown--open');
                }
            });

            $('html').on('click', function () {
                collapse.find('[data-toggle="dropdown"]').closest('.dropdown').removeClass('dropdown--open');
            });

            navbar.on('click', function (e) {
                e.stopPropagation();
            });

        });

    };
    
})(jQuery, window, document);
