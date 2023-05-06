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

        // Проверка поля post_type_name
        if ( $options['post_type'] == 'custom_posts') {
            if (empty( $options['post_type_name'])) {
                $err_message['post_type_name'] = 'Введите название типа записи';
            } elseif(!preg_match('/^[a-z0-9-_]+$/i', $options['post_type_name'])) {
                $err_message['post_type_name'] = 'Разрешены только латинские буквы, - и _';
            }
        }

        // Проверка поля заголовка на заполненность
        if ( empty( $options['post_title'] ) ) {
            $err_message['post_title'] = 'Пожалуйста, введите заголовок записи';
        }

        // Проверка формата даты на валидность
//        if ( !empty($options['post_date']) && !$this->validDate($options['post_date'])) {
//            $err_message['post_date'] = 'Неверный формат даты';
//        }
        //todo: сделать валидацию даты

        // Проверка полей категории на заполненность
        if ( $options['add_category'] && ( empty($options['post_cat_name']) || empty($options['post_cat_url']) ) ) {
            $err_message['category'] = 'Поля для категории должны быть заполнены';
        }

        // Проверяем массив ошибок, если не пустой, то возвращаем ошибку
        if ( $err_message ) {
            wp_send_json_error(
                array(
                    'err_message' => $err_message
                )
            );
        }
    }

    public function validDate($d) {
        $format = 'Y-m-d H:i:s';
        $date = \DateTime::createFromFormat($format, $d);
        $date->format('Y-m-d H:i:s');

        return $date && $date->format('Y-m-d H:i:s') == $d;
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

        if (!empty($options['post_date']) && $options['post_date'] !== '____-__-__ __:__:__') {
            $date = $options['post_date'];
            $pub_date = date('r', strtotime($date.' -3 hours'));
            $post_date = date('Y-m-d H:i:s', strtotime($date));
            $post_date_gmt = date('Y-m-d H:i:s', strtotime($date.' -3 hours'));
        } else {
            $time_offset = time() + 3600 * 3; // +03:00
            $pub_date = date('r');
            $post_date = date('Y-m-d H:i:s', $time_offset);
            $post_date_gmt = date('Y-m-d H:i:s');
        }

        if (!empty($options['post_title'])) {
            $post_title = $options['post_title'];
        } else {
            $post_title = 'Created Title';
        }

        if (!empty($options['post_url'])) {
            $post_url = $options['post_url'];
        } else {
            $post_url = $this->translit_sef($post_title);
        }

        if (!empty($options['post_excerpt'])) {
            $post_excerpt = $options['post_excerpt'];
        } else {
            $post_excerpt = '';
        }

        if (!empty($options['post_content'])) {
            $post_content = $options['post_content'];
        } else {
            $post_content = '';
        }

        if (!empty($options['post_type'])) {
            if ($options['post_type'] !== 'custom_posts') {
                $post_type = $options['post_type'];
            } else {
                $post_type = $options['post_type_name'];
            }
        } else {
            $post_type = 'post';
        }


        for ($i = 1; $i <= $count_posts; $i++) {
            $items[$i] = array(
                'title' => '<![CDATA['.$post_title.' '.$i.']]>',
//                'link' => '#',
                'pubDate' => $pub_date,
//                'dc:creator' => '<![CDATA[admin]]>',
//                'guid' => '#',
//                'description' => 'Desc',
                'content:encoded' => '<![CDATA['.$post_content.']]>',
                'excerpt:encoded' => '<![CDATA['.$post_excerpt.']]>',
//                'wp:post_id' => $i,
                'wp:post_date' => '<![CDATA['.$post_date.']]>',
                'wp:post_date_gmt' => '<![CDATA['.$post_date_gmt.']]>',
                'wp:post_modified' => '<![CDATA['.$post_date.']]>',
                'wp:post_modified_gmt' => '<![CDATA['.$post_date_gmt.']]>',
                'wp:comment_status' => '<![CDATA[closed]]>',
                'wp:ping_status' => '<![CDATA[closed]]>',
                'wp:post_name' => '<![CDATA['.$post_url.' '.$i.']]>',
                'wp:status' => '<![CDATA[publish]]>',
                'wp:post_parent' => '0',
                'wp:menu_order' => '0',
                'wp:post_type' => '<![CDATA['.$post_type.']]>',
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

            if($options['add_category']){
                $category = $dom->createElement('category', '<![CDATA['.$options['post_cat_name'].']]>');
                $category->setAttribute("domain", 'category');
                $category->setAttribute("nicename", $options['post_cat_url']);
                $dom_item->appendChild($category);
            }

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

    public function translit_sef($value)
    {
        $converter = array(
            'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
            'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
            'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
            'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
            'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
            'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
            'э' => 'e',    'ю' => 'yu',   'я' => 'ya',
        );

        $value = mb_strtolower($value);
        $value = strtr($value, $converter);
        $value = mb_ereg_replace('[^-0-9a-z]', '-', $value);
        $value = mb_ereg_replace('[-]+', '-', $value);
        $value = trim($value, '-');

        return $value;
    }
}