// Set const for Twitch API credentials
const clientId = '8sfdd3sko2vfcpqfg5dfhpsogvnj3b';
const clientSecret = 'fd1j0r921pntbd7yxmw5vsvr3p51r7';



// Make the POST request to obtain an access token
axios.post('https://id.twitch.tv/oauth2/token', null, {
  params: {
    client_id: clientId,
    client_secret: clientSecret,
    grant_type: 'client_credentials',
  },
})
.then(response => {
  const accessToken = "Bearer " +response.data.access_token;
  console.log(accessToken);
  // Now you can use the access token to make authorized requests to Twitch API.
  fetch(
    "https://api.igdb.com/v4/games",
    { method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Client-ID': '8sfdd3sko2vfcpqfg5dfhpsogvnj3b',
        'Authorization': accessToken,
      },
      body: "fields *;",
      mode: 'no-cors',
  })
    .then(response => {
        console.log(response.json());
    })
    .then(data => {
        console.log(data);
      })
    .catch(err => {
        console.error(err);
    });
})
.catch(error => {
  // Handle error
  console.error(error);
});


