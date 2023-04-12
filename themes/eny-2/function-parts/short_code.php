<?php
function enyProductShortCode($atts)
{
  extract(shortcode_atts(array(
    'id' => 0
  ), $atts));
  $post = get_post($id);
  $image = array(
    'data' => get_field('eny_product_main_picture', $id) ?: array('url' => get_template_directory_uri() . "/assets/default.png"),
    'source' => get_field('eny_product_main_picture', $id) ? get_field('eny_product_main_picture_source', $id) : "",
    'source_link' => get_field('eny_product_main_picture_source_link', $id) ?: "#"
  );

  $min_price_site;
  $sites = array();
    // 2022/3/16 officialリンク追加対応
    if ($official_price = get_field("eny_product_official_price", $id)) {
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
  // 2020/11/04 auPAYmarketリンク追加対応
  if ($auPAYmarket_price = get_field("eny_product_auPAYmarket_price", $id)) {
    $auPAYmarket_link = null;
    if (get_field("eny_product_auPAYmarket_search_way", $id) == "キーワード") {
      $auPAYmarket_link = getAffiliateLink("https://wowma.jp/itemlist?" . urlencode(get_field("eny_product_auPAYmarket_keyword", $id)), 'auPAYmarket');
    } else {
      $auPAYmarket_link = get_field("eny_product_auPAYmarket_link", $id);
    }
    $auPAYmarket = array(
      "name" => "auPAYmarket",
      "price" => number_format($auPAYmarket_price),
      "link" => $auPAYmarket_link,
    );
    if (!$min_price_site) {
      $min_price_site = $auPAYmarket;
    } elseif ($min_price_site['price'] > $auPAYmarket['price']) {
      $min_price_site = $auPAYmarket;
    }
    array_push($sites, $auPAYmarket);
  }

  if ($amazon_price = get_field("eny_product_amazon_price", $id)) {
    // $amazon_link = !empty(get_field("eny_product_amazon_link", $id)) ? getAffiliateLink(get_field("eny_product_amazon_link", $id), 'amazon') : null;
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
  if ($rakuten_price = get_field("eny_product_rakuten_price", $id)) {
    $rakuten_link = null;
    if (get_field("eny_product_rakuten_search_way", $id) == "キーワード") {
      $rakuten_link = getAffiliateLink("https://search.rakuten.co.jp/search/mall/" . urlencode(get_field("eny_product_rakuten_keyword", $id)), 'rakuten');
    } else {
      $rakuten_link = !empty(get_field("eny_product_rakuten_link", $id)) ? getAffiliateLink(get_field("eny_product_rakuten_link", $id), 'rakuten') : null;
    }
    $rakuten = array(
      "name" => "rakuten",
      "price" => $rakuten_price,
      "link" => $rakuten_link,
    );
    if (!$min_price_site) {
      $min_price_site = $rakuten;
    } elseif ($min_price_site['price'] > $rakuten['price']) {
      $min_price_site = $rakuten;
    }
    array_push($sites, $rakuten);
  }
  if ($yahoo_price = get_field("eny_product_yahoo_price", $id)) {
    $yahoo_link = null;
    if (get_field("eny_product_yahoo_search_way", $id) == "キーワード") {
      $yahoo_link = getAffiliateLink("https://shopping.yahoo.co.jp/search?p=" . urlencode(get_field("eny_product_yahoo_keyword", $id)), 'yahoo');
    } else {
      $yahoo_link = !empty(get_field("eny_product_yahoo_link", $id)) ? getAffiliateLink(get_field("eny_product_yahoo_link", $id), 'yahoo') : null;
    }
    $yahoo = array(
      "name" => "yahoo",
      "price" => $yahoo_price,
      "link" => $yahoo_link,
    );
    if (!$min_price_site) {
      $min_price_site = $yahoo;
    } elseif ($min_price_site['price'] > $yahoo['price']) {
      $min_price_site = $yahoo;
    }
    array_push($sites, $yahoo);
  }

  $rating_array = array();
  $rating_ave = null;
  $review_count_sum = null;
  if ($amazon_rating = get_field("eny_product_amazon_rating", $id)) {
    array_push($rating_array, $amazon_rating);
  }
  if ($rakuten_rating = get_field("eny_product_rakuten_rating", $id)) {
    array_push($rating_array, $rakuten_rating);
  }
  if ($yahoo_rating = get_field("eny_product_yahoo_rating", $id)) {
    array_push($rating_array, $yahoo_rating);
  }
  // 2020/11/04 auPAYmarketリンク追加対応
  if ($auPAYmarket_rating = get_field("eny_product_auPAYmarket_rating", $id)) {
    array_push($rating_array, $auPAYmarket_rating);
  }
  // 2022/3/16 officialリンク追加対応
  if ($auPAYmarket_rating = get_field("eny_product_official_rating", $id)) {
    array_push($rating_array, $official_rating);
  }

  if (count($rating_array) > 0) {
    $rating_ave = round(array_sum($rating_array) / count($rating_array), 2);
  }
  if ($amazon_review_count = get_field("eny_product_amazon_review_count", $id)) {
    $review_count_sum += $amazon_review_count;
  }
  if ($rakuten_review_count = get_field("eny_product_rakuten_review_count", $id)) {
    $review_count_sum += $rakuten_review_count;
  }
  if ($yahoo_review_count = get_field("eny_product_yahoo_review_count", $id)) {
    $review_count_sum += $yahoo_review_count;
  }
  // 2020/11/04 auPAYmarketリンク追加対応
  if ($auPAYmarket_review_count = get_field("eny_product_auPAYmarket_review_count", $id)) {
    $review_count_sum += $auPAYmarket_review_count;
  }
  // 2022/3/17 officialリンク追加対応
  if ($official_review_count = get_field("eny_product_official_review_count", $id)) {
    $review_count_sum += $official_review_count;
  }
  $permalink = get_permalink($id);
  $details = array();
  if (have_rows('eny_product_details', $id)) {
    while (have_rows('eny_product_details', $id)) {
      the_row();
      $detail = get_sub_field('eny_product_detail_key') . '：' . get_sub_field('eny_product_detail_value');
      array_push($details, $detail);
    }
  }
  $details_html = implode('<br>', $details);

  $html = <<<HTML
<section class="itemcard">
  <div class="itemcard__content">
    <figure class="itemcard__content__img">
      <a href="{$permalink}">
        <img src="{$image['data']['url']}" alt="{$post->post_title}">
      </a>
      <figcaption>
        出典：
        <a target="_blank" rel="nofollow" href="{$image['source_link']}">
          {$image['source']}
        </a>
      </figcaption>
    </figure>
    <div class="itemcard__content__info">
HTML;
  /* 2020/9/17 Stailor inc 立川更新
  if ($min_price_site) :
    $min_site_image_url = get_template_directory_uri() . '/assets/' .
      $min_price_site['name'] . '.svg';
    $price_format = number_format($min_price_site['price']);
    $html .= <<<HTML
	    <em class="itemcard__content__info__price">
        <img src="{$min_site_image_url}">
        <span>最安値</span>
        {$price_format}
        <span>(税込)</span>
      </em>
HTML;
  endif;*/
  if ($rating_ave) :
    $item_stars = '<img class="star" src="' . get_template_directory_uri() . '/assets/star.svg" alt="">';
    $item_stars_gray = '<img class="star" src="' . get_template_directory_uri() . '/assets/star-gray.svg" alt="">';
    $stars_html = "";
    for ($i = 0; $i < round($rating_ave); $i++) {
      $stars_html .= $item_stars;
    }
    $stars_gray_html = "";
    for ($i = 0; $i < 5 - round($rating_ave); $i++) {
      $stars_gray_html .= $item_stars_gray;
    }
    $html .= <<<HTML
      <div class="itemcard__content__info__rating">
        {$stars_html}
        {$stars_gray_html}
        <em>
			{$rating_ave}
			<span>({$review_count_sum}件)</span>
		</em>
      </div>
HTML;
  endif;

  $html .= <<<HTML
      <div class="itemcard__content__info__shop">
  HTML;
  // 2020/11/04 auPAYmarketリンク追加対応
  foreach ($sites as $site) :
    $btn_name = "購入";
    if ($site['name'] == "official") {
      $btn_name = "公式サイトで詳細を見る";
      $html .= <<<HTML
      <a target="_blank" href="{$site['link']}" rel="nofollow">
        <button class="button button--solid" type="button">
          <p>{$btn_name}</p>
        </button>
      </a>
      HTML;
    } elseif ($site['name'] == "auPAYmarket") {
      $btn_name = "auPAYマーケットで詳細を見る";
      $html .= <<<HTML
      <a target="_blank" href="{$site['link']}" rel="nofollow">
        <button class="button button--solid" type="button">
          <p>{$btn_name}</p>
        </button>
      </a>
      HTML;
    } elseif ($site['name'] == "rakuten") {
      $btn_name = "楽天で詳細を見る";
      $html .= <<<HTML
      <a target="_blank" href="{$site['link']}" rel="nofollow">
        <button class="button button--solid" type="button">
          <p>{$btn_name}</p>
        </button>
      </a>
      HTML;
    } elseif ($site['name'] == "yahoo") {
      $btn_name = "Yahoo!ショッピングで詳細を見る";
      $html .= <<<HTML
      <a target="_blank" href="{$site['link']}" rel="nofollow">
        <button class="button button--solid" type="button">
          <p>{$btn_name}</p>
        </button>
      </a>
      HTML;
    } elseif ($site['name'] == "amazon") {
      $btn_name = "Amazonで詳細を見る";
      $html .= <<<HTML
      <a target="_blank" href="{$site['link']}" rel="nofollow">
        <button class="button button--solid" type="button">
          <p>{$btn_name}</p>
        </button>
      </a>
      HTML;
    }
  endforeach;
  $html .= <<<HTML
  </div>
  HTML;
  /*
  $html .= <<<HTML
	      <a href="{$permalink}">
          <button class="button button--solid" type="button">
            <p>amazon 楽天 yahoo!ショッピングで詳細を見る</p>
          </button>
        </a>
      </div>
  HTML;
  */


  $html .= <<<HTML
    </div>
  </div>
HTML;

  if ($details_html) {
    $html .= <<<HTML
  <section class="itemcard__details">
    <em class="itemcard__details__title">詳細情報</em>
    <div class="itemcard__details__data">
      {$details_html}
    </div>
  </section>
HTML;
  }

  $html .= <<<HTML
</section>
HTML;

  return $html;
}
add_shortcode('eny_product', 'enyProductShortCode');









function enyAuthorCommentShortCode($atts)
{
  extract(shortcode_atts(array(
    'comment' => ''
  ), $atts));
  global $post;
  $author = get_userdata($post->post_author);
  $auth_url = get_author_posts_url($post->post_author);
  $get_avatar = get_avatar($author->user_email, 256);

  $html = <<<HTML
<div class="auth-comment">
  <a href="{$auth_url}">
    <div class="auth-thumb">
     {$get_avatar}
    </div>
  </a>
  <div class="comment-details">
    <p>{$comment}</p>
  </div>
</div>
HTML;

  return $html;
}
add_shortcode('eny_author_comment', 'enyAuthorCommentShortCode');







function enyProductListShortCode($atts)
{
  extract(shortcode_atts(array(
    'ids' => ''
  ), $atts));

  $no_whitespaces_ids = preg_replace('/\s*,\s*/', ',', filter_var($ids, FILTER_SANITIZE_STRING));
  $ids_array = explode(',', $no_whitespaces_ids);
  $html = '<h2>この記事でおすすめする商品</h2><ul class="recommend-list"><div>';

  for ($i = 0; $i < count($ids_array); $i++) {
    $post = get_post($ids_array[$i]);
    $name = mb_strimwidth($post->post_title, 0, isMobileDevice() ? 32 : 47, "...");
    $image = array(
      'data' => get_field('eny_product_main_picture', $ids_array[$i]) ?: array('url' => get_template_directory_uri() . "/assets/default.png"),
      'source' => get_field('eny_product_main_picture_source', $ids_array[$i])
    );
    $permalink = get_permalink($ids_array[$i]);

    $html .= <<<HTML
<li>
	<a href="{$permalink}">
		<div class="category-card">
			<img class="category-card__img" src="{$image['data']['url']}" alt="{$post->post_title}">
		</div>
		<p class="category-card__label">{$name}</p>
	</a>
</li>
HTML;
  }

  $html .= '<span class="cat-summary__cat__lists__spacer"></span></div></ul>';

  return $html;
}
add_shortcode('eny_product_list', 'enyProductListShortCode');






function enyProductRankingShortCode($atts)
{
  extract(shortcode_atts(array(
    'ids' => ''
  ), $atts));

  $no_whitespaces_ids = preg_replace('/\s*,\s*/', ',', filter_var($ids, FILTER_SANITIZE_STRING));
  $ids_array = explode(',', $no_whitespaces_ids);
  $home_url = home_url();

  $html = <<<HTML
<h2>おすすめの商品一覧</h2>
<table class="ranking-list">
  <thead class="ranking-list__head">
	  <tr>
      <th>製品</th>
      <th>最安値</th>
      <th>評価</th>
      <th>リンク</th>
	  </tr>
  </thead>
  <tbody class="ranking-list__body">
HTML;

  for ($i = 0; $i < count($ids_array); $i++) {
    $id = $ids_array[$i];
    $post = get_post($id);
    $min_price_site = '';
    $sites = array();
    if ($amazon_price = get_field("eny_product_amazon_price", $id)) {
      $amazon_link = !empty(get_field("eny_product_amazon_link", $id)) ? getAffiliateLink(get_field("eny_product_amazon_link", $id), 'amazon') : null;
      $amazon = array(
        "name" => "amazon",
        "price" => $amazon_price,
        "link" => $amazon_link,
      );
      $min_price_site = $amazon;
      array_push($sites, $amazon);
    };

    if ($rakuten_price  = get_field("eny_product_rakuten_price", $id)) {
      $rakuten_link = null;
      if (get_field("eny_product_rakuten_search_way", $id) == "キーワード") {
        $rakuten_link = getAffiliateLink("https://search.rakuten.co.jp/search/mall/" . urlencode(get_field("eny_product_rakuten_keyword", $id)), 'rakuten');
      } else {
        $rakuten_link = !empty(get_field("eny_product_rakuten_link", $id)) ? getAffiliateLink(get_field("eny_product_rakuten_link", $id), 'rakuten') : null;
      }
      $rakuten = array(
        "name" => "rakuten",
        "price" => $rakuten_price,
        "link" => $rakuten_link,
      );
      if (!$min_price_site) {
        $min_price_site = $rakuten;
      } elseif ($min_price_site['price'] > $rakuten['price']) {
        $min_price_site = $rakuten;
      }

      array_push($sites, $rakuten);
    }

    if ($yahoo_price = get_field("eny_product_yahoo_price", $id)) {
      $yahoo_link = null;
      if (get_field("eny_product_yahoo_search_way", $id) == "キーワード") {
        $yahoo_link = getAffiliateLink("https://shopping.yahoo.co.jp/search?p=" . urlencode(get_field("eny_product_yahoo_keyword", $id)), 'yahoo');
      } else {
        $yahoo_link = !empty(get_field("eny_product_yahoo_link", $id)) ? getAffiliateLink(get_field("eny_product_yahoo_link", $id), 'yahoo') : null;
      }
      $yahoo = array(
        "name" => "yahoo",
        "price" => $yahoo_price,
        "link" => $yahoo_link,
      );
      if (!$min_price_site) {
        $min_price_site = $yahoo;
      } elseif ($min_price_site['price'] > $yahoo['price']) {
        $min_price_site = $yahoo;
      }
      array_push($sites, $yahoo);
    }


  // 2021/11/04 auPAYmarketリンク追加対応
  if ($auPAYmarket_price = get_field("eny_product_auPAYmarket_price", $id)) {
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
    } elseif ($min_price_site['price'] > $auPAYmarket['price']) {
      $min_price_site = $auPAYmarket;
    }
    array_push($sites, $auPAYmarket);
  }

  // 2022/3/16 officialリンク追加対応
  if ($official_price = get_field("eny_product_official_price", $id)) {
    $official_link = null;
    if (get_field("eny_product_official_search_way", $id) == "キーワード") {
      $official_link = getAffiliateLink("https://wowma.jp/itemlist?" . urlencode(get_field("eny_product_official_keyword", $id)), 'official');
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

    if ($min_price_site) {
      $minimum_price = number_format((float) $min_price_site['price']);
    }

    $rating_array = array();
    $rating_ave = null;
    if ($amazon_rating = get_field("eny_product_amazon_rating", $id)) {
      array_push($rating_array, $amazon_rating);
    }
    if ($rakuten_rating = get_field("eny_product_rakuten_rating", $id)) {
      array_push($rating_array, $rakuten_rating);
    }
    if ($yahoo_rating = get_field("eny_product_yahoo_rating", $id)) {
      array_push($rating_array, $yahoo_rating);
    }

    // 2020/11/04 auPAYmarketリンク追加対応
    if ($auPAYmarket_rating = get_field("eny_product_auPAYmarket_rating", $id)) {
      array_push($rating_array, $auPAYmarket_rating);
    }
    // 2020/11/04 auPAYmarketリンク追加対応
    if ($official_rating = get_field("eny_product_official_rating", $id)) {
      array_push($rating_array, $official_rating);
    }
    if (count($rating_array) > 0) {
      $rating_ave = round(array_sum($rating_array) / count($rating_array), 2);
    }
    $thumbnail_url =  get_the_post_thumbnail_url($id) ?: get_bloginfo('stylesheet_directory') . '/assets/default.png';
    $item_stars = '<img class="star" src="' . get_template_directory_uri() . '/assets/star.svg" alt="">';
    $item_stars_gray = '<img class="star" src="' . get_template_directory_uri() . '/assets/star-gray.svg" alt="">';
    $index_color = $i < 3 ? "secondary" : "gray";
    $stars_html = "";
    $name = mb_strimwidth($post->post_title, 0, isMobileDevice() ? 28 : 34, "……");
    for ($j = 0; $j < round($rating_ave); $j++) {
      $stars_html .= $item_stars;
    }
    $stars_gray_html = "";
    for ($k = 0; $k < 5 - round($rating_ave); $k++) {
      $stars_gray_html .= $item_stars_gray;
    }
    $_i = $i + 1;

    $html .= <<<HTML
    <tr class="ranking-list__body__row">
      <td class="ranking-list__body__row__img">
        <a href="{$home_url}/products/{$id}">
          <img src="{$thumbnail_url}" alt="">
          <p class="name">{$name}</p>
		  <div class="{$index_color}">
            {$_i}
          </div>
        </a>
      </td>
      <td class="ranking-list__body__row__price">
        <p>
          {$minimum_price}円
        </p>
      </td>
      <td class="ranking-list__body__row__rating">
        {$stars_html}
        {$stars_gray_html}
		<em>
			{$rating_ave}
		</em>
      </td>
      <td class="ranking-list__body__row__link">
HTML;

    foreach ($sites as $site) {
      $img_source = get_template_directory_uri() . '/assets/' . $site['name'] . '.svg';
      $html .= <<<HTML
        <a target="_blank" href="{$site['link']}" rel="nofollow">
          <img src="{$img_source}" alt="">
        </a>
HTML;
    }

    $html .= <<<HTML
      </td>
    </tr>
HTML;
  }

  $html .= <<<HTML
  </tbody>
</table>
HTML;

  return $html;
}
add_shortcode('eny_product_ranking', 'enyProductRankingShortCode');