// This tells the js to wait for the page to be load before starting the js
window.addEventListener("load", (event) => {
        
    // The select that will contain every country in an option 
    const textHint = document.getElementById("textHint");


    const optionResult = document.createElement("option");




    // la function est appelé avec comme parametre le contenu de la search bar
    async function showHint(srch) {
        
        while(textHint.childNodes.length > 0) {
            textHint.classList.remove('hide');
            textHint.removeChild(child[0]);
        }  
        // appel asynchrone
        const response = await fetch(
            "https://restcountries.com/v3.1/translation/" + srch
        );
        console.log("response = ", response);

        const data = await response.json();
        // const data = await JSON.parse(response);

        console.log("data = ", data);
        if(data) {

            data.forEach((info) => {
                const newOptionResult = optionResult.cloneNode();
                
                newOptionResult.textContent = info['translations']['fra']['common'];
                newOptionResult.value = info;

                textHint.appendChild(newOptionResult);
        
            });

        } else {
                
        }

    }

    // id de la search bar
    const searchBar = document.getElementById("searchInput");

    // la function est appelé à chaque fois qu'une touche est 'up' après avoir appuyé dessus
    searchBar.addEventListener("keyup", (e) => {
        
        if (searchBar.value.length > 3) {
            
            showHint(searchBar.value)
        }

    });

    // recupère toutes les balises enfants de textHint 
    const child = textHint.childNodes;

    // ferme le dropdown menu de la bar de recherche et la vide quand on clique dans la page 
    // window.addEventListener("click", function() {
    //     if(textHint.classList.contains("dropDownMenuHint")) {
    //         while(child.length > 0) {
    //             textHint.removeChild(child[0]);
    //         }      
    //         textHint.classList.remove("dropDownMenuHint");
    //     }
    // });
});