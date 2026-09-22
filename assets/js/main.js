(function(){
  const toggle=document.querySelector('.nav-toggle');
  const nav=document.querySelector('.nav');
  if(toggle){
    toggle.addEventListener('click',()=>{
      const open=nav.classList.toggle('open');
      toggle.setAttribute('aria-expanded',String(open));
    });
  }
  document.querySelectorAll('.nav a').forEach(a=>a.addEventListener('click',()=>nav?.classList.remove('open')));

  const reveals=document.querySelectorAll('.reveal');
  const io=new IntersectionObserver(entries=>{
    entries.forEach(entry=>{ if(entry.isIntersecting){ entry.target.classList.add('visible'); io.unobserve(entry.target); }});
  },{threshold:.12});
  reveals.forEach(el=>io.observe(el));

  const year=document.getElementById('year');
  if(year) year.textContent=new Date().getFullYear();
})();
