/*
		<div id="token_div" style="display: none;">
			<h4>Registration Token</h4>
			<p id="token" style="word-break: break-all;"></p>
			<button onclick="deleteToken()">Delete Token</button>
		</div>

		<div id="permission_div" style="display: none;">
			<h4>Needs Permission</h4>
			<p id="token"></p>
			<button onclick="requestPermission()">Request Permission</button>
		</div>

		<div id="messages"></div>
*/		

	
	var firebaseConfig = {
		apiKey: "AIzaSyC0q7hQgHENru540ziaG62xtVSV4LJ2Jx0",
		authDomain: "nocontrabando-notificaciones.firebaseapp.com",
		projectId: "nocontrabando-notificaciones",
		storageBucket: "nocontrabando-notificaciones.appspot.com",
		messagingSenderId: "400235843880",
		appId: "1:400235843880:web:4ec573c51397afcb2b001d",
		measurementId: "G-3Z6HS96PEK"
	};

	firebase.initializeApp(firebaseConfig);

	var messaging = firebase.messaging();

  messaging.onMessage(function(payload) {

    console.log('[firebase-init.js] Message received ', payload);

    var title = payload.notification.title;
    var body = payload.notification.body;
    var icon = payload.notification.icon; 

    var notificacion_id = payload.data.notificacion_id; 
    var clic_action = payload.data.click_action; 

    const notificationTitle = title;
    const notificationOptions = {
      body: body,
      icon: icon,
      clic_action: clic_action
    };
    /*
    self.registration.showNotification(notificationTitle, notificationOptions);
    */
  });


  function resetUI() {
    messaging.getToken({vapidKey: 'BM_gKXG7j_3FWtXy26Pxu3sefyBlI6yvB0wWCIqf_OFX_FsYVJ5bXG1sD4XsSFWqEw-OEA22y0BidK155X3Qwrk'}).then((currentToken) => {
      if (currentToken) {
        sendTokenToServer(currentToken);
      } else {
        console.log('No registration token available. Request permission to generate one.');
        setTokenSentToServer(false);
      }
    }).catch((err) => {
      console.log('An error occurred while retrieving token. ', err);
      setTokenSentToServer(false);
    });
  }


  function sendTokenToServer(currentToken) {
	// Send the current token to your server.
	url = "https://appsnocontrabando.com/api/dispositivo/"; // tipo:	1=IOS, 2=ANDROID, 3=WEB
	$.ajax({
		type: "POST",
		url:url, 
		data: { "token": currentToken,  "tipo": 3 },
		success: function(data) {
		  //alert("id: "+ data.id);
      //alert("sql: "+ data.sql);
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
    });
  }

  resetUI();