<h2>Créer une question</h2>

<?php if (!empty($errors)): ?>
  <ul style="color:#fca5a5">
    <?php foreach($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
  </ul>
<?php endif; ?>

<form method="post" action="/back/questions" data-validate>
  <label>Libellé<br>
    <input name="label" required maxlength="255" value="<?= htmlspecialchars($label ?? '') ?>">
  </label><br><br>

  <label>Type<br>
    <select name="type" id="qtype">
      <option value="text"   <?= (isset($type) && $type==='text')?'selected':''; ?>>Texte</option>
      <option value="number" <?= (isset($type) && $type==='number')?'selected':''; ?>>Nombre</option>
      <option value="select" <?= (isset($type) && $type==='select')?'selected':''; ?>>Choix (select)</option>
    </select>
  </label><br><br>

  <div id="optsBox">
    <label>Options (JSON pour type select)<br>
      <input name="options_json" placeholder='["Oui","Non"]' value="<?= htmlspecialchars($opts ?? '') ?>">
    </label><br><br>
  </div>

  <button class="btn primary" type="submit">Créer</button>
</form>

<script>
  const typeEl = document.getElementById('qtype');
  const box = document.getElementById('optsBox');
  const opts = document.querySelector('input[name="options_json"]');

  function toggle() {
    const sel = typeEl.value === 'select';
    box.style.display = sel ? '' : 'none';
    if (!sel) opts.value = ''; // essentiel: ne pas envoyer '' quand ce n'est pas 'select'
  }
  typeEl.addEventListener('change', toggle);
  toggle();
</script>
