<?php
/*----------------------------------
アイキャッチ画像を有効化
----------------------------------*/
add_theme_support('post-thumbnails');

/*----------------------------------
アイキャッチ画像URL取得
----------------------------------*/
function get_thumbnail_src($id, $size)
{
    $thumbnail_id = get_post_thumbnail_id($id);
    $src_info = wp_get_attachment_image_src($thumbnail_id, $size);
    $src = $src_info[0];
    return $src;
}
/*----------------------------------
プロフィール情報html有効化
----------------------------------*/
remove_filter('pre_user_description', 'wp_filter_kses');
add_filter('pre_user_description', 'wp_filter_post_kses');

/*----------------------------------
カテゴリー、タグの説明文を呼び出すときに<p>を削除する。
----------------------------------*/
remove_filter('term_description', 'wpautop');

/*----------------------------------
投稿一覧で予約投稿した記事の時間を表示する
----------------------------------*/
function add_scheduled_posts_date_column_time($h_time, $post)
{
    $h_time .= '<br />' . get_post_time('H:i', false, $post);
    return $h_time;
}
add_filter('post_date_column_time', 'add_scheduled_posts_date_column_time', 10, 2);

/*----------------------------------
検索時は投稿からのみ検索
----------------------------------*/
function custom_search($search, $wp_query)
{
    if (!$wp_query->is_search) {
        return;
    }
    //投稿記事のみ検索
    $search .= " AND post_type = 'post'";
    return $search;
}
add_filter('posts_search', 'custom_search', 10, 2);

/*----------------------------------
パンくずリスト出力
----------------------------------*/
function breadcrumb()
{
    global $post;
    global $wpdb;
    $str = '';
    if (!is_home() && !is_admin()) {
        $str .= '<nav id="breadcrumb" aria-label="breadcrumb">';
        $str .= '<ul>';
        $str .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . home_url() . '" itemprop="url"><span itemprop="title"><i class="fa fa-home"></i> トップ</span></a></li>';
        $str .= '<li><i class="fa fa-angle-right"></i></li>';

        if (is_search()) {
            $str .= '<li>「' . get_search_query() . '」の検索結果</li>';
        } elseif (is_category()) {
            $cat = get_queried_object();
            if ($cat->parent != 0) {
                $ancestors = array_reverse(get_ancestors($cat->cat_ID, 'category'));
                foreach ($ancestors as $ancestor) {
                    $str .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><i class="fa fa-folder-open"></i> <a href="' . get_category_link($ancestor) . '" itemprop="url"><span itemprop="title">' . get_cat_name($ancestor) . '</span></a></li>';
                    $str .= '<li><i class="fa fa-angle-right"></i></li>';
                }
            }
            $str .= '<li><i class="fa fa-folder-open"></i> ' . $cat->name . '</li>';
        } elseif (is_page()) {
            if ($post->post_parent != 0) {
                $ancestors = array_reverse(get_post_ancestors($post->ID));
                foreach ($ancestors as $ancestor) {
                    $str .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . get_permalink($ancestor) . '" itemprop="url"><span itemprop="title">' . get_the_title($ancestor) . '</span></a></li>';
                    $str .= '<li><i class="fa fa-angle-right"></i></li>';
                }
            } else {
                $str .= '<li>' . $post->post_title . '</li>';
            }
        } elseif (is_attachment()) {
            if ($post->post_parent != 0) {
                $str .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . get_permalink($post->post_parent) . '" itemprop="url"><span itemprop="title">' . get_the_title($post->post_parent) . '</span></a></li>';
                $str .= '<li><i class="fa fa-angle-right"></i></li>';
            }
            $str .= '<li>' . $post->post_title . '</li>';
        } elseif (is_single()) {
            $categories = get_the_category($post->ID);
            $cat = $categories[0];
            foreach ($categories as $cat_key => $cat_value) {
                if ($cat_value->name == '症状') {
                    $cat = $categories[$cat_key];
                    break;
                }
            }
            if ($cat->parent != 0) {
                $ancestors = array_reverse(get_ancestors($cat->cat_ID, 'category'));
                foreach ($ancestors as $ancestor) {
                    $str .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . get_category_link($ancestor) . '" itemprop="url"><span itemprop="title"><i class="fa fa-folder-open"></i>' . get_cat_name($ancestor) . '</span></a></li>';
                    $str .= '<li><i class="fa fa-angle-right"></i></li>';
                }
            }
            $str .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="' . get_category_link($cat->term_id) . '"><span itemprop="title"><i class="fa fa-folder-open"></i> ' . $cat->cat_name . '</span></a></li>';
            $str .= '<li><i class="fa fa-angle-right"></i></li>';
            $str .= '<li>' . wp_title('', false) . '</li>';
        } else {
            $str .= '<li>' . wp_title('', false) . '</li>';
        }
        $str .= '</ul>';
        $str .= '</nav>';
    }
    echo $str;
}

