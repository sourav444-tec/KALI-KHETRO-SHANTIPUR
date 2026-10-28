(() => {
  const body = document.body;
  const langToggle = document.getElementById('langToggle');
  let language = localStorage.getItem('kks-language') || 'bn';

  function setLanguage(lang) {
    language = lang;
    localStorage.setItem('kks-language', lang);
    document.documentElement.lang = lang;
    document.querySelectorAll('[data-en][data-bn]').forEach(el => {
      el.textContent = el.dataset[lang] || el.dataset.en || el.textContent;
    });
    document.querySelectorAll('[data-placeholder-en][data-placeholder-bn]').forEach(el => {
      el.placeholder = el.dataset['placeholder' + (lang === 'en' ? 'En' : 'Bn')] || '';
    });
    if (langToggle) langToggle.textContent = lang === 'bn' ? 'EN' : 'বাংলা';
    const search = document.getElementById('pujaSearch');
    if (search) search.placeholder = search.dataset[lang === 'en' ? 'placeholderEn' : 'placeholderBn'];
  }
  setLanguage(language);
  langToggle?.addEventListener('click', () => setLanguage(language === 'bn' ? 'en' : 'bn'));

  const menuToggle = document.getElementById('menuToggle');
  menuToggle?.addEventListener('click', () => document.querySelector('.navlinks')?.classList.toggle('open'));
  document.querySelectorAll('.navlinks a').forEach(a => a.addEventListener('click', () => document.querySelector('.navlinks')?.classList.remove('open')));

  const slides = [...document.querySelectorAll('.hero-slide')];
  const dots = [...document.querySelectorAll('.dot')];
  let currentSlide = 0;
  function showSlide(i) {
    if (!slides.length) return;
    currentSlide = (i + slides.length) % slides.length;
    slides.forEach((s, n) => s.classList.toggle('active', n === currentSlide));
    dots.forEach((d, n) => d.classList.toggle('active', n === currentSlide));
  }
  dots.forEach((dot, i) => dot.addEventListener('click', () => showSlide(i)));
  if (slides.length > 1) window.setInterval(() => showSlide(currentSlide + 1), 5500);

  const search = document.getElementById('pujaSearch');
  const category = document.getElementById('categoryFilter');
  function filterPujas() {
    const q = (search?.value || '').toLowerCase().trim();
    const cat = category?.value || '';
    document.querySelectorAll('.puja-card').forEach(card => {
      card.hidden = !(card.dataset.name.includes(q) && (!cat || card.dataset.category === cat));
    });
  }
  search?.addEventListener('input', filterPujas);
  category?.addEventListener('change', filterPujas);

  document.querySelectorAll('.details-toggle').forEach(button => {
    button.addEventListener('click', () => {
      const target = document.getElementById(button.dataset.target);
      target?.classList.toggle('open');
      const isOpen = target?.classList.contains('open');
      button.textContent = language === 'bn' ? (isOpen ? 'বিস্তারিত লুকান −' : 'বিস্তারিত দেখুন +') : (isOpen ? 'Hide details −' : 'View details +');
    });
  });

  const modal = document.getElementById('sosModal');
  const openSOS = document.getElementById('openSOS');
  const closeSOS = document.getElementById('closeSOS');
  function setModal(open) {
    if (!modal) return;
    modal.classList.toggle('visible', open);
    modal.setAttribute('aria-hidden', String(!open));
    body.classList.toggle('modal-open', open);
    if (open) closeSOS?.focus();
  }
  openSOS?.addEventListener('click', () => setModal(true));
  closeSOS?.addEventListener('click', () => setModal(false));
  modal?.addEventListener('click', e => { if (e.target === modal) setModal(false); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') setModal(false); });
})();


// Interactive homepage map; coordinates are maintained by the admin.
(function initKaliMap(){
  const el=document.getElementById('pujaMap'); if(!el||!window.L)return;
  const points=Array.isArray(window.KALI_MAP_POINTS)?window.KALI_MAP_POINTS:[];
  const fallback=[23.2472,88.4330]; const map=L.map(el,{scrollWheelZoom:false}).setView(fallback,14);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19,attribution:'&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a>'}).addTo(map);
  const groups={puja:L.layerGroup().addTo(map),service:L.layerGroup().addTo(map)}; const valid=[];
  function esc(v){return String(v??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));}
  points.forEach(p=>{const lat=Number(p.lat),lng=Number(p.lng);if(!Number.isFinite(lat)||!Number.isFinite(lng)||!lat||!lng)return;const isPuja=p.kind==='puja';const marker=L.circleMarker([lat,lng],{radius:isPuja?9:7,color:'#fff7e8',weight:2,fillColor:isPuja?'#a51f37':'#d7a94c',fillOpacity:1});const dir=isPuja&&p.mapUrl?p.mapUrl:'https://www.openstreetmap.org/directions?to='+encodeURIComponent(lat+','+lng);const linkLabel=isPuja&&p.mapUrl?'Open Google Maps ↗':'Get directions ↗';marker.bindPopup('<div class="map-popup-title">'+esc(p.name||p.nameEn||'Location')+'</div><div class="map-popup-meta">'+esc(p.category||(isPuja?'Kali Puja':'Local service'))+(p.address?'<br>'+esc(p.address):'')+(p.phone?'<br><strong>Phone:</strong> '+esc(p.phone):'')+'</div><a class="map-popup-link" target="_blank" rel="noopener noreferrer" href="'+esc(dir)+'">'+linkLabel+'</a>');marker.addTo(groups[isPuja?'puja':'service']);valid.push([lat,lng]);});
  function fit(layer){const arr=[];layer.eachLayer(m=>arr.push(m.getLatLng()));if(arr.length)map.fitBounds(L.latLngBounds(arr).pad(.18),{maxZoom:15});else map.setView(fallback,14);}
  document.getElementById('showAllMap')?.addEventListener('click',()=>{map.addLayer(groups.puja);map.addLayer(groups.service);fit({eachLayer:cb=>{groups.puja.eachLayer(cb);groups.service.eachLayer(cb);}});});
  document.getElementById('showPujasMap')?.addEventListener('click',()=>{map.addLayer(groups.puja);map.removeLayer(groups.service);fit(groups.puja);});
  document.getElementById('showServicesMap')?.addEventListener('click',()=>{map.removeLayer(groups.puja);map.addLayer(groups.service);fit(groups.service);});
  if(valid.length)map.fitBounds(L.latLngBounds(valid).pad(.15),{maxZoom:14});
})();
