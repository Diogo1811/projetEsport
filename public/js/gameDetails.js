// This tells the js to wait for the page to be load before starting the js
window.addEventListener("load", (event) => {

  // carroussel 
  const swiper = new Swiper('.swiper', {

      // Optional parameters

      // Numbers of slides that I want
      slidesPerView: 3,

      // return to the first one at the end
      loop: true,

      // space between the slides
      spaceBetween: 5,
      
      // Automatic move from a slide to another
      autoplay: {
        delay: 4000,
      },
    
      // If we need pagination
      pagination: {
        el: '.swiper-pagination',
      },
    
      // Navigation arrows
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    
      // And if we need scrollbar
      scrollbar: {
        el: '.swiper-scrollbar',
      },
  });

  // swiper.toggle(event)

  // Test
  const datas = document.querySelectorAll('.swiper-zoom-container')
  const modal = document.getElementById('myModal')
  const modalImg = document.getElementById('modalImage')
  const cross = document.getElementById('closeCross')

  console.log(datas);
  console.log(modal);
  console.log(cross);

  // event listener for each figure
  datas.forEach(function (data) {
      data.addEventListener('click', function () {
          console.log(data);
          modal.classList.remove("hide")
          cross.classList.remove("hide")
          cross.classList.add("close")
          modal.classList.add("modal")
          modalImg.src = data.src;
      })
  })

  cross.addEventListener('click', function () {
      modal.classList.add("hide")
      modal.classList.remove("modal")
      modalImg.src = "";


  })

});
