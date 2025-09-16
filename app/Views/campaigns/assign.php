<h2>Assigner des clients — <?= htmlspecialchars($campaign['title']) ?></h2>

<p class="muted">Cochez les clients à assigner. Les personnes déjà assignées sont cochées.</p>

<form method="post" action="/back/campaigns/<?= $campaign['id'] ?>/assign" id="assignForm">
  <div class="card" style="margin:10px 0; max-height:420px; overflow:auto;">
    <table>
      <thead><tr><th style="width:56px;">#</th><th>Client</th><th>Email</th></tr></thead>
      <tbody>
      <?php foreach ($clients as $u): $checked = in_array((int)$u['id'], $assignedIds, true); ?>
        <tr>
          <td>
            <input type="checkbox" name="client_ids[]" value="<?= $u['id'] ?>" <?= $checked?'checked':'' ?>>
          </td>
          <td><?= htmlspecialchars($u['name']) ?></td>
          <td class="muted"><?= htmlspecialchars($u['email']) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="form__actions">
    <a class="btn" href="/back/campaigns">Annuler</a>
    <button class="btn btn--primary" type="submit">Enregistrer</button>
  </div>
</form>

<script>

</script>
