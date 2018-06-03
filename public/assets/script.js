$(function(){

  /* Global JS to enable the close button on modals */

  function closeActiveModal() {
    $(".modal.is-active").removeClass("is-active");
  }

  $(document).keyup(function(e){
    if(e.keyCode == 27) {
      closeActiveModal();
    }
  });

  $(".modal-background, .modal button.delete").click(function(e){
    closeActiveModal();
    e.preventDefault();
  });

  /* add http:// to URL fields on blur */
  $("input[type=url]").on("blur", function(){
    if(!$(this).val().match(/^https?:/)) {
      $(this).val("http://"+$(this).val());
    }
  });

});


