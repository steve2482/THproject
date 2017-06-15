document.addEventListener("DOMContentLoaded", function(event) {
  const form = document.getElementById('sub-form');
  const errorHTML = document.getElementById('js-errors');

  // Submit Form Event Listener
  form.addEventListener('keyup', (e) => {
    e.preventDefault();
    errorHTML.innerHTML = '';
    validateForm();
  });

  // Form Validation
  function validateForm() {
    // If name and email are empty
    if (!form['name'].value && !form['email'].value) {
      errorHTML.innerHTML += 'Name & Email fields are required!';
    }
    // If name is empty 
    else if (!form['name'].value) {
      errorHTML.innerHTML += 'Name field is required!';
    }
    // If email is empty
    else if (!form['email'].value) {
      errorHTML.innerHTML += 'Email field is required!';
    } 
    // Else name and email have values, validate email
    else {
      validateEmail(form['email'].value);
    }
  }

  // Email Validation
  function validateEmail(email) {  
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    // If email format is good 
    if(email.match(mailformat)) { 
      return true;  
    } 
    // Else email format fails
    else {  
      errorHTML.innerHTML += 'You have entered an invalid email address!';  
      return false;  
    }  
  }  
});
