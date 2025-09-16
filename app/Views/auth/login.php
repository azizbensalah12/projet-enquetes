<section class="card card--elevated auth-card">
  <header class="card__header">
    <h2 class="card__title">Connexion</h2>
    <p class="muted">Accédez à vos enquêtes assignées.</p>
  </header>

  <?php if (!empty($error)): ?>
    <div class="alert alert--error" role="alert">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <form method="post" action="/login" class="form" data-validate novalidate>
    <div class="form__row">
      <label class="form__label" for="email">Email</label>
      <input class="input" id="email" type="email" name="email" placeholder="ex. client@example.com" required>
    </div>

    <div class="form__row">
      <label class="form__label" for="password">Mot de passe</label>
      <input class="input" id="password" type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
    </div>

    <div class="form__actions">
      <button class="btn btn--primary" type="submit">Se connecter</button>
    </div>
  </form>
</section>
