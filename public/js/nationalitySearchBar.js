// This tells the js to wait for the page to be load before starting the js
window.addEventListener("load", (event) => {
        
    // The select that will contain every country in an option 
    const countryBox = document.getElementById("countryBoxes");

    // Option to put all the search results in option elements to choose the one we want in the select
    const inputResult = document.createElement("input");
    
    // Label is there to tell which contry is in each box
    const countryName = document.createElement("label");





    // Function that will show the search responses
    async function showHint(srch) {

        console.log(child);
    
        while(countryBox.childNodes.length > 0) {

            console.log(countryBox.childNodes.length);
            countryBox.classList.remove('hide');
            countryBox.removeChild(child[0]);
        }   

        // appel asynchrone
        const response = await fetch(
            "https://restcountries.com/v3.1/translation/" + srch
        );
        console.log("response = ", response);

        const data = await response.json();

        console.log("data = ", data);
        if(data) {

            data.forEach((info) => {

                // Creation of a new input
                const newInputResult = inputResult.cloneNode();

                // Creation of a new label
                const newCountryName = countryName.cloneNode();
                
                // Label will have country's name translated in french
                newCountryName.textContent = info['translations']['fra']['common'];
                
                // we link the label to the input
                newCountryName.htmlFor = info['cca2'];

                // The input will have the code of the country as a value
                newInputResult.value = info['cca2'];

                // The input's id will have the code of the country as a value
                newInputResult.id = info['cca2'];

                // the input will be a checkbox type of input
                newInputResult.type = 'checkbox';


                countryBox.appendChild(newInputResult);
                countryBox.appendChild(newCountryName);

                // if () {
                    
                // }
        
            });

        } else {
                
        }

        console.log(child);
        console.log(child[1]);


        // child.forEach(box => {
        //     console.log(box);
        //     if (box.tagName.toLowerCase() === 'input') {
                
        //         console.log(box.checked);
        //     }
        // });

    }

    // searchBar's Id
    const searchBar = document.getElementById("countrySearch");

    // event listner to listen when the key goes up
    searchBar.addEventListener("keyup", (e) => {
        
        // The result is shown only when at least 4 letters are typed so their are less results showed 
        if (searchBar.value.length > 3 ) {
            
            // we start the function after the 4 letters are placed
            showHint(searchBar.value)

            child.forEach(box => {
                console.log(box);
                if (box.tagName.toLowerCase() === 'input') {
                    
                    console.log(box.checked);
                }
            });
        }

    });

    // recupère toutes les balises enfants de countryBox 
    const child = countryBox.childNodes;

    

    // ferme le dropdown menu de la bar de recherche et la vide quand on clique dans la page 
    // window.addEventListener("click", function() {
    //     if(countryBox.classList.contains("dropDownMenuHint")) {
    //         while(child.length > 0) {
    //             countryBox.removeChild(child[0]);
    //         }      
    //         countryBox.classList.remove("dropDownMenuHint");
    //     }
    // });



    
});