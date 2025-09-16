<h2>Statistiques — <?= htmlspecialchars($campaign['title']) ?></h2>
<p class="muted">Répondants uniques : <b><?= (int)$respondents ?></b></p>

<div class="card" style="margin-top:10px;">
  <h3>Moyennes (questions numériques)</h3>
  <?php if (empty($statsNumber)): ?>
    <p class="muted">Aucune question de type nombre.</p>
  <?php else: ?>
    <table>
      <thead><tr><th>Question</th><th>Moyenne</th><th>Nb</th></tr></thead>
      <tbody>
        <?php foreach ($statsNumber as $n): ?>
          <tr>
            <td><?= htmlspecialchars($n['label']) ?></td>
            <td><?= number_format((float)$n['avg_val'], 2, ',', ' ') ?></td>
            <td><?= (int)$n['cnt'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<div class="card" style="margin-top:10px;">
  <h3>Répartition (questions à choix)</h3>
  <?php if (empty($statsSelect)): ?>
    <p class="muted">Aucune question de type select.</p>
  <?php else: ?>
    <?php foreach ($statsSelect as $qid => $q): ?>
      <div class="card" style="margin:10px 0;">
        <h4 style="margin:0 0 6px 0;"><?= htmlspecialchars($q['label']) ?></h4>
        <table>
          <thead><tr><th>Option</th><th>Nombre</th></tr></thead>
          <tbody>
          <?php foreach ($q['options'] as $row): ?>
            <tr>
              <td><?= htmlspecialchars($row['value']) ?></td>
              <td><?= (int)$row['nb'] ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<p style="margin-top:12px;">
  <a class="btn btn--primary" href="/back/campaigns/<?= $campaign['id'] ?>/export">Exporter CSV</a>
</p>
