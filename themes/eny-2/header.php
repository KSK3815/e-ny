<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Eny_Media
 */
?>
<!doctype html>
<html <?php language_attributes();?>>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-T4Q3LPQ');</script>
<!-- End Google Tag Manager -->

<head>
  <!-- adsense TAG -->
  <script data-ad-client="ca-pub-6830121310854700" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
  <!-- End adsense TAG -->
  <script async src=“https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6830121310854700” crossorigin=“anonymous”></script>
  <meta charset="<?php bloginfo('charset');?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php if (( is_single() && get_post_type( get_the_ID() ) == "eny_product" ) || (explode("/", $_SERVER['REQUEST_URI'])[1] == "categories")) : ?>
      <meta name="robots" content="noindex,nofollow">
    <?php endif;?>

    <?php if (explode("?", $_SERVER['REQUEST_URI'], 2)[0] == '/323243/' || explode("?", $_SERVER['REQUEST_URI'], 2)[0] == '/323243'): ?>
      <!-- EBiS tag version4.10 start -->
      <script type="text/javascript">
          (function(a,d,e,b,i,s){ window[i]=window[i]||[];var f=function(a,d,e,b,i,s){
              var o=a.getElementsByTagName(d)[0],h=a.createElement(d),t='text/javascript';
              h.type=t;h.async=e;h.onload=function(){ window[i].init(
                  { argument:s, auto:true }
              );};h._p=o;return h;},h=f(a,d,e,b,i,s),l='//taj',j=b+s+'/cmt.js';h.src=l+'1.'+j;
              h._p.parentNode.insertBefore(h,h._p);h.onerror=function(k){k=f(a,d,e,b,i,s);
                  k.src=l+'2.'+j;k._p.parentNode.insertBefore(k,k._p);};
          })(document,'script',true,'ebis.ne.jp/','ebis','5UQXb7eX');
      </script>
      <!-- EBiS tag end -->
    <?php endif; ?>
    <?php
    if (is_category()):
        $home_url = home_url();
        $canonical = "";
        $pt = get_query_var('post_type');
        $term_id = get_queried_object()->term_id;

        if (empty($pt)) {
            $canonical = $home_url . '/categories/' . $term_id;
        } else {
            if ($pt == 'post') {
                $canonical = $home_url . '/categories/' . $term_id . '/articles';
            } elseif ($pt == 'eny_product') {
                $canonical = $home_url . '/categories/' . $term_id . '/products';
            }
        }
        ?>

      <link rel="canonical" href="<?php echo $canonical ?>" />

    <?php
    elseif (is_404() && !empty(get_query_var('q')) && !empty(get_query_var('post_type'))):
        $home_url = home_url();
        $canonical = "";
        $q = get_query_var('q');
        $pt = get_query_var('post_type');
        if ($pt == "post") {
            $canonical = $home_url . '/search?q=' . $q . '&post_type=post';
        } elseif ($pt == "eny_product") {
            $canonical = $home_url . '/search?q=' . $q . '&post_type=eny_product';
        }
        ?>
      <link rel="canonical" href="<?php echo $canonical ?>" />
    <?php endif; ?>

    <?php wp_head(); ?>
    <?php
    $title = "";
    $description = "";
    // 検索結果ページ用
    if (is_404() && !empty(get_query_var('q'))) {
        $search_q = get_query_var('q');
        $pt = get_query_var('post_type');
        if ($pt == 'post') {
            $title = "「{$search_q}」の記事検索結果 | eny";
        } elseif ($pt == 'eny_product') {
            $title = "「{$search_q}」の商品検索結果 | eny";
        } else {
            $title = "「{$search_q}」の検索結果 | eny";
        }
        // 商品ページ
    } elseif (is_single() && (get_query_var('post_type') == 'eny_product')) {
        $product_name = get_the_title();
        $product_category = end(get_lowest_terms())->name;
        $title = "{$product_name}の通販・レビュー・価格比較｜通販比較サイトeny";
        $description = "{$product_name}の通販なら、enyでレビュー・口コミ・価格比較・最安値をチェック！{$product_category}の人気ランキングや、おすすめ・おしゃれな商品の使い方や選び方をご紹介する記事も公開しています。";
        // カテゴリーページ
    } elseif (is_category() && empty(get_query_var('post_type'))) {
        $current_category = get_category($cat)->name;
        $title = "{$current_category}の通販・記事一覧｜通販比較サイトeny";
        $description = "{$current_category}の通販なら、enyでレビュー・口コミ・価格比較・最安値をチェック！様々な商品やメーカーから絞り込んで探すこともできます。また、おすすめ・おしゃれな商品の使い方や選び方、人気ランキングをご紹介する記事も公開しています。";
        // カテゴリー記事ページ
    } elseif (is_category() && (get_query_var('post_type') == 'post')) {
        $current_category = get_category($cat)->name;
        $title = "{$current_category}のおすすめ・おしゃれ記事一覧｜通販比較サイトeny";
        $description = "{$current_category}の通販ならeny。おすすめ・おしゃれな商品の使い方や選び方、人気ランキングをご紹介する記事を公開しています。レビュー・口コミ・価格比較・最安値をチェックして、あなたにぴったりの商品を見つけてください。";
        // カテゴリー商品ページ
    } elseif (is_category() && (get_query_var('post_type') == 'eny_product')) {
        $current_category = get_category($cat)->name;
        $maker_params = $_GET['makers'] ?: array();
        if (count($maker_params) == 1) {
            $maker_name = get_term($maker_params[0], 'eny_product_maker')->name;
            $title = "{$maker_name}の{$current_category}の通販・レビュー・価格比較｜通販比較サイトeny";
            $description = "{$maker_name}の{$current_category}の通販なら、enyでおすすめ人気商品のレビュー・口コミ・価格比較・最安値をチェック！様々な商品やメーカーから絞り込んで探すこともできます。";
        } else {
            $title = "{$current_category}の通販・レビュー・価格比較｜通販比較サイトeny";
            $description = "{$current_category}の通販なら、enyでおすすめ人気商品のレビュー・口コミ・価格比較・最安値をチェック！様々な商品やメーカーから絞り込んで探すこともできます。";
        }
    }
    if ($title) : ?>
      <script>
          document.title = "<?php echo $title ?>";
      </script>
    <?php endif;
    if ($description) : ?>
      <script>
          let desc = document.getElementsByName('description')[0];
          const descContent = "<?php echo $description ?>"
          if (desc) {
              desc.setAttribute("content", descContent)
          } else {
              desc = document.createElement("meta");
              desc.setAttribute("name", "description");
              desc.setAttribute("content", descContent);
              document.head.insertBefore(desc, document.head.getElementsByTagName('title')[0].nextSibling)
          }
      </script>
    <?php endif;
    ?>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-124569376-1"></script>
  <script>
      window.dataLayer = window.dataLayer || [];

      function gtag() {
          dataLayer.push(arguments);
      }
      gtag('js', new Date());
      gtag('config', 'UA-124569376-1');
  </script>
</head>

<body <?php body_class();?>>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T4Q3LPQ"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<div id="page" class="site-wrapper">

    <?php if (!is_front_page()): ?>
      <header id="js-header" class="site-header">
        <div class="site-header__contents container">
          <a class="site-header__contents__logo" href="<?php echo home_url(); ?>">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/logo-light.svg" alt="">
          </a>

          <button class="d-none" type="button">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="site-header__contents__search">
            <form id="header-search" method="get" action="<?php echo home_url('/') . 'search'; ?>">
              <input id="header-search-input" aria-label="Search" name="q" type="text" placeholder="商品を探す" value="<?php echo htmlspecialchars($q); ?>">
              <button id="header-search-submit" type="submit">
                <i class="fas fa-search"></i>
              </button>
            </form>
          </div>
        </div>
      </header>
    <?php endif; ?>

    <?php
    // // If the current user can manage options(ie. an admin)
    // if( current_user_can( 'manage_options' ) )
    //     // Print the saved global
    //     printf( '<div><strong>Current template:</strong> %s</div>', get_page_template() );
    ?>

  <main class="site-content <?php if (!is_front_page()) {
      echo 'has-header';
  } ?>">