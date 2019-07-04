$(document).click(function (e) {
    if (e.target.id === "user_post_content")
    {
        $('#user_post_content').animate({height: 80}, "fast");
        $('.post_maker-wrapper').animate({height: 134}, "fast");
        $('.quacker-btn-displayer').fadeTo("fast", 1);
    }
    else
    {
        $('#user_post_content').animate({height: 36}, "fast");
        $('.post_maker-wrapper').animate({height: 52}, "fast");
        $('.quacker-btn-displayer').fadeTo("fast", 0);
    }
});
$('.submit-post_maker').click(function () {
    if ($('.post_maker textarea').val().length > 140)
    {
        alert("Broken Duck, too many characters in your quack (140 max)");
        return;
    }
    $.ajax({
        type: 'POST',
        url: '/',
        data: $('.post_maker form').serialize(),
        dataType: 'json',
        success: function (data) {
            if (data.success === false)
                alert("Broken duck");
            else
            {
                $('#user_post_content').val('');
                $('.timeline').prepend(data.htmlTemplate);
                $($('.card-timeline')[0]).hide();
                $($('.card-timeline')[0]).css('opacity', '0');
                animate_timeline();
            }
        }
    });
});
function animate_timeline()
{
    $($('.card-timeline')[0]).slideDown("fast");
    $($('.card-timeline')[0]).fadeTo("fast", 1);
}