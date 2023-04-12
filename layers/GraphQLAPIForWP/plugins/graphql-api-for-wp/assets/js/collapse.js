/**
 * Collapsible source: https://www.w3schools.com/howto/howto_js_collapsible.asp
 */
jQuery( document ).ready( function($){
    $('.collapsible').on('click', function(e){
        e.preventDefault();
        this.classList.toggle("active");
        var content = this.nextElementSibling;
        if (content.style.display === "block") {
            content.style.display = "none";
        } else {
            content.style.display = "block";
        }
    });
});
