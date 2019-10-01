<form class="form search-form" role="search" method="get" action="<?= esc_url(home_url()); ?>">
    <div class="form__group search-form__input">
        <label class="form__label sr" for="s">Search*</label>
        <input type="text" name="s" id="s" class="input input--text" placeholder="Search..." value="<?= esc_attr(get_search_query()); ?>">
    </div>
    <div class="form__group search-form__button">
        <button type="submit" class="button button--primary button--block">Search</button>
    </div>
</form>
