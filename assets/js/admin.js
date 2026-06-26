document.addEventListener('DOMContentLoaded',()=>{
  const ctx=document.getElementById('salesBars');
  if(ctx){
    ctx.querySelectorAll('.bar').forEach((bar,i)=>setTimeout(()=>bar.style.height=bar.dataset.height+'%',120*i));
  }
  document.querySelectorAll('[data-confirm]').forEach(el=>el.addEventListener('click',e=>{
    if(!confirm(el.dataset.confirm)) e.preventDefault();
  }));
});
