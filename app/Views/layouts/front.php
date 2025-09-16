<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Portail Client — Enquêtes</title>

  <!-- Feuille de styles globale -->
  <link rel="stylesheet" href="/assets/app.css">
  <!-- Favicon (optionnel) -->
  <link rel="icon" href="/favicon.ico">

  <script>
    // Validation légère côté client (respecte tes attributs required)
    document.addEventListener('submit', (e) => {
      const f = e.target;
      if(!f.matches('[data-validate]')) return;
      let ok = true;
      f.querySelectorAll('[required]').forEach(el => {
        if (!el.value.trim()) { ok=false; el.classList.add('error'); }
        else el.classList.remove('error');
      });
      if (!ok) {
        e.preventDefault();
        alert('Merci de compléter les champs requis.');
      }
    });
  </script>
</head>
<body>
  <header class="site-header">
    <div class="container header-inner">
      <h1 class="brand">
        <span class="brand-dot" aria-hidden="true"></span>
        Portail Client
      </h1>
      <nav class="main-nav" aria-label="Navigation principale">
        <a class="nav-btn" href="/surveys">Mes enquêtes</a>
        <a class="nav-btn outline" href="/logout">Déconnexion</a>
      </nav>
    </div>
  </header>

  <main class="container page">
    <?= $content ?>
  </main>

  <footer class="site-footer">
    <div class="container small muted">
      © <?= date('Y') ?> — Enquêtes de satisfaction. Tous droits réservés.
    </div>
  </footer>
</body>
</html>
