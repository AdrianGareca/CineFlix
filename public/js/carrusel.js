(function() {
  const pista   = document.getElementById('carruselPista');
  const btnPrev = document.getElementById('carruselPrev');
  const btnNext = document.getElementById('carruselNext');

  // Si no hay carousel en la página, no hacer nada
  if (!pista || !btnPrev || !btnNext) return;

  const slides = document.querySelectorAll('.carrusel-slide');
  const dots   = document.querySelectorAll('.dot');
  const total  = slides.length;
  if (total === 0) return;

  let actual   = 0;
  let intervalo;

  function irA(indice) {
    actual = (indice + total) % total;
    pista.style.transform = `translateX(-${actual * 100}%)`;
    dots.forEach(d => d.classList.remove('activo'));
    if (dots[actual]) dots[actual].classList.add('activo');
  }

  function siguiente() { irA(actual + 1); }
  function anterior()  { irA(actual - 1); }

  function iniciarAuto()   { intervalo = setInterval(siguiente, 4500); }
  function reiniciarAuto() { clearInterval(intervalo); iniciarAuto(); }

  btnNext.addEventListener('click', () => { siguiente(); reiniciarAuto(); });
  btnPrev.addEventListener('click', () => { anterior();  reiniciarAuto(); });

  dots.forEach(d => d.addEventListener('click', () => {
    irA(parseInt(d.dataset.index));
    reiniciarAuto();
  }));

  // Soporte swipe táctil
  let touchX = 0;
  pista.addEventListener('touchstart', e => {
    touchX = e.touches[0].clientX;
  }, { passive: true });
  pista.addEventListener('touchend', e => {
    const diff = touchX - e.changedTouches[0].clientX;
    if (Math.abs(diff) > 40) {
      diff > 0 ? siguiente() : anterior();
      reiniciarAuto();
    }
  }, { passive: true });

  iniciarAuto();
})();
