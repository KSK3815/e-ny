<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Eny_Media
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>


	<?php if (get_post_meta($post->ID, '難しさ', true)) : ?>
		<?php if (get_post_meta($post->ID, '難しさ', true) == "初心者向け") : ?>
			<div class="difficulty-label">
				<img src="<?php echo get_template_directory_uri(); ?>/assets/icon-beginner.png" alt="">For Beginners
			</div>
		<?php elseif (get_post_meta($post->ID, '難しさ', true) == "アマチュア") : ?>
			<div class="difficulty-label amateur">
				For Amateurs
			</div>
		<?php else : ?>
			<div class="difficulty-label expert">
				For Experts
			</div>
		<?php endif; ?>
	<?php endif; ?>


	<?php 
		if ( has_post_thumbnail( $post->ID ) ) {
			eny_media_post_thumbnail();
		} else { ?>
			<img class="post-thumbnail" src="<?php get_bloginfo('stylesheet_directory') . '/assets/default.png' ?>">
		<?php } 
	?>


	<header class="entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta">
				<?php
				eny_media_posted_on();
				?>
			</div><!-- .entry-meta -->
			<div class="entry-author">
				<?php $curauth = get_userdata($wp_query->post->post_author); ?>
				<a href="<?php echo home_url(); ?>/author/<?php echo $curauth->nickname; ?>" class="author-avatar">
					<?php echo get_avatar( $curauth->user_email , '90 ');?>
				</a>
				<div class="author-preview">
					<p class="author-position">
						<?php echo get_the_author_meta('position', $curauth->id);?>
					</p>
					<p class="author-name">
						<a href="<?php echo home_url(); ?>/author/<?php echo $curauth->nickname; ?>">
							<?php echo $curauth->display_name;?>
						</a>
						<img src="<?php echo get_template_directory_uri(); ?>/assets/badge-authorized.png" alt="">
					</p>
					<p class="author-oneliner"><?php echo substr($curauth->user_description, 0, 61), "..."; ?></p>
				</div>
			</div>
		<?php endif; ?>
	</header><!-- .entry-header -->



	<div class="content_left_sub" id="main_sub">
		<?php
			$the_content = do_shortcode(get_the_content());
			if (preg_match_all('/<!-- %item_([1-9]+)% -->/', get_the_content(), $return)) :


				if (have_rows('item_info')) :
					$item_number = 0;


					while (have_rows('item_info')) : 
						the_row();
						$item_number++;
						$item_name = get_sub_field('item_name');
						$item_price = number_format(get_sub_field('item_price'));
						$item_tax = get_sub_field('item_tax') == 'taxinc' ? '税込' : '税抜';
						if ($item_image = get_sub_field('item_image')['url']) {
							$item_image = get_sub_field('item_image')['url'];
						}
						else {
							$item_image = get_template_directory_uri() . "/assets/default.png";
						}
						$item_imagesource = get_sub_field('item_imagesource');
						$item_imagesourceurl = get_sub_field('item_imagesourceurl');
						$item_amazonurl = get_sub_field('item_amazonurl');
						$item_rakutenurl = get_sub_field('item_rakutenurl');
						// $item_yahoourl = get_sub_field('item_yahoourl');

						$item_searchkey = get_sub_field('item_searchkey');
						if ($item_searchkey) :
							$item_yahoourl = 'https://shopping.yahoo.co.jp/search;_ylt=A2RmPknzeANdEEUAWWGlKdhE?p=' . $item_searchkey;
						else :
							$item_yahoourl = get_sub_field('item_yahoourl');
						endif;
						$item_detail = get_sub_field('item_detail');
						$item_cheapest = get_sub_field('item_cheapest');
						$item_rating = get_sub_field('item_rating');
						$item_stars = '<img class="star" src="' . get_template_directory_uri() . '/assets/star.svg" alt="">';
						$item_stars_gray = '<img class="star" src="' . get_template_directory_uri() . '/assets/star-gray.svg" alt="">';
						$item_reviewno = get_sub_field('item_reviewno');
						if (!empty($item_amazonurl) && !preg_match('/\/\/af.moshimo.com\//i', $item_amazonurl)) {
							$item_amazonurl = "//af.moshimo.com/af/c/click?a_id=1496765&p_id=170&pc_id=185&pl_id=4065&url=" . urlencode($item_amazonurl);
						}
						if (!empty($item_rakutenurl) && !preg_match('/\/\/af.moshimo.com\//i', $item_rakutenurl)) {
							$item_rakutenurl = "//af.moshimo.com/af/c/click?a_id=1496764&p_id=54&pc_id=54&pl_id=6036&url=" . urlencode($item_rakutenurl);
						}
						if (!empty($item_yahoourl) && !preg_match('/\/\/ck.jp.ap.valuecommerce.com\//i', $item_yahoourl)) {
							$item_yahoourl = 'https://ck.jp.ap.valuecommerce.com/servlet/referral?sid=3422348&pid=885849747&vc_url=' . urlencode($item_yahoourl);
						}
						$item_html = '';

