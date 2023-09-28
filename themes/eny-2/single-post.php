<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Eny_Media
 */

get_header();

$cat = get_the_category();
$cg_tree = get_category_tree($cat[0]->term_id);
$args = array(
  'term_id' => $cat[0]->term_id,
  'taxonomy' => 'category'
);
$current_hierarchy = get_current_hierarchy($args);
$pointer = &$cg_tree;
for ($i = 0; $i < $current_hierarchy + 1; $i++) {
    $pointer = &$pointer[0]['cr'];
}
$pointer[0] = array(
  'name' => $post->post_title
);
unset($pointer);
$current_hierarchy += 1;

$template_directory = get_template_directory_uri();
$stylesheet_directory = get_bloginfo('stylesheet_directory');
$thumbnail_url =  get_the_post_thumbnail_url(get_the_id()) ?: $stylesheet_directory . '/assets/default.png';
$thumbnail_size = getimagesize($thumbnail_url);

$the_content = apply_filters('the_content', get_the_content());
if (preg_match_all('/<!-- %item_([1-9]+)% -->/', get_the_content(), $return)) :
  if (have_rows('item_info')) :
    $item_number = 0;
    while (have_rows('item_info')) :
      the_row();
      $item_number++;
      $item_name = get_sub_field('item_name');
      $item_price = !empty(get_sub_field('item_price')) ? number_format(get_sub_field('item_price')) : "";
      $item_tax = get_sub_field('item_tax') == 'taxinc' ? '税込' : '税抜';
      $item_image = get_sub_field('item_image') ? get_sub_field('item_image')['url'] : $template_directory . "/assets/default.png";
      $item_imagesource = get_sub_field('item_imagesource');
      $item_imagesourceurl = get_sub_field('item_imagesourceurl');
      // アイテムリンク
      $item_amazonurl = !empty(get_sub_field('item_amazonurl')) ? getAffiliateLink(get_sub_field('item_amazonurl'), 'amazon') : null;
      $item_rakutenurl = !empty(get_sub_field('item_rakutenurl')) ? getAffiliateLink(get_sub_field('item_rakutenurl'), 'rakuten') : null;
      $item_yahoourl = !empty(get_sub_field('item_yahoourl')) ? getAffiliateLink(get_sub_field('item_yahoourl'), 'yahoo') : null;
      // $item_searchkey = get_sub_field('item_searchkey');
      // if ($item_searchkey) :
      // 	$item_yahoourl = 'https://shopping.yahoo.co.jp/search;_ylt=A2RmPknzeANdEEUAWWGlKdhE?p=' . $item_searchkey;
      // else :
      // 	$item_yahoourl = get_sub_field('item_yahoourl');
      // endif;
      $item_detail = get_sub_field('item_detail');
      $item_cheapest = get_sub_field('item_cheapest');
      $item_rating = get_sub_field('item_rating');
      $item_stars = '<img class="star" src="' . $template_directory . '/assets/star.svg" alt="">';
      $item_stars_gray = '<img class="star" src="' . $template_directory . '/assets/star-gray.svg" alt="">';
      $item_reviewno = get_sub_field('item_reviewno');
      $item_permalink = get_permalink($id);

      $item_html = <<<HTML
<section class="itemcard">
  <div class="itemcard__content">
    <figure class="itemcard__content__img">
        <img src="{$item_image}" alt="{$item_name}">
      <figcaption>
        出典：<a target="_blank" href="{$item_imagesourceurl}">{$item_imagesource}</a>
	    </figcaption>
	  </figure>
    <div class="itemcard__content__info">
HTML;
/** 2020/09/17 stailor inc. Tatsukawa廃止
      if (!empty($item_price)) :
        $link_image = "";
        if (!empty($item_cheapest)) {
            if ($item_cheapest == 'Amazon') {
                $link_image = $template_directory . '/assets/amazon.svg';
            } elseif ($item_cheapest == '楽天') {
                $link_image = $template_directory . '/assets/rakuten.svg';
            } elseif ($item_cheapest == 'Yahoo!') {
                $link_image = $template_directory . '/assets/yahoo.svg';
            }
        }
        $item_html .= <<<HTML
      <em class="itemcard__content__info__price">
HTML;

      if (!empty($item_cheapest)) :
      $item_html .= <<<HTML
        <img src="{$link_image}">
HTML;
      $item_html .= <<<HTML
        <span>最安値</span>
        {$item_price}円
        <span>({$item_tax})</span>
      </em>
HTML;
        endif;
      endif;
**/

      if (!empty($item_rating)) :
        $stars_html = "";
        for ($i = 0; $i < round($item_rating); $i++) {
            $stars_html .= $item_stars;
        }
        $stars_gray_html = "";
        for ($i = 0; $i < 5 - round($item_rating); $i++) {
            $stars_gray_html .= $item_stars_gray;
        }
        $review_count_html = empty($item_reviewno) ?  "" : '(' . $item_reviewno . '件)';
        $item_html .= <<<HTML
      <em>
        {$stars_html}
        {$stars_gray_html}
        <span class="rating">{$item_rating}</span>
        {$review_count_html}
      </em>
