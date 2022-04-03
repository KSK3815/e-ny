<?php
if ( !is_paged() ) {
	// 左上
	$myposts = get_posts('meta_key=pickup&meta_value=1&order=DESC&numberposts=1');
	foreach($myposts as $post) {
		$title = $post->post_title;
		$id = $post->ID;
		$link = get_the_permalink($post->ID);
		$img = get_thumbnail_src($post->ID, 'large');
		$pickup = get_post_meta($post->ID, 'pickup', true);
		$cat = get_the_category();
		$cat_name = $cat[0]->cat_name;
		echo "
		<a href=".$link." class='block hover'>
			<div class='pickup_box".$pickup."'>
				<img class='cover' src='".get_template_directory_uri()."/images/spacer_pickup_sq.png' style='background-image: url(".$img.")'>
				<h2 class='fontll'>".$title."</h2>
				<span>".$cat_name."</span>
			</div>
		</a>";
	}
	// 右上
	$myposts = get_posts('meta_key=pickup&meta_value=2&order=DESC&numberposts=1');
	foreach($myposts as $post) {
		$title = $post->post_title;
		$id = $post->ID;
		$link = get_the_permalink($post->ID);
		$img = get_thumbnail_src($post->ID, 'large');
		$pickup = get_post_meta($post->ID, 'pickup', true);
		$cat = get_the_category();
		$cat_name = $cat[0]->cat_name;
		echo "
		<a href=".$link." class='block hover'>
			<div class='sp_position'>
				<div class='pickup_box".$pickup."'>
					<img class='cover' src='".get_template_directory_uri()."/images/spacer_pickup_sq.png' style='background-image: url(".$img.")'>
					<h2 class='fontll'>".$title."</h2>
					<span>".$cat_name."</span>
				</div>
			</div>
		</a>";
	}
	// 左下
	$myposts = get_posts('meta_key=pickup&meta_value=3&order=DESC&numberposts=1');
	foreach($myposts as $post) {
		$title = $post->post_title;
		$id = $post->ID;
		$link = get_the_permalink($post->ID);
		$img = get_thumbnail_src($post->ID, 'large');
		$pickup = get_post_meta($post->ID, 'pickup', true);
		$cat = get_the_category();
		$cat_name = $cat[0]->cat_name;
		echo "
		<a href=".$link." class='block hover'>
			<div class='pickup_box".$pickup."'>
				<img class='cover' src='".get_template_directory_uri()."/images/spacer_pickup_sq_m.png' style='background-image: url(".$img.")'>
				<h2 class='fontl'>".$title."</h2>
				<span>".$cat_name."</span>
			</div>
		</a>";
	}
	// 中央下
	$myposts = get_posts('meta_key=pickup&meta_value=4&order=DESC&numberposts=1');
	foreach($myposts as $post) {
		$title = $post->post_title;
		$id = $post->ID;
		$link = get_the_permalink($post->ID);
		$img = get_thumbnail_src($post->ID, 'large');
		$pickup = get_post_meta($post->ID, 'pickup', true);
		$cat = get_the_category();
		$cat_name = $cat[0]->cat_name;
		echo "
		<a href=".$link." class='block hover'>
			<div class='pickup_box".$pickup."'>
				<img class='cover' src='".get_template_directory_uri()."/images/spacer_pickup_sq_m.png' style='background-image: url(".$img.")'>
				<h2 class='fontl'>".$title."</h2>
				<span>".$cat_name."</span>
			</div>
		</a>";
	}
	// 右下
	$myposts = get_posts('meta_key=pickup&meta_value=5&order=DESC&numberposts=1');
	foreach($myposts as $post) {
		$title = $post->post_title;
		$id = $post->ID;
		$link = get_the_permalink($post->ID);
		$img = get_thumbnail_src($post->ID, 'large');
		$pickup = get_post_meta($post->ID, 'pickup', true);
		$cat = get_the_category();
		$cat_name = $cat[0]->cat_name;
		echo "
		<a href=".$link." class='block hover'>
			<div class='pickup_box".$pickup."'>
				<img class='cover' src='".get_template_directory_uri()."/images/spacer_pickup_sq_m.png' style='background-image: url(".$img.")'>
				<h2 class='fontl'>".$title."</h2>
				<span>".$cat_name."</span>
			</div>
		</a>";
	}
}
?>
<div class="clear"></div>