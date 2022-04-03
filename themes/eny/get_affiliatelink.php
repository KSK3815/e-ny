<?php

// test
// $_POST['amazonurl'] = "https://www.amazon.co.jp/%E3%83%84%E3%83%A0%E3%83%A9%E6%BC%A2%E6%96%B9-%E3%80%90%E7%AC%AC2%E9%A1%9E%E5%8C%BB%E8%96%AC%E5%93%81%E3%80%91%E3%83%84%E3%83%A0%E3%83%A9%E6%BC%A2%E6%96%B9%E9%BB%84%E9%80%A3%E8%A7%A3%E6%AF%92%E6%B9%AF%E3%82%A8%E3%82%AD%E3%82%B9%E9%A1%86%E7%B2%92A-24%E5%8C%85/dp/B014R3COZ4/ref=sr_1_1?s=hpc&ie=UTF8&qid=1524535196&sr=1-1&keywords=%E3%83%84%E3%83%A0%E3%83%A9%E3%80%80%E9%BB%84%E9%80%A3%E8%A7%A3%E6%AF%92%E6%B9%AF%E3%82%A8%E3%82%AD%E3%82%B9%E9%A1%86%E7%B2%92A";
// $_POST['rakutenurl'] = "https://item.rakuten.co.jp/kobe-menken/fre_10003854/";
// $_POST['yahoourl'] = "https://store.shopping.yahoo.co.jp/gionsakura/ourengedokutou.html";
// $_POST['itemname'] = "ツムラ漢方黄連解毒湯エキス顆粒";
// validate
$datas = [];
$errors = [];

// Amazon
if ( !empty($_POST['amazonurl']) ) {
	if ( preg_match('/mononostore-22/i', $_POST['amazonurl']) ) {
		$datas['error_amazonurl'] = 'Amazon:生成済み(新規生成したい場合は商品リンクを記入してください)';
	} else {
		if ( preg_match('/\/\/www.amazon.co.jp\//i', $_POST['amazonurl'])  ) {
			if( preg_match('/dp\/([a-zA-Z0-9]+)[?|\/]?/i', $_POST['amazonurl'],$return_asin) ){
				$itemname = $return_asin[1];
			} else {
				$itemname = $_POST['itemname'];
			}
			if ( empty($itemname) ) {
				$datas['error_amazonurl'] = '商品名を入力してください';
			} elseif ( $getamazon = getlink_Amazon($itemname) ) {
				if ( preg_match('/amazon.co.jp/i', $getamazon ) ) {
					$datas['amazonurl'] = $getamazon;
				} else {
					$datas['error_amazonurl'] = 'amazonアクセスエラーが発生しました。少し経ってからやりなおしください:'.$getamazon;
				}
			} else {
				$datas['error_amazonurl'] = 'amazonURLを取得できませんでした';
			}
		} else {
			$datas['error_amazonurl'] = 'amazonURLが正しくありません';
		}
	}
}
// 楽天
if ( !empty($_POST['rakutenurl']) ) {
	if ( preg_match('/\/\/af.moshimo.com\//i', $_POST['rakutenurl'])  ) {
		$datas['error_rakutenurl'] = '楽天:生成済み';
	} else {
		if ( preg_match('/\/\/item.rakuten.co.jp\//i', $_POST['rakutenurl'])  ) {
			$datas['rakutenurl'] = "//af.moshimo.com/af/c/click?a_id=987653&p_id=54&pc_id=54&pl_id=616&url=".urlencode($_POST['rakutenurl']);
			$context = stream_context_create(array(
		    'http' => array('ignore_errors' => true)
		   ));
			if ( $rhtml = file_get_contents($_POST['rakutenurl'], false, $context) ) {
				if (  preg_match('/apprakuten:item_code" content="(.*?):(.*?)"/', $rhtml, $return) ) {
					$item_rakutenurl_m = "http://m.rakuten.co.jp/".$return[1]."/i/".$return[2]."/";
					$datas['rakutenurl'] .= "&m=".urlencode($item_rakutenurl_m);
				}
			}
		} else {
			$datas['error_rakutenurl'] = '楽天URLが正しくありません';
		}
	}
}
// Yahoo
if ( !empty($_POST['yahoourl']) ) {
	if ( preg_match('/\/\/ck.jp.ap.valuecommerce.com\//i', $_POST['yahoourl'])  ) {
		$datas['error_yahoourl'] = 'Yahoo:生成済み';
	} else {
		if ( preg_match('/\/\/store.shopping.yahoo.co.jp\//i', $_POST['yahoourl']) ) {
			$datas['yahoourl'] = '//ck.jp.ap.valuecommerce.com/servlet/referral?sid=3393764&pid=885172234&vc_url='.urlencode($_POST['yahoourl']);
		} else {
			$datas['error_yahoourl'] = 'YahooURLが正しくありません';
		}
	}
}
//amazon:rakuten:yahoo

