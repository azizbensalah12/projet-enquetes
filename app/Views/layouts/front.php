<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>FrontOffice — Enquêtes</title>
  <style>
    :root{--blue:#38bdf8} *{box-sizing:border-box}
    body{margin:0;font-family:system-ui,Segoe UI,Roboto,Arial,sans-serif;background:#0b1020;color:#e5e7eb}
    header{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;background:linear-gradient(90deg,#22d3ee,#0ea5e9)}
    header h1{margin:0;color:#0b1020;font-size:20px;font-weight:800}
    nav a{color:#0b1020;text-decoration:none;font-weight:700;margin-left:12px;background:#e0f2fe;padding:6px 10px;border-radius:10px}
    main{max-width:900px;margin:24px auto;padding:0 16px}
    .card{background:rgba(17,24,39,.8);border:1px solid rgba(148,163,184,.2);border-radius:16px;padding:18px}
    input,select,textarea{width:100%;padding:10px;border-radius:10px;border:1px solid rgba(148,163,184,.25);background:#0b1020;color:#e5e7eb}
    .btn{display:inline-block;padding:8px 14px;border-radius:10px;text-decoration:none;border:1px solid rgba(148,163,184,.35);color:#e5e7eb}
    .btn.primary{background:linear-gradient(90deg,#22d3ee,#0ea5e9);border:none;color:#0b1020;font-weight:800}
    ul{padding-left:18px}
  </style>
  <script>
    document.addEventListener('submit',e=>{
      const f=e.target;if(!f.matches('[data-validate]'))return;
      let ok=true; f.querySelectorAll('[required]').forEach(el=>{ if(!el.value.trim()) ok=false; });
      if(!ok){ e.preventDefault(); alert('Merci de compléter les champs requis.'); }
    });
  </script>
</head>
<body>
<header>
  <h1>Portail Client</h1>
  <nav>
    <a href="/surveys">Mes enquêtes</a>
    <a href="/logout">Déconnexion</a>
  </nav>
</header>
<main>
  <div class="card">
    <?= $content ?>
  </div>
</main>
</body>
</html>
