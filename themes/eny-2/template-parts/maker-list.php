<?php if (count($makers) > 0) : ?>
<section class="brand-filter">
  <h1 class="brand-filter__title">メーカー</h1>
  <form class="brand-filter__inputs" id="makers-form" action="<?php get_current_link() ?>" method="GET">
    <?php foreach ($makers as $maker) :?>
    <label>
      <input name="makers[]" value="<?php echo $maker->term_id ?>" type="checkbox" <?php echo (count($maker_params)==0 || in_array($maker->term_id, $maker_params)) ? 'checked' : ''; ?>>
      <p><?php echo $maker->name ?>
      </p>
    </label>
    <?php endforeach; ?>
  </form>
</section>

<script>
  const inputs = document.querySelectorAll("input");
  for (let i = 0; i < inputs.length; i++) {
    let input = inputs[i];
    input.addEventListener('click', () => {
      const form = document.getElementById('makers-form');
      form.submit();
    })
  }
</script>
<?php endif;
