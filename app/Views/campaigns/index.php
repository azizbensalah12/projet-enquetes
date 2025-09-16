<h2>Campagnes</h2>
<p><a class="btn" href="/back/campaigns/create">+ Nouvelle campagne</a></p>
<table><tr><th>ID</th><th>Titre</th><th>Statut</th><th># Questions</th><th>Actions</th></tr>
<?php foreach ($campaigns as $c): ?>
  <tr>
    <td><?= $c['id'] ?></td>
    <td><?= htmlspecialchars($c['title']) ?></td>
    <td><?= htmlspecialchars($c['status']) ?></td>
    <td><?= (int)$c['questions_count'] ?></td>
    <td>
      <a class="btn" href="/back/campaigns/<?= $c['id'] ?>/edit">Modifier</a>
      <form method="post" action="/back/campaigns/<?= $c['id'] ?>/delete" style="display:inline">
        <button class="btn" type="submit" onclick="return confirm('Supprimer ?')">Supprimer</button>
      </form>
    </td>
  </tr>
<?php endforeach; ?>
</table>