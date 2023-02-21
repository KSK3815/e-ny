<?php
  $min_price_site = null;
  $sites = array();
  if ($amazon_price = get_field("eny_product_amazon_price", $item->ID)) {
      $amazon = array(
      "name" => "amazon",
      "price" => $amazon_price,
      "link" => !empty(get_field("eny_product_amazon_link", $item->ID)) ? getAffiliateLink(get_field("eny_product_amazon_link", $item->ID), 'amazon') : null,
      "icon" => get_template_directory_uri() . '/assets/amazon-2nd.png'
    );
      $min_price_site = $amazon;
      array_push($sites, $amazon);
  }
  if ($rakuten_price = get_field("eny_product_rakuten_price", $item->ID)) {
      $rakuten_link = null;
      if (get_field("eny_product_rakuten_search_way", $item->ID) == "キーワード") {
          $rakuten_link = getAffiliateLink("https://search.rakuten.co.jp/search/mall/". urlencode(get_field("eny_product_rakuten_keyword", $item->ID)), 'rakuten');
      } else {
          $rakuten_link = !empty(get_field("eny_product_rakuten_link", $item->ID)) ? getAffiliateLink(get_field("eny_product_rakuten_link", $item->ID), 'rakuten') : null;
      }
      $rakuten = array(
      "name" => "rakuten",
      "price" => $rakuten_price,
      "link" => $rakuten_link,
      "icon" => get_template_directory_uri() . '/assets/rakuten-2nd.png'

    );
      if (!$min_price_site) {
          $min_price_site = $rakuten;
      } elseif ($min_price_site['price'] > $rakuten['price']) {
          $min_price_site = $rakuten;
      }
      array_push($sites, $rakuten);
  }
  if ($yahoo_price = get_field("eny_product_yahoo_price", $item->ID)) {
      $yahoo_link = null;
      if (get_field("eny_product_yahoo_search_way", $item->ID) == "キーワード") {
          $yahoo_link = getAffiliateLink("https://shopping.yahoo.co.jp/search?p=" . urlencode(get_field("eny_product_yahoo_keyword", $item->ID)), 'yahoo');
      } else {
          $yahoo_link = !empty(get_field("eny_product_yahoo_link", $item->ID)) ? getAffiliateLink(get_field("eny_product_yahoo_link", $item->ID), 'yahoo') : null;
      }
      $yahoo = array(
      "name" => "yahoo",
      "price" => $yahoo_price,
      "link" => $yahoo_link,
      "icon" => get_template_directory_uri() . '/assets/yahoo-2nd.png'
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
  if ($amazon_rating = get_field("eny_product_amazon_rating", $item->ID)) {
      array_push($rating_array, $amazon_rating);
  }
  if ($rakuten_rating = get_field("eny_product_rakuten_rating", $item->ID)) {
      array_push($rating_array, $rakuten_rating);
  }
  if ($yahoo_rating = get_field("eny_product_yahoo_rating", $item->ID)) {
      array_push($rating_array, $yahoo_rating);
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
  $thumbnail_url =  get_the_post_thumbnail_url($item->ID)? : get_bloginfo('stylesheet_directory').'/assets/default.png';
  $item_stars = '<img class="star" src="' . get_template_directory_uri() . '/assets/star.svg" alt="">';
  $item_stars_gray = '<img class="star" src="' . get_template_directory_uri() . '/assets/star-gray.svg" alt="">';
  $maker_info = get_the_terms($item->ID, 'eny_product_maker');
?>

<article class="item-list">
	<a class="item-list__image" href="<?php echo get_permalink($item->ID); ?>">
		<img src="<?php echo $thumbnail_url ?>" alt="">
	</a>

	<div class="item-list__details">
		<?php if ($maker_info && count($maker_info) > 0) : ?>
		<em class="item-list__details__maker">
			<?php echo $maker_info[0]->name ?>
		</em>
		<?php endif;?>

		<a class="item-list__details__name" href="<?php echo get_permalink($item->ID); ?>">
			<h1>
				<?php echo $item->post_title; ?>
			</h1>
		</a>

		<?php if ($min_price_site) : ?>
		<p class="item-list__details__price">
			<?php
        echo '最安値';
        echo '<strong>' . number_format($min_price_site['price']) . '円</strong>';
      ?>
		</p>
		<?php endif ;?>

		<?php if ($rating_ave) :?>
		<p class="item-list__details__rate">
			<?php echo str_repeat($item_stars, round($rating_ave)) ?>
			<?php echo str_repeat($item_stars_gray, 5 - round($rating_ave)) ?>
			<strong><?php echo round($rating_ave, 2) ?></strong>
			<?php if ($review_count_sum): ?>
			<span>(<?php echo $review_count_sum; ?>)</span>
			<?php endif; ?>
		</p>
		<?php endif; ?>

		<?php if (count($sites) > 0) : ?>
		<div class="item-list__details__shops">
			<?php foreach ($sites as $site) : ?>
			<a target="_blank" rel="nofollow" href="<?php echo !empty($site['link']) ? $site['link'] : "#"; ?>">
				<img src="<?php echo $site['icon']; ?>" alt="">
			</a>
			<?php endforeach ?>
		</div>
		<?php endif; ?>
	</div>
</article>
