<?php
  $home_url = home_url();
?>
<div class="breadcrumbs">
  <a class="breadcrumbs__link" href="<?php echo $home_url ?>">TOP</a>
  <?php
    $pointer = $cg_tree[0];
    for ($i=0; $i < $current_hierarchy; $i++) :
  ?>
    <span class="breadcrumbs__divider">
      <i class="fas fa-angle-right"></i>
    </span>
    <a class="breadcrumbs__link" href="<?php echo $home_url . '/categories/' . $pointer['id'] ?>">
      <?php echo $pointer['name'] ?>
    </a>
  <?php
    $pointer = $pointer['cr'][0];
    endfor;
  ?>
  <span class="breadcrumbs__divider">
    <i class="fas fa-angle-right"></i>
  </span>
  <span class="breadcrumbs__link current"><?php echo $pointer['name'] ?></span>
</div>
