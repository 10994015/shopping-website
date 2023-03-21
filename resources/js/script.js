const header = document.querySelector('header')
window.addEventListener('scroll', (e)=>{
    (window.scrollY > 0) ? header.classList.add('active') : header.classList.remove('active');
})