$item_html = <<<EOD
<div class="itemcard">
	<div class="itemname">{$item_name}</div>
	<div class="itemcont">
		<div class="itemflex">
			<div class="itemimgs col-12 col-sm-6">
				<div class="itemimg">
					<img src="{$item_image}" alt="{$item_name}">
				</div>
				<div class="itemimgsc">
					出典：<a target="_blank" href="{$item_imagesourceurl}">{$item_imagesource}</a>
				</div>
			</div>
			<div class="iteminfo col-12 col-sm-5">
EOD;

						$item_html .= '<p class="fs-20">';
						if (!empty($item_cheapest)) {
							if ($item_cheapest == 'Amazon') :
								$item_html .= '<img src="' . get_template_directory_uri() . '/assets/amazon.svg" alt="">';
							elseif ($item_cheapest == '楽天') :
								$item_html .= '<img src="' . get_template_directory_uri() . '/assets/rakuten.svg" alt="">';
							elseif ($item_cheapest == 'Yahoo!') :
								$item_html .= '<img src="' . get_template_directory_uri() . '/assets/yahoo.svg" alt="">';
							endif;
							$item_html .= '<span class="fs-12">最安値</span>';
						}
						if (!empty($item_price)) {
							$item_html .= '<b>' . $item_price . '円</b> <span class="fs-12">(' . $item_tax . ')</span></p>';
						}
						if (!empty($item_rating)) {
							$item_html .= '<p class="fs-21">' . str_repeat( $item_stars, round($item_rating) ) . str_repeat( $item_stars_gray, 5 - round($item_rating) ) . '<b class="rating">' . $item_rating . '</b>';
						}
						if (!empty($item_reviewno)) {
							$item_html .= '(' . $item_reviewno . '件)</p>';
						}
						$item_html .= '<div class="itembtns">';
						if (!empty($item_amazonurl)) {
							$item_html .= '<div class="secondary-btn"><a target="_blank" href="' . $item_amazonurl . '" rel="nofollow"> <span></span>Amazonで購入</a></div>';
						}
						if (!empty($item_rakutenurl)) {
							$item_html .= '<div class="secondary-btn"><a target="_blank" href="' . $item_rakutenurl . '" rel="nofollow"> <span></span>楽天で購入</a></div>';
						}
						if (!empty($item_yahoourl)) {
							$item_html .= '<div class="secondary-btn"><a target="_blank" href="' . $item_yahoourl . '" rel="nofollow"> <span></span>Yahoo!ショッピングで購入</a></div>';
						}

$item_html .= <<<EOD
			</div>
		</div>
	</div>
	<div class="details">
		<strong>詳細情報</strong>
		<div class="data">
			{$item_detail}
		</div>
	</div>
</div>
</div>
EOD;

						$the_content = str_replace("<!-- %item_{$item_number}% -->", $item_html, $the_content);
					endwhile;


				endif;


			endif; 

			if (preg_match_all('/<!-- %internal_link% -->/', get_the_content(), $return)) :
				if (get_field('article_id')) :
					$article_id = get_field('article_id');
					$article_title = get_the_title($article_id);
					$article_thumburl = get_the_post_thumbnail_url($article_id);
					$article_url = get_permalink($article_id);
					$article_preview = mb_strimwidth(get_the_content($article_id), 0, 235, '...');
					$article_preview = strip_tags($article_preview);

$item_html = <<<EOD
<a href="{$article_url}">
	<div class="internal-link">
		<div class="link-thumb">
				<img src="{$article_thumburl}" alt="">
		</div>
		<div class="link-details">
			<p class="title">{$article_title}</p>
			<p class="preview">{$article_preview}</p>
		</div>
	</div>
</a>
EOD;
					$linked_content = str_replace("<!-- %internal_link% -->", $item_html, $the_content);
				endif;
			endif; 
		?>

	<div class="entry-content">
		<?php
		if (preg_match_all('/<!-- %internal_link% -->/', get_the_content(), $return)) :
		$linked_content = str_replace("]]&gt;", " ", $linked_content);
		echo $linked_content;
		else :
		$the_content = str_replace("]]>", " ", $the_content);
		echo $the_content;
		endif;


		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'eny-media' ),
			'after'  => '</div>',
		) );
		?>

		<?php if (function_exists('dw_reactions')) { dw_reactions(); } ?>
		<div class="separator">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/separator.svg" alt="">
		</div>
		<div class="btm-entry-author">
			<?php $curauth = get_userdata($wp_query->post->post_author); ?>
			<a href="<?php echo home_url(); ?>/author/<?php echo $curauth->nickname; ?>" class="author-avatar">
				<?php echo get_avatar( $curauth->user_email , '90 ');?>
			</a>
			<div class="author-preview">
				<p class="author-position">
					<?php echo get_the_author_meta('position', $curauth->id);?>
				</p>
				<p class="author-name">
					<a href="<?php echo home_url(); ?>/author/<?php echo $curauth->nickname; ?>">
						<?php echo $curauth->display_name;?>
					</a>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/badge-authorized.png" alt="">
				</p>
				<p class="author-oneliner"><?php echo mb_strimwidth($curauth->user_description, 0, 225), "..."; ?></p>
			</div>
		</div>
		<p class="author-oneliner mobile"><?php echo mb_strimwidth($curauth->user_description, 0, 90), "..."; ?></p>

	</div><!-- .entry-content -->

	<?php //related_posts(); ?>

</article><!-- #post-<?php the_ID(); ?> -->
