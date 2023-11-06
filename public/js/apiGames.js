// // // Set const for Twitch API credentials
// const clientId = '8sfdd3sko2vfcpqfg5dfhpsogvnj3b';
// const clientSecret = 'qzwix8527fb8tdsp0bj6qn3811mij7';

// // Make the POST request to obtain an access token
// axios.post('https://id.twitch.tv/oauth2/token', null, {
//   params: {
//     client_id: clientId,
//     client_secret: clientSecret,
//     grant_type: 'client_credentials',
//   },
// })
// .then(response => {
//   const accessToken = "Bearer " + response.data.access_token;

//   // Your JavaScript code in your frontend

//   function fetchDataFromExternalAPI() {
//     const externalApiUrl = 'https://api.igdb.com/v4/games';  // The external API URL


//     fetch(`/api-proxy?external_api_url=${externalApiUrl}`, {
//         method: 'POST', // or any other method
//         headers: {
//             // Add any required headers here
//             'mode': 'no-cors',
//             'Client-ID': '8sfdd3sko2vfcpqfg5dfhpsogvnj3b',
//             'Authorization': accessToken,
//             // Other headers as needed
//         },
//         body: "fields name;",
//     })
//     .then(response => {
//         if (response.status === 200) {
//             return response.json();
//         } else {
//             console.error(`Error: ${response.statusText}`);
//         }
//     })
//     .then(data => {
//         // Handle the response from your Symfony proxy
//         console.log(data);
//     })
//     .catch(error => {
//         console.error(error);
//     });
//   }

// // Call the function to fetch data from the external API
// fetchDataFromExternalAPI();


// //   console.log(accessToken);
// //   // Now you can use the access token to make authorized requests to Twitch API.
// //   fetch(
// //     "https://api.igdb.com/v4/games",
// //     { method: 'POST',
// //       headers: {
// //         "Access-Control-Allow-Origin": "*",
// //         "Access-Control-Allow-Methods": "GET,PUT,POST,DELETE,PATCH,OPTIONS",
// //         'mode': 'no-cors',
// //         'Accept': 'application/json',
// //         'Client-ID': '8sfdd3sko2vfcpqfg5dfhpsogvnj3b',
// //         'Authorization': accessToken,
// //         'Body': "fields *;"
// //       },
// //       // body: "fields *;",
// //   })
// //     .then(response => {
// //         console.log(response.json());
// //     })
// //     .then(data => {
// //         console.log(data);
// //     })
// //     .catch(err => {
// //         console.error(err);
// //     });
// })
// .catch(error => {
//   // Handle error
//   console.error(error);
// });



