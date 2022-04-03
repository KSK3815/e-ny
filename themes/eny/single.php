<?php get_header(); ?>

<body id="content">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WQ7JKXS" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div id="container">
        <div class="area">
            <?php include_once("inc_header.php"); ?>
            <div id="main" class="content_left" style="padding-left:0px; padding-right:0px;">
                <?php breadcrumb(); ?>

                <?php while (have_posts()) : the_post(); ?>
                    <div class="hentry post-wrapper" id="main_content">
                        <?php
                        if (have_rows('item_info')) :
                            $count = 0;
                            while (have_rows('item_info')) : the_row();
                                if (get_sub_field('item_name') != '' && get_sub_field('item_image')['url'] != '') :
                                    $count++;
                                    $items5[$count]['item_name'] = get_sub_field('item_name');
                                    $items5[$count]['item_image'] = get_sub_field('item_image')['url'];
                                    if ($count == 5) {
                                        break;
                                    }
                                endif;
                            endwhile;
                            reset_rows();
                        endif;

                        if (count($items5) != 0) :
                            shuffle($items5);
                            ?>
                            <ul class="headitems">
                                <?php foreach ($items5 as $items5_value) : ?>
                                    <li><img src="<?php echo $items5_value['item_image']; ?>" alt="<?php echo $items5_value['item_name']; ?>"></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <div class="title-area">
                            <h1 class="entry-title"><?php the_title(); ?>
                            </h1>
                            <div class="datumo_date fonts date updated"><i class="fa fa-clock-o"></i>
                                <?php the_time('Y/m/d'); ?>
                            </div>
                            <a class="single_category" href="<?php echo get_category_link(get_the_category()[0]->cat_ID); ?>">
                                <?php echo get_the_category()[0]->cat_name; ?>
                            </a>
                        </div>
                        <div class="clear"></div>

                        <div class="content_left_sub" id="main_sub">
                            <?php
                            $the_content = do_shortcode(get_the_content());
                            if (preg_match_all('/<!-- %item_([1-9]+)% -->/', get_the_content(), $return)) :

                                if (have_rows('item_info')) :
                                    $item_number = 0;
                                    while (have_rows('item_info')) : the_row();
                                        $item_number++;
                                        $item_name = get_sub_field('item_name');
                                        $item_price = number_format(get_sub_field('item_price'));
                                        $item_tax = get_sub_field('item_tax') == 'taxinc' ? '税込' : '税抜';
                                        $item_image = get_sub_field('item_image')['url'];
                                        $item_imagesource = get_sub_field('item_imagesource');
                                        $item_imagesourceurl = get_sub_field('item_imagesourceurl');
                                        $item_amazonurl = get_sub_field('item_amazonurl');
                                        $item_rakutenurl = get_sub_field('item_rakutenurl');
                                        // $item_rakutenurl_m = get_sub_field('item_rakutenurl_m');
                                        $item_yahoourl = get_sub_field('item_yahoourl');
                                        $item_detail = get_sub_field('item_detail');
                                        // amazon asociate
                                        // if ( !empty($item_amazonurl) && !preg_match('/mononostore-22/i', $item_amazonurl) ) {
                                        // 	if( preg_match('/dp\/([a-zA-Z0-9]+)[?|\/]?/i', $item_amazonurl, $return_asin) ){
                                        // 		$itemname = $return_asin[1];
                                        // 	} else {
                                        // 		$itemname = $item_name;
                                        // 	}
                                        // 	if ( !empty($itemname) ) {
                                        // 		$getamazon = getlink_Amazon($itemname);
                                        // 		if ( preg_match('/amazon.co.jp/i', $getamazon ) ) {
                                        // 			$item_amazonurl = $getamazon;
                                        // 		}
                                        // 	}
                                        // }
                                        if (!empty($item_amazonurl) && !preg_match('/\/\/af.moshimo.com\//i', $item_amazonurl)) {
                                            // もしも経由Amazonアフィリエイト
                                            $item_amazonurl = "//af.moshimo.com/af/c/click?a_id=1496765&p_id=170&pc_id=185&pl_id=4065&url=" . urlencode($item_amazonurl);
                                        }
                                        if (!empty($item_rakutenurl) && !preg_match('/\/\/af.moshimo.com\//i', $item_rakutenurl)) {
                                            // もしも経由楽天アフィリエイト
                                            $item_rakutenurl = "//af.moshimo.com/af/c/click?a_id=1496764&p_id=54&pc_id=54&pl_id=6036&url=" . urlencode($item_rakutenurl);
                                        }
                                        if (!empty($item_yahoourl) && !preg_match('/\/\/ck.jp.ap.valuecommerce.com\//i', $item_yahoourl)) {
                                            $item_yahoourl = 'https://ck.jp.ap.valuecommerce.com/servlet/referral?sid=3422348&pid=885849747&vc_url=' . urlencode($item_yahoourl);
                                        }
                                        // if ( empty($item_amazonurl) && empty($item_rakutenurl) && empty($item_rakutenurl) ) {
                                        // 	$item_price = '';
                                        // }
                                        // if ( !empty($item_rakutenurl_m) ) {
                                        // 		$item_rakutenurl .= "&m=".urlencode($item_rakutenurl_m);
                                        // 	}
                                        $item_html = '';
                                        $item_html = <<<EOD
		<div class="itemcard">
	    <div class="itemname">{$item_name}</div>
	    <div class="itemcont">
	      <div class="itemimgs">
	        <div class="itemimg">
	        	<img src="{$item_image}" alt="{$item_name}">
	      	</div>
	        <div class="itemimgsc">
		        出典：<a target="_blank" href="{$item_imagesourceurl}">{$item_imagesource}</a>
	        </div>
	      </div>
	      <div class="iteminfo">
