window.addEventListener("load", (event) => {

    const menuBurger = document.getElementById('menuButton')

    const menuNav = document.getElementById('menuNav')

    console.log(menuBurger);
    console.log(menuNav);

    menuBurger.addEventListener("click", (event) => {

        menuNav.classList.toggle('responsiveHide')

    })
})