<h2>Questions</h2>
<p><a class="btn" href="/back/questions/create">+ Nouvelle question</a></p>
<table><tr><th>ID</th><th>Libellé</th><th>Type</th><th>Actions</th></tr>
<?php foreach ($questions as $q): ?>
  <tr>
    <td><?= $q['id'] ?></td>
    <td><?= htmlspecialchars($q['label']) ?></td>
    <td><?= htmlspecialchars($q['type']) ?></td>
    <td>
      <a class="btn" href="/back/questions/<?= $q['id'] ?>/edit">Modifier</a>
      <form method="post" action="/back/questions/<?= $q['id'] ?>/delete" style="display:inline">
        <button class="btn" type="submit" onclick="return confirm('Supprimer ?')">Supprimer</button>
      </form>
    </td>
  </tr>
<?php endforeach; ?>
</table>