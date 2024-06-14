let preloader = document.querySelector('.preloader');
let logoPreload = document.querySelector('.logo-preloader');
let logoLetter = document.querySelectorAll('.logo');
let navbar = document.querySelector('.navbar');
let loader = document.querySelector('.loader');

window.addEventListener('load', () => {
  setTimeout(() => {
    logoLetter.forEach((span, idx) => {
      setTimeout(() => {
        span.classList.add('active');
        loader.style.display = "none";
      }, (idx + 1) * 400)
    });
    setTimeout(() => {
      logoLetter.forEach((span, idx) => {
        setTimeout(() => {
          span.classList.remove('active')
          span.classList.add('fade');
        }, (idx + 1) * 50)
      })
    }, 2000);
    setTimeout(() => {
      preloader.style.top = '-100vh';
      navbar.classList.add('fixed-top');
    }, 2300)
  })
});