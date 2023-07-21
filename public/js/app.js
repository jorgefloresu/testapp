// Registering Service Worker
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/js/sw.js');
}
  
if (navigator.onLine) {
    console.log("online");
} else {
    console.log("offline");
}
  