/*----------------------------------
ページナビゲーション
----------------------------------*/
function yutopro_pagenavi($parent = "")
{
    $count = 2;

    global $wp_query;

    if (is_category() || is_tag()) {
        $total_posts = get_queried_object()->count;
    } else {
        $total_posts = wp_count_posts()->publish;
    }
    $total_page = ceil($total_posts / get_option('posts_per_page'));
    if (!$total_page) {
        $total_page = 1;
    }

    if (!$total_page) {
        $total_page = 1;
    }

    global $paged;
    if ($paged) {
        $nowpage = $paged;
    } else {
        $nowpage = 1;
    }

    if ($total_page > 1) {
        echo "<div class=\"yutopro_pagenavi\">\n";
        $start = $nowpage - $count;
        if ($start < 1) {
            $start = 1;
        }
        $end = $start + $count + $count;
        if ($end > $total_page) {
            $end = $total_page;
        }
        if ($start > 1) {
            $prev = $nowpage - 1;
            echo '<a href="' . home_url() . '"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>';
            echo '<a href="' . get_pagenum_link($prev) . '"><i class="fa fa-angle-left" aria-hidden="true"></i></a>';
        }
        for ($i = $start; $i <= $end; $i++) {
            if ($i == $nowpage) {
                echo '<span class="current">' . $i . '</span>';
            } else {
                echo '<a href="' . get_pagenum_link($i) . '">' . $i . '</a>';
            }
        }
        if ($end < $total_page) {
            $next = $nowpage + 1;
            echo '<a href="' . get_pagenum_link($next) . '" class="next"><i class="fa fa-angle-right" aria-hidden="true"></i>
		</a>';
            echo '<a href="' . get_pagenum_link($total_page) . '"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>';
        }
        echo "</div><!-- pagination -->";
    }
}

/*----------------------------------
投稿編集画面のデザイン調整CSS
----------------------------------*/
function my_admin_style()
{
    ?>
    <style>
        .aioseop textarea {
            height: 200px;
        }

        #wpwrap {
            visibility: hidden;
        }
    </style>
<?php
}
add_action('admin_head-post.php', 'my_admin_style');
add_action('admin_head-post-new.php', 'my_admin_style');

/*----------------------------------
投稿編集画面に処理の追加
----------------------------------*/
function add_original_post()
{
    global $post; ?>
    <script>
        (function($) {

            // タイトルに文字数表示欄を出力
            $("div#titlewrap").append($('<div class="title_count">').css({
                padding: "0px 10px",
            }));
            // タイトルの文字数を表示する関数
            var countCurator = function() {
                var thisValueLength = "文字数: <span class='word-count'>" + $('input#title').val().length + "</span>";
                $('.title_count').html(thisValueLength);
            }
            // 公開の確認
            $("#publish").click(function() {
                if ($("#publish").val() == "公開") {
                    return confirm("これは「公開」です。すぐに公開されてもよろしいですか？");
                }
            });

            // All in one SEOのDescriptionのタイトル文字数変更(60文字→28文字)
            if ($('input[name="aiosp_title"]').size() > 0) {
                $('#aiosp_title_wrapper div.aioseop_option_div').html($('#aiosp_title_wrapper div.aioseop_option_div').html().replace("60文字", "28文字"));
            }
            // All in one SEOのDescriptionのディスクリプション文字数変更(160文字→120文字)
            if ($('textarea[name=aiosp_description]').size() > 0) {
                $('textarea[name=aiosp_description]').attr("cols", "60");
                $('#aiosp_description_wrapper div.aioseop_option_div').html($('#aiosp_description_wrapper div.aioseop_option_div').html().replace("160文字", "120文字"));
            }
            // enterキーを無効
            $(function() {
                $("input").keydown(function(e) {
                    if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
                        return false;
                    } else {
                        return true;
                    }
                });
            });
            $(window).load(function() {
                // 編集画面表示
                $("#wpwrap").css("visibility", "visible");
                // タイトルに文字数をカウントする関数をbind
                $('input#title').bind('keyup', function() {
                    countCurator();
                });
                // 初回の文字数カウント
                countCurator();
            });
        })(jQuery);
    </script>
