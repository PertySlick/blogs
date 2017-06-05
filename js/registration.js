$(document).ready(function() {
  $('#userName').blur(checkUserName);
  $('[type=password]').blur(checkVerify);
  $('#password').blur(checkPassword);
  $('#email').blur(validateEmail);
  $('#bio').keyup(sanitizeHTML);
  $('#registration').on('submit', validateForm);

});

var isError = false;

function validateForm(event) {
  clearAllStatus();
  checkUserName();
  checkVerify();
  validateEmail();
  checkRequired();
  if (isError) event.preventDefault();
}


function checkUserName() {
  var userName = $('#userName');
  
  if (userName.val() !== null && userName.val() !== '') {
    $.post('model/ajax.php', {action: 'userExists', userName: userName.val()}, function(data) {
      if (data == 'true') postError(userName, 'This user name is already taken');
      else if (data == 'false') postSuccess(userName);
      else userName.val('Error Checking User Name');
    });
  } else {
    clearStatus(userName);
  }
}


// Make sure password meets requirements (8 characters, at least 1 digit and special)
function checkPassword() {
  var password = $('#password');
  var pattern = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/;
  
  if (password.val() !== '' && !pattern.test(password.val())) {
    postError(password, '6 characters including a number and special character');
    console.log(pattern.test(password.val()));
  }
}


// Check that password and verify values match
function checkVerify() {
  var password = $('#password');
  var verify = $('#verify');
  
  if (password.val() != verify.val()) {
    postError(password, 'Password and Verify values must match');
    postError(verify, 'Password and Verify values must match');
  } else {
    clearStatus(password);
    clearStatus(verify);
    checkPassword();
  }
}


// Validate email format
function validateEmail() {
  var email = $('#email');
  var pattern = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
  
  if (email.val() !== '' && !pattern.test(email.val())) {
    postError(email, 'This is not a valid email format');
  } else {
    clearStatus(email);
  }
}


// Remove html tags from bio input
function sanitizeHTML() {
  var bio = $("#bio");
  bio.val(bio.val().replace(/<(.|\n)*?>/g, ""));
}


// Check all required fields
function checkRequired() {
  $('[data-required=true]').each(function() {
    if ($(this).val() === null || $(this).val() === '') {
      postError($(this), 'This field is required');
    }
  });
}



// Toggle Bootstrap error class on a particular field
function postError(field, message) {
  isError = true;
  field.parent().toggleClass('has-error', true);
  field.siblings('.help-block').text(message);
}


// Remove all status indicator classes from a single field
function clearStatus(field) {
  field.parent().toggleClass('has-error', false);
  field.parent().toggleClass('has-success', false);
  field.siblings('.help-block').text('');
}


// Clear all status indicator classes from all fields
function clearAllStatus() {
  isError = false;
  $('input, textarea').each(function() {
    $(this).parent().toggleClass('has-error', false);
    $(this).parent().toggleClass('has-success', false);
    $(this).siblings('.help-block').text('');
  });
}


// Toggle Bootstrap success class on a particular field
function postSuccess(field) {
  clearStatus(field);
  field.parent().toggleClass('has-success', true);
}


