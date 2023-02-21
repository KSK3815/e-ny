<?php
  $home_url = home_url();
  $pointer = $cg_tree[0]
?>

<nav class="cat-nav">
  <h1 class="cat-nav__title">カテゴリー</h1>

  <?php for ($i=0; $i < $current_hierarchy; $i++) :?>
  <ul class="cat-nav__parent">
    <?php
      $link = get_home_url() . '/categories/' . $pointer['id'];
    ?>
    <li>
      <a href="<?php echo $link ?>">
        <i class="fas fa-chevron-left"></i>
        <span><?php echo mb_strimwidth($pointer['name'], 0, isMobileDevice() ? 100 : 40, "…"); ?></span>
      </a>
    </li>
  </ul>
  <?php $pointer = $pointer['cr'][0] ?>
  <?php endfor; ?>

  <p class="cat-nav__current">
    <?php echo mb_strimwidth($pointer['name'], 0, isMobileDevice() ? 100 : 40, "…"); ?>
  </p>

  <?php if ($current_hierarchy != 3) :?>
  <ul class="cat-nav__child">
    <?php if ($pointer['cr']) : foreach ($pointer['cr'] as $term) : ?>
    <li>
      <a href="<?php echo $home_url . '/categories/' . $term['id']; ?>">
        <i class="fas fa-chevron-right"></i>
        <span><?php echo mb_strimwidth($term['name'], 0, isMobileDevice() ? 100 : 40, "…"); ?></span>
      </a>
    </li>
    <?php endforeach; endif; ?>
  </ul>
  <?php endif; ?>
</nav>
