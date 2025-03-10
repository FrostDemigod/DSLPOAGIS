"use strict";

// constant fucntion to verify emails.
const checkEmail = str => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(str);

// validation.js content
document.addEventListener('DOMContentLoaded', function () {

  // Register form validation
  const registerForm = document.getElementById('register-form');
  if (registerForm) {

    // input and error declaration
    const nameInput = document.getElementById('name');
    const nameError = nameInput.nextElementSibling;

    const genderSelect = document.getElementById('gender');
    const genderError = genderSelect.nextElementSibling;

    const userInput = document.getElementById('username');
    const userError = userInput.nextElementSibling;

    const emailInput = document.getElementById('email');
    const emailError = emailInput.nextElementSibling;

    const passwordInput = document.getElementById('password');
    const passwordError = passwordInput.nextElementSibling;

    const cpassInput = document.getElementById('confirm_password');
    const cpassError = cpassInput.nextElementSibling;

    const titleInput = document.getElementById('title');
    const titleError = titleInput.nextElementSibling;

    const descInput = document.getElementById('description');
    const descError = descInput.nextElementSibling;

    registerForm.addEventListener("submit", (ev) => {
      let errors = false;
      // If validation fails, prevent form submission with ev.preventDefault()

      if (nameInput.value !== "") {
        nameError.classList.add('hidden');
      } else {
        nameError.classList.remove('hidden');
        errors = true;
      }

      // check if there was nothing selected in the dropdown and handle appropriately
      if (genderSelect.value != 0) {
        genderError.classList.add('hidden');
      } else {
        genderError.classList.remove('hidden');
        errors = true;
      }

      if (userInput.value !== "") {
        userError.classList.add('hidden');
      } else {
        userError.classList.remove('hidden');
        errors = true;
      }

      // check if email is valid and handle appropriately
      if (checkEmail(emailInput.value)) {
        emailError.classList.add('hidden');
      } else {
        emailError.classList.remove('hidden');
        errors = true;
      }

      if (passwordInput.value !== "") {
        passwordError.classList.add('hidden');
      } else {
        passwordError.classList.remove('hidden');
        errors = true;
      }

      if (cpassInput.value == passwordInput.value) {
        cpassError.classList.add('hidden');
      } else {
        cpassError.classList.remove('hidden');
        errors = true;
      }

      if (titleInput.value !== "") {
        titleError.classList.add('hidden');
      } else {
        titleError.classList.remove('hidden');
        errors = true;
      }

      if (descInput.value !== "") {
        descError.classList.add('hidden');
      } else {
        descError.classList.remove('hidden');
        errors = true;
      }

        // IF THERE ARE ERRORS, PREVENT FORM SUBMISSION
        if (errors)
        ev.preventDefault();
    });
  }
});


// validation.js content
document.addEventListener('DOMContentLoaded', function () {
  // Edit item form validation
  const editItemForm = document.getElementById('edit-form');
  if (editItemForm) {
    // input and error declaration
    const titleInput = document.getElementById('title');
    const titleError = titleInput.nextElementSibling;

    const descInput = document.getElementById('description');
    const descError = descInput.nextElementSibling;

    const statusButtons = Array.from(document.getElementsByName('status'));
    const statusError = statusButtons[2].closest('div').nextElementSibling;

    const detailsInput = document.getElementById('details');
    const detailsError = detailsInput.nextElementSibling;

    const proofInput = document.getElementById('proof');
    const proofError = proofInput.nextElementSibling;

    editItemForm.addEventListener("submit", (ev) => {
      let errors = false;
      // if validation fails prevent form submission

      if (titleInput.value !== "") {
        titleError.classList.add('hidden');
      } else {
        titleError.classList.remove('hidden');
        errors = true;
      }

      if (descInput.value !== "") {
        descError.classList.add('hidden');
      } else {
        descError.classList.remove('hidden');
        errors = true;
      }

      if (statusButtons.some(button => button.checked)) {
        statusError.classList.add('hidden');
      } else {
        statusError.classList.remove('hidden');
        errors = true;
      }

      if (detailsInput.value !== "") {
        detailsError.classList.add('hidden');
      } else {
        detailsError.classList.remove('hidden');
        errors = true;
      }

      if (proofInput.files.length != 0) {
        proofError.classList.add('hidden');
      } else {
        proofError.classList.remove('hidden');
        errors = true;
      }

        // IF THERE ARE ERRORS, PREVENT FORM SUBMISSION
        if (errors)
        ev.preventDefault();
    });
  }
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var listLink = document.getElementById("preview");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on the button, open the modal
    listLink.addEventListener('click', showModalPopUp);
    
    function showModalPopUp() {
      $('#myModal').modal('show');
      var options = {
        url: listLink.dataset.content, //Set the url of the page
        title: 'View Item', //Set the title for the pop up
        allowMaximize: false,
        showClose: true,
      width: 600,
      height: 400
    };

    //Invoke the modal dialog by passing in the options array variable
    SP.SOD.execute('sp.ui.dialog.js', 'SP.UI.ModalDialog.showModalDialog', options);
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
      modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
    const copyLinkBtn = document.getElementById('copyLinkBtn');

    if (copyLinkBtn) {
      copyLinkBtn.addEventListener('click', function () {
        const listItem = this.closest('li');
        const listLink = listItem.querySelector('a');
        const publicListLink = window.location.origin + '/view-item.php?id=' + encodeURIComponent(list_id); // Replace listId with the actual list ID
        copyToClipboard(publicListLink);
        alert('Public list link copied to clipboard!');
      });
    }
  
    function copyToClipboard(text) {
      const textarea = document.createElement('textarea');
      textarea.value = text;
      document.body.appendChild(textarea);
      textarea.select();
      document.execCommand('copy');
      document.body.removeChild(textarea);
    }
  
  
    function Toggle() {
      let temp = document.getElementById("password");
    
      if (temp.type === "password") {
        temp.type = "text";
      } else {
        temp.type = "password";
      }
    }

  });
