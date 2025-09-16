<section class="card card--elevated">
  <header class="card__header">
    <h2 class="card__title">Mes enquêtes</h2>
    <p class="muted">Consultez et répondez aux enquêtes qui vous ont été assignées.</p>
  </header>

  <?php if (empty($campaigns)): ?>
    <div class="empty">
      <div class="empty__icon" aria-hidden="true">📭</div>
      <p class="empty__text">Aucune enquête n’est disponible pour le moment.</p>
    </div>
  <?php else: ?>
    <ul class="list list--cards">
      <?php foreach ($campaigns as $c): ?>
        <?php
          $status = strtolower((string)($c['status'] ?? ''));
          $map = [
            'published' => ['Publié',   'badge--published', '✅'],
            'draft'     => ['Brouillon','badge--draft',     '📝'],
            'archived'  => ['Clôturée', 'badge--archived',  '🗂️'],
            'closed'    => ['Clôturée', 'badge--archived',  '🗂️'],
          ];
          [$label,$class,$icon] = $map[$status] ?? [ucfirst((string)$c['status']), 'badge--neutral', 'ℹ️'];
        ?>
        <li class="list__item">
          <a class="tile" href="/surveys/<?= $c['id'] ?>" aria-label="Ouvrir l’enquête « <?= htmlspecialchars($c['title']) ?> »">
            <div class="tile__content">
              <div class="tile__title"><?= htmlspecialchars($c['title']) ?></div>
              <?php if (!empty($c['description'])): ?>
                <div class="tile__desc muted"><?= htmlspecialchars($c['description']) ?></div>
              <?php endif; ?>
            </div>
            <span class="badge <?= $class ?>">
              <span class="badge__icon" aria-hidden="true"><?= $icon ?></span>
              <?= $label ?>
            </span>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</section>
