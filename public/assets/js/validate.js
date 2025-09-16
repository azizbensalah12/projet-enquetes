document.addEventListener('submit', (e) => {
  const form = e.target;
  if (form.matches('[data-validate]')) {
    let ok = true;
    form.querySelectorAll('[required]').forEach(el => {
      if (!el.value.trim()) { ok=false; el.classList.add('error'); }
      else el.classList.remove('error');
    });
    if (!ok) {
      e.preventDefault();
      alert('Merci de compléter les champs requis.');
    }
  }
});