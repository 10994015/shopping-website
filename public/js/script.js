/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!********************************!*\
  !*** ./resources/js/script.js ***!
  \********************************/
var header = document.querySelector('header');
window.addEventListener('scroll', function (e) {
  window.scrollY > 0 ? header.classList.add('active') : header.classList.remove('active');
});
/******/ })()
;