<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Eny_Media
 */

get_header();

$home_url = home_url();

$number   = 15;
$paged    = (get_query_var('paged')) ? get_query_var('paged') : 1;
$offset   = ($paged - 1) * $number;
$users    = get_users();
$query    = get_users('&offset='.$offset.'&number='.$number);
$total_users = count($users);
$total_query = count($query);
$total_pages = ceil($total_users / $number);

$current_page = max(1, get_query_var('paged'));
?>

<main class="container">

	<div class="breadcrumbs">
		<a class="breadcrumbs__link" href="<?php echo $home_url ?>">TOP</a>
		<span class="breadcrumbs__divider">
			<i class="fas fa-angle-right"></i>
		</span>
		<span class="breadcrumbs__link current">ライター一覧</span>
	</div>


	<section>

		<h1 class="u-page-title">
			ライター一覧
			<span>
				(<?php echo count($users); ?>件)
			</span>
		</h1>


		<div class="row">

			<?php foreach ($query as $q) : ?>
			<a class="user-list col-md-6 col-lg-4 u-margin-bottom-sm" href="<?php echo get_author_posts_url($q->ID);?>">
				<div class="user-list__avatar">
					<?php echo get_avatar($q->ID, 256); ?>
				</div>

				<section class="user-list__preview">
					<p class="user-list__preview__position">
						<?php echo get_the_author_meta('position', $q->ID);?>
					</p>

					<h1 class="user-list__preview__name">
						<span><?php echo get_the_author_meta('display_name', $q->ID);?></span>

						<?php if (in_array("公式ライター", get_field('eny_user_label', "user_{$q->ID}") ? : array())) : ?>
						<img src="<?php echo get_template_directory_uri(); ?>/assets/badge-authorized.png" alt="">
						<?php endif; ?>
					</h1>

					<?php if (get_the_author_meta('description', $q->ID) != '') : ?>
					<p class="user-list__preview__profile"><?php echo mb_strimwidth(get_the_author_meta('description', $q->ID), 0, isMobileDevice() ? 60 : 80, "..."); ?>
					</p>
					<?php endif; ?>
				</section>
			</a>
			<?php endforeach; ?>

		</div>


		<?php if ($total_users > $total_query) : ?>
		<div class="nav-links">
			<?php
          echo paginate_links(array(
            'base' => get_pagenum_link(1) . '%_%',
            'format' => '/page/%#%/',
            'current' => $current_page,
            'total' => $total_pages,
            'prev_next'    => false,
            'mid_size'  => 1,
          ));
        ?>
		</div>
		<?php endif; ?>

	</section>

</main><!-- #main -->

<?php
get_footer();
