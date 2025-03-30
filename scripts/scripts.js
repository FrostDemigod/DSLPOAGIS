"use strict";

// constant fucntion to verify emails.
const checkEmail = str => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(str);

// Selector for the side panel
document.querySelector(".side-panel-toggle").addEventListener("click", () => {
  document.querySelector(".wrapper").classList.toggle("side-panel-open");
});
document.querySelector(".side-panel-close").addEventListener("click", () => {
  document.querySelector(".wrapper").classList.toggle("side-panel-open");
});

const openModal = document.querySelector(".modal-form-open");
const closeModal = document.querySelector(".modal-form-close");
const formModal = document.querySelector("[add-pin-modal]");

openModal.addEventListener("click", () => {
  formModal.showModal();
})
closeModal.addEventListener("click", () => {
  document.getElementById('latitude').value = null
  document.getElementById('longitude').value = null
  formModal.close();
})

// Event listener to close dialog if you ever click off it
// doesnt work right now
/*dialog.addEventListener("click", e => {
  const dialogDimensions = dialog.getBoundingClientRect()
  if (
    e.clientX < dialogDimensions.left ||
    e.clientX > dialogDimensions.right ||
    e.clientY < dialogDimensions.top ||
    e.clientY > dialogDimensions.bottom
  ) {
    dialog.close()
  }  
})*/

// Zoom Functionality
const iframe = document.getElementById('map-frame');
const zoomInBtn = document.getElementById('zoom-in');
const zoomOutBtn = document.getElementById('zoom-out');

// Initial zoom level
let zoomLevel = 1;

// Function to zoom in
if (zoomInBtn && zoomOutBtn && iframe) {
    zoomInBtn.addEventListener('click', () => {
        zoomLevel += 0.2;  // Increase zoom level
        iframe.style.transform = `scale(${zoomLevel})`;
        iframe.style.transformOrigin = '0 0';  // Keep zoom anchored at the top-left
    });

    // Function to zoom out
    zoomOutBtn.addEventListener('click', () => {
        zoomLevel -= 0.2;  // Decrease zoom level
        if (zoomLevel < 0.5) zoomLevel = 0.5;  // Limit minimum zoom level
        iframe.style.transform = `scale(${zoomLevel})`;
        iframe.style.transformOrigin = '0 0';
    });
}

//Location usage doesnt work rn
const http = new XMLHttpRequest()

document.querySelector('.use-loc').addEventListener('click', function() {
  getCoords();
});

function getCoords(){

  if(navigator.geolocation){
    navigator.geolocation.getCurrentPosition((position) => {
      document.getElementById('latitude').value = position.coords.latitude
      document.getElementById('longitude').value = position.coords.longitude
    },
  (err) => {
    alert(err.message)
  })
  }
  else{
    alert("Your browser does not support the location services")
  }
}

// zoom in and zooom out functionality for map and main.html
document.querySelector('.zoom-in').addEventListener('click', () => {
  const iframe = document.querySelector('.map-container iframe');
  iframe.contentWindow.postMessage('zoomIn', '*');
});

document.querySelector('.zoom-out').addEventListener('click', () => {
  const iframe = document.querySelector('.map-container iframe');
  iframe.contentWindow.postMessage('zoomOut', '*');
});

// Help Modal Functionality
const helpButton = document.querySelector(".help-btn");
const helpModal = document.querySelector(".help-modal");
const closeModalButton = document.querySelector(".modal-close");

helpButton.addEventListener("click", () => {
  helpModal.showModal();
});

closeModalButton.addEventListener("click", () => {
  helpModal.close();
});