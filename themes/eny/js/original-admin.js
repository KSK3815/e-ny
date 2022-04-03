jQuery(function($) {
  // 投稿画面のみ
  if(!($('#post').length)){
    return;
  }
  // ボタン追加
  var getlinkBtn = '<span id="" class="button getlinkBtn">リンク生成</span>'
  $('[data-field_name="item_yahoourl"]').append(getlinkBtn);
  var loadingicon = '<div class="loading loading-hide"><div class="loading_icon"></div></div>';
  $('[data-field_name="item_yahoourl"]').append(loadingicon);


  $(document).on('click', '.getlinkBtn', function(){

    var amazonurl = $(this).closest('tbody').children('tr[data-field_name="item_amazonurl"]').find('input').val();
    var rakutenurl = $(this).closest('tbody').children('tr[data-field_name="item_rakutenurl"]').find('input').val();
    var rakutenurl_m = $(this).closest('tbody').children('tr[data-field_name="item_rakutenurl_m"]').find('input').val();
    var yahoourl = $(this).closest('tbody').children('tr[data-field_name="item_yahoourl"]').find('input').val();
    var itemname = $(this).closest('tbody').children('tr[data-field_name="item_name"]').find('input').val();
    if (!amazonurl && !rakutenurl && !yahoourl ) {
      alert('URL入力してください');
      return;
    }

    var $loading = $(".loading");
    var $thisclick = $(this);

    $.ajax({
        url: modulefile,
        type: 'post',
        dataType: 'json',
        data: {
            amazonurl: amazonurl,
            rakutenurl: rakutenurl,
            rakutenurl_m: rakutenurl_m,
            yahoourl: yahoourl,
            itemname: itemname,
        },
        beforeSend:function(){
          $loading.removeClass("loading-hide");
        },
    })
    .done(function (data, textStatus, jqXHR) {
      $loading.addClass("loading-hide");

      if (data.amazonurl || data.rakutenurl || data.yahoourl ) {
        var alertmessage = "リンク生成完了\n";
        if (data.error_amazonurl) {
          alertmessage += "\n" + data.error_amazonurl;
        } else if (data.amazonurl) {
          alertmessage += "\n" + '【Amazon】' + data.amazonurl;
          $thisclick.closest('tr').children('td[data-field_name="item_amazonurl"]').find('input').val(data.amazonurl);
        }
        if (data.error_rakutenurl) {
           alertmessage += "\n" + data.error_rakutenurl;
        } else if (data.rakutenurl) {
          alertmessage += "\n" + '【楽天】' + data.rakutenurl;
          $thisclick.closest('tr').children('td[data-field_name="item_rakutenurl"]').find('input').val(data.rakutenurl);
        }
        if (data.error_yahoourl) {
           alertmessage += "\n" + data.error_yahoourl;
        } else if (data.yahoourl) {
          alertmessage += "\n" + '【Yahoo】' + data.yahoourl;
          $thisclick.closest('tr').children('td[data-field_name="item_yahoourl"]').find('input').val(data.yahoourl);
        }
      } else {
        var alertmessage = "生成済み(新規生成したい場合は商品リンクを記入してください)\n";
      }
      if (alertmessage) alert(alertmessage);

    })
    .fail(function (jqXHR, textStatus, errorThrown) {
        $loading.addClass("loading-hide");
        alert('ERROR:リンク生成できません'.textStatus);
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
  });
});
