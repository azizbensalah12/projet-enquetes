<h2>Modifier l’utilisateur</h2>

<?php if (!empty($errors)): ?>
  <ul style="color:#fca5a5">
    <?php foreach($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
  </ul>
<?php endif; ?>

<form method="post" action="/admin/users/<?= (int)$user['id'] ?>" data-validate>
  <label>Nom<br>
    <input name="name" required maxlength="100" value="<?= htmlspecialchars($user['name'] ?? '') ?>">
  </label><br><br>

  <label>Email<br>
    <input type="email" name="email" required maxlength="150" value="<?= htmlspecialchars($user['email'] ?? '') ?>">
  </label><br><br>

  <label>Rôle<br>
    <select name="role">
      <?php $r = $user['role'] ?? 'client'; ?>
      <option value="client" <?= $r==='client'?'selected':''; ?>>client</option>
      <option value="agent"  <?= $r==='agent'?'selected':'';  ?>>agent</option>
      <option value="admin"  <?= $r==='admin'?'selected':'';  ?>>admin</option>
    </select>
  </label><br><br>

  <details>
    <summary>Changer le mot de passe (optionnel)</summary>
    <small class="muted">Laissez vide pour ne pas modifier.</small><br>
    <input type="password" name="password" minlength="6" placeholder="Nouveau mot de passe">
  </details>
  <br>

  <button class="btn primary" type="submit">Enregistrer</button>
  <a class="btn" href="/admin/users">Annuler</a>
</form>
