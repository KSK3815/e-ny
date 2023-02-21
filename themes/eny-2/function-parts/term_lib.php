<?php
if (! function_exists('get_current_hierarchy')) {
  function get_current_hierarchy($args) {
    $default_args = array(
      'term_id' => 0,
      'taxonomy' => 'category'
    );
    $args = array_merge($default_args, $args);
    if(!$args['term_id']) return 0;
    $term_hierarchy = get_ancestors( $args['term_id'], $args['taxonomy'] );
    $current_hierarchy = count($term_hierarchy);
    return $current_hierarchy;
  }
}

if (! function_exists('get_category_tree')) {
  function get_category_tree($term_id = -1) {
    $cg_tree = array();

    if ($term_id == -1) {
      $acts = get_ancestor_terms('category');
      for ($i=0; $i < count($acts); $i++) { 
        $cg_tree[$i]['id'] = $acts[$i]->term_id;
        $cg_tree[$i]['name'] = $acts[$i]->name;
        $cr = get_children_terms('category', $acts[$i]->term_id);
        for ($j=0; $j < count($cr); $j++) { 
          $cg_tree[$i]['cr'][$j]['id'] = $cr[$j]->term_id;
          $cg_tree[$i]['cr'][$j]['name'] = $cr[$j]->name;
          $gcr = get_children_terms('category', $cr[$j]->term_id);
          for ($k=0; $k < count($gcr); $k++) { 
            $cg_tree[$i]['cr'][$j]['cr'][$k]['id'] = $gcr[$k]->term_id;
            $cg_tree[$i]['cr'][$j]['cr'][$k]['name'] = $gcr[$k]->name;
          }
        }
      }
      return $cg_tree;
    }

    $term_hierarchy = array_reverse(get_ancestors($term_id, 'category'));
    $current_hierarchy = count($term_hierarchy);


    switch ($current_hierarchy) {
      case 0:
        $cg = get_category($term_id);
        $cg_tree[0]['id'] = $cg->term_id;
        $cg_tree[0]['name'] = $cg->name;
        $cr = get_children_terms('category', $term_id);
        for ($i=0; $i < count($cr); $i++) { 
          $cg_tree[0]['cr'][$i]['id'] = $cr[$i]->term_id;
          $cg_tree[0]['cr'][$i]['name'] = $cr[$i]->name;
        }
      break;
      case 1:
        $parent_cat = get_category($term_hierarchy[0]);
        $cg_tree[0]['id'] = $parent_cat->term_id;
        $cg_tree[0]['name'] = $parent_cat->name;
        $current_cat = get_category($term_id);
        $cg_tree[0]['cr'][0]['id'] = $current_cat->term_id;
        $cg_tree[0]['cr'][0]['name'] = $current_cat->name;
        $cr = get_children_terms('category', $current_cat->term_id);
        for ($i=0; $i < count($cr); $i++) { 
          $cg_tree[0]['cr'][0]['cr'][$i]['id'] = $cr[$i]->term_id;
          $cg_tree[0]['cr'][0]['cr'][$i]['name'] = $cr[$i]->name;
        }
      break;
      case 2:
        $grand_parent_cat = get_category($term_hierarchy[0]);
        $cg_tree[0]['id'] = $grand_parent_cat->term_id;
        $cg_tree[0]['name'] = $grand_parent_cat->name;
        $parent_cat = get_category($term_hierarchy[1]);
        $cg_tree[0]['cr'][0]['id'] = $parent_cat->term_id;
        $cg_tree[0]['cr'][0]['name'] = $parent_cat->name;
        $current_cat = get_category($term_id);
        $cg_tree[0]['cr'][0]['cr'][0]['id'] = $current_cat->term_id;
        $cg_tree[0]['cr'][0]['cr'][0]['name'] = $current_cat->name;
      break;
    }
    return $cg_tree;
  }
}

if (! function_exists('get_ancestor_terms')) {
  function get_ancestor_terms($taxonomy) {
    $args = array(
      'taxonomy' => $taxonomy,
      'hide_empty' => false, 
      'fields' => 'all',
      'hide_empty' => 0,
      'parent' => 0,
      'orderby' => 'ID',
      'order' => 'ASC'
    );
    $terms = get_terms($args);
    return $terms ;
  }
}

if(! function_exists('get_children_terms')) {
  function get_children_terms($taxonomy, $term_id) {
    $args = array(
      'taxonomy' => $taxonomy,
      'hide_empty' => false, 
      'fields' => 'all',
      'hide_empty' => 0,
      'parent' => $term_id
    );
    $children_ids = get_terms($args);
    return $children_ids;
  }
}

if (! function_exists('get_lowest_terms')) {
  function get_lowest_terms ($sbjcat = '', $taxonomy = 'category') {
    $terms = get_the_terms($sbjcat, $taxonomy);
    if (empty($terms)) return false;
    $candidate = $terms;
    $cnt = count($terms);
      if ($cnt > 1) {
        foreach ($terms as $key => $term) {
          foreach ($terms as $term2) {
            if (term_is_ancestor_of($term->term_id, $term2->term_id, $taxonomy)) {
              unset($candidate[$key]);
              break;
            }
          }
        }
      }
      return $candidate;
  }
}

if (!function_exists('count_posts_in_term')) {
  function count_posts_in_term($args) {
    global $wpdb;

    $default_args = array(
      'post_type' => 'post',
      'limit' => 15,
      'offset' => 0,
    );
    $args = array_merge($default_args, $args);

$query = <<<SQL
  SELECT DISTINCT count(p.ID)
  FROM 
    {$wpdb->posts} AS p
    LEFT OUTER JOIN {$wpdb->term_relationships} AS tr ON p.ID = tr.object_id
    LEFT OUTER JOIN {$wpdb->term_taxonomy} AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
  WHERE 
    p.post_status = 'publish' AND
    p.post_type = '{$args['post_type']}' AND
    tt.term_id = {$args['term_id']}
SQL;

    $results = $wpdb->get_results($query);
    $key = "count(p.ID)";
    return $results[0]->$key;
  }
}

if (!function_exists('get_posts_in_term')) {
  function get_posts_in_term($args) {
    global $wpdb;

    $default_args = array(
      'post_type' => 'post',
      'limit' => 15,
      'offset' => 0,
    );
    $args = array_merge($default_args, $args);

$query = <<<SQL
  SELECT DISTINCT p.ID, p.post_title
  FROM 
    {$wpdb->posts} AS p
    LEFT OUTER JOIN {$wpdb->term_relationships} AS tr ON p.ID = tr.object_id
    LEFT OUTER JOIN {$wpdb->term_taxonomy} AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
  WHERE 
    p.post_status = 'publish' AND
    p.post_type = '{$args['post_type']}' AND
    tt.term_id = {$args['term_id']}
  ORDER BY p.post_date DESC
  LIMIT {$args['offset']}, {$args['limit']} 
SQL;
    
    return $wpdb->get_results($query);;
  }
}
?>