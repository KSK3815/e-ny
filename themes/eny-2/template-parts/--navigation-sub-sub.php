<?php $home_url ?>
<p>カテゴリー</p>

<p class="previous">
  <?php
    $link = get_home_url() . '/categories/' . $cg_tree[0]['id'];
    $img_src = get_template_directory_uri() . '/assets/arrow-back.svg';
  ?>
  <a href="<?php echo $link ?>">
    <img src="<?php echo $img_src ?>">
    <?php echo $cg_tree[0]['name']; ?>
  </a>
</p>

<p class="previous">
  <?php
    $link = get_home_url() . '/categories/' . $cg_tree[0]['cr'][0]['id'];
    $img_src = get_template_directory_uri() . '/assets/arrow-back.svg';
  ?>
  <a href="<?php echo $link ?>">
    <img src="<?php echo $img_src ?>">
    <?php echo mb_strimwidth($cg_tree[0]['cr'][0]['name'], 0, isMobileDevice() ? 1000 : 18, "..."); ?>
  </a>
</p>

<p class="current">
  <?php echo mb_strimwidth($cg_tree[0]['cr'][0]['cr'][0]['name'], 0, isMobileDevice() ? 1000 : 18, "..."); ?>
</p>

<div class="child">
  <?php foreach ($cg_tree[0]['cr'][0]['cr'][0]['cr'] as $term) { ?>
    <li>
      <a href="<?php echo $home_url . '/categories/' . $term['id']; ?>">
        <?php echo $term['name'] ?>
      </a>
    </li>
  <?php } ?>
</div>