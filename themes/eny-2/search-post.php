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
global $pages;
if (empty($pages)) {
    $pages = array();
}

$page = $_GET['page'] ? : 1;
$article_limit = 16;
$article_offset = ($page-1)*$article_limit;

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

$q = get_query_var("q");
$my_cat_array = array();
$my_cats = get_categories();

$cg_tree[0] = array(
    'name' => "「{$q}」の検索結果"
);
$current_hierarchy = 0;

$esc_q = $wpdb->esc_like($q);


$query = <<<SQL
SELECT DISTINCT p.ID, p.post_title
FROM {$wpdb->posts} AS p
  LEFT OUTER JOIN {$wpdb->prefix}most_popular AS mp ON p.ID = mp.post_id
WHERE
	p.post_status = 'publish'
	AND p.post_type = 'post'
	AND (
		p.post_title like '%{$esc_q}%'
		OR p.post_content like '%{$esc_q}%'
	)
{$sort_query}
LIMIT {$article_offset}, {$article_limit}
SQL;
$article_results = $wpdb->get_results($query);
$query = <<<SQL
SELECT count(DISTINCT p.ID)
FROM {$wpdb->posts} AS p
WHERE
	p.post_status = 'publish'
	AND p.post_type = 'post'
	AND (
		p.post_title like '%{$esc_q}%'
		OR p.post_content like '%{$esc_q}%'
	)
SQL;
$article_count = $wpdb->get_results($query);
$key = "count(DISTINCT p.ID)";
$article_count = $article_count[0]->$key;

$total_page = ceil($article_count / $article_limit);
?>

<main class="container">
  <?php include 'template-parts/breadcrumbs.php' ?>

  <div class="results">
    <?php if ($article_count) : ?>
    <div class="art-container">
      <p class="display-header">
        「<?php echo $q ?>」に関する記事
        <span>(<?php echo $article_count ?>件)</span>
        <?php include 'template-parts/select-sort.php' ?>
      </p>
      <div class="articles row">
        <?php if (count($article_results) > 0): global $post?>
        <?php for ($i=0; $i < count($article_results); $i++) :
          $post = $article_results[$i]
        ?>
        <div class="col-sm-12 col-md-12 col-lg-6 left-side">
          <?php include 'template-parts/card-article.php' ?>
        </div>
        <?php endfor;?>
        <?php endif; ?>
      </div>
      <?php if ($article_count > $article_limit) : ?>
      <div class="nav-links">
        <?php
          echo paginate_links(array(
            'base' => $home_url . '/search' . '%_%',
            'format' => '?page=%#%',
            'current' => $page,
            'total' => $total_page,
            'prev_next'    => false,
            'mid_size'  => 1,
          ));
        ?>
      </div>
      <?php endif;?>
    </div>
    <?php endif; ?>

  </div>

</main><!-- #main -->

<?php
get_footer();
