<?php

/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Eny_Media
 */

get_header();
$home_url = home_url();
$template_directory = get_template_directory_uri();

if (have_posts()) : the_post();

  $cat = get_lowest_terms();
  $this_cat = end($cat);
  $cg_tree = get_category_tree($this_cat->term_id);
  $args = array(
    'term_id' => $this_cat->term_id,
    'taxonomy' => 'category'
  );
  $current_hierarchy = get_current_hierarchy($args) < 3 ?: 2;
  $pointer = &$cg_tree;
  for ($i = 0; $i < $current_hierarchy + 1; $i++) {
    $pointer = &$pointer[0]['cr'];
  }
  $pointer[0] = array(
    'name' => $post->post_title
  );
  unset($pointer);
  $current_hierarchy += 1;

  $this_cat = end($cat);
  $sql = "
    SELECT
      p.*
    FROM
      {$wpdb->prefix}most_popular mp
      RIGHT OUTER JOIN {$wpdb->prefix}posts p ON mp.post_id = p.ID
      LEFT OUTER JOIN {$wpdb->term_relationships} AS tr ON p.ID = tr.object_id
      LEFT OUTER JOIN {$wpdb->term_taxonomy} AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
    WHERE
      p.post_type = 'eny_product' AND
      tt.term_id = {$this_cat->term_id} AND
      p.post_status = 'publish'
    ORDER BY 7_day_stats IS NULL ASC, 7_day_stats DESC, post_date_gmt DESC
    LIMIT 10
  ";
  $posts = $wpdb->get_results($sql);


  $images = array();
  $images[] = array(
    'image' => get_field('eny_product_main_picture') ?: array('url' => $template_directory . '/assets/default.png'),
    'image_source' => get_field('eny_product_main_picture') ? get_field('eny_product_main_picture_source') : "",
    'image_source_link' => get_field('eny_product_main_picture_source_link') ? getAffiliateLink(get_field('eny_product_main_picture_source_link'), 'amazon') : null
  );
  if (have_rows('eny_product_pictures')) :
    while (have_rows('eny_product_pictures')) : the_row();
      $images[] = array(
        'image' => get_sub_field('eny_product_picture'),
      );
    endwhile;
  endif;

  $min_price_site;
  $sites = array();
  if ($amazon_price = get_field("eny_product_amazon_price")) {
      if (strpos(get_field("eny_product_amazon_link", $id), "?") !== false) {
          $amazon_link = get_field("eny_product_amazon_link", $id) . "&tag=eny0f-22";
      } else {
          $amazon_link = get_field("eny_product_amazon_link", $id) . "?tag=eny0f-22";
      }
    $amazon = array(
      "name" => "amazon",
      "price" => $amazon_price,
      "link" => $amazon_link,
    );
    $min_price_site = $amazon;
    array_push($sites, $amazon);
  }
  if ($rakuten_price = get_field("eny_product_rakuten_price")) {
    $rakuten_link = null;
    if (get_field("eny_product_rakuten_search_way") == "キーワード") {
      $rakuten_link = getAffiliateLink("https://search.rakuten.co.jp/search/mall/" . urlencode(get_field("eny_product_rakuten_keyword")), 'rakuten');
    } else {
      $rakuten_link = !empty(get_field("eny_product_rakuten_link")) ? getAffiliateLink(get_field("eny_product_rakuten_link"), 'rakuten') : null;
    }
    $rakuten = array(
      "name" => "rakuten",
      "price" => $rakuten_price,
      "link" => $rakuten_link,
    );
    if (!$min_price_site || $min_price_site && $min_price_site['price'] > $rakuten['price']) {
      $min_price_site = $rakuten;
    }
    array_push($sites, $rakuten);
  }
  if ($yahoo_price = get_field("eny_product_yahoo_price")) {
    $yahoo_link = null;
    if (get_field("eny_product_yahoo_search_way") == "キーワード") {
      $yahoo_link = getAffiliateLink("https://shopping.yahoo.co.jp/search?p=" . urlencode(get_field("eny_product_yahoo_keyword")), 'yahoo');
    } else {
      $yahoo_link = !empty(get_field("eny_product_yahoo_link")) ? getAffiliateLink(get_field("eny_product_yahoo_link"), 'yahoo') : null;
    }
    $yahoo = array(
      "name" => "yahoo",
      "price" => $yahoo_price,
      "link" => $yahoo_link,
    );
    if (!$min_price_site || $min_price_site && $min_price_site['price'] > $yahoo['price']) {
      $min_price_site = $yahoo;
    }
    array_push($sites, $yahoo);
  }
  if ($auPAYmarket_price = get_field("eny_product_auPAYmarket_price")) {
    $auPAYmarket_link = null;
    if (get_field("eny_product_auPAYmarket_search_way", $id) == "キーワード") {
      $auPAYmarket_link = getAffiliateLink("https://wowma.jp/itemlist?" . urlencode(get_field("eny_product_auPAYmarket_keyword", $id)), 'auPAYmarket');
    } else {
      $auPAYmarket_link = get_field("eny_product_auPAYmarket_link", $id);
    }
    $auPAYmarket = array(
      "name" => "auPAYmarket",
      "price" => $auPAYmarket_price,
      "link" => $auPAYmarket_link,
    );
    if (!$min_price_site) {
      $min_price_site = $auPAYmarket;
    } elseif ($auPAYmarket['price'] != 0 && $min_price_site['price'] > $auPAYmarket['price']) {
      $min_price_site = $auPAYmarket;
    }
    array_push($sites, $auPAYmarket);
  }

  // 2022/3/16 officialリンク追加対応
  if ($official_price = get_field("eny_product_official_price")) {
    $official_link = null;
    if (get_field("eny_product_official_search_way", $id) == "キーワード") {
      // $official_link = getAffiliateLink("https://wowma.jp/itemlist?" . urlencode(get_field("eny_product_official_keyword", $id)), 'official');
    } else {
      $official_link = get_field("eny_product_official_link", $id);
    }
    $official = array(
      "name" => "official",
      "price" => $official_price,
      "link" => $official_link,
    );
    if (!$min_price_site) {
      $min_price_site = $official;
    } elseif ($min_price_site['price'] > $official['price']) {
      $min_price_site = $official;
    }
    array_push($sites, $official);
  }

  $rating_array = array();
  $rating_ave = null;
  $review_count_sum = null;
  if ($amazon_rating = get_field("eny_product_amazon_rating")) {
    array_push($rating_array, $amazon_rating);
  }
  if ($rakuten_rating = get_field("eny_product_rakuten_rating")) {
    array_push($rating_array, $rakuten_rating);
  }
  if ($yahoo_rating = get_field("eny_product_yahoo_rating")) {
    array_push($rating_array, $yahoo_rating);
  }
  if ($auPAYmarket_rating = get_field("eny_product_auPAYmarket_rating")) {
    array_push($rating_array, $auPAYmarket_rating);
  }
  if (count($rating_array) > 0) {
    $rating_ave = array_sum($rating_array) / count($rating_array);
  }
  if ($amazon_review_count = get_field("eny_product_amazon_review_count")) {
    $review_count_sum += $amazon_review_count;
  }
  if ($rakuten_review_count = get_field("eny_product_rakuten_review_count")) {
    $review_count_sum += $rakuten_review_count;
  }
  if ($yahoo_review_count = get_field("eny_product_yahoo_review_count")) {
    $review_count_sum += $yahoo_review_count;
  }
  if ($auPAYmarket_review_count = get_field("eny_product_auPAYmarket_review_count")) {
    $review_count_sum += $auPAYmarket_review_count;
  }
  ?>


  <main class="container">
    <div class="promotion-notice"><p style="color: #808080;font-size: 13px;margin-bottom: 20px;">※当サイトではアフィリエイトプログラムを利用して商品を紹介しています。</p></div>
    <?php include 'template-parts/breadcrumbs.php' ?>


    <section class="product-detail row">

      <div class="product-detail__img col-md-5 col-lg-6">
        <div class="product-detail__img__main">
          <?php if ($images[0]['image']) : ?>
            <img id="displayed-image" class="" src="<?php echo $images[0]['image']['url'] ?>">
          <?php endif; ?>
        </div>
        <div class="product-detail__img__sub" id="sub-images">
          <?php for ($i = 0; $i < count($images); $i++) : ?>
            <img src="<?php echo $images[$i]['image']['url'] ?>">
          <?php endfor ?>
        </div>
        <p class="product-detail__img__sorce">
          <?php if ($images[0]['image_source']) : ?>
            <a href="<?php echo !empty($images[0]['image_source_link']) ? $images[0]['image_source_link'] : "#" ?>">出典：<?php echo $images[0]['image_source'] ?></a>
          <?php endif; ?>
        </p>
      </div>

      <div class="product-detail__info col-md-7 col-lg-6">
        <h1 class="product-detail__info__title">
          <?php the_title(); ?>
        </h1>

        <?php if ($rating_ave) :
            $item_stars = '<img class="star" src="' . get_template_directory_uri() . '/assets/star.svg" alt="">';
            $item_stars_gray = '<img class="star" src="' . get_template_directory_uri() . '/assets/star-gray.svg" alt="">';
            ?>
          <div class="product-detail__info__rating">
            <?php echo str_repeat($item_stars, round($rating_ave)) ?>
            <?php echo str_repeat($item_stars_gray, 5 - round($rating_ave)) ?>
            <em>
              <?php echo round($rating_ave, 2) ?>
              <span><?php echo '(' . $review_count_sum . '件)' ?></span>
            </em>
          </div>
        <?php endif ?>

        <div class="product-detail__info__desc">
          <?php the_field('eny_product_discription'); ?>
        </div>

        <?php if ($min_price_site) : ?>
          <em class="product-detail__info__price">
            <img src="<?php echo get_template_directory_uri() . '/assets/' . $min_price_site['name'] . '.svg' ?>">
            <span class="">最安値</span>
            <?php echo number_format($min_price_site['price']) ?>
            <span class="">(税込)</em>
          </em>
        <?php endif ?>

        <?php foreach ($sites as $site) : ?>
          <div class="product-detail__info__shop">
            <div>
              <div>
                <img src="<?php echo get_template_directory_uri() . '/assets/' . $site['name'] . '.png'; ?>">
              </div>
              <p><?php echo number_format($site['price'])."円" ?><span></span></p>
            </div>
            <a target="_blank" rel="nofollow" href="<?php echo !empty($site['link']) ? $site['link'] : "#"; ?>">
              <button class="button button--solid" type="button">
                <p>詳細を見る</p>
              </button>
            </a>
          </div>
        <?php endforeach ?>


        <small class="product-detail__info__alert">
          価格が変わっている可能性があるため、販売サイトでご確認ください。
          表示価格に送料は含んでおらず、販売サイトによって送料は異なります。
        </small>
      </div>
    </section>


    <?php if (count($posts) > 0) : ?>

      <section>
        <h1 class="u-page-title"><?php echo $this_cat->name ?>の人気ランキング</h1>

        <ul class="recommend-list">
          <div>
            <?php
                global $post;
                for ($i = 0; $i < count($posts); $i++) :
                  $post = $posts[$i];
                  setup_postdata($post);
                  $prices = array();
                  if (!empty(get_field("eny_product_amazon_price"))) {
                    array_push($prices, get_field("eny_product_amazon_price"));
                  }
                  if (!empty(get_field("eny_product_rakuten_price"))) {
                    array_push($prices, get_field("eny_product_rakuten_price"));
                  }
                  if (!empty(get_field("eny_product_yahoo_price"))) {
                    array_push($prices, get_field("eny_product_yahoo_price"));
                  }
                  if (!empty(get_field("eny_product_auPAYmarket_price"))) {
                    array_push($prices, get_field("eny_product_auPAYmarket_price"));
                  }
                  $lowest_price = (count($prices) > 0) ? min($prices) : null;
                  $rating_array = array();
                  $rating_ave = null;
                  $review_count_sum = null;
                  if ($amazon_rating = get_field("eny_product_amazon_rating", $item->ID)) {
                    array_push($rating_array, $amazon_rating);
                  }
                  if ($rakuten_rating = get_field("eny_product_rakuten_rating", $item->ID)) {
                    array_push($rating_array, $rakuten_rating);
                  }
                  if ($yahoo_rating = get_field("eny_product_yahoo_rating", $item->ID)) {
                    array_push($rating_array, $yahoo_rating);
                  }
                  if ($auPAYmarket_rating = get_field("eny_product_auPAYmarket_rating", $item->ID)) {
                    array_push($rating_array, $auPAYmarket_rating);
                  }
                  if (count($rating_array) > 0) {
                    $rating_ave = array_sum($rating_array) / count($rating_array);
                  }
                  if ($amazon_review_count = get_field("eny_product_amazon_review_count", $item->ID)) {
                    $review_count_sum += $amazon_review_count;
                  }
                  if ($rakuten_review_count = get_field("eny_product_rakuten_review_count", $item->ID)) {
                    $review_count_sum += $rakuten_review_count;
                  }
                  if ($yahoo_review_count = get_field("eny_product_yahoo_review_count", $item->ID)) {
                    $review_count_sum += $yahoo_review_count;
                  }
                  if ($auPAYmarket_review_count = get_field("eny_product_auPAYmarket_review_count", $item->ID)) {
                    $review_count_sum += $auPAYmarket_review_count;
                  }
                  $thumbnail_url =  get_the_post_thumbnail_url() ?: $template_directory . '/assets/default.png';
                  $item_stars = '<img class="star" src="' . $template_directory . '/assets/star.svg" alt="">';
                  $item_stars_gray = '<img class="star" src="' . $template_directory . '/assets/star-gray.svg" alt="">';
                  ?>

              <li class="item-summary-list">
                <a href="<?php the_permalink() ?>">
                  <div class="item-summary-list__img">
                    <img class="" src="<?php echo $thumbnail_url; ?>" alt="">
                    <div class="<?php echo ($i < 3) ? 'secondary' : 'gray'; ?>">
                      <?php echo $i + 1 ?>
                    </div>
                  </div>
                  <h2 class="item-summary-list__title"><?php the_title() ?>
                  </h2>

                  <?php if ($lowest_price) : ?>
                    <div class="item-summary-list__price">
                      <span>最安値</span>
                      <em><?php echo number_format($lowest_price) ?>円</em>
                    </div>
                  <?php endif; ?>

                  <?php if ($rating_ave) : ?>
                    <div class="item-summary-list__rating">
                      <?php echo str_repeat($item_stars, round($rating_ave)) ?>
                      <?php echo str_repeat($item_stars_gray, 5 - round($rating_ave)) ?>
                      <em>
                        <?php echo round($rating_ave, 2) ?>
                        <span><?php echo '(' .  $review_count_sum . ')'; ?></span>
                      </em>
                    </div>
                  <?php endif; ?>
                </a>
              </li>

            <?php endfor;
                wp_reset_postdata() ?>
            <span class="recommend-list__spacer"></span>
          </div>
        </ul>

        <a class="cat-archive__articles__button" href="<?php echo $home_url . '/categories/' . $this_cat->term_id . '/products?sort=popular_weekly' ?>">
          <button class="button" type="button">
            <p>もっと見る</p>
          </button>
        </a>
      </section>

    <?php endif; ?>


    <?php
      $args = array(
        'term_id' => $this_cat->term_id
      );
      $count = count_posts_in_term($args);
      if ($count > 0) :
        $args = array(
          'term_id' => $this_cat->term_id,
          'limit' => 6
        );
        $results = get_posts_in_term($args);
        ?>

      <section class="u-margin-bottom-md">
        <h1 class="u-page-title">関連記事</h1>

        <ul class="row u-list-style-none">

          <?php foreach ($results as $article) :
                $article = get_post($article->ID);
                // $article_cat = end(get_the_category($article->ID));
                $article_cat = $this_cat;
                $article_thumbnail =  get_the_post_thumbnail_url($article->ID) ?: $template_directory . '/assets/default.png';
                ?>
            <li class="col-lg-6 u-margin-bottom-md">
              <div class="related-article">
                <a class="related-article__thumbnail" href="<?php echo get_permalink($article->ID); ?>" rel="bookmark" title="<?php the_title(); ?>">
                  <img src="<?php echo $article_thumbnail ?>">
                </a>
                <div class="related-article__details">
                  <a class="related-article__details__title" href="<?php echo get_permalink($article->ID) ?>" rel="bookmark">
                    <h2><?php echo $article->post_title ?>
                    </h2>
                  </a>
                  <a class="related-article__details__category" href="<?php echo home_url('categories/' . $article_cat->term_id) ?>">
                    <p><?php echo $article_cat->name ?>
                    </p>
                  </a>
                  <p class="related-article__details__summary">
                    <?php
                          $text = mb_strimwidth($article->post_content, 0, 160, '……');
                          $text = strip_tags($text);
                          $text = strip_shortcodes($text);
                          if (strpos($text, '[') !== false) {
                            $text = mb_strimwidth(content_excerpt(get_post($post->ID)->post_content, 300), 0, 160, '……');
                          }
                          echo $text
                          ?>
                  </p>
                </div>
              </div>
            </li>
          <?php endforeach ?>

        </ul>
      </section>

    <?php endif; ?>


  </main><!-- #main -->


  <script>
    // window.addEventListener('load', function(){
    const img = document.getElementById('displayed-image')
    const sub_imgs = document.getElementById('sub-images')
    for (let i = 0; i < sub_imgs.children.length; i++) {
      sub_imgs.children[i].addEventListener('click', (e) => {
        img.src = e.target.src
      })
      sub_imgs.children[i].addEventListener('mouseover', (e) => {
        img.src = e.target.src
      })
    }
    // });
  </script>

<?php
endif;
get_footer();