<?php
}
add_action('admin_footer-post.php', 'add_original_post');
add_action('admin_footer-post-new.php', 'add_original_post');

/*----------------------------------
ダッシュボードでいらないのを非表示
----------------------------------*/
function example_remove_dashboard_widgets()
{
    global $wp_meta_boxes;
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['semperplugins-rss-feed']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
}
add_action('wp_dashboard_setup', 'example_remove_dashboard_widgets');

/*----------------------------------
投稿編集画面でいらないのを非表示
----------------------------------*/
function my_remove_meta_boxes()
{
    remove_meta_box('commentstatusdiv', 'post', 'normal'); // ディスカッション
    remove_meta_box('commentsdiv', 'post', 'normal'); // コメント
    remove_meta_box('formatdiv', 'post', 'normal'); // フォーマット
    remove_meta_box('pageparentdiv', 'post', 'normal'); // ページ属性
    remove_meta_box('postcustom', 'post', 'normal'); // カスタムフィールド
    remove_meta_box('trackbacksdiv', 'post', 'normal'); // トラックバック
}
add_action('admin_menu', 'my_remove_meta_boxes');


/*----------------------------------
ログインURLを変更する
----------------------------------*/
define('ANYWHERE_LOGIN_PAGE', 'admin/eny.php');
add_action('login_init', 'anywhere_login_init');
add_filter('site_url', 'anywhere_login_site_url', 10, 4);
add_filter('wp_redirect', 'anywhere_login_wp_redirect', 10, 2);
if (!function_exists('anywhere_login_init')) {
    function anywhere_login_init()
    {
        if (!defined('ANYWHERE_LOGIN') || sha1('yutopro') != ANYWHERE_LOGIN) {
            status_header(403);
            exit;
        }
    }
}
if (!function_exists('anywhere_login_site_url')) {
    function anywhere_login_site_url($url, $path, $orig_scheme, $blog_id)
    {
        if (($path == 'wp-login.php' || preg_match('/wp-login\.php\?action=\w+/', $path)) && (is_user_logged_in() || strpos($_SERVER['REQUEST_URI'], ANYWHERE_LOGIN_PAGE) !== false)
        ) {
            $url = str_replace('wp-login.php', ANYWHERE_LOGIN_PAGE, $url);
        }
        return $url;
    }
}
if (!function_exists('anywhere_login_wp_redirect')) {
    function anywhere_login_wp_redirect($location, $status)
    {
        if (strpos($_SERVER['REQUEST_URI'], ANYWHERE_LOGIN_PAGE) !== false) {
            $location = str_replace('wp-login.php', ANYWHERE_LOGIN_PAGE, $location);
        }
        return $location;
    }
}

/*----------------------------------
YUTOPROスマホ判断
----------------------------------*/
function yutopro_mobile()
{
    $ua = $_SERVER['HTTP_USER_AGENT'];
    if ((strpos($ua, 'iPhone') !== false)
        || (strpos($ua, 'Android') !== false) && (strpos($ua, 'Mobile') !== false)
        || (strpos($ua, 'Windows') !== false) && (strpos($ua, 'Phone') !== false)
        || (strpos($ua, 'BB10') !== false)
        || (strpos($ua, 'Opera Mini') !== false)
        || (strpos($ua, 'Opera Mobi') !== false)
    ) {
        return true;
    } else {
        return false;
    }
}


// タグ非表示
function hide_taxonomy_from_menu()
{
    global $wp_taxonomies;

    if (!empty($wp_taxonomies['post_tag']->object_type)) {
        foreach ($wp_taxonomies['post_tag']->object_type as $oti => $object_type) {
            if ($object_type == 'post') {
                unset($wp_taxonomies['post_tag']->object_type[$oti]);
            }
        }
    }
    return true;
}
add_action('init', 'hide_taxonomy_from_menu');


// short code
// [sitename]
function shortcode_email()
{
    return get_bloginfo('admin_email');
}
add_shortcode('email', 'shortcode_email');
// [sitename]
function shortcode_sitename()
{
    return get_bloginfo('name');
}
add_shortcode('sitename', 'shortcode_sitename');
// [description]
function shortcode_description()
{
    return get_bloginfo('description');
}
add_shortcode('description', 'shortcode_description');
// [url]
function shortcode_url()
{
    return get_bloginfo('url');
}
add_shortcode('url', 'shortcode_url');
// [template_url]
function shortcode_templateurl()
{
    return get_bloginfo('template_url');
}
add_shortcode('template_url', 'shortcode_templateurl');
// [domain]
function shortcode_domain()
{
    return $_SERVER['SERVER_NAME'];
}
add_shortcode('domain', 'shortcode_domain');


