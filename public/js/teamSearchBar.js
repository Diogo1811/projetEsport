// This tells the js to wait for the page to be load before starting the js
window.addEventListener("load", (event) => {
        
    // The select that will contain every team in differents options
    const textHint = document.getElementById("textTeamHint");
    
    const labelForResult = document.getElementById("labelSelectTeam");

    // Option to put all the search results in option elements to choose the one we want in the select
    const optionResult = document.createElement("option");
    




    // Function that will show the search responses
    async function showHint(srch) {
    
        while(textHint.childNodes.length > 0) {
            textHint.classList.remove('hide');
            labelForResult.classList.remove('hide');
            textHint.removeChild(child[0]);
        }  

        // appel asynchrone
        const response = await fetch(
            "/searchTeam/" + srch,
            {
                method: "GET",
            }
        );

        console.log("response = ", response);

        const data = await response.json();
        // const data = await JSON.parse(response);

        console.log("data = ", data);
        if(data) {

            data.forEach((info) => {
                console.log(info);
                const newOptionResult = optionResult.cloneNode();
                
                newOptionResult.textContent = info['name'];
                newOptionResult.value = info['id'];

                textHint.appendChild(newOptionResult);
        
            });

        } else {
            textHint.classList.add('hide') 
        }

    }

    // id de la search bar
    const searchBar = document.getElementById("searchTeamInput");

    // la function est appelé à chaque fois qu'une touche est 'up' après avoir appuyé dessus
    searchBar.addEventListener("input", (e) => {

        showHint(searchBar.value)

    });

    // recupère toutes les balises enfants de textHint 
    const child = textHint.childNodes;

});