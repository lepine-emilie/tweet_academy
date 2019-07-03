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