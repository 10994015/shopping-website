const header = document.querySelector('header')
const cartBtn = document.querySelector('#cart-btn')
const shopCartSildebar = document.querySelector('#shop-cart-sildebar')
const cartClose = shopCartSildebar.querySelector('#close-cart')
const cartBack = shopCartSildebar.querySelector('.back')
const cartSildbar = shopCartSildebar.querySelector('.cart')
window.addEventListener('scroll', (e)=>{
    (window.scrollY > 0) ? header.classList.add('active') : header.classList.remove('active');
})
if(cartBtn != null){
    cartBtn.addEventListener('click', ()=>{
        cartSildbar.style.right = "0"
        shopCartSildebar.style.display = "block"
    })
}

const closeCartFn = ()=>{
    cartSildbar.style.right = "-100%"
    setTimeout(()=>{
        shopCartSildebar.style.display = "none"
    },150)
}
cartBack.addEventListener('click', closeCartFn);
cartClose.addEventListener('click', closeCartFn);