echo json_encode($datas);
exit;


// $itemname: 商品名またはASIN
function getlink_Amazon($itemname) {

  if(!$itemname) return false;

  $params["Service"]    = "AWSECommerceService";
  $params["AWSAccessKeyId"] = "AKIAISNK4G4YHQC4F7ZA";
  $params["Version"]    = "2013-09-01";
  $params["Operation"]    = "ItemSearch";
  $params["SearchIndex"]  = "All";
  $params["Keywords"]    = $itemname;
  $params["AssociateTag"]   = "mononostore-22";
  $params["ResponseGroup"]  = "Large";

  $Secret_Access_Key = "KgeIcP8ntZhoHdXBGtV5BUzAfRTVQqVaPQhU37xH";
  $baseurl = "http://ecs.amazonaws.jp/onca/xml";
  $base_request = "";
  foreach ( $params as $k => $v ) {
    $base_request .= "&" . $k . "=" . $v;
  }
  $base_request = $baseurl . "?" . substr( $base_request, 1 );

  $params["Timestamp"] = gmdate( "Y-m-d\TH:i:s\Z" );
  $base_request .= "&Timestamp=" . $params["Timestamp"];

  $base_request = "";
  foreach ( $params as $k => $v ) {
    $base_request .= "&" . $k . "=" . rawurlencode( $v );
    $params[$k] = rawurlencode( $v );
  }
  $base_request = $baseurl . "?" . substr( $base_request, 1 );

  $base_request = preg_replace( "/.*\?/", "", $base_request );
  $base_request = str_replace( "&", "\n", $base_request );

  ksort( $params );
  $base_request = "";
  foreach ( $params as $k => $v ) {
    $base_request .= "&" . $k . "=" . $v;
  }
  $base_request = substr( $base_request, 1 );
  $base_request = str_replace( "&", "\n", $base_request );

  $base_request = str_replace( "\n", "&", $base_request );

  $parsed_url = parse_url( $baseurl );
  $base_request = "GET\n" . $parsed_url["host"] . "\n" . $parsed_url["path"] . "\n" . $base_request;

  $signature = base64_encode( hash_hmac( "sha256", $base_request, $Secret_Access_Key, true ) );
  $signature = rawurlencode( $signature );

  $base_request = "";
  foreach ( $params as $k => $v ) {
    $base_request .= "&" . $k . "=" . $v;
  }
  $base_request = $baseurl . "?" . substr( $base_request, 1 ) . "&Signature=" . $signature;
  $context = stream_context_create(array(
    'http' => array('ignore_errors' => true)
   ));
  $xml = file_get_contents($base_request, false, $context);
  if ( strpos($http_response_header[0], '200') == false ) {
    return "API ERROR: {$http_response_header[0]}";
  } else {
    $xmltojson = json_encode(simplexml_load_string($xml), true);
    $jsontoarr = json_decode($xmltojson,TRUE);
  }

  if ( $jsontoarr['Items']['TotalResults'] == 1 ) return $jsontoarr['Items']['Item']['DetailPageURL'];
  if ( $jsontoarr['Items']['TotalResults'] > 1 ) return $jsontoarr['Items']['Item'][0]['DetailPageURL'];

  return false;
}
