$(document).ready(function(){
   $('[data-toggle="offcanvas"]').click(function(e){
       $("#navigation").toggleClass("hidden-xs");
   });
});

$(".delete").on("submit", function(e){
        return confirm("Are you sure to delete?");
 });

$(".scrap").on("submit", function(e){
        return confirm("Are you sure to move to scrap?");
 });

