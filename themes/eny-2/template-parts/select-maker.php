<div class="post-sorter">
  <select id="select-maker" class="post-sorter__button" name="sort">
    <option name="makers[]" value="">全てのメーカー</option>
    <?php foreach ($makers as $maker) : ?>
    <option name="makers[]" value="<?php echo $maker->term_id ?>" <?php echo in_array($maker->term_id, $maker_params) ? 'selected' : '' ?>><?php echo $maker->name ?>
    </option>
    <?php endforeach; ?>
  </select>
  <i class="post-sorter__icon fas fa-sort-down"></i>
</div>

<script>
  window.addEventListener('load', function() {
    const select = document.getElementById('select-maker');
    select.addEventListener('change', function() {
      const sortParam = getSortParameter();
      const searchParam = getSearchParameter();
      const postTypeParam = getPostTypeParameter();
      let makerParams = [];
      if (this.options[this.selectedIndex].value) {
        makerParams[0] = this.options[this.selectedIndex].value;
      }
      window.location.href = setParameter({
        "makers%5B%5D": makerParams,
        "sort": sortParam,
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

  function getSortParameter() {
    let paramsArray = [];
    const url = location.href;
    const parameter = url.split("?");
    if (parameter.length > 1) {
      const params = parameter[1].split("&");
      for (i = 0; i < params.length; i++) {
        let kv = params[i].split("=");
        if (kv[0] == "sort") {
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
