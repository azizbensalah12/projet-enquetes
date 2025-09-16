<h2>Réponses</h2>

<form method="get" action="/back/responses" class="form" style="margin:10px 0;">
  <div class="form__row" style="max-width:420px;">
    <label class="form__label">Campagne</label>
    <select class="input" name="campaign_id" onchange="this.form.submit()">
      <option value="">Toutes les campagnes</option>
      <?php foreach ($campaigns as $c): ?>
        <option value="<?= $c['id'] ?>" <?= ($cid==$c['id']?'selected':'') ?>>
          <?= htmlspecialchars($c['title']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
</form>

<?php if (empty($rows)): ?>
  <p class="muted">Aucune réponse pour ce filtre.</p>
<?php else: ?>
  <table>
    <thead>
      <tr>
        <th>Date</th>
        <th>Client</th>
        <th>Question</th>
        <th>Réponse</th>
        <th>Campagne</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['created_at']) ?></td>
          <td><?= htmlspecialchars($r['client']) ?><br><span class="muted"><?= htmlspecialchars($r['email']) ?></span></td>
          <td><?= htmlspecialchars($r['question']) ?></td>
          <td><?= htmlspecialchars($r['reponse']) ?></td>
          <td>#<?= (int)$r['campaign_id'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
