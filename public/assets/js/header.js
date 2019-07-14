$('.dropdown-btn').click(function () {
    $('.dropdown-menu').toggle();
});
$(document).click(function (e) {
    if (e.target.className !== 'dropdown-img-btn' &&
        e.target.className !== 'dropdown-item')
    {
        $('.dropdown-menu').hide();
    }
});
$('.logo-click').click(function (e) {
    e.preventDefault();
    var audioElement = new Audio('http://www.animal-sounds.org/farm/Duck-quacking%20animals039.wav');
    audioElement.play();
});
$('.search_bar').submit(function(e){
    var content = $('input[name=\'search_term\']').val();
    if (content === ""){
        e.preventDefault();
        alert("Please enter at least one character in the search field");
    }
});