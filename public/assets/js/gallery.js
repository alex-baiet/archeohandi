import { Window } from "./window.js";

/*
Rend les images "can-zoom" affichable en pleine écran.
*/

Window.addOnLoad(function () {
  const imgs = document.querySelectorAll('.can-zoom');
  const fullPage = document.querySelector('#fullpage');

  fullPage.addEventListener('click', function () {
    fullPage.style.display = 'none';
  })

  for (let i = 0; i < imgs.length; i++) {
    const img = imgs[i];
    
    img.addEventListener('click', function () {
      fullPage.style.backgroundImage = 'url(' + img.src + ')';
      fullPage.style.display = 'block';
    });
  }
})
