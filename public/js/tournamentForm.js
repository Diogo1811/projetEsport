// get all the labels and input i want to display
// const singleEliminations = document.querySelectorAll('.singleElimination');
// const doubleEliminations = document.querySelectorAll('.doubleElimination');
// const swissElements = document.querySelectorAll('.swiss');
// const roundRobins = document.querySelectorAll('.roundRobin');
const openSignUpInputs = document.querySelectorAll('.openSignUpOn');

// console.log(singleEliminations);
// console.log(doubleEliminations);
// console.log(swissElements);
// console.log(roundRobins);
// console.log(openSignUpInputs);

// get the select on which the modification made will decide if i display or not
// const tournamentType = document.getElementById('tournament_tournament_type');
// console.log(tournamentType.innerText);

// tournamentType.addEventListener('change', function() {

//     const selectedValue = tournamentType.value;

//     console.log(selectedValue);
    
//     // We check the value of the select
//     switch (selectedValue) {

//         // if the select's value is single elimination **************************************************************
//         case 'single elimination':

//             // we hide every input with the class doubleElimination in case the user selects single elimination after he selected double elimination
//             doubleEliminations.forEach(doubleElimination => {
                    
//                 doubleElimination.style.display = 'none';
               
//             });

//             // we hide every input with the class swiss in case the user selects single elimination after he selected swiss
//             swissElements.forEach(swiss => {

//                 swiss.style.display = 'none';

//             });

//             // we hide every input with the class roundRobin in case the user selects single elimination after he selected round Robin
//             roundRobins.forEach(roundRobin => {
                    
//                 roundRobin.style.display = 'none';

//             });


//             // we display every input with the class singleElimination
//             singleEliminations.forEach(singleElimination => {

//                 singleElimination.style.display = 'block';

//             });


//             break;

//         // if the select's value is double elimination **************************************************************
//         case 'double elimination':
            
//             // we hide every input with the class singleElimination in case the user selects double elimination after he selected single elimination
//             singleEliminations.forEach(singleElimination => {
             
//                 singleElimination.style.display = 'none';

//             });

//             // we hide every input with the class swiss in case the user selects double elimination after he selected swiss
//             swissElements.forEach(swiss => {

//                 swiss.style.display = 'none';

//             });

//             // we hide every input with the class roundRobin in case the user selects double elimination after he selected round Robin
//             roundRobins.forEach(roundRobin => {
  
//                 roundRobin.style.display = 'none';

//             });

//             // we display every input with the class doubleElimination
//             doubleEliminations.forEach(doubleElimination => {

//                 doubleElimination.style.display = 'block';
            
//             });


//             break;

//         // if the select's value is swiss****************************************************************************
//         case 'swiss':

//             // we hide every input with the class singleElimination in case the user selects swiss after he selected single elimination
//             singleEliminations.forEach(singleElimination => {

//                 singleElimination.style.display = 'none';
                

//             });

//             // we hide every input with the class doubleElimination in case the user selects swiss after he selected double elimination
//             doubleEliminations.forEach(doubleElimination => {

//                 doubleElimination.style.display = 'none';
             
//             });

//             // we hide every input with the class roundRobin in case the user selects swiss after he selected round Robin
//             roundRobins.forEach(roundRobin => {

//                 roundRobin.style.display = 'none';

//             });


//             // we display every input with the class swiss
//             swissElements.forEach(swiss => {


//                 swiss.style.display = 'block';


//             }); 

//             break;

//         // if the select's value is round robin
//         case 'round robin':

//             // we hide every input with the class singleElimination in case the user selects round robin after he selected single elimination
//             singleEliminations.forEach(singleElimination => {

//                 singleElimination.style.display = 'none';

//             });

//             // we hide every input with the class doubleElimination in case the user selects round robin after he selected double elimination
//             doubleEliminations.forEach(doubleElimination => {

//                 doubleElimination.style.display = 'none';

//             });

//             // we hide every input with the class swiss in case the user selects round robin after he selected swiss
//             swissElements.forEach(swiss => {

//                 swiss.style.display = 'none';

//             });

//             // we display every input with the class roundRobin
//             roundRobins.forEach(roundRobin => {
                
//                 roundRobin.style.display = 'block';

//             });


//             break;
    
//         default:

//             break;
//     }
// })



// NOW WE DISPLAY THE INPUTS FOR THE OPEN SIGN UP IF IT'S TRUE AND HIDE THEM IF IT'S FALSE ****************************************************

// we set the constent with the radio button of the open sign up
const radioOpenSignUpInputs = document.getElementsByName('tournament[open_signup]');
const openSignUp = document.getElementById('tournament_open_signup_1');
const closedSignUp = document.getElementById('tournament_open_signup_0');
console.log(radioOpenSignUpInputs);
console.log("Bouton radio :" + openSignUp);

openSignUp.addEventListener('change', function() {

    console.log('on écoute le changement sur la radio oui des inscriptions');

    if (openSignUp.checked) {

        console.log('la radio inscription oui est checked');
        openSignUpInputs.forEach(openSignUpInput => {
    
            openSignUpInput.style.display = 'block';
    
        });
    }
})

closedSignUp.addEventListener('change', function() {

    console.log('on écoute le changement sur la radio non des inscriptions');

    if (closedSignUp.checked) {

        console.log('la radio inscription non est checked');
        openSignUpInputs.forEach(openSignUpInput => {
    
            openSignUpInput.style.display = 'none';
    
        });
    }
})



