<?php

// $members = wp_list_authors([
//   'orderby'       => 'post_count',
//   'order'         => 'DESC',
//   'number'        => null,]);
$members = get_users([
  'role'   => 'author',
  'orderby'=>'post_count',
  'order'  =>DESC,
  'count_total'=>true,
  'number'     => 6,
]);

// print "<pre>@";print_r(get_avatar(9));print"@</pre>";
// print "<pre>@";print_r(the_author_meta('description',9));print"@</pre>";
// print "<pre>@";print_r(the_author_meta('display_name',9));print"@</pre>";
// print "<pre>@";print_r(count_user_posttype(9,"post"));print"@</pre>";
// print "<pre>@";print_r($members);print"@</pre>";
// exit;
?>
<div class="memberlists">
  <div class="heading">
    <div class="headtitle">人気の専門家</div>
    <a class="headlink faright" href="<?php echo home_url();?>/expertslist/">もっと見る<i></i></a>
  </div>
<?php
foreach($members as $member) :
  $description = mb_substr(strip_tags(strip_shortcodes( get_the_author_meta('description',$member->data->ID) )), 0, 50, 'UTF-8');
?>
  <a href='<?php echo home_url();?>/experts/<?php echo $member->data->ID; ?>' class='block hover'>
    <div class='memberlist'>
        <div class="avatar">
          <?php echo get_avatar($member->data->ID); ?>
        </div>
        <div class="author-detail">
          <p class="author-name"><strong><?php echo get_the_author_meta('display_name',$member->data->ID); ?></strong></p>
          <p class="author-desc"><?php echo $description; ?>...</p>
        </div>
    </div>
    <i class="fa fa-angle-right"></i>
  </a>
<?php endforeach; ?>
</div>

