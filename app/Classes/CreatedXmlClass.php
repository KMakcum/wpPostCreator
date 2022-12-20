<?php

namespace App\Classes;

class CreatedXmlClass
{

    /**
     * @var object
     */
    public $dom;


    public function __construct()
    {
        add_action('wp_ajax_get_xml_file', array($this, 'get_xml_file'));
        add_action('wp_ajax_nopriv_get_xml_file', array($this, 'get_xml_file'));
        add_action('after_setup_theme', [$this, 'ajaxScripts']);
    }

    public function ajaxScripts()
    {
        wp_enqueue_script( 'formHandler', get_template_directory_uri() . '/app/assets/js/form-handler.js', ['jquery'], '', true );

        wp_localize_script('formHandler', 'form_data',
            array(
                'ajax_url' => site_url() . '/wp-admin/admin-ajax.php',
                'nonce' => wp_create_nonce( 'form-nonce' ),
            )
        );
    }

    protected function validate_fields ($nonce, $options) {
        // Массив ошибок
        $err_message = array();

        // Проверяем nonce. Если проверкане прошла, то блокируем отправку
        if ( ! wp_verify_nonce( $nonce, 'form-nonce' ) ) {
            wp_die( 'Данные отправлены с стороннего адреса' );
        }

        // Проверяем на спам. Если скрытое поле заполнено или снят чек, то блокируем отправку
        if ( false === $options['site_anticheck'] || ! empty( $options['site_submitted'] ) ) {
            wp_die( 'Спам' );
        }

        // Проверка полей с проблемами
        if ( (!empty( $options['count_posts'] )) ) {
            if (!is_numeric($options['count_posts'])) {
                $err_message['count_posts'] = 'Введите числовое значение количества постов';
            } elseif ($options['count_posts'] < 1 || $options['count_posts'] > 100) {
                $err_message['count_posts'] = 'Введите число в диапозоне от 1 до 100';
            }
        } else {
            $err_message['count_posts'] = 'Введите количество постов';
        }

        // Проверка поля заголовка на заполненность
        if ( empty( $options['post_title'] ) ) {
            $err_message['post_title'] = 'Пожалуйста, введите заголовок записи';
        }

        // Проверка поля имени на заполненность
        if ( !empty( $options['post_date'] ) && $this->validDate('25/05/2017')) {
            $err_message['post_date'] = 'Неверный формат даты';
        }


        // Проверяем массив ошибок, если не пустой, то возвращаем ошибку
        if ( $err_message ) {
            wp_send_json_error(
                array(
                    'err_message' => $err_message
                )
            );
        }

        wp_send_json_error(
            array(
                'message' => $options
            )
        );
    }

    public function validDate($date) {
        $d = \DateTime::createFromFormat('YYYY-MM-DD HH:mm:ss', $date);
        return $d && $d->format('YYYY-MM-DD HH:mm:ss') === $date;
    }

    public function get_xml_file(){
        mb_parse_str($_POST['form'], $options);

        $this->validate_fields($_POST['nonce'], $options);

        $response = $this->save_and_download_xml($options);

        if ($response['status'] == 'success') {
            wp_send_json_success(
                array(
                    'file_link' => $response['file_link'],
                    'file_name' => $response['file_name'],
                    'message' => $response['message'],
                ),
                200);
        } else {
            wp_send_json_error(
                array(
                    'message' => $response['message']
                )
            );
        }

        die();
    }

    public function default_posts($options){
        $items = [];
        $count_posts = intval($options['count_posts']);

        if (!empty($count_posts)){
            if ($count_posts<1) {
                $count_posts = 1;
            } elseif ($count_posts>100) {
                $count_posts = 100;
            }
        } else {
            $count_posts = 1;
        }

        $pub_date = date('r');
        $post_date = date('Y-m-d H:i:s');

        for ($i = 1; $i <= $count_posts; $i++) {
            $items[$i] = array(
                'title' => '<![CDATA[Тестовый заголовок '.$i.']]>',
//                'link' => '#',
                'pubDate' => $pub_date,
//                'dc:creator' => '<![CDATA[admin]]>',
//                'guid' => '#',
//                'description' => 'Desc',
                'content:encoded' => '<![CDATA[Полное описание]]>',
                'excerpt:encoded' => '<![CDATA[Краткое описание]]>',
//                'wp:post_id' => $i,
                'wp:post_date' => '<![CDATA['.$post_date.']]>',
                'wp:post_date_gmt' => '<![CDATA['.$post_date.']]>',
                'wp:post_modified' => '<![CDATA['.$post_date.']]>',
                'wp:post_modified_gmt' => '<![CDATA['.$post_date.']]>',
                'wp:comment_status' => '<![CDATA[closed]]>',
                'wp:ping_status' => '<![CDATA[closed]]>',
                'wp:post_name' => '<![CDATA[testovyj_zagolovok]]>',
                'wp:status' => '<![CDATA[publish]]>',
                'wp:post_parent' => '0',
                'wp:menu_order' => '0',
                'wp:post_type' => '<![CDATA[post]]>',
                'wp:post_password' => '<![CDATA[]]>',
                'wp:is_sticky' => '0',
            );
        }

        return $items;
    }

