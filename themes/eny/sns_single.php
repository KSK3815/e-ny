<ul class="social">
	<li><a href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>" target="_blank" rel="nofollow"><i class="fa fa-facebook"></i></a></li>
	<li><a href="http://twitter.com/share?url=<?php the_permalink(); ?>&lang=ja&text=<?php the_title(); ?>" target="_blank" rel="nofollow"><i class="fa fa-twitter"></i></a></li>

	<li><a href="http://b.hatena.ne.jp/add?mode=confirm&url=<?php the_permalink(); ?>&title=<?php echo urlencode( the_title( "" , "" , 0 ) ) ?>" target="_blank" rel="nofollow"><i class="fa fa-hatena"></i></a></li>
	<li>
		<a href="https://timeline.line.me/social-plugin/share?url=<?php the_permalink(); ?>" target="_blank" rel="nofollow" class="pc">
			<i class="fa fa-line"></i>
		</a>
		<a href="http://line.naver.jp/R/msg/text/?<?php the_title() ?> <?php the_permalink(); ?>" target="_blank" rel="nofollow" class="sp">
			<i class="fa fa-line"></i>
		</a>
	</li>
	<li class="clear"></li>
</ul>