$(document).ready(function(){
    $(this).on('click', '#dai_navbar .dropdown-toggle', function(that) {
    	console.log('hi', that);
    	$(this).parent().find('.dropdown-menu').toggle();
    });
});