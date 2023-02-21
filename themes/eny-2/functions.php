<?php

/**
 * Eny Media functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Eny_Media
 */

if (!function_exists('eny_media_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function eny_media_setup()
    {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on Eny Media, use a find and replace
         * to change 'eny-media' to the name of your theme in all the template files.
         */
        load_theme_textdomain('eny-media', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(array(
            'menu-1' => esc_html__('Primary', 'eny-media'),
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        // Set up the WordPress core custom background feature.
        add_theme_support('custom-background', apply_filters('eny_media_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )));

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');

        /**
         * Add support for core custom logo.
         *
         * @link https://codex.wordpress.org/Theme_Logo
         */
        add_theme_support('custom-logo', array(
            'height' => 250,
            'width' => 250,
            'flex-width' => true,
            'flex-height' => true,
        ));
    }
endif;
add_action('after_setup_theme', 'eny_media_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function eny_media_content_width()
{
    // This variable is intended to be overruled from themes.
    // Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
    // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
    $GLOBALS['content_width'] = apply_filters('eny_media_content_width', 640);
}
add_action('after_setup_theme', 'eny_media_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function eny_media_widgets_init()
{
    register_sidebar(array(
        'name' => esc_html__('Sidebar', 'eny-media'),
        'id' => 'sidebar-1',
        'description' => esc_html__('Add widgets here.', 'eny-media'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}
add_action('widgets_init', 'eny_media_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function eny_media_scripts()
{
    wp_enqueue_style('style-default', get_stylesheet_uri());
    wp_enqueue_style('style-foundation', get_template_directory_uri() . '/css/foundation/index.css');
    wp_enqueue_style('style-main', get_template_directory_uri() . '/css/main/index.css');

    wp_enqueue_style('font-noto-sans-jp', 'https://fonts.googleapis.com/css?family=Noto+Sans+JP:400,700&display=swap&subset=japanese');

    wp_enqueue_script('icon-fontawesome', 'https://kit.fontawesome.com/b5a5daace0.js', array(), '20190916', false);

    wp_enqueue_script('navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true);

    wp_enqueue_script('skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true);

    wp_enqueue_script('ui-interactions', get_template_directory_uri() . '/js/ui-interactions.js', array(), '20190916', true);

    wp_enqueue_script('convert-affiliate-links.js', get_template_directory_uri() . '/js/convert-affiliate-links.js', array(), '20190919', true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'eny_media_scripts');

function kaiza_filter_rest_endpoints( $endpoints ) {
    /* REST APIで投稿一覧取得を無効にする */
    if ( isset( $endpoints['/wp/v2/posts'] ) ) {
        unset( $endpoints['/wp/v2/posts'] );
    }
    /* REST APIで投稿記事取得（単記事）を無効にする */
    if ( isset( $endpoints['/wp/v2/posts/(?P<id>[d]+)'] ) ) {
        unset( $endpoints['/wp/v2/posts/(?P<id>[d]+)'] );
    }
    /* REST APIでユーザー情報取得を無効にする */
    if ( isset( $endpoints['/wp/v2/users'] ) ) {
        unset( $endpoints['/wp/v2/users'] );
    }
    if ( isset( $endpoints['/wp/v2/users/(?P<id>[d]+)'] ) ) {
        unset( $endpoints['/wp/v2/users/(?P<id>[d]+)'] );
    }
    return $endpoints;
}
add_filter( 'rest_endpoints', 'kaiza_filter_rest_endpoints', 10, 1 );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
    require get_template_directory() . '/inc/jetpack.php';
}

function wordpress_breadcrumbs()
{
    $delimiter = '<span class="delimiter">|</span>';
    $name = 'TOP'; //text for the 'Home' link
    $currentBefore = '<span class="current">';
    $currentAfter = '</span>';
    if (!is_home() && !is_front_page() || is_paged()) {
        echo '<div id="crumbs">';
        global $post;
        $home = get_bloginfo('url');
        echo '<a href="' . $home . '">' . $name . '</a> ' . $delimiter . ' ';
        if (is_category()) {
            global $wp_query;
            $cat_obj = $wp_query->get_queried_object();
            $thisCat = $cat_obj->term_id;
            $thisCat = get_category($thisCat);
            $parentCat = get_category($thisCat->parent);
            if ($thisCat->parent != 0) {
                echo(get_category_parents($parentCat, true, ' ' . $delimiter . ' '));
            }

            echo $currentBefore;
            single_cat_title();
            echo $currentAfter;
        } elseif (is_day()) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
            echo $currentBefore . get_the_time('d') . $currentAfter;
        } elseif (is_month()) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo $currentBefore . get_the_time('F') . $currentAfter;
        } elseif (is_year()) {
            echo $currentBefore . get_the_time('Y') . $currentAfter;
        } elseif (is_single()) {
            $cat = get_the_category();
            $cat = $cat[0];
            echo get_category_parents($cat, true, ' ' . $delimiter . ' ');
            echo $currentBefore;
            the_title();
            echo $currentAfter;
        } elseif (is_page() && !$post->post_parent) {
            echo $currentBefore;
            the_title();
            echo $currentAfter;
        } elseif (is_page() && $post->post_parent) {
            $parent_id = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                $parent_id = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            foreach ($breadcrumbs as $crumb) {
                echo $crumb . ' ' . $delimiter . ' ';
            }
            echo $currentBefore;
            the_title();
            echo $currentAfter;
        } elseif (is_search()) {
            echo $currentBefore . '「' . get_search_query() . '」の検索結果' . $currentAfter;
        } elseif (is_tag()) {
            echo $currentBefore . 'Posts tagged &#39;';
            single_tag_title();
            echo '&#39;' . $currentAfter;
        } elseif (is_author()) {
            global $author;
            $userdata = get_userdata($author);
            echo $currentBefore . 'Articles posted by ' . $userdata->display_name . $currentAfter;
        } elseif (is_404()) {
            echo $currentBefore . 'Error 404' . $currentAfter;
        }
        echo '</div>';
    }
}

// Reduce page numbers for pagination menu
function emm_paginate_loop($start, $max, $page = 0, $range = 5)
{
    $output = "";
    for ($i = $start; $i <= $max; $i++) {
        $output .= ($page === intval($i))
            ? "<span class='emm-page emm-current'>$i</span>"
            : "<a href='" . get_pagenum_link($i) . "' class='emm-page'>$i</a>";
    }
    return $output;
}

// Change width of embed

function crunchify_embed_defaults($embed_size)
{
    $embed_size['width'] = 610;
    $embed_size['height'] = 500;
    return $embed_size;
}
add_filter('embed_defaults', 'crunchify_embed_defaults');

function console_log($output, $with_script_tags = true)
{
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}

// function admin_func() {
//     echo '<link rel="stylesheet" href="'.get_template_directory_uri().'/css/original-loading.css"><script>var modulefile="'.get_bloginfo("template_directory").'/get_affiliatelink.php";</script><script type="text/javascript" src="'.get_bloginfo("template_directory").'/js/original-admin.js"></script>';
// }
// add_action('admin_head', 'admin_func');

// Get image of categories
function get_categories_with_images($post_id, $separator)
{

    //first get all categories of that post
    $post_categories = wp_get_post_categories($post_id);
    $cats = array();

    foreach ($post_categories as $c) {
        $cat = get_category($c);
        $cat_data = get_option("category_$c");

        //and then i just display my category image if it exists
        $cat_image = '';
        if (isset($cat_data['img'])) {
            $cat_image = '<img src="' . $cat_data['img'] . '">';
        }
        $cats[] = $cat_image . '<a href="' . get_category_link($c) . '">' . $cat->name . '</a>';
    }
    return implode($separator, $cats);
}

// Check if mobile device

function isMobileDevice()
{
    $aMobileUA = array(
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile',
    );

    //Return true if Mobile User Agent is detected
    foreach ($aMobileUA as $sMobileKey => $sMobileOS) {
        if (preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])) {
            return true;
        }
    }
    //Otherwise return false..
    return false;
}

// Shorten category names

function truncate_list_cats($cat)
{
    if (isMobileDevice() == false) :
        $limit = 14; // do not make this less than 15
    else :
        $limit = 1000;
    endif;
    if (strlen($cat) > $limit) {
        $cat = mb_strimwidth($cat, 0, $limit) . '...';
    }

    return $cat;
}

add_filter('list_cats', 'truncate_list_cats');

// Enable featured posts

function sm_custom_meta()
{
    add_meta_box('sm_meta', __('Featured Posts', 'sm-textdomain'), 'sm_meta_callback', 'post');
}

function sm_meta_callback($post)
{
    $featured = get_post_meta($post->ID); ?>
<p>
  <div class="sm-row-content">
    <label for="meta-checkbox">
      <input type="checkbox" name="meta-checkbox" id="meta-checkbox" value="yes" <?php if (isset($featured['meta-checkbox'])) {
        checked($featured['meta-checkbox'][0], 'yes');
    } ?> />
      <?php _e('Featured this post', 'sm-textdomain') ?>
    </label>

  </div>
</p>
<?php
}
add_action('add_meta_boxes', 'sm_custom_meta');

function sm_meta_save($post_id)
{

    // Checks save status
    $is_autosave = wp_is_post_autosave($post_id);
    $is_revision = wp_is_post_revision($post_id);
    $is_valid_nonce = (isset($_POST['sm_nonce']) && wp_verify_nonce($_POST['sm_nonce'], basename(__FILE__))) ? 'true' : 'false';

    // Exits script depending on save status
    if ($is_autosave || $is_revision || !$is_valid_nonce) {
        return;
    }

    // Checks for input and saves
    if (isset($_POST['meta-checkbox'])) {
        update_post_meta($post_id, 'meta-checkbox', 'yes');
    } else {
        update_post_meta($post_id, 'meta-checkbox', '');
    }
}
add_action('save_post', 'sm_meta_save');

// Get thumbnail of post for popular articles

function get_thumbnail_src($id, $size)
{
    $thumbnail_id = get_post_thumbnail_id($id);
    $src_info = wp_get_attachment_image_src($thumbnail_id, $size);
    $src = $src_info[0];
    return $src;
}
// Don't compress uploaded images
add_filter('jpeg_quality', function ($arg) {
    return 100;
});

// Add fields to user information
function modify_user_contact_methods($user_contact)
{

    // Add user contact methods
    $user_contact['position'] = __('Position');
    $user_contact['twitter'] = __('Twitter Username');
    $user_contact['facebook'] = __('Facebook Username');
    $user_contact['instagram'] = __('Instagram Username');
    $user_contact['blog'] = __('Blog URL');

    return $user_contact;
}
add_filter('user_contactmethods', 'modify_user_contact_methods');
remove_filter('template_redirect', 'redirect_canonical'); // redirect補正をオフにする（2021.09.22 e-ny移管対応）

require_once locate_template('function-parts/rewrite_rule.php', true);
require_once locate_template('function-parts/term_lib.php', true);
require_once locate_template('function-parts/short_code.php', true);
require_once locate_template('function-parts/control_main_query.php', true);
require_once locate_template('function-parts/affiliate.php', true);
// require_once locate_template('function-parts/control_seo_tag.php', true);

function content_excerpt($content, $num)
{
    $content = preg_replace('#\[[^\]]+\]#', '', $content);
    $content = strip_tags($content);
    $content = str_replace(array("\r\n", "\n", "\r"), "", $content);
    $content = mb_strcut($content, 0, $num, 'UTF-8');
    return $content;
}

function get_current_link()
{
    return (is_ssl() ? 'https' : 'http') . '://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
}

// Custom Blocks by Lazy Blocks
if (function_exists('lazyblocks')) :

    lazyblocks()->add_block(array(
        'id' => 91307,
        'title' => 'Important Comment',
        'icon' => 'dashicons dashicons-info',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/important-comment',
        'description' => '',
        'category' => 'common',
        'category_label' => 'common',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'html' => false,
            'multiple' => true,
            'inserter' => true,
        ),
        'controls' => array(
            'control_a28b584e6f' => array(
                'label' => 'ラベル',
                'name' => 'label',
                'type' => 'text',
                'child_of' => '',
                'default' => '',
                'placeholder' => '',
                'help' => '',
                'placement' => 'content',
                'hide_if_not_selected' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'required' => 'false',
                'choices' => array(
                ),
                'checked' => 'false',
                'allow_null' => 'false',
                'multiple' => 'false',
                'allowed_mime_types' => array(
                ),
                'alpha' => 'false',
                'min' => '',
                'max' => '',
                'step' => '',
                'date_time_picker' => 'date_time',
                'multiline' => 'false',
            ),
            'control_721bd84cd0' => array(
                'label' => '重要文',
                'name' => 'important-comment',
                'type' => 'rich_text',
                'child_of' => '',
                'default' => '',
                'placeholder' => '',
                'help' => '記事の内容で重要なことを入力します。',
                'placement' => 'content',
                'hide_if_not_selected' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'required' => 'true',
                'choices' => array(
                ),
                'checked' => 'false',
                'allow_null' => 'false',
                'multiple' => 'false',
                'allowed_mime_types' => array(
                ),
                'alpha' => 'false',
                'min' => '',
                'max' => '',
                'step' => '',
                'date_time_picker' => 'date_time',
                'multiline' => 'false',
            ),
        ),
        'code' => array(
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<div class="bl-important-comment">
        <div class="bl-important-comment__title">
            <i class="fas fa-lightbulb"></i>
            <em>{{label}}</em>
        </div>
        <div class="bl-important-comment__text">
            {{{important-comment}}}
        </div>
    </div>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => false,
        ),
        'condition' => array(
            0 => 'post',
        ),
    ));

    lazyblocks()->add_block(array(
        'id' => 91304,
        'title' => 'Alert Comment',
        'icon' => 'dashicons dashicons-welcome-comments',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/alert-comment',
        'description' => '',
        'category' => 'common',
        'category_label' => 'common',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'html' => false,
            'multiple' => true,
            'inserter' => true,
        ),
        'controls' => array(
            'control_40baf44da5' => array(
                'label' => '注意文',
                'name' => 'alert-comment',
                'type' => 'rich_text',
                'child_of' => '',
                'default' => '',
                'placeholder' => '',
                'help' => 'ユーザーに注意を促す文章を入力します。',
                'placement' => 'content',
                'hide_if_not_selected' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'required' => 'true',
                'choices' => array(
                ),
                'checked' => 'false',
                'allow_null' => 'false',
                'multiple' => 'false',
                'allowed_mime_types' => array(
                ),
                'alpha' => 'false',
                'min' => '',
                'max' => '',
                'step' => '',
                'date_time_picker' => 'date_time',
                'multiline' => 'false',
            ),
        ),
        'code' => array(
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<div class="bl-alert-comment">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            {{{alert-comment}}}
        </div>
    </div>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => false,
        ),
        'condition' => array(
            0 => 'post',
        ),
    ));

    lazyblocks()->add_block(array(
        'id' => 91288,
        'title' => 'Writer Comment',
        'icon' => 'dashicons dashicons-admin-comments',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/writer-comment',
        'description' => '',
        'category' => 'common',
        'category_label' => 'common',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'html' => false,
            'multiple' => true,
            'inserter' => true,
        ),
        'controls' => array(
            'control_196b004b7e' => array(
                'label' => 'コメント',
                'name' => 'comment',
                'type' => 'rich_text',
                'child_of' => '',
                'default' => '',
                'placeholder' => 'test desu ne',
                'help' => '吹き出しの中のコメントを入力します。',
                'placement' => 'content',
                'hide_if_not_selected' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'required' => 'true',
                'choices' => array(
                ),
                'checked' => 'false',
                'allow_null' => 'false',
                'multiple' => 'false',
                'allowed_mime_types' => array(
                ),
                'alpha' => 'false',
                'min' => '',
                'max' => '',
                'step' => '',
                'date_time_picker' => 'date_time',
                'multiline' => 'false',
            ),
            'control_44bbf14899' => array(
                'label' => 'アイコン',
                'name' => 'icon',
                'type' => 'image',
                'child_of' => '',
                'default' => '',
                'placeholder' => '',
                'help' => '通常はこの記事のライター画像が表示されますが、設定すると設定した画像からコメントが出るようになります。',
                'placement' => 'content',
                'hide_if_not_selected' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'required' => 'false',
                'choices' => array(
                ),
                'checked' => 'false',
                'allow_null' => 'false',
                'multiple' => 'false',
                'allowed_mime_types' => array(
                ),
                'alpha' => 'false',
                'min' => '',
                'max' => '',
                'step' => '',
                'date_time_picker' => 'date_time',
                'multiline' => 'false',
            ),
        ),
        'code' => array(
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<div class="bl-writer-comment">
        <img class="js-writer-comment-avatar" src="{{icon.url}}" alt="{{icon.alt}}">
        <div>
            {{{comment}}}
        </div>
    </div>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => false,
        ),
        'condition' => array(
            0 => 'post',
        ),
    ));

endif;
add_filter( 'wp_sitemaps_enabled', '__return_false' );
