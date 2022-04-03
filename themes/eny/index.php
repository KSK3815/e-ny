<?php get_header(); ?>

<body id="home">
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WQ7JKXS" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->
  <div id="container">
    <div class="area">
      <?php include("inc_header.php"); ?>
      <div class="topimage">
        <img class="topimage__img topimage__img--pc" src="<?php echo get_template_directory_uri(); ?>/images/kv_1.jpg" srcset="<?php echo get_template_directory_uri(); ?>/images/kv_1.jpg 1x, <?php echo get_template_directory_uri(); ?>/images/kv_1@2x.jpg 2x" alt="topimage">
        <img class="topimage__img topimage__img--sp" src="<?php echo get_template_directory_uri(); ?>/images/kv_1_sp.jpg" srcset="<?php echo get_template_directory_uri(); ?>/images/kv_1_sp.jpg 1x, <?php echo get_template_directory_uri(); ?>/images/kv_1_sp@2x.jpg 2x" alt="topimage">
        <!-- <div class="toptext">
          <p class="toptitle">安心できるモノ選びをあなたに。</p>
          <p class="topsub">専門家や各種免許を持ったプロが様々なモノを丁寧にご紹介します。</p>
        </div> -->
      </div>
      <?php include("pickup.php"); ?>
      <div id="main" class="content_left top">
        <?php include("members.php"); ?>
        <?php include("new.php"); ?>
      </div><!-- content_left -->
      <div class="clear sp"></div>

      <div class="content_right">
        <?php get_sidebar(); ?>
      </div><!-- content_right -->

      <div class="clear"></div>
    </div><!-- area -->
  </div><!-- container -->
  <?php get_footer(); ?>
</body>

</html>