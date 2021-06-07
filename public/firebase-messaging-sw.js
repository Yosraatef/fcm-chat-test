// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.
importScripts('https://www.gstatic.com/firebasejs/8.6.3/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.6.3/firebase-messaging.js');

// Initialize the Firebase app in the service worker by passing in
// your app's Firebase config object.
// https://firebase.google.com/docs/web/setup#config-object
// Your web app's Firebase configuration
var firebaseConfig = {
  apiKey: "AIzaSyAAA_w7SdZ7KVqbbAbzxI8lu6GuR6nrLCU",
  authDomain: "fcm-chat-new.firebaseapp.com",
  projectId: "fcm-chat-new",
  storageBucket: "fcm-chat-new.appspot.com",
  messagingSenderId: "242955858753",
  appId: "1:242955858753:web:c861e7b726ed8a497827c0"
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);
// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();


messaging.onBackgroundMessage((payload) => {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  // Customize notification here
  const title { title , body} = payload.notification;
  const notificationOptions = {
    body,
  };

  return self.registration.showNotification(title,
    notificationOptions);
});
