<section class="card card--elevated">
  <header class="card__header">
    <h2 class="card__title"><?= htmlspecialchars($campaign['title']) ?></h2>
    <?php if (!empty($campaign['description'])): ?>
      <p class="muted"><?= htmlspecialchars($campaign['description']) ?></p>
    <?php endif; ?>
  </header>

  <form method="post" action="/surveys/<?= $campaign['id'] ?>/submit" class="form" data-validate>
    <?php foreach ($questions as $q): ?>
      <div class="form__row">
        <label class="form__label">
          <?= htmlspecialchars($q['label']) ?>
          <?php if ($q['type'] !== 'text'): ?><span class="req" title="Champ requis">*</span><?php endif; ?>
        </label>

        <?php if ($q['type'] === 'text'): ?>
          <input class="input" type="text" name="q_<?= $q['id'] ?>" placeholder="Votre réponse" required>

        <?php elseif ($q['type'] === 'number'): ?>
          <input class="input" type="number" name="q_<?= $q['id'] ?>" inputmode="decimal" placeholder="0" required>

        <?php elseif ($q['type'] === 'select'): ?>
          <?php $opts = $q['options_json'] ? json_decode($q['options_json'], true) : []; ?>
          <select class="input" name="q_<?= $q['id'] ?>" required>
            <option value="" disabled selected>— Sélectionnez une option —</option>
            <?php foreach ($opts as $opt): ?>
              <option value="<?= htmlspecialchars($opt) ?>"><?= htmlspecialchars($opt) ?></option>
            <?php endforeach; ?>
          </select>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>

    <div class="form__actions">
      <a class="btn" href="/surveys">Annuler</a>
      <button class="btn btn--primary" type="submit">Envoyer</button>
    </div>
  </form>
</section>
