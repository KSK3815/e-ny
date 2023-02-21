<?php
// function change_posts_per_page($query) {
//   $pt = get_query_var('post_type');
//   if ( $query->is_category() && $pt == "post" ) {
//     $page = $_GET['page'] ? : 1;
//     $limit = 16;
//     $query->set('posts_per_page', $limit);
//     $query->set('offset', ($page-1)*$limit);
//     return;
//   }
//  }
//  add_action( 'pre_get_posts', 'change_posts_per_page' );
?>