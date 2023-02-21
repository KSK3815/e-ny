if (document.getElementsByTagName('figcaption')) {
  const caption_links = document.getElementsByTagName('figcaption')

  for (let i = 0; i < caption_links.length; i++) {
    const innerElement = caption_links[i].children
    const atag = Array.prototype.filter.call(innerElement, function(el) {
      return el.getAttribute('href') !== null
    })
    const link = atag[0].getAttribute('href')
    const domain = link.match(/^https?:\/{2,}(.*?)(?:\/|\?|#|$)/)[0]
    // console.log(domain)
    let site = ''
    if (domain.includes('rakuten')) {
      site = 'rakuten'
    }
    if (domain.includes('amazon')) {
      site = 'amazon'
    }
    if (domain.includes('yahoo')) {
      site = 'yahoo'
    }
    if (site !== '') {
      const affiliateLink = getAffiliateLink(link, site)
      atag[0].setAttribute('href', affiliateLink)
    }
  }
}

function getAffiliateLink(link, site) {
  let affiliateLink = ''
  const encodedUrl = encodeURI(link)
  switch (site) {
    case 'amazon':
      affiliateLink = '//af.moshimo.com/af/c/click?a_id=1496765&p_id=170&pc_id=185&pl_id=4065&url=' + encodedUrl
      break
    case 'rakuten':
      affiliateLink = '//af.moshimo.com/af/c/click?a_id=1496764&p_id=54&pc_id=54&pl_id=6036&url=' + encodedUrl
      break
    case 'yahoo':
      affiliateLink =
        'https://ck.jp.ap.valuecommerce.com/servlet/referral?sid=3422348&pid=885849747&vc_url=' + encodedUrl
      break
  }
  return affiliateLink
}
