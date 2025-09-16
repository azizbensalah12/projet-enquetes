<h2>Connexion</h2>
<?php if (!empty($error)): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="post" action="/login" data-validate>
  <label>Email<br><input type="email" name="email" required></label><br><br>
  <label>Mot de passe<br><input type="password" name="password" required></label><br><br>
  <button class="btn primary" type="submit">Se connecter</button>
</form>