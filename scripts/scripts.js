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

// Add event listeners to layer checkboxes
document.querySelectorAll('.dropdown input[type="checkbox"]').forEach((checkbox) => {
  checkbox.addEventListener('change', (event) => {
    const layerName = event.target.getAttribute('data-layer');
    const iframe = document.querySelector('.map-container iframe');

    // Send a message to the iframe to toggle the layer
    iframe.contentWindow.postMessage({ action: 'toggleLayer', layerName }, '*');
  });
});