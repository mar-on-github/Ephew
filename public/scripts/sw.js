  import { precacheAndRoute } from 'workbox-precaching/precacheAndRoute';

  precacheAndRoute([{"revision":"3e1fc3346e1cf5b0403c12122289ca7a","url":"styles/dark.css"},{"revision":"ca84341f76121295ff642b1f2e391962","url":"styles/ephew-base.css"},{"revision":"4e8905653078a8cdfceab5fdac018a57","url":"styles/light.css"},{"revision":"ff6f1425f7b1dc881b26d2b4768796da","url":"styles/sepia.css"},{"revision":"048274bc674a52942e6783d20726543b","url":"styles/snow.css"}]);

  let deferredPrompt; // Allows to show the install prompt
const installButton = document.getElementById("install_button");
window.addEventListener("beforeinstallprompt", e => {
  console.log("beforeinstallprompt fired");
  // Prevent Chrome 76 and earlier from automatically showing a prompt
  e.preventDefault();
  // Stash the event so it can be triggered later.
  deferredPrompt = e;
  // Show the install button
  installButton.hidden = false;
  installButton.addEventListener("click", installApp);
});
function installApp() {
  // Show the prompt
  deferredPrompt.prompt();
  installButton.disabled = true;

  // Wait for the user to respond to the prompt
  deferredPrompt.userChoice.then(choiceResult => {
    if (choiceResult.outcome === "accepted") {
      console.log("PWA setup accepted");
      installButton.hidden = true;
    } else {
      console.log("PWA setup rejected");
    }
    installButton.disabled = false;
    deferredPrompt = null;
  });
}
