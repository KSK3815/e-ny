<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Eny_Media
 */
$home_url = home_url();
?>

</main><!-- #content -->

<footer class="footer">
  <a href="<?php echo home_url(); ?>">
    <img src="<?php echo get_template_directory_uri(); ?>/assets/logo-dark.svg" alt="">
  </a>
  <nav class="footer-links">
    <a href="<?php echo $home_url; ?>">TOP</a>
    <a href="<?php echo $home_url . '/authors/' ?>">ライター一覧</a>
    <a href="<?php echo $home_url . '/articles/' ?>">人気記事一覧</a>
    <a href="<?php echo $home_url . '/new-posts/' ?>">新着記事一覧</a>
    <a href="<?php echo $home_url . '/terms/' ?>">利用規約</a>
    <a href="<?php echo $home_url . '/policy/' ?>">プライバシーポリシー</a>
    <a href="https://gojuon.jp/" rel="noreferrer noopener" target="_blank">運営会社</a>
  </nav>
  <div class="footer-copyright">
    Copyright © 2019 eny
  </div>
</footer>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>

</html>
