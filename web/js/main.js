$(document).ready(function() {
  $('.scrollTo').click( function() { // Au clic sur un élément
    var page = $(this).attr('href'); // Page cible
    var speed = 750; // Durée de l'animation (en ms)
    $('html, body').animate( { scrollTop: $(page).offset().top }, speed ); // Go
    return false;
  });
});

// Vérifcation formulaire
/* $(function() {
  $("#form-login").click(function() {
    valid = true;
    $("#inputPassword").keyup(function() {
      if(!$("#inputPassword").val().match(/^[a-zA-Z0-9]{6}$/i)) {
        $("#inputPassword").tooltip('show');
        valid = false;
      }
      else {
        $("#inputPassword").tooltip('hide');
        valid = true;
      }
    });
    return valid;
  });
}); */
