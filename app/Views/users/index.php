<h2>Utilisateurs</h2>

<p><a class="btn primary" href="/admin/users/create">+ Nouvel utilisateur</a></p>

<table>
  <tr>
    <th>ID</th><th>Nom</th><th>Email</th><th>Rôle</th><th>Créé le</th><th>Actions</th>
  </tr>
  <?php foreach ($users as $u): ?>
    <tr>
      <td><?= (int)$u['id'] ?></td>
      <td><?= htmlspecialchars($u['name']) ?></td>
      <td><?= htmlspecialchars($u['email']) ?></td>
      <td><?= htmlspecialchars($u['role']) ?></td>
      <td><?= htmlspecialchars($u['created_at'] ?? '') ?></td>
      <td>
        <a class="btn" href="/admin/users/<?= (int)$u['id'] ?>/edit">Modifier</a>
        <form method="post" action="/admin/users/<?= (int)$u['id'] ?>/delete" style="display:inline">
          <button class="btn danger" type="submit" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
