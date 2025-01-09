// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.
importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js');

// Initialize the Firebase app in the service worker by passing in
// your app's Firebase config object.
// https://firebase.google.com/docs/web/setup#config-object
firebase.initializeApp({
    apiKey: "AIzaSyCeDv6NNrAQX6kLBeqfMr1yOqjRg6NlSTI",
    authDomain: "eco-harmony.firebaseapp.com",
    projectId: "eco-harmony",
    storageBucket: "eco-harmony.appspot.com",
    messagingSenderId: "873875602448",
    appId: "1:873875602448:web:0cf52e175507249187de95",
    measurementId: "G-KBPGY7LHLQ"
  });

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();


messaging.onBackgroundMessage(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  // Customize notification here
  const notificationTitle = 'Background Message Title';
  const notificationOptions = {
    body: 'Background Message body.',
    icon: '/firebase-logo.png'
  };

  self.registration.showNotification(notificationTitle,
    notificationOptions);
});

messaging.setBackgroundMessageHandler(function(payload){
  const title = "Hello Wprld";
  const options = {
    body: payload.data.status
  }
  
  return self.registration.showNotification(title, options);
  })