HTML;
      endif;

      if (!(empty($item_amazonurl) && empty($item_rakutenurl) && empty($item_yahoourl))) :
        $amazon_btn_html = empty($item_amazonurl) ? "" : <<<HTML
      <a target="_blank" href="{$item_amazonurl}" rel="noopener noreferrer">
        <button class="button button--solid" type="button">
          <p>Amazonで詳細を見る。</p>
        </button>
      </a>
HTML;
      $rakuten_btn_html = empty($item_rakutenurl) ? "" : <<<HTML
      <a target="_blank" href="{$item_rakutenurl}" rel="noopener noreferrer">
        <button class="button button--solid" type="button">
          <p>楽天で詳細を見る</p>
        </button>
      </a>
HTML;
      $yahoo_btn_html = empty($item_yahoourl) ? "" : <<<HTML
      <a target="_blank" href="{$item_yahoourl}" rel="noopener noreferrer">
        <button class="button button--solid" type="button">
          <p>Yahoo!ショッピングで詳細を見る</p>
        </button>
      </a>
HTML;
      $item_html .= <<<HTML
      <div class="itemcard__content__info__shop">
        {$amazon_btn_html}
        {$rakuten_btn_html}
        {$yahoo_btn_html}
      </div>
HTML;
      endif;

      $item_html .= <<<HTML
    </div>
  </div>
  <section class="itemcard__details">
    <em class="itemcard__details__title">詳細情報</em>
    <div class="itemcard__details__data">
      {$item_detail}
    </div>
	</section>
</section>
HTML;

      $the_content = str_replace("<!-- %item_{$item_number}% -->", $item_html, $the_content);
    endwhile;
  endif;
endif;
?>

