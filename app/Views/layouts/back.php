<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BackOffice — Enquêtes</title>
  <style>
    :root{--bg:#0f172a;--card:#111827;--muted:#94a3b8;--acc:#38bdf8;--ok:#22c55e;--danger:#ef4444}
    *{box-sizing:border-box} body{margin:0;font-family:system-ui,Segoe UI,Roboto,Arial,sans-serif;background:#0b1020;color:#e5e7eb}
    header{background:linear-gradient(90deg,#0ea5e9,#22d3ee);color:#0b1020;padding:16px 20px}
    header h1{margin:0;font-size:20px;font-weight:700}
    nav{display:flex;gap:12px;margin-top:6px}
    nav a{color:#0b1020;text-decoration:none;font-weight:600;background:#e0f2fe;padding:6px 10px;border-radius:10px}
    main{max-width:1100px;margin:24px auto;padding:0 16px}
    .card{background:rgba(17,24,39,.8);backdrop-filter: blur(6px);border:1px solid rgba(148,163,184,.2);border-radius:16px;padding:18px;margin-bottom:18px}
    table{width:100%;border-collapse:collapse;background:#0b1020;border-radius:12px;overflow:hidden}
    th,td{padding:10px;border-bottom:1px solid rgba(148,163,184,.15)}
    th{text-align:left;color:#a5b4fc;background:#0f172a}
    .btn{display:inline-block;border:1px solid rgba(148,163,184,.35);padding:6px 10px;border-radius:10px;color:#e5e7eb;text-decoration:none}
    .btn.primary{background:linear-gradient(90deg,#0ea5e9,#22d3ee);border:none;color:#0b1020;font-weight:700}
    .btn.danger{border-color:rgba(239,68,68,.5);color:#fecaca}
    input,select,textarea{width:100%;padding:10px;border-radius:10px;border:1px solid rgba(148,163,184,.25);background:#0b1020;color:#e5e7eb}
    .grid{display:grid;gap:12px}
    @media(min-width:800px){.grid-2{grid-template-columns:1fr 1fr}}
    .muted{color:#94a3b8}
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
  <h1>BackOffice — Gestion d’enquêtes</h1>
  <nav>
    <a href="/back/campaigns">Campagnes</a>
    <a href="/back/questions">Questions</a>
    <a href="/admin/users">Utilisateurs</a>
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
