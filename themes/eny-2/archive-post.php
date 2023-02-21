<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Eny_Media
 */

get_header();

$home_url = home_url();

$cg_tree = get_category_tree($cat);

$args = array(
  'term_id' => $cat,
  'taxonomy' => 'category'
);
$current_hierarchy = get_current_hierarchy($args);
$current_cat = get_category($cat);

$sort_param = $_GET['sort']? : "new";
$sort_query = "";
switch ($sort_param) {
  case 'new':
    $sort_query = "ORDER BY p.post_date DESC";
  break;
  case 'popular_dayly':
    $sort_query = "ORDER BY 1_day_stats IS NULL ASC, 1_day_stats DESC, post_date_gmt DESC";
  break;
  case 'popular_weekly':
    $sort_query = "ORDER BY 7_day_stats IS NULL ASC, 7_day_stats DESC, post_date_gmt DESC";
  break;
  case 'popular_monthly':
    $sort_query = "ORDER BY 30_day_stats IS NULL ASC, 30_day_stats DESC, post_date_gmt DESC";
  break;
  case 'popular_all_time':
    $sort_query = "ORDER BY all_time_stats IS NULL ASC, all_time_stats DESC, post_date_gmt DESC";
  break;
}

$page = $_GET['page'] ? : 1;
$limit = 16;
$offset = ($page-1)*$limit;
$args = array(
  'post_type' => 'post',
  'term_id' => $cat
);
$count = count_posts_in_term($args);
$total_page = ceil($count / $limit);
// $total_page = ceil($wp_query->found_posts / $limit);

$query = <<<SQL
  SELECT DISTINCT p.ID, p.post_title
  FROM
    {$wpdb->posts} AS p
    LEFT OUTER JOIN {$wpdb->term_relationships} AS tr ON p.ID = tr.object_id
    LEFT OUTER JOIN {$wpdb->term_taxonomy} AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
    LEFT OUTER JOIN {$wpdb->prefix}most_popular AS mp ON p.ID = mp.post_id
  WHERE
    p.post_status = 'publish' AND
    p.post_type = 'post' AND
    tt.term_id = {$current_cat->term_id}
  {$sort_query}
  LIMIT {$offset}, {$limit}
SQL;
$results = $wpdb->get_results($query);
?>

<main class="container">
  <?php include 'template-parts/breadcrumbs.php' ?>

  <div class="row post-archive flex-column-reverse flex-lg-row">

    <div class="col-lg-3">
      <?php include 'template-parts/navigation.php' ?>
    </div>

    <div class="col-lg-9">
      <section class="post-archive__articles">
        <div class="post-archive__articles__title">
          <h1>
            <?php echo $current_cat->name ?>の記事一覧
            <span>
              <!-- (<?php $myCount = $wp_query->found_posts;
              echo $myCount ?>件) -->
              <?php echo '(' . $count .'件)' ?>
            </span>
          </h1>

          <?php include 'template-parts/select-sort.php' ?>
        </div>

        <ul class="post-archive__articles__lists">
          <?php if (count($results) > 0): global $post?>
          <?php for ($i=0; $i < count($results); $i++) :
              $post = $results[$i]
            ?>
          <li>
            <?php include 'template-parts/card-article.php' ?>
          </li>
          <?php endfor;?>
          <?php endif; ?>
        </ul>

        <div class="nav-links">
          <?php
            echo paginate_links(array(
              'base' => $home_url . '/categories/' . $current_cat->term_id . '/articles/' . '%_%',
              'format' => '?page=%#%',
              'current' => $page,
              'total' => $total_page,
              'prev_next'    => false,
              'mid_size'  => 1,
            ));
          ?>
        </div>
      </section>
    </div>
  </div>

</main><!-- #main -->


<?php
//get_sidebar();
get_footer();
