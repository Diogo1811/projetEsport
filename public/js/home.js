// This tells the js to wait for the page to be load before starting the js
window.addEventListener("load", (event) => {

    // We select the tournaments game cover 
    let tournaments = $(`.tournamentFigures`);

    function showStartDate(event) {
        console.log('clicked');
        $(event.currentTarget).toggleClass(`active`);
        $(event.currentTarget).next().slideToggle(300);
        tournaments.not($(event.currentTarget)).removeClass(`active`);
        $(`.tournamentDetailsHome`).not($(event.currentTarget).next()).slideUp(300);
    }

    tournaments.on(`click`, showStartDate);

    let interval = 0;
    $(`.tournamentsToDisplay`).each(function () {
        interval = interval + 150;
        $(this).delay(interval).fadeIn()
    });

})

// // This tells the js to wait for the page to be load before starting the js
// window.addEventListener("load", (event) => {

//     // We select the tournaments game cover 
//     let tournaments = $(`.tournamentFigures`);

//     function showStartDate(event) {
//         console.log('clicked');
//         let tournamentDetails = $(event.currentTarget).next('.tournamentDetailsHome');
//         $(event.currentTarget).toggleClass(`active`);
//         tournamentDetails.slideToggle(300);
//         tournaments.not($(event.currentTarget)).removeClass(`active`);
//         $(`.tournamentDetailsHome`).not(tournamentDetails).slideUp(300);
//     }

//     tournaments.on(`click`, showStartDate);

//     let interval = 0;
//     $(`.tournamentsToDisplay`).each(function () {
//         interval = interval + 150;
//         $(this).delay(interval).fadeIn();
//     });

// });