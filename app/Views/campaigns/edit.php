<h2>Modifier la campagne</h2>
<form method="post" action="/back/campaigns/<?= $campaign['id'] ?>" data-validate>
  <label>Titre<br><input name="title" required value="<?= htmlspecialchars($campaign['title']) ?>"></label><br><br>
  <label>Description<br><textarea name="description" required><?= htmlspecialchars($campaign['description']) ?></textarea></label><br><br>
  <label>Statut<br>
    <select name="status">
      <?php foreach (['draft'=>'Brouillon','published'=>'Publiée'] as $k=>$v): ?>
        <option value="<?= $k ?>" <?= $campaign['status']===$k?'selected':'' ?>><?= $v ?></option>
      <?php endforeach; ?>
    </select></label><br><br>
  <button class="btn primary" type="submit">Enregistrer</button>
</form>
<h3>Assigner à un client</h3>
<form method="post" action="/back/campaigns/<?= $campaign['id'] ?>/assign">
  <label>ID client<br><input type="number" name="client_id" required></label>
  <button class="btn" type="submit">Assigner</button>
</form>