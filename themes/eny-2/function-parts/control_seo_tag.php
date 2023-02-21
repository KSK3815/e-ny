<?php
// function change_title_tag( $title ) {
//   if (!empty(get_query_var('q'))) {
//     $title = '「' . get_query_var('q') . '」の検索結果 | eny'; 
//   }
//   return "test";
// }
// add_filter( 'pre_get_document_title', 'change_title_tag' );
//Wordpress4.4以上でのタイトルカスタマイズ
function change_document_title_parts( $title_parts ){
	if(is_404()):
		if($q = get_query_var('q')) {
			$title_parts['title'] = '「' . $q. '」の検索結果 | eny'; 
		} else {
			$title_parts['title'] = 'お探しのページは見つかりませんでした';
		}
	endif;

return $title_parts;
}
add_filter( 'document_title_parts', 'change_document_title_parts' );
?>