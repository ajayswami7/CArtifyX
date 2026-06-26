document.addEventListener('DOMContentLoaded',()=>{
  document.querySelectorAll('[data-cart-action="add"]').forEach(button=>{
    button.addEventListener('click',async()=>{
      const body=new FormData();
      body.append('action','add');
      body.append('product_id',button.dataset.productId);
      body.append('quantity','1');
      body.append('ajax','1');
      const res=await fetch('cart.php',{method:'POST',body});
      const data=await res.json();
      const count=document.getElementById('cartCount');
      if(count) count.textContent=data.count;
      const toast=document.createElement('div');
      toast.className='toast align-items-center text-bg-dark border-0 show app-toast';
      toast.innerHTML='<div class="d-flex"><div class="toast-body">Added to cart.</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>';
      document.body.appendChild(toast);
      setTimeout(()=>toast.remove(),2200);
    });
  });
});
