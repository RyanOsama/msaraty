importScripts('https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging-compat.js');

firebase.initializeApp({
  apiKey: "AIzaSyABNN8WBK-uWXtYrqaQyuebIL700BTx2EQ",
  authDomain: "msarat-system.firebaseapp.com",
  projectId: "msarat-system",
  storageBucket: "msarat-system.firebasestorage.app",
  messagingSenderId: "639464793085",
  appId: "1:639464793085:web:df2b522a0535bb04b9ef94"
});

const messaging = firebase.messaging();