// $itemname: 商品名またはASIN
function getlink_Amazon($itemname)
{
    if (!$itemname) {
        return false;
    }

    $params["Service"]    = "AWSECommerceService";
    $params["AWSAccessKeyId"] = "AKIAISNK4G4YHQC4F7ZA";
    $params["Version"]    = "2013-09-01";
    $params["Operation"]    = "ItemSearch";
    $params["SearchIndex"]  = "All";
    $params["Keywords"]    = $itemname;
    $params["AssociateTag"]   = "mononostore-22";
    $params["ResponseGroup"]  = "Large";

    $Secret_Access_Key = "KgeIcP8ntZhoHdXBGtV5BUzAfRTVQqVaPQhU37xH";
    $baseurl = "http://ecs.amazonaws.jp/onca/xml";
    $base_request = "";
    foreach ($params as $k => $v) {
        $base_request .= "&" . $k . "=" . $v;
    }
    $base_request = $baseurl . "?" . substr($base_request, 1);

    $params["Timestamp"] = gmdate("Y-m-d\TH:i:s\Z");
    $base_request .= "&Timestamp=" . $params["Timestamp"];

    $base_request = "";
    foreach ($params as $k => $v) {
        $base_request .= "&" . $k . "=" . rawurlencode($v);
        $params[$k] = rawurlencode($v);
    }
    $base_request = $baseurl . "?" . substr($base_request, 1);

    $base_request = preg_replace("/.*\?/", "", $base_request);
    $base_request = str_replace("&", "\n", $base_request);

    ksort($params);
    $base_request = "";
    foreach ($params as $k => $v) {
        $base_request .= "&" . $k . "=" . $v;
    }
    $base_request = substr($base_request, 1);
    $base_request = str_replace("&", "\n", $base_request);

    $base_request = str_replace("\n", "&", $base_request);

    $parsed_url = parse_url($baseurl);
    $base_request = "GET\n" . $parsed_url["host"] . "\n" . $parsed_url["path"] . "\n" . $base_request;

    $signature = base64_encode(hash_hmac("sha256", $base_request, $Secret_Access_Key, true));
    $signature = rawurlencode($signature);

    $base_request = "";
    foreach ($params as $k => $v) {
        $base_request .= "&" . $k . "=" . $v;
    }
    $base_request = $baseurl . "?" . substr($base_request, 1) . "&Signature=" . $signature;
    $context = stream_context_create(array(
        'http' => array('ignore_errors' => true)
    ));
    $xml = file_get_contents($base_request, false, $context);
    if (strpos($http_response_header[0], '200') == false) {
        return "API ERROR: {$http_response_header[0]}";
    } else {
        $xmltojson = json_encode(simplexml_load_string($xml), true);
        $jsontoarr = json_decode($xmltojson, true);
    }

    if ($jsontoarr['Items']['TotalResults'] == 1) {
        return $jsontoarr['Items']['Item']['DetailPageURL'];
    }
    if ($jsontoarr['Items']['TotalResults'] > 1) {
        return $jsontoarr['Items']['Item'][0]['DetailPageURL'];
    }

    return false;
}

function admin_func()
{
    echo '<link rel="stylesheet" href="' . get_template_directory_uri() . '/css/original-loading.css"><script>var modulefile="' . get_bloginfo("template_directory") . '/get_affiliatelink.php";</script><script type="text/javascript" src="' . get_bloginfo("template_directory") . '/js/original-admin.js"></script>';
}
add_action('admin_head', 'admin_func');

// 記事数取得
function count_user_posttype($userid, $posttype)
{
    global $wpdb;
    $where = get_posts_by_author_sql($posttype, true, $userid, true);
    $count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts " . $where);
    return $count;
}



