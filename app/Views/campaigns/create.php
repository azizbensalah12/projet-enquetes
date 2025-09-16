<h2>Créer une campagne</h2>
<form method="post" action="/back/campaigns" data-validate>
  <label>Titre<br><input name="title" required></label><br><br>
  <label>Description<br><textarea name="description" required></textarea></label><br><br>
  <label>Statut<br>
    <select name="status">
      <option value="draft">Brouillon</option>
      <option value="published">Publiée</option>
    </select></label><br><br>
  <fieldset>
    <legend>Associer des questions</legend>
    <?php foreach ($questions as $q): ?>
      <label><input type="checkbox" name="question_ids[]" value="<?= $q['id'] ?>"> <?= htmlspecialchars($q['label']) ?></label><br>
    <?php endforeach; ?>
  </fieldset><br>
  <button class="btn primary" type="submit">Créer</button>
</form>