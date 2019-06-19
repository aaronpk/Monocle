$(function(){

  // Display all dates in the browser's locale and timezone
  // Stashes the original display date in the "title" element so you can still see it on hover
  $("time").map(function(i, el){
    $(el).attr("title", $(el).text().trim());
    var date = new Date($(el).attr("datetime"));
    if(!isNaN(date.getTime())) {
      $(el).text(date.toLocaleString());
    }
  });

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


  /***************************************************************
   * Lazy-load images
   * https://www.zachleat.com/web/facepile/
   */
  if( typeof IntersectionObserver !== "undefined" && "forEach" in NodeList.prototype ) {
    var observer = new IntersectionObserver(function(changes) {
      if("connection" in navigator && navigator.connection.saveData === true) {
        return;
      }
      changes.forEach(function(change) {
        if(change.isIntersecting) {
          if(change.target.getAttribute("data-lazy-style")) {
            change.target.setAttribute("style", change.target.getAttribute("data-lazy-style"));
          }
          if(change.target.getAttribute("data-lazy-src")) {
            change.target.setAttribute("src", change.target.getAttribute("data-lazy-src"));
          }
          observer.unobserve(change.target);
        }
      });
    });
    document.querySelectorAll("img[data-lazy-src],a[data-lazy-style]").forEach(function(img) {
      observer.observe(img);
    });
  } else {
    // IntersectionObserver is not supported
  }
  /****************************************************************/

});


// add http:// to URL fields on blur
// https://aaronparecki.com/2018/06/03/3/
document.addEventListener('DOMContentLoaded', function() {
  var elements = document.querySelectorAll("input[type=url]");
  Array.prototype.forEach.call(elements, function(el, i){
    el.addEventListener("blur", function(e){
      if(e.target.value.match(/^(?!https?:).+\..+/)) {
        e.target.value = "http://"+e.target.value;
      }
    });
  });
});


function parseQueryString(string) {
  if(string == "") { return {}; }
  var segments = string.split("&").map(s => s.split("=") );
  var queryString = {};
  segments.forEach(s => queryString[s[0]] = decodeURIComponent(s[1]));
  return queryString;
}


