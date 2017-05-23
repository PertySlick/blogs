/*
 * File Name: blogs.js
 * Author: Timothy Roush
 * Date Created: 5/20/17
 * Assignment: The Blogs Site
 * Description: Custom JavaScripts For Blogs Site
 */

// Script to handle "jury-rigged" custom file upload input
$(document).on('change', ':file', function() {
  var input = $(this);
  var label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
  input.trigger('fileselect', [label]);
  });

$("#lame").click(function() {
    $("#image").click();
})

$(document).ready( function() {
      $(':file').on('fileselect', function(event, label) {

          var input = $(this).parents('.input-group').find(':text');

          if( input.length ) {
              input.val(label);
          } else {
              if( label ) alert(label);
          }

      });
  });