window.addEventListener('load',()=>document.getElementById('pageLoader')?.classList.add('hide'));
document.addEventListener('DOMContentLoaded',()=>{
  const back=document.getElementById('backToTop');
  window.addEventListener('scroll',()=>{if(back)back.style.display=window.scrollY>400?'grid':'none'});
  back?.addEventListener('click',()=>window.scrollTo({top:0,behavior:'smooth'}));
  document.querySelectorAll('.quick-view').forEach(btn=>btn.addEventListener('click',()=>{
    const p=JSON.parse(btn.dataset.product||'{}');
    document.getElementById('quickImage').src=p.image||'';
    document.getElementById('quickBrand').textContent=p.brand||'';
    document.getElementById('quickName').textContent=p.name||'';
    document.getElementById('quickPrice').innerHTML=p.price||'';
    document.getElementById('quickDescription').textContent=p.description||'';
    document.getElementById('quickLink').href='single-product.php?id='+p.id;
    new bootstrap.Modal(document.getElementById('quickViewModal')).show();
  }));
  const search=document.querySelector('.search-pill input');
  const suggestions=document.getElementById('searchSuggestions');
  const terms=['Satin dress','Linen blazer','Sneakers','Tote bag','Women co-ord','Resort shirt'];
  search?.addEventListener('input',()=>{
    const q=search.value.toLowerCase().trim();
    if(!q){suggestions.style.display='none';return;}
    suggestions.innerHTML=terms.filter(t=>t.toLowerCase().includes(q)).slice(0,5).map(t=>`<a href="search.php?q=${encodeURIComponent(t)}">${t}</a>`).join('');
    suggestions.style.display=suggestions.innerHTML?'block':'none';
  });
});
