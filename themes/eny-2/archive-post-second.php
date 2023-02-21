<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Eny_Media
 */

get_header();
$home_url = home_url();

$sql = "
  SELECT
    p.*
  FROM
    {$wpdb->prefix}most_popular mp
    RIGHT OUTER JOIN {$wpdb->prefix}posts p ON mp.post_id = p.ID
  WHERE
    p.post_type = 'post' AND
    p.post_status = 'publish'
  ORDER BY 7_day_stats IS NULL ASC, 7_day_stats DESC, post_date_gmt DESC
  LIMIT 24
  ";
$posts = $wpdb->get_results($sql);
global $post;

$cg_tree[] = array(
  'name' => '人気記事一覧'
);
$current_hierarchy = 0;
?>

<main class="container">

  <?php include 'template-parts/breadcrumbs.php' ?>

  <section class="">
    <h1 class="u-page-title">
      人気記事一覧
    </h1>

    <ul class="row u-list-style-none">

      <?php
          if (count($posts) > 0) :
            for ($i=0; $i < count($posts); $i++) :
              $post = $posts[$i];
              setup_postdata($post);
            ?>
      <li class="col-lg-6 u-margin-bottom-md">
        <?php if ($i < 3): ?>
        <div class='ranking-badge ranking-badge--external ranking-badge--top'>
          <?php echo $i+1 ?>
        </div>
        <?php else : ?>
        <div class='ranking-badge ranking-badge--external'>
          <?php echo $i+1 ?>
        </div>
        <?php endif; ?>

        <?php include 'template-parts/card-article.php' ?>
      </li>
      <?php
            endfor;
            wp_reset_postdata();
          else:
            get_template_part('template-parts/content', 'none');
          endif;
        ?>

    </ul>
  </section>

</main><!-- #main -->

<?php
get_footer();
