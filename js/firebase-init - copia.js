	var firebaseConfig = {
		apiKey: "AIzaSyC0q7hQgHENru540ziaG62xtVSV4LJ2Jx0",
		authDomain: "nocontrabando-notificaciones.firebaseapp.com",
		projectId: "nocontrabando-notificaciones",
		storageBucket: "nocontrabando-notificaciones.appspot.com",
		messagingSenderId: "400235843880",
		appId: "1:400235843880:web:4ec573c51397afcb2b001d",
		measurementId: "G-3Z6HS96PEK"
	};
	// Initialize Firebase
	firebase.initializeApp(firebaseConfig);

	var messaging = firebase.messaging();
  // IDs of divs that display registration token UI or request permission UI.
  const tokenDivId = 'token_div';
  const permissionDivId = 'permission_div';

  // Handle incoming messages. Called when:
  // - a message is received while the app has focus
  // - the user clicks on an app notification created by a service worker
  //   `messaging.onBackgroundMessage` handler.

  /*
  messaging.onMessage((payload) => {
    console.log('Message received. ', payload);
    appendMessage(payload);
  });
  */

  function resetUI() {
    //clearMessages();
    showToken('loading...');
    messaging.getToken({vapidKey: 'BM_gKXG7j_3FWtXy26Pxu3sefyBlI6yvB0wWCIqf_OFX_FsYVJ5bXG1sD4XsSFWqEw-OEA22y0BidK155X3Qwrk'}).then((currentToken) => {
      if (currentToken) {
        sendTokenToServer(currentToken);
        updateUIForPushEnabled(currentToken);
      } else {
        console.log('No registration token available. Request permission to generate one.');
        updateUIForPushPermissionRequired();
        setTokenSentToServer(false);
      }
    }).catch((err) => {
      console.log('An error occurred while retrieving token. ', err);
      //showToken('Error retrieving registration token. ', err);
	  updateUIForPushPermissionRequired();
      setTokenSentToServer(false);
    });
  }


  function showToken(currentToken) {
    // Show token in console and UI.
    const tokenElement = document.querySelector('#token');
    tokenElement.textContent = currentToken;
  }

  // Send the registration token your application server, so that it can:
  // - send messages back to this app
  // - subscribe/unsubscribe the token from topics
  function sendTokenToServer(currentToken) {
	// Send the current token to your server.
	url = "https://appsnocontrabando.com/api/dispositivo/"; // tipo:	1=IOS, 2=ANDROID, 3=WEB
	$.ajax({
		type: "POST",
		url:url, 
		data: { "token": currentToken,  "tipo": 3 },
		success: function(data) {
		//alert("id: "+ data.id);
		}
	});	

    if (!isTokenSentToServer()) {
      console.log('Sending token to server...');  
      setTokenSentToServer(true);
    } else {
      console.log('Token already sent to server so won\'t send it again unless it changes');
    }
  }

  function isTokenSentToServer() {
    return window.localStorage.getItem('sentToServer') === '1';
  }

  function setTokenSentToServer(sent) {
    window.localStorage.setItem('sentToServer', sent ? '1' : '0');
  }

  function showHideDiv(divId, show) {
    const div = document.querySelector('#' + divId);
    if (show) {
      div.style = 'display: visible';
    } else {
      div.style = 'display: none';
    }
  }

  function requestPermission() {
    console.log('Requesting permission...');
    Notification.requestPermission().then((permission) => {
      if (permission === 'granted') {
        console.log('Notification permission granted.');
        resetUI();
      } else {
        console.log('Unable to get permission to notify.');
      }
    });
  }

  function deleteToken() {
    // Delete registration token.
    messaging.getToken().then((currentToken) => {
      messaging.deleteToken(currentToken).then(() => {
        console.log('Token deleted.');
		url = "https://appsnocontrabando.com/api/baja/"; 
		$.ajax({
			type: "POST",
			url:url, 
			data: { "token": currentToken },
			success: function(data) {
			  //alert("id: "+ data.id);
			}
		  });

        setTokenSentToServer(false);
        resetUI();
      }).catch((err) => {
        console.log('Unable to delete token. ', err);
      });
    }).catch((err) => {
      console.log('Error retrieving registration token. ', err);
      showToken('Error retrieving registration token. ', err);
    });
  }

  /*
  // Add a message to the messages element.
  function appendMessage(payload) {
    const messagesElement = document.querySelector('#messages');
    const dataHeaderElement = document.createElement('h5');
    const dataElement = document.createElement('pre');
    dataElement.style = 'overflow-x:hidden;';
    dataHeaderElement.textContent = 'Received message:';
    dataElement.textContent = payload.notification.title; //JSON.stringify(payload, null, 2);
    messagesElement.appendChild(dataHeaderElement);
    messagesElement.appendChild(dataElement);
  }


  // Clear the messages element of all children.
  function clearMessages() {
    const messagesElement = document.querySelector('#messages');
    while (messagesElement.hasChildNodes()) {
      messagesElement.removeChild(messagesElement.lastChild);
    }
  }
*/

  function updateUIForPushEnabled(currentToken) {
    showHideDiv(tokenDivId, true);
    showHideDiv(permissionDivId, false);
    showToken(currentToken);
  }

  function updateUIForPushPermissionRequired() {
    showHideDiv(tokenDivId, false);
    showHideDiv(permissionDivId, true);
  }

  resetUI();


/*
	messaging.requestPermission()
	.then(function() {
		console.log('Se han aceptado las notificaciones');
	})
	.catch(function(err) {
		mensajeFeedback(err);
		console.log('No se ha recibido permiso / token: ', err);
	});
*/