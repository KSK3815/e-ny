<?php
$id = $item['id'];
// $image_url = z_taxonomy_image_url( $item['id'], NULL, TRUE );
$short_name = mb_strimwidth($item['name'], 0, 28, "...");
$args = array(
    'post_type' => 'eny_product',
    'numberposts' => '1',
    'orderby'          => 'date',
    'order'            => 'ASC',
    'tax_query' => array(
        array(
            'taxonomy' => 'category',
            'field' => 'id',
            'terms' => $id
        )
    )
);
$post_in_this_term = get_posts($args);
// $thumbnail_url = ($post_in_this_term[0]->ID && get_the_post_thumbnail_url($post_in_this_term[0]->ID)) ? get_the_post_thumbnail_url($post_in_this_term[0]->ID, 'thumbnail') : get_template_directory_uri().'/assets/default.png';
$thumbnail_url =  ($post_in_this_term[0]->ID && wp_get_attachment_image_src( get_post_thumbnail_id($post_in_this_term[0]->ID), array(100, 100) )) ? wp_get_attachment_image_src( get_post_thumbnail_id($post_in_this_term[0]->ID), array(100, 100) ) : array(get_template_directory_uri().'/assets/default.png');
?>
<div class="category-card">
  <img class="category-card__img" src="<?php echo $thumbnail_url[0] ?>">
  <p class="category-card__label"><?php echo $short_name ?></p>
</div>