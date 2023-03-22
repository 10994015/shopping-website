/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!********************************!*\
  !*** ./resources/js/script.js ***!
  \********************************/
var header = document.querySelector('header');
var cartBtn = document.querySelector('#cart-btn');
var shopCartSildebar = document.querySelector('#shop-cart-sildebar');
var cartClose = shopCartSildebar.querySelector('#close-cart');
var cartBack = shopCartSildebar.querySelector('.back');
var cartSildbar = shopCartSildebar.querySelector('.cart');
window.addEventListener('scroll', function (e) {
  window.scrollY > 0 ? header.classList.add('active') : header.classList.remove('active');
});
cartBtn.addEventListener('click', function () {
  cartSildbar.style.right = "0";
  shopCartSildebar.style.display = "block";
});
var closeCartFn = function closeCartFn() {
  cartSildbar.style.right = "-100%";
  setTimeout(function () {
    shopCartSildebar.style.display = "none";
  }, 150);
};
cartBack.addEventListener('click', closeCartFn);
cartClose.addEventListener('click', closeCartFn);
/******/ })()
;