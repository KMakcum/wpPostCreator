
<section class="main_form section">
    <div class="container">
        <div class="main_form__wrapper">
            <h1 class="main_form__title">Создание постов для Wordpress</h1>
            <div class="main_form__text">
                Данный сайт создан для быстрого наполнения и тестирования сайта во время разработки.
                Он позволяет создать посты, страницы, товары и другие типы записей для Wordpress.
                После генерации постов Вы получите файл в формате .xml для импорта путем стандартного функционала.
            </div>
            <form id="main_form" action="#" class="main_form__wrapper form">

                <div class="form_section form_section--posts">
                    <h2 class="form_section__title">Основные параметры</h2>

                    <div class="form_area">

                        <div class="form_field form_field--full">
                            <label for="count_posts">Количествово постов</label>
                            <input type="number" id="count_posts" min="1" max="100" name="count_posts" required>
                        </div>

                        <div class="form_field form_field--full">
                            <div class="form_field__wrapper switcher-select">
                                <label class="pk-radio">
                                    <input type="radio" name="post_type" value="post" checked>
                                    <span>Записи</span>
                                </label>

                                <label class="pk-radio">
                                    <input type="radio" name="post_type" value="page">
                                    <span>Страницы</span>
                                </label>

                                <label class="pk-radio">
                                    <input type="radio" name="post_type" value="product">
                                    <span>Товары</span>
                                </label>

                                <label class="pk-radio">
                                    <input type="radio" name="post_type" value="custom_posts">
                                    <span>Кастомный тип записи</span>
                                </label>

                                <div class="form_field__hidden" data-radio="custom_posts">
                                    <label for="post_type_name">Название типа записи. Например: posts, pages, products</label>
                                    <input type="text" id="post_type_name" name="post_type_name" minlength="3" maxlength="30">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <hr>

                <div class="form_section form_section--presets">
                    <h2 class="form_section__title">Предустановки</h2>

                    <div class="form_section__btns">
                        <div class="btn btn--border-bottom form_section__btn" data-settings="default">По умолчанию</div>
                        <div class="btn btn--border-bottom form_section__btn" data-settings="clear">Очистить все поля</div>
                    </div>
                </div>

                <hr>

                <div class="form_section form_section--posts">

                    <h2 class="form_section__title">Поля для типа записи</h2>

                    <div class="form_area">

                        <div class="form_field form_field--half">
                            <label for="post_title">Заголовок</label>
                            <input type="text" id="post_title" name="post_title">
                        </div>

                        <div class="form_field form_field--half">
                            <label for="post_url">Ярлык записи</label>
                            <input type="text" id="post_url" name="post_url">
                        </div>

                        <?php if (false):?>
                        <div class="form_field form_field--full">
                            <label for="post_date">Дата публикации</label>
                            <input type="text" id="post_date" name="post_date" placeholder="гггг-мм-дд чч:мм:сс">
                        </div>
                        <?php endif;?>

                        <div class="form_field form_field--half">
                            <label for="post_excerpt">Краткое описание</label>
                            <textarea id="post_excerpt" name="post_excerpt"></textarea>
                        </div>

                        <div class="form_field form_field--half">
                            <label for="post_content">Полное описание</label>
                            <textarea id="post_content" name="post_content"></textarea>
                        </div>

                        <div class="form_field form_field--full">
                            <label class="pk_checkbox">
                                <input class="accordion__link--single"
                                       value="add_cat"
                                       data-link="category"
                                       type="checkbox"
                                       id="add_category"
                                       name="add_category">
                                <span>Добавить в категорию</span>
                            </label>
                        </div>

                        <div class="form_field form_field--half accordion__panel--single" data-panel="category">
                            <label for="post_cat_name">Категория</label>
                            <input type="text" id="post_cat_name" name="post_cat_name">
                            <div class="form_field__desc">
                                Если категории не существует на вашем сайте то она будет создана автоматически.<br>
                            </div>
                        </div>

                        <div class="form_field form_field--half accordion__panel--single" data-panel="category">
                            <label for="post_cat_url">Ярлык категории</label>
                            <input type="text" id="post_cat_url" name="post_cat_url">
                            <div class="form_field__desc">
                                Оставьте поле пустым и ярлык сформируется транслитом автоматически
                            </div>
                        </div>

                    </div>

                </div>

                <hr>

<!--                <div class="form_field form_field--full">-->
<!--                    <label>-->
<!--                        <input type="text" name="input-2">-->
<!--                    </label>-->
<!--                </div>-->

                <input type="radio" name="site_anticheck" id="site_anticheck" style="display: none !important;" value="true" checked="checked"/>
                <input type="text" name="site_submitted" id="site_submitted" value="" style="display: none !important;"/>

                <div class="form_field form_field--full">
                    <button class="btn btn--border-bottom"><?= __('Создать xml файл')?></button>
                </div>
            </form>
        </div>

        <div class="main_form__xml_content">

        </div>
    </div>
</section>
