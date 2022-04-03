<?php
$categories = get_categories();
?>
<header id="header">
  <div class="search_font">
    <i class="fa fa-search fa-2x" aria-hidden="true"></i>
  </div>
  <div class="headlogo">
    <?php if ( is_home() ) : ?><h1><?php endif; ?>
      <a href="<?php echo home_url();?>/"><img src="<?php echo get_template_directory_uri(); ?>/images/eny-logo@2x.png" alt="<?php bloginfo('name'); ?>"></a>
    <?php if ( is_home() ) : ?></h1><?php endif; ?>
  </div>
  <form method="get" id="search_form" action="<?php echo home_url('/'); ?>">
    <input name="s" type="text" placeholder="探したいキーワードを入力" value="<?php echo htmlspecialchars($s); ?>" >
    <input class="search fontl" type="submit" value="&#xf002;">
  </form>

    <div class="hammenu-container">
      <div class="hammenu"></div>
    </div>

    <div class="topnavi layer-top">
      <div class="container">
        <ul>
        <?php foreach ($categories as $category): ?>
          <li>
            <a class="faright" href="<?php echo home_url();?>/category/<?php echo $category->slug ?>"><?php echo $category->name ?><i></i></a>
          </li>
        <?php endforeach ?>
        </ul>
      </div>
    </div>
<style type="text/css">


</style>
</header>
