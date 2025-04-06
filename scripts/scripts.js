"use strict";

// Constant function to verify emails.
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
});
closeModal.addEventListener("click", () => {
  document.getElementById('latitude').value = null;
  document.getElementById('longitude').value = null;
  formModal.close();
});

// Zoom Functionality
const iframe = document.getElementById('map-frame');
const zoomInBtn = document.getElementById('zoom-in');
const zoomOutBtn = document.getElementById('zoom-out');

// Initial zoom level
let zoomLevel = 1;

if (zoomInBtn && zoomOutBtn && iframe) {
    zoomInBtn.addEventListener('click', () => {
        zoomLevel += 0.2;  
        iframe.style.transform = `scale(${zoomLevel})`;
        iframe.style.transformOrigin = '0 0'; 
    });

    zoomOutBtn.addEventListener('click', () => {
        zoomLevel -= 0.2;  
        if (zoomLevel < 0.5) zoomLevel = 0.5;  
        iframe.style.transform = `scale(${zoomLevel})`;
        iframe.style.transformOrigin = '0 0';
    });
}

// Location functionality
document.querySelector('.use-loc').addEventListener('click', () => {
  getCoords();
});

function getCoords() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition((position) => {
      document.getElementById('latitude').value = position.coords.latitude;
      document.getElementById('longitude').value = position.coords.longitude;
    },
    (err) => {
      alert(err.message);
    });
  } else {
    alert("Your browser does not support location services");
  }
}

// Zoom in and zoom out functionality for map and main.html
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
// Help Modal Functionality
const helpButton = document.querySelector(".help-button");

// Help messages for dialog chain with images
const helpMessages = [
  {
    text: "Use your mouse or touch gestures to navigate the map. Click and drag to move, and use the zoom buttons to zoom in and out.",
    img: "images/Zoom.png"
  },
  {
    text: "Click the 'Add Pin' button to mark specific locations. You can add a description, upload an image, and save your pin for future reference once you have an account.", /* final */
    img: "images/Add.png"
  },
  {
    text: "Use the 'Map Layers' button to toggle between different layers, such as Bird Watching Points, Hazard Areas, etc.",
    img: "images/Layers.png"
  },
  {
    text: "Turn layers on and off to customize your view!",
    img: "images/Cust.png"
  },
  {
    text: "Click on pins or layers to view detailed information, including coordinates, descriptions, and related data.",
    img: "images/Bird.png"
  },
  {
    text: "If the map does not load properly refresh the page and check your internet connection.",
    img: "images/Wifi.png"
  }
];

let currentMessageIndex = 0;

// Function to show the initial emoji help page
function showEmojiHelp() {
  const emojiModal = document.createElement('dialog');
  emojiModal.classList.add('help-modal');
  emojiModal.style.width = "80%";
  emojiModal.style.maxWidth = "500px";
  emojiModal.innerHTML = `
    <div style="text-align: center; padding: 2rem; font-size: 1.5rem;">
      <p>Welcome to the Help Section!</p>
      <div style="font-size: 4rem; display: flex; justify-content: space-around; margin: 1.5rem 0;">
        <span>🗺️</span>
        <span>📍</span>
        <span>🔍</span>
      </div>
      <p>Explore the map, add pins, and zoom in or out!</p>
      <div style="display: flex; justify-content: space-between; margin-top: 1rem;">
        <button class="btn-close" style="padding: 0.5rem 1.5rem; background: #dc3545; color: white; border: none; cursor: pointer;">Close</button>
        <button class="btn-next" style="padding: 0.5rem 1.5rem; background: #007bff; color: white; border: none; cursor: pointer;">Next</button>
      </div>
    </div>
  `;
  
  document.body.appendChild(emojiModal);
  emojiModal.showModal();

  // Close button functionality
  emojiModal.querySelector(".btn-close").addEventListener("click", () => {
    emojiModal.close();
    emojiModal.remove();
  });

  // Next button leads to dialog chain
  emojiModal.querySelector(".btn-next").addEventListener("click", () => {
    emojiModal.close();
    emojiModal.remove();
    currentMessageIndex = 0;  // Reset index before starting the chain
    showHelpDialog();
  });
}

// 🌟 Function to display dialog chain
function showHelpDialog() {
  if (currentMessageIndex < helpMessages.length) {
    const dialog = document.createElement('dialog');
    dialog.classList.add('help-modal');
    dialog.innerHTML = `
      <div style="padding: 1.5rem; font-size: 1.2rem;">
        <p>${helpMessages[currentMessageIndex].text}</p>
        <img src="${helpMessages[currentMessageIndex].img}" alt="Help Image" style="width: 100%; height: auto; margin-top: 1rem;">
        <div style="display: flex; justify-content: space-between; margin-top: 1rem;">
          <button class="btn-close" style="padding: 0.5rem 1.5rem; background: #dc3545; color: white; border: none; cursor: pointer;">Close</button>
          <button class="btn-next" style="padding: 0.5rem 1.5rem; background: #007bff; color: white; border: none; cursor: pointer;">Next</button>
        </div>
      </div>
    `;
    
    document.body.appendChild(dialog);
    dialog.showModal();

    // Close button functionality
    dialog.querySelector('.btn-close').addEventListener('click', () => {
      dialog.close();
      dialog.remove();
      currentMessageIndex = 0;  // Reset the chain
    });

    // Next button functionality
    dialog.querySelector('.btn-next').addEventListener('click', () => {
      dialog.close();
      dialog.remove();
      currentMessageIndex++;
      if (currentMessageIndex < helpMessages.length) {
        showHelpDialog();
      } else {
        currentMessageIndex = 0;  // Reset the chain
      }
    });
  }
}

// 🌟 Event listener for Help button
helpButton.addEventListener("click", () => {
  showEmojiHelp();
});
