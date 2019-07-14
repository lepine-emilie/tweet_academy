let waiting_quacks = 0;
$('.button-div-actu').hide();
$('.timeline').prepend("<div class='" +
    " wrapper_new-posts'></div>");
(async function loopActualisation(){
    await sleep(5);
    $.post(
        '/actualisation',
        function (data) {
            if (data.success === true)
            {
                $($('.wrapper_new-posts')[0]).hide();
                for (let value of data.htmlTemplate) {
                    $($('.wrapper_new-posts')[0]).prepend(value);
                }

                waiting_quacks += data.htmlTemplate.length;
                let quack = waiting_quacks === 1 ? 'Quack' : 'Quacks';
                $('.new_posts_btn')
                    .addClass("text-primary")
                    .html("Show "+waiting_quacks+" new "+ quack);
                $('.button-div-actu').slideDown('fast');
                $('.button-div-actu').css('opacity', '0');
                $('.button-div-actu').fadeTo('fast', '1');
            }
        },
        'json'
    );
    loopActualisation();
})();
$('.button-div-actu').click(async function () {
    waiting_quacks = 0;
    $('.button-div-actu').animate({opacity: 0}, '100', function () {
        $('.new_posts_btn').html('<div class="spinner-border text-primary"' +
            ' role="status"></div>');
    });
    $('.button-div-actu').delay('100').fadeTo('fast', '1');
    await sleep(1);
    $($('.wrapper_new-posts')[0]).slideDown('fast');
    $($('.wrapper_new-posts')[0]).css('opacity', '0');
    $($('.wrapper_new-posts')[0]).fadeTo('fast', 1);
    $('.timeline').prepend("<div class='" +
        " wrapper_new-posts'></div>");
    $('.button-div-actu').slideUp('fast');
});
function sleep(secs)
{
    var ms = secs * 1000;
    return new Promise(resolve =>
        setTimeout(resolve, ms));
}