// 新しいリクエストkeyの名前の設定
add_filter('query_vars', 'add_query_vars_filter');
function add_query_vars_filter($vars)
{
    array_push($vars, 'meta_key');
    array_push($vars, 'meta_value');
    // $vars[] = "meta_key";
    // $vars[] = "meta_value";
    return $vars;
}
add_filter('init', 'flushRules');
function flushRules()
{
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}
// REWRITE:: http://ドメイン/area/tokyo/131130/
add_filter('rewrite_rules_array', 'wp_insertMyRewriteRules');
function wp_insertMyRewriteRules($rules)
{
    $newrules = array();
    $newrules['experts/([^/]+)/?$'] = '/?post_type=experts&meta_key=$matches[1]';
    $newrules['editors/([^/]+)/?$'] = '/?post_type=expertslist&meta_key=$matches[1]';
    return $newrules + $rules;
}
// カスタム投稿タイプ
function add_custom()
{
    register_post_type(
        'experts',
        array(
            'label' => '専門家',
            'show_ui' => false,
            'public' => true,
            'has_archive' => true,
            'rewrite' => array(
                'slug' => 'experts',
                'with_front' => false
            )
        )
    );
    register_post_type(
        'expertslist',
        array(
            'label' => '専門家リスト',
            'show_ui' => false,
            'public' => true,
            'has_archive' => true,
            'rewrite' => array(
                'slug' => 'expertslist',
                'with_front' => false
            )
        )
    );
}
add_action('init', 'add_custom');

/*----------------------------------
ビジュアルエディター　無効
----------------------------------*/
function disable_visual_editor_mypost()
{
    add_filter('user_can_richedit', 'disable_visual_editor_filter');
}
function disable_visual_editor_filter()
{
    return false;
}
add_action('load-post.php', 'disable_visual_editor_mypost');
add_action('load-post-new.php', 'disable_visual_editor_mypost');

/*----------------------------------
ユーザーの執筆を優先
----------------------------------*/
add_filter('override_post_lock', '__return_false');

/*----------------------------------
version 削除
----------------------------------*/
remove_action('wp_head', 'wp_generator');
function remove_cssjs_ver2($src)
{
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'remove_cssjs_ver2', 9999);
add_filter('script_loader_src', 'remove_cssjs_ver2', 9999);

function disable_emojis()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
}
add_action('init', 'disable_emojis');

/*----------------------------------
ステータスの追加  編集中
----------------------------------*/
function init_custom_status()
{
    register_post_status('candidate', array(
        // ステータスの表示名
        'label'       => '編集中',
        // 公開するステータスの場合はpublic, ログインユーザーのみの場合はprotected
        'protected'   => true,
        // 管理画面に表示するテキスト
        'label_count' => _n_noop('編集中 <span class="count">(%s)</span>', '編集中 <span class="count">(%s)</span>'),
    ));
}
add_action('init', 'init_custom_status');

/**
 * 投稿画面のステータスに独自のステータスを追加します。
 */