    public function create_xml($options) {
        $count_items = intval($options['count_posts']);
        $post_type   = $options['post_type'];


//        $site_title = 'postcreator';
//        $site_link = 'http://postcreator.loc/';
//        $site_desc = 'Desc. Ещё один сайт на WordPress';
//        $site_pubDate = date('r');
//        $site_lang = 'ru-RU';
        $wp__wxr_version = '1.2';
//        $wp__base_site_url = 'http://postcreator.loc/';
//        $wp__base_blog_url = 'http://postcreator.loc/';
//$wp__author = '';
        $wp__author_id = '1';
        $wp__author_login = '<![CDATA[admin]]>';
        $wp__author_email = '<![CDATA[test@gmail.com]]>';
        $wp__author_display_name = '<![CDATA[admin]]>';
        $wp__author_first_name = '<![CDATA[]]>';
        $wp__author_last_name = '<![CDATA[]]>';

        $dom = new \domDocument("1.0", "utf-8"); // Создаём XML-документ версии 1.0 с кодировкой utf-8
        $rss = $dom->createElement("rss"); // Создаём rss элемент
        $channel = $dom->createElement("channel"); // Создаём channel элемент
        $rss->setAttribute("version", '2.0'); // Устанавливаем атрибут "version" у узла "rss"
        $rss->setAttribute("xmlns:excerpt", 'http://wordpress.org/export/1.2/excerpt/');
        $rss->setAttribute("xmlns:content", 'http://purl.org/rss/1.0/modules/content/');
        $rss->setAttribute("xmlns:wfw", 'http://wellformedweb.org/CommentAPI/');
        $rss->setAttribute("xmlns:dc", 'http://purl.org/dc/elements/1.1/');
        $rss->setAttribute("xmlns:wp", 'http://wordpress.org/export/1.2/');
        $dom->appendChild($rss);
        $rss->appendChild($channel);

//Created head
//        $title = $dom->createElement("title", $site_title);
//        $link = $dom->createElement("link", $site_link);
//        $description = $dom->createElement("description", $site_desc);
//        $pubDate = $dom->createElement("pubDate", $site_pubDate);
//        $language = $dom->createElement("language", $site_lang);
        $wxr_version = $dom->createElement("wp:wxr_version", $wp__wxr_version);
//        $base_site_url = $dom->createElement("wp:base_site_url", $wp__base_site_url);
//        $base_blog_url = $dom->createElement("wp:base_blog_url", $wp__base_blog_url);

//        $channel->appendChild($title);
//        $channel->appendChild($link);
//        $channel->appendChild($description);
//        $channel->appendChild($pubDate);
//        $channel->appendChild($language);
        $channel->appendChild($wxr_version);
//        $channel->appendChild($base_site_url);
//        $channel->appendChild($base_blog_url);
//Created head end

//Created author
        $author = $dom->createElement("wp:author");
        $author_id = $dom->createElement("wp:author_id", $wp__author_id);
        $author_login = $dom->createElement("wp:author_login", $wp__author_login);
        $author_email = $dom->createElement("wp:author_email", $wp__author_email);
        $author_display_name = $dom->createElement("wp:author_display_name", $wp__author_display_name);
        $author_first_name = $dom->createElement("wp:author_first_name", $wp__author_first_name);
        $author_last_name = $dom->createElement("wp:author_last_name", $wp__author_last_name);

        $author->appendChild($author_id);
        $author->appendChild($author_login);
        $author->appendChild($author_email);
        $author->appendChild($author_display_name);
        $author->appendChild($author_first_name);
        $author->appendChild($author_last_name);

        $channel->appendChild($author);
//Created author end

        $items = $this->default_posts($options);

        foreach ($items as $item) {
            $dom_item = $dom->createElement("item");

            foreach ($item as $tag_label => $tag_value) {
                $item_field = $dom->createElement($tag_label, $tag_value);
                $dom_item->appendChild($item_field);
            }

            $category = $dom->createElement('category', '<![CDATA[Категория 1]]>');
            $category->setAttribute("domain", 'category');
            $category->setAttribute("nicename", 'kategoriya-1');
            $dom_item->appendChild($category);

            $channel->appendChild($dom_item);
        }

        $dom->formatOutput = true;

        return $dom;
    }

    public function get_xml($options){
        $dom = $this->create_xml($options);

        return $dom->saveXML();
    }

    public function save_and_download_xml($options) {
        $server_dir = $_SERVER['DOCUMENT_ROOT'];
        $file_dir = '/wp-content/uploads/CreatedXml/';
        $file_name = 'posts.xml';
        $dir_for_save = $server_dir.$file_dir.$file_name;
        $dir_for_load = $file_dir.$file_name;

        if (!is_dir($file_dir)) {
            mkdir($file_dir, 0777, true);
        }

        // Пишем содержимое в файл
        $dom = $this->get_xml($options);
        $bytesCount = file_put_contents($dir_for_save, htmlspecialchars_decode($dom), LOCK_EX);
        if ($bytesCount === 0 || $bytesCount === false) {
            $response = array(
                'status' => 'err',
                'message' => 'При сохранении данных произошла ошибка!',
            );

            return $response;
        } else {
            $response = array(
                'status' => 'success',
                'message' => 'Сохранение прошло успешно',
                'file_link' => $dir_for_load,
                'file_name' => $file_name,
            );

            return $response;
        }

//        $dom->save("users.xml"); // Сохраняем полученный XML-документ в файл
    }
}