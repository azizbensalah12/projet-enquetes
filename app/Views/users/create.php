<h2>Créer un utilisateur</h2>

<?php if (!empty($errors)): ?>
  <ul style="color:#fca5a5">
    <?php foreach($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
  </ul>
<?php endif; ?>

<form method="post" action="/admin/users" data-validate>
  <label>Nom<br>
    <input name="name" required maxlength="100" value="<?= htmlspecialchars($name ?? '') ?>">
  </label><br><br>

  <label>Email<br>
    <input type="email" name="email" required maxlength="150" value="<?= htmlspecialchars($email ?? '') ?>">
  </label><br><br>

  <label>Mot de passe<br>
    <input type="password" name="password" required minlength="6" placeholder="≥ 6 caractères">
  </label><br><br>

  <label>Rôle<br>
    <select name="role">
      <?php $r = $role ?? 'client'; ?>
      <option value="client" <?= $r==='client'?'selected':''; ?>>client</option>
      <option value="agent"  <?= $r==='agent'?'selected':'';  ?>>agent</option>
      <option value="admin"  <?= $r==='admin'?'selected':'';  ?>>admin</option>
    </select>
  </label><br><br>

  <button class="btn primary" type="submit">Créer</button>
  <a class="btn" href="/admin/users">Annuler</a>
</form>