<!-- single content start -->
<article class="article container">
  <div class="article-wrapper">
    <div class="promotion-notice"><p style="color: #bbb;font-size: 13px;margin-bottom: 20px;">※本サイト（記事）にはプロモーションが含まれています。</p></div>

    <?php
    while (have_posts()) :
      include 'template-parts/breadcrumbs.php';
      the_post();
    ?>

    <?php
      if (get_field('post_difficulty')) {
          if (get_field('post_difficulty') == "初心者") : ?>
    <div class="difficulty-label">
      <img src="<?php echo get_template_directory_uri(); ?>/assets/icon-beginner.png" alt="">初心者向け
    </div>
    <?php elseif (get_field('post_difficulty') == "アマチュア") : ?>
    <div class="difficulty-label amateur">
      アマチュア向け
    </div>
    <?php elseif (get_field('post_difficulty') == "プロ") : ?>
    <div class="difficulty-label expert">
      プロ向け
    </div>
    <?php endif;
      } ?>

    <div class="article__eyecatch">
      <p><?php echo ('hogehoge'); ?></p>
      <p><?php echo (($post->ID)); ?></p>
      <p><?php echo ($thumbnail_url); ?></p>
      <p><?php echo ($thumbnail_size); ?></p>
      <p><?php echo (has_post_thumbnail($post->ID)); ?></p>
      <?php
    if (has_post_thumbnail($post->ID)) {
        if ($thumbnail_size[0] / $thumbnail_size[1] < 1.45) {
            echo '<div class="contain">';
            eny_media_post_thumbnail();
            echo '</div>';
        } else {
            eny_media_post_thumbnail();
        }
    } else {
        echo '<img class="post-thumbnail" src="' . get_bloginfo('stylesheet_directory')
      . '/assets/default.png" />';
    }
    ?>
    </div>


    <header class="article__header">
      <h1 class="article__header__title">
        <?php the_title(); ?>
      </h1>

      <div class="article__header__date">
        <i class="fas fa-pen"></i>
        <?php eny_media_posted_on(); ?>
      </div>

      <a class="article__header__author" href="<?php echo get_author_posts_url($wp_query->post->post_author); ?>">
        <?php $curauth = get_userdata($wp_query->post->post_author); ?>
        <div class="article__header__author__avator">
          <?php echo get_avatar($curauth->user_email, 256); ?>
        </div>
        <section class="article__header__author__preview">
          <?php
            $author_position = get_the_author_meta('position', $curauth->id);
            if ($author_position):
          ?>
          <strong>
            <?php echo $author_position; ?>
          </strong>
          <?php endif; ?>

          <h1>
            <span><?php echo $curauth->display_name; ?></span>

            <?php
            if (get_field('eny_user_label', 'user_'.$curauth->id)):
            ?>
            <img src="<?php echo get_template_directory_uri(); ?>/assets/badge-authorized.png" alt="">
            <?php endif; ?>
          </h1>

          <p>
            <?php
          if (mb_strlen($curauth->user_description, 'UTF-8')>80) {
              echo mb_substr($curauth->user_description, 0, 80, 'UTF-8').'……';
          } else {
              echo $curauth->user_description;
          }
          ?>
          </p>
        </section>
      </a>
    </header>

    <main class="article__content">

      <?php echo $the_content ?>

    </main><!-- .entry-content -->

    <a class="article__writer article__header__author" href="<?php echo get_author_posts_url($wp_query->post->post_author); ?>">
      <?php $curauth = get_userdata($wp_query->post->post_author); ?>
      <div class="article__header__author__avator">
        <?php echo get_avatar($curauth->user_email, 256); ?>
      </div>
      <section class="article__header__author__preview">
        <?php
            $author_position = get_the_author_meta('position', $curauth->id);
            if ($author_position):
          ?>
        <strong>
          <?php echo $author_position; ?>
        </strong>
        <?php endif; ?>

        <h1>
          <span><?php echo $curauth->display_name; ?></span>

          <?php if (get_field('eny_user_label', 'user_'.$curauth->id)): ?>
          <img src="<?php echo get_template_directory_uri(); ?>/assets/badge-authorized.png" alt="">
          <?php endif; ?>
        </h1>

        <p>
          <?php
          if (mb_strlen($curauth->user_description, 'UTF-8')>80) {
              echo mb_substr($curauth->user_description, 0, 80, 'UTF-8').'……';
          } else {
              echo $curauth->user_description;
          }
          ?>
        </p>
      </section>
    </a>

    <?php
    $args = array(
      'term_id' => $cat[0]->term_id,
      'limit' => 4
    );
    $results = get_posts_in_term($args);
    if (count($results) > 0) :
      ?>
    <aside class="article__related-posts">
      <h1 class="article__related-posts__title">関連記事</h1>

      <ul class="article__related-posts__articles">

        <?php foreach ($results as $article) :
        $article = get_post($article->ID);
        $article_cat = get_the_category($article->ID)[0];
        $article_thumbnail_url = empty(get_the_post_thumbnail_url($article->ID)) ? $template_directory . "/assets/default.png" : get_the_post_thumbnail_url($article->ID);
        ?>

        <li>
          <div class="related-article">
            <a class="related-article__thumbnail" href="<?php echo get_permalink($article->ID) ?>" rel="bookmark" title="">
              <img src="<?php echo $article_thumbnail_url ?>">
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
                $text = mb_strimwidth($article->post_content, 0, 240, '……');
                $text = strip_tags($text);
                $text = strip_shortcodes($text);
                if (strpos($text, '[') !== false) {
                    $text = mb_strimwidth(content_excerpt(get_post($post->ID)->post_content, 240), 0, 65, '……');
                }
                echo $text
                ?>
              </p>
            </div>
          </div>
        </li>
        <?php endforeach; ?>

      </ul>
    </aside>
    <?php endif; ?>

  </div>
</article>

<script>
<?php $curauth = get_userdata($wp_query->post->post_author); ?>

if (document.querySelectorAll('.js-writer-comment-avatar')) {
	const writerImg = document.querySelectorAll('.js-writer-comment-avatar')

	for (const image of writerImg) {
		if (!image.getAttribute('src')) {
		    image.setAttribute('src', '<?php echo get_avatar_url($curauth->user_email); ?>')
		}
	}
}
</script>

<?php endwhile; ?>

<?php
get_footer();
