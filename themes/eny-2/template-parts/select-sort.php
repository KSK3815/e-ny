<div class="post-sorter">
  <select id="select-sort" class="post-sorter__button" name="sort">
    <option value="new" <?php echo $sort_param == 'new' ? "selected" : "" ?>>新着</option>
    <option value="popular_dayly" <?php echo $sort_param == 'popular_dayly' ? "selected" : "" ?>>デイリーランキング</option>
    <option value="popular_weekly" <?php echo $sort_param == 'popular_weekly' ? "selected" : "" ?>>週間ランキング</option>
    <option value="popular_monthly" <?php echo $sort_param == 'popular_monthly' ? "selected" : "" ?>>月間ランキング</option>
    <option value="popular_all_time" <?php echo $sort_param == 'popular_all_time' ? "selected" : "" ?>>総合ランキング</option>
  </select>
  <i class="post-sorter__icon fas fa-sort-down"></i>
</div>

<script>
  window.addEventListener('load', function() {
    const select = document.getElementById('select-sort')
    select.addEventListener('change', function() {
      const makerParams = getMakerParameters();
      const searchParam = getSearchParameter();
      const postTypeParam = getPostTypeParameter();
      let sortParams = [];
      if (this.options[this.selectedIndex].value) {
        sortParams[0] = this.options[this.selectedIndex].value;
      }
      window.location.href = setParameter({
        "makers%5B%5D": makerParams,
        "sort": sortParams,
        "q": searchParam,
        "post_type": postTypeParam
      })
    })
  });

  function setParameter(paramsArray) {
    var resurl = location.href.replace(/\?.*$/, "");
    for (key in paramsArray) {
      resurl += (resurl.indexOf('?') == -1) ? '?' : '&';
      for (let i = 0; i < paramsArray[key].length; i++) {
        resurl += (i == 0) ? '' : '&';
        resurl += key + '=' + paramsArray[key][i];
      }
    }
    return resurl;
  }

  function getMakerParameters() {
    let paramsArray = [];
    const url = location.href;
    const parameters = url.split("?");
    if (parameters.length > 1) {
      const params = parameters[1].split("&");
      for (i = 0; i < params.length; i++) {
        let kv = params[i].split("=");
        if (kv[0] == "makers%5B%5D" || kv[0] == "makers%5B0%5D") {
          paramsArray.push(kv[1]);
        }
      }
    }
    return paramsArray;
  };

  function getSearchParameter() {
    let paramArray = [];
    const url = location.href;
    const parameters = url.split("?");
    if (parameters.length > 1) {
      const params = parameters[1].split("&");
      for (i = 0; i < params.length; i++) {
        let kv = params[i].split("=");
        if (kv[0] == "q") {
          paramArray.push(kv[1]);
        }
      }
    }
    return paramArray;
  }

  function getPostTypeParameter() {
    let paramArray = [];
    const url = location.href;
    const parameters = url.split("?");
    if (parameters.length > 1) {
      const params = parameters[1].split("&");
      for (i = 0; i < params.length; i++) {
        let kv = params[i].split("=");
        if (kv[0] == "post_type") {
          paramArray.push(kv[1]);
        }
      }
    }
    return paramArray;
  }
</script>
