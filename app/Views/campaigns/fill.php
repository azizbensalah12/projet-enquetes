<h2><?= htmlspecialchars($campaign['title']) ?></h2>
<p><?= htmlspecialchars($campaign['description']) ?></p>

<form method="post" action="/surveys/<?= $campaign['id'] ?>/submit">
  <?php foreach ($questions as $q): ?>
    <div>
      <label><b><?= htmlspecialchars($q['label']) ?></b></label><br>

      <?php if ($q['type'] === 'text'): ?>
        <input type="text" name="q_<?= $q['id'] ?>">
      <?php elseif ($q['type'] === 'number'): ?>
        <input type="number" name="q_<?= $q['id'] ?>">
      <?php elseif ($q['type'] === 'select'): ?>
        <?php $opts = $q['options_json'] ? json_decode($q['options_json'], true) : []; ?>
        <select name="q_<?= $q['id'] ?>">
          <?php foreach ($opts as $opt): ?>
            <option value="<?= htmlspecialchars($opt) ?>"><?= htmlspecialchars($opt) ?></option>
          <?php endforeach; ?>
        </select>
      <?php endif; ?>

    </div><br>
  <?php endforeach; ?>

  <button class="btn primary" type="submit">Envoyer</button>
</form>