EOD;
/** */
                                        if (!empty($item_price)) {
                                            $item_html .= '<p class="fs-20"><b>' . $item_price . '円</b> <span class="fs-12">(' . $item_tax . ')</span></p>';
                                        }
                                        $item_html .= '<ul class="itembtns">';
                                        if (!empty($item_amazonurl)) {
                                            $item_html .= '<li><a target="_blank" href="' . $item_amazonurl . '" rel="nofollow"> <span></span>Amazonで詳細を見る</a></li>';
                                        }
                                        if (!empty($item_rakutenurl)) {
                                            $item_html .= '<li><a target="_blank" href="' . $item_rakutenurl . '" rel="nofollow"> <span></span>楽天で詳細を見る</a></li>';
                                        }
                                        if (!empty($item_yahoourl)) {
                                            $item_html .= '<li><a target="_blank" href="' . $item_yahoourl . '" rel="nofollow"> <span></span>Yahoo!ショッピングで詳細を見る</a></li>';
                                        }
                                        $item_html .= <<<EOD
	        </ul>
	        <dl>
	          <dt><strong>詳細情報</strong></dt>
	          <dd>
		          {$item_detail}
	          </dd>
	        </dl>
	      </div>
	    </div>
    </div>
EOD;

                                        $the_content = str_replace("<!-- %item_{$item_number}% -->", $item_html, $the_content);
                                    endwhile;
                                endif;
                            endif;


                            // 監修者
                            $editor_html = '';
                            if ($user_description = get_the_author_meta('user_description')) :
                                $user_avatar = get_avatar(get_the_author_meta('ID'), 150);
                                $user_nickname = get_the_author_meta('nickname');
                                $user_url = home_url() . "/experts/" . get_the_author_meta('ID');
                                $editor_html = <<<EOD
						<div class="author-area">
							<div class="author-img">
								<a href="{$user_url}">{$user_avatar}</a>
							</div>
							<div class="author-detail">
								<p class="author-name"><strong><a href="{$user_url}">{$user_nickname}</a></strong></p>
								<p>{$user_description}</p>
							</div>
						</div>
EOD;
                            endif;

                            if (preg_match_all('/<!-- %editor% -->/', get_the_content(), $return)) :
                                $the_content = str_replace("<!-- %editor% -->", $editor_html, $the_content);
                                echo $the_content;
                            else :
                                echo $the_content;
                                echo $editor_html;
                            endif;


                        endwhile;

                        /* include("sns_single.php"); */
                        ?>
                        <div class="copy_btn" data-clipboard-text="<?php the_title();  ?> │ <?php the_permalink();  ?>">この記事のURLとタイトルをコピーする</div>
                    </div><!-- hentry -->

                    <div class="clear"></div>
                </div>
                <?php include("category_posts.php"); ?>

            </div><!-- content_left -->

            <div class="content_right">
                <?php get_sidebar(); ?>
            </div><!-- content_right -->

            <div class="clear"></div>

        </div><!-- area -->
    </div><!-- container -->
    <?php get_footer(); ?>
    <script>
        var clipboard = new Clipboard('.copy_btn'); //clipboard.min.jsが動作する要素をクラス名で指定

        //クリックしたときの挙動
        $(function() {
            $('.copy_btn').click(function() {
                $(this).addClass('copied');
                $(this).text('コピーしました');
            });
        });
    </script>
</body>

</html>
