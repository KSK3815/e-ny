<?php 
function getAffiliateLink($link, $site) {
  $affiliate_link = $site;
  switch ($site) {
    case 'amazon':
      if (!preg_match('/\/\/af.moshimo.com\//i', $link)) {
        $affiliate_link = "//af.moshimo.com/af/c/click?a_id=1496765&p_id=170&pc_id=185&pl_id=4065&url=" . urlencode($link);
      }
    break;
    case 'rakuten':
      if (!preg_match('/\/\/af.moshimo.com\//i', $link)) {
        $affiliate_link = "//af.moshimo.com/af/c/click?a_id=1496764&p_id=54&pc_id=54&pl_id=616&url=" . urlencode($link);
      }
    break;
    case 'yahoo':
      if (!preg_match('/\/\/ck.jp.ap.valuecommerce.com\//i', $link)) {
        $affiliate_link = 'https://ck.jp.ap.valuecommerce.com/servlet/referral?sid=3422348&pid=885849747&vc_url=' . urlencode($link);
      }
    break;
  }
  return $affiliate_link;
}
?>