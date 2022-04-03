// $(function(){
// 	$(".search_font").click(function(){
// 		$(this).next().slideToggle();
// 		return false;
// 	});
// 	$(".top_menu_list").click(function(){
// 		$(this).next().slideToggle();
// 		return false;
// 	});
// });

/* single page */
$(".page_navs a").click(function(){
	var h2Text = $(this).text();
	var index = 0;
	var wknum = -1;
	$(".page_navs li").each(function(){
		if($(this).text() === h2Text) {
			index = wknum;
		}
		wknum++;
	});
	var scrollTop = $("h2").eq(index).offset().top;
	if($("#spnav").css("display") === "block") {
		scrollTop = scrollTop - 60;
	} else {
		scrollTop = scrollTop - 15;
	}
	$('body,html').animate({scrollTop: scrollTop},500);
	return false;
});


