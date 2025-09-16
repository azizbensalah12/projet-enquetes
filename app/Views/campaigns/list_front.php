<h2>Mes enquêtes</h2>
<?php if (empty($campaigns)): ?>
  <p>Aucune enquête disponible.</p>
<?php else: ?>
  <ul>
    <?php foreach ($campaigns as $c): ?>
      <li>
        <a href="/surveys/<?= $c['id'] ?>">
          <?= htmlspecialchars($c['title']) ?>
        </a> (<?= htmlspecialchars($c['status']) ?>)
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
