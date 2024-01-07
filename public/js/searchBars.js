// This tells the js to wait for the page to be load before starting the js
window.addEventListener("load", (event) => {
        
    // The select that will contain every country in differents options
    const textHint = document.getElementById("textHint");
    
    // The select that will contain every country in differents options
    const labelForResult = document.getElementById("labelSelectCountry");

    // Option to put all the search results in option elements to choose the one we want in the select
    const optionResult = document.createElement("option");
    




    // Function that will show the search responses
    async function showHint(srch) {

        // recupère toutes les balises enfants de textHint 
        const child = textHint.childNodes;
    
        while(child.length > 0) {
            textHint.classList.remove('hide');
            labelForResult.classList.remove('hide');
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
                newOptionResult.value = info['cca2'];

                textHint.appendChild(newOptionResult);
        
            });

        } else {
            textHint.classList.add('hide') 
        }

    }

    // id de la search bar
    const searchBar = document.getElementById("searchInput");

    // la function est appelé à chaque fois qu'une touche est 'up' après avoir appuyé dessus
    searchBar.addEventListener("keyup", (e) => {
        
        if (searchBar.value.length > 2) {
            
            showHint(searchBar.value)
        }else{
            textHint.classList.add('hide');
            labelForResult.classList.add('hide');
        }

    });


});