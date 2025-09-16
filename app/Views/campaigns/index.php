<h2>Campagnes</h2>

<p>
  <a class="btn primary" href="/back/campaigns/create">+ Nouvelle campagne</a>
</p>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Titre</th>
      <th>Statut</th>
      <th># Questions</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($campaigns as $c): ?>
    <tr>
      <td><?= (int)$c['id'] ?></td>
      <td><?= htmlspecialchars($c['title']) ?></td>
      <td><?= htmlspecialchars($c['status']) ?></td>
      <td><?= (int)($c['question_count'] ?? 0) ?></td>
      <td style="display:flex;gap:8px;flex-wrap:wrap">
        <a class="btn" href="/back/campaigns/<?= $c['id'] ?>/edit">Modifier</a>
        <a class="btn" href="/back/campaigns/<?= $c['id'] ?>/assign">Assigner</a>
        <a class="btn" href="/back/campaigns/<?= $c['id'] ?>/stats">Stats</a>
        <a class="btn" href="/back/campaigns/<?= $c['id'] ?>/export">Export</a>

        <form action="/back/campaigns/<?= $c['id'] ?>/delete" method="post" style="display:inline">
          <button class="btn danger" type="submit" onclick="return confirm('Supprimer cette campagne ?')">Supprimer</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