function post_submitbox_custom_status()
{
    global $post;

    $screen = get_current_screen();
    // 投稿以外の投稿タイプは中断
    if ('post' != $screen->post_type) {
        return;
    } ?>
    <script>
        jQuery(function($) {
            // プルダウンにステータスを追加します。
            var postStatus = $('#post_status');
            postStatus.append('<option value="candidate">編集中</option>');

            <?php
            // 投稿ステータスが標準以外のステータスの場合、ステータス表示を調整します。
            if (!in_array(
                $post->post_status,
                array('public', 'private', 'pending', 'draft', 'auto-draft'),
                true
            )) {
                ?>
                postStatus.val('<?php echo esc_js($post->post_status) ?>');
                $('#post-status-display').html($('option:selected', postStatus).text());
            <?php
        } ?>

            // 各操作がされた際に「下書きとして保存」ボタンのテキストを変更します。
            // (wp-admin/js/post.jsのupdateText()に外から手を入れられないため、1秒ごとに監視して調整します)
            var updateTextEx = function() {
                if ($('option:selected', postStatus).val() == 'candidate') {
                    $('#post-status-display').html($('option:selected', postStatus).text());
                    $('#save-post').show().val('編集中として保存');
                }
            };
            updateTextEx();
            setInterval(updateTextEx, 1000);
        });
    </script><?php
        }
        add_action('post_submitbox_misc_actions', 'post_submitbox_custom_status');

        /**
         * クイック編集のステータスに独自のステータスを追加します。
         */
        function inline_edit_custom_status()
        {
            $screen = get_current_screen();
            // 投稿以外の投稿タイプは中断
            if ('post' != $screen->post_type) {
                return;
            } ?>
    <script>
        jQuery(function($) {
            var postStatus = $('[name=_status]');
            postStatus.append('<option value="candidate">編集中</option>');
        });
    </script><?php
        }
        add_action('admin_footer-edit.php', 'inline_edit_custom_status');

        /**
         * 投稿一覧のステータスリンクの並びを変更します。(変更しないと独自のステータスがゴミ箱より後になる)
         */
        function views_edit_custom_status($views)
        {
            if (isset($views['trash'])) {
                $trash = $views['trash'];
                unset($views['trash']);
                $views['trash'] = $trash;
            }
            return $views;
        }
        add_filter('views_edit-post', 'views_edit_custom_status'); // 投稿一覧

        /**
         * 「すべて」の投稿一覧の際に投稿タイトルの脇に表示されるステータス表示を追加します。
         */
        function display_post_states_custom_status($states)
        {
            global $post;
            $status = get_query_var('post_status');
            if ('candidate' != $status && 'candidate' == $post->post_status) {
                return array('編集中');
            }
            return $states;
        }
        add_filter('display_post_states', 'display_post_states_custom_status');


        /*----------------------------------
ステータスの追加  提出
----------------------------------*/
        function init_custom_status_2()
        {
            register_post_status('candidate', array(
                // ステータスの表示名
                'label'       => '提出',
                // 公開するステータスの場合はpublic, ログインユーザーのみの場合はprotected
                'protected'   => true,
                // 管理画面に表示するテキスト
                'label_count' => _n_noop('提出 <span class="count">(%s)</span>', '提出 <span class="count">(%s)</span>'),
            ));
        }
        add_action('init', 'init_custom_status_2');

        /**
         * 投稿画面のステータスに独自のステータスを追加します。
         */
        function post_submitbox_custom_status_2()
        {
            global $post;

            $screen = get_current_screen();
            // 投稿以外の投稿タイプは中断
            if ('post' != $screen->post_type) {
                return;
            } ?>
    <script>
        jQuery(function($) {
            // プルダウンにステータスを追加します。
            var postStatus = $('#post_status');
            postStatus.append('<option value="candidate">提出</option>');

            <?php
            // 投稿ステータスが標準以外のステータスの場合、ステータス表示を調整します。
            if (!in_array(
                $post->post_status,
                array('public', 'private', 'pending', 'draft', 'auto-draft'),
                true
            )) {
                ?>
                postStatus.val('<?php echo esc_js($post->post_status) ?>');
                $('#post-status-display').html($('option:selected', postStatus).text());
            <?php
        } ?>

            // 各操作がされた際に「下書きとして保存」ボタンのテキストを変更します。
            // (wp-admin/js/post.jsのupdateText()に外から手を入れられないため、1秒ごとに監視して調整します)
            var updateTextEx = function() {
                if ($('option:selected', postStatus).val() == 'candidate') {
                    $('#post-status-display').html($('option:selected', postStatus).text());
                    $('#save-post').show().val('提出として保存');
                }
            };
            updateTextEx();
            setInterval(updateTextEx, 1000);
        });
    </script><?php
        }
        add_action('post_submitbox_misc_actions', 'post_submitbox_custom_status_2');

        /**
         * クイック編集のステータスに独自のステータスを追加します。
         */
        function inline_edit_custom_status_2()
        {
            $screen = get_current_screen();
            // 投稿以外の投稿タイプは中断
            if ('post' != $screen->post_type) {
                return;
            } ?>
    <script>
        jQuery(function($) {
            var postStatus = $('[name=_status]');
            postStatus.append('<option value="candidate">提出</option>');
        });
    </script><?php
        }
        add_action('admin_footer-edit.php', 'inline_edit_custom_status_2');

        /**
         * 投稿一覧のステータスリンクの並びを変更します。(変更しないと独自のステータスがゴミ箱より後になる)
         */
        function views_edit_custom_status_2($views)
        {
            if (isset($views['trash'])) {
                $trash = $views['trash'];
                unset($views['trash']);
                $views['trash'] = $trash;
            }
            return $views;
        }
        add_filter('views_edit-post', 'views_edit_custom_status_2'); // 投稿一覧

        /**
         * 「すべて」の投稿一覧の際に投稿タイトルの脇に表示されるステータス表示を追加します。
         */
        function display_post_states_custom_status_2($states)
        {
            global $post;
            $status = get_query_var('post_status');
            if ('candidate' != $status && 'candidate' == $post->post_status) {
                return array('提出');
            }
            return $states;
        }
        add_filter('display_post_states', 'display_post_states_custom_status_2');
