"use strict";

// constant fucntion to verify emails.
const checkEmail = str => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(str);

// Selector for the side panel
document.querySelector(".side-panel-toggle").addEventListener("click", () => {
  document.querySelector(".wrapper").classList.toggle("side-panel-open");
});

const openModal = document.querySelector(".modal-form-open");
const closeModal = document.querySelector(".modal-form-close");
const formModal = document.querySelector("[add-pin-modal]");

openModal.addEventListener("click", () => {
  formModal.showModal();
})
closeModal.addEventListener("click", () => {
  formModal.close();
})

// Event listener to close dialog if you ever click off it
// doesnt work right now
dialog.addEventListener("click", e => {
  const dialogDimensions = dialog.getBoundingClientRect()
  if (
    e.clientX < dialogDimensions.left ||
    e.clientX > dialogDimensions.right ||
    e.clientY < dialogDimensions.top ||
    e.clientY > dialogDimensions.bottom
  ) {
    dialog.close()
  }
})