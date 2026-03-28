<!DOCTYPE html>
<html>
<head>

<title>Firebase Test</title>

<script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging.js"></script>

</head>

<body>

<h2>Get Firebase Token</h2>

<button onclick="getToken()">Get Token</button>

<p id="token"></p>

<script>

const firebaseConfig = {
  apiKey: "AIzaSyABNN8WBK-uWXtYrqaQyuebIL700BTx2EQ",
  authDomain: "msarat-system.firebaseapp.com",
  projectId: "msarat-system",
  storageBucket: "msarat-system.firebasestorage.app",
  messagingSenderId: "639464793085",
  appId: "1:639464793085:web:df2b522a0535bb04b9ef94"
};

firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

function getToken(){

Notification.requestPermission().then((permission)=>{

if(permission === "granted"){

messaging.getToken().then((currentToken)=>{

document.getElementById("token").innerText = currentToken;

fetch("http://localhost/msaraty/public/api/save-token",{

method:"POST",

headers:{
"Content-Type":"application/json"
},

body:JSON.stringify({
token: currentToken
})

});

});

}

});

}

</script>

</body>
</html>
