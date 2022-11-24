
<section class="main_form section">
    <div class="container">
        <div class="main_form__wrapper">
            <h1 class="main_form__title">Title</h1>
            <div class="main_form__text">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores atque consectetur consequatur consequuntur corporis cum explicabo iure laboriosam magnam minima neque nesciunt non, odit officiis, perspiciatis, reiciendis sit velit veritatis. Alias blanditiis dignissimos id magni, natus numquam sed sit temporibus vero? Beatae eveniet id incidunt numquam quam rem sequi voluptate?
            </div>
            <form id="main_form" action="#" class="main_form__wrapper form">

                <div class="form_section form_section--posts">
                    <div class="form_section__title">Предустановки</div>

                    <div class="form_field field-full">
                        <label for="count_posts">Количествово постов</label>
                        <input type="number" id="count_posts" min="1" max="100" name="count_posts" required>
                    </div>

                    <div class="form_field field-full">
                        <label for="posts">Записи</label>
                        <input type="radio" id="posts" name="post_type" value="posts">

                        <label for="pages">Страницы</label>
                        <input type="radio" id="pages" name="post_type" value="pages">

                        <label for="products">Товары</label>
                        <input type="radio" id="products" name="post_type" value="products">

                        <label for="custom_posts">Кастомный тип записи</label>
                        <input type="radio" id="custom_posts" name="post_type" value="custom_posts">
                    </div>

                    <div class="form_field field-full">
                        <label for="post_type_name">Название типа записи. Например: posts, pages, products</label>
                        <input type="text" id="post_type_name" name="post_type_name">
                    </div>
                </div>

                <hr>

                <div class="form_section form_section--presets">
                    <div class="form_section__title">Предустановки</div>

                    <div class="form_section__btns">
                        <div class="btn form_section__btn">Default</div>
                    </div>
                </div>

                <hr>

                <div class="form_section form_section--presets">
                    <div class="form_section__title">Поля для типа записи</div>

                    <div class="form_field field-full">
                        <label for="post_title">Заголовок</label>
                        <input type="text" id="post_title" name="post_title">
                    </div>

                    <div class="form_field field-full">
                        <label for="post_excerpt">Кртакое описание</label>
                        <textarea id="post_excerpt" name="post_excerpt"></textarea>
                    </div>

                    <div class="form_field field-full">
                        <label for="post_content">Полное описание</label>
                        <textarea id="post_content" name="post_content"></textarea>
                    </div>

                    <div class="form_field field-full">
                        <label for="post_date">Дата публикации</label>
                        <input type="text" id="post_date" name="post_date">
                    </div>

                    <div class="form_field field-full">
                        <label for="post_url">URL записи</label>
                        <input type="text" id="post_url" name="post_url">
                    </div>

                    <div class="form_field field-half">
                        <label for="post_cat">Категория</label>
                        <input type="text" id="post_cat" name="post_cat">
                        <div class="form_field__desc">
                            Если категории не существует на вашем сайте то она будет создана автоматически.<br>
                            Оставьте поле пустым чтобы запись относилась к категории по умолчанию
                        </div>
                    </div>

                    <div class="form_field field-half">
                        <label for="post_cat">Ярлык категории</label>
                        <input type="text" id="post_cat" name="post_cat">
                        <div class="form_field__desc">
                            Оставьте поле пустым и ярлык сформируется транслитом автоматически
                        </div>
                    </div>

                </div>

                <hr>

<!--                <div class="form_field field-full">-->
<!--                    <label>-->
<!--                        <input type="text" name="input-2">-->
<!--                    </label>-->
<!--                </div>-->


                <div class="form_field field-full">
                    <button class="btn"><?= __('Создать xml файл')?></button>
                </div>
            </form>
        </div>

        <div class="main_form__xml_content">

        </div>
    </div>
</section>
