<h2>Modifier la question</h2>
<form method="post" action="/back/questions/<?= $q['id'] ?>" data-validate>
  <label>Libellé<br><input name="label" required value="<?= htmlspecialchars($q['label']) ?>"></label><br><br>
  <label>Type<br>
    <select name="type">
      <?php foreach (['text','number','select'] as $t): ?>
        <option value="<?= $t ?>" <?= $q['type']===$t?'selected':'' ?>><?= $t ?></option>
      <?php endforeach; ?>
    </select></label><br><br>
  <label>Options (JSON)<br><input name="options_json" value="<?= htmlspecialchars($q['options_json']) ?>"></label><br><br>
  <button class="btn primary" type="submit">Enregistrer</button>
</form>