<!DOCTYPE html>
<html lang="ja">

<head>
 	<!-- adsense TAG -->
	 <script data-ad-client=“ca-pub-6830121310854700” async src=“https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js”></script>
	<!-- End adsense TAG -->
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-WQ7JKXS');</script>
	<!-- End Google Tag Manager -->
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />-->
	<title></title>
	<meta name="keywords" content="<?php get_the_category()[0]->cat_name; ?>" />
	<?php if (is_category()): ?>
	<?php if (tag_description() == "") : ?>
	<meta name="description" content="<?php bloginfo('name'); ?>の<?php single_tag_title(); ?>に関する記事をまとめました。">
	<?php else: ?>
	<meta name="description" content="'.tag_description().'" />
	<?php endif; ?>
	<?php elseif (is_tag()): ?>
	<meta name="keywords" content="<?php single_tag_title(); ?>" />
	<?php if (tag_description() == "") : ?>
	<meta name="description" content="<?php bloginfo('name'); ?>の<?php single_tag_title(); ?>に関する記事をまとめました。">
	<?php else: ?>
	<meta name="description" content="<?php tag_description(); ?>">
	<?php endif; ?>
	<?php endif; ?>
	<!--[if lt IE 9]>
	<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<script src="//css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
	<![endif]-->
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.ico" type="image/x-icon">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" id="fontawesome">
	<link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/sanitize.css">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/style.css?<?php echo date("YmdHis", filemtime(dirname(__FILE__).'/css/style.css')); ?>">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/main.css?<?php echo date("YmdHis", filemtime(dirname(__FILE__).'/css/main.css')); ?>">
	<?php wp_deregister_script('jquery'); ?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/common.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/footer.js"></script>
	<script src="https://cdn.jsdelivr.net/clipboard.js/1.5.13/clipboard.min.js"></script>
	<?php wp_head(); ?>
</head>