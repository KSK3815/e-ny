<?php

  // 独自ルーティング
  add_filter('rewrite_rules_array', 'wp_insertMyRewriteRules');
  function wp_insertMyRewriteRules($rules) {
    $newrules = array();
    $newrules['categories/(\d+)/?$'] = '/?cat=$matches[1]';
    // 記事アーカイブ
    $newrules['articles'] = '/?static=articles&template=archive-post-second';
    $newrules['categories/(\d+)/articles/?$'] = '/?post_type=post&cat=$matches[1]';
    // 商品アーカイブ
    $newrules['categories/(\d+)/products/?$'] = '/?post_type=eny_product&cat=$matches[1]';
    // 記事個別ページ
    // default
    // 商品個別ページ
    $newrules['products/(\d+)/?$'] = '/?p=$matches[1]&post_type=eny_product';
    return $newrules + $rules;
  }

  add_filter('query_vars', 'add_query_vars_filter');
  function add_query_vars_filter($vars) {
    array_push($vars, 'template');
    array_push($vars, 'q');
    return $vars;
  }

  add_filter('template_include', 'custom_template', 1);
  function custom_template($template) {
    $tp = get_query_var('template');
    if ($tp == "archive-post-second") {
      $template = get_query_template('archive-post-second');
    }
    $q = get_query_var('q');
    $pt = get_query_var('post_type');
    if ($q) {
      if ($pt == 'post') {
        $template = get_query_template('search-post');
      } else if ($pt == 'eny_product') {
        $template = get_query_template('search-eny');
      } else {
        $template = get_query_template('search');
      }
    }
    return $template;
  }

  // add_action( 'init', 'mytheme_add_permastruct', 0 );
  // function mytheme_add_permastruct() {
  //   global $wp_rewrite;
  //   $wp_rewrite->add_permastruct( 'eny_product', '/products/%eny_product%/', false );
  // }
  
  add_filter('init', 'flushRules');
  function flushRules() {
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
  }

  // パーマリンク変更
  function append_eny_product_link( $url, $post ) {
    if ( 'eny_product' == get_post_type( $post ) ) {
      $url = home_url('/products/'. $post->ID);
    }
    return $url;
  }
  add_filter( 'post_type_link', 'append_eny_product_link', 10, 2 );

  if (! function_exists('select_category_archive_template')) {
    function select_category_archive_template() {

      $pt = get_query_var('post_type');
      $term_hierarchy = array_reverse( get_ancestors( get_queried_object()->term_id, 'category' ) );
      $current_hierarchy = count($term_hierarchy);
      $template = '';
      
      switch ($pt) {
        case 'post':
          $template = "archive-post.php";
        break;
        case 'eny_product':
          $template = "archive-eny_product.php";
        break;
        default:
          switch ($current_hierarchy) {
            case 0:
              $template = "category.php";
            break;
            case 1:
              $template = "category-sub.php";
            break;
            case 2:
              $template = "category-sub-sub.php";
            break;
          }
        break;
      }
      return locate_template( $template );
        
    }
    add_filter( 'category_template', 'select_category_archive_template' );
  }

?>