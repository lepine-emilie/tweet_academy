$(document).click(function (e) {
    if (e.target.classList.contains('emojionearea-editor'))
    {
        $('.emojionearea-editor').animate({height: 80}, "fast");
        $('.post_maker-wrapper').animate({height: 134}, "fast");
        $('.quacker-btn-displayer').fadeTo("fast", 1);
    }
    else
    {
        $('.emojionearea-editor').animate({height: 36}, "fast");
        $('.post_maker-wrapper').animate({height: 52}, "fast");
        $('.quacker-btn-displayer').fadeTo("fast", 0);
    }
});
$(document).on('keyup', '.emojionearea', function () {
    $('.suggestions').html('');
    let regex = /@([A-Za-z0-9])\w+/g;
    let myVal = $('.emojionearea-editor').html();
    let revVal = myVal.split('').reverse().join('');
    let spaceKey = revVal.indexOf((' '));
    let myCutString = revVal.substr(0, spaceKey);
    if (spaceKey === -1)
        myCutString = revVal;
    myCutString = myCutString.split('').reverse().join('');
    console.log(myCutString);
    if (regex.test(myCutString))
    {
        $.post(
            "/completionSuggest",
            {
                'entry': myCutString
            },
            function (data) {
                if (data.success === true)
                {
                    let content = "";
                    for (let value of data.htmlTemplate) {
                        content += value;
                    }
                    $('.suggestions').html(content);
                }
                else
                {
                    console.log("Broken duck");
                }
            },
            'json'
        );
    }
});
$(document).on('click' , '.mini_user_card', function () {
    let username = $(this).find('.username').html();
    let myVal = $('.emojionearea-editor').html();
    let revVal = myVal.split('').reverse().join('');
    let spaceKey = revVal.indexOf((' '));
    let cuttedVal = revVal.substr(spaceKey);
    if (spaceKey === -1)
        cuttedVal = '';
    cuttedVal = cuttedVal.split('').reverse().join('');
    $('.emojionearea-editor').html(cuttedVal + username);
    $('.suggestions').html('');
});
$('.submit-post_maker').click(function () {
    if ($('.emojionearea-editor').text().length > 140)
    {
        alert("Broken Duck, too many characters in your quack (140 max)");
        return;
    }
    $.ajax({
        type: 'POST',
        url: '/post/submit',
        data: new FormData(document.getElementById("formPostMaker")),
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.success === false)
                alert("Broken duck");
            else
            {
                $('.emojionearea-editor').html('');
                $('#user_post_media_url').val('');
                $('.timeline').prepend(data.htmlTemplate);
                $($('.card-timeline')[0]).hide();
                $($('.card-timeline')[0]).css('opacity', '0');
                $('.suggestions').html('');
                animate_timeline();
                $('.timeline').prepend("<div class='wrapper_new-posts'>"+
                $($('.wrapper_new-posts')[0]).html() +
                "</div>");
                $($('.wrapper_new-posts')[0]).hide();
                $($('.wrapper_new-posts')[1]).html('');
            }
        }
    });
});
function animate_timeline()
{
    $($('.card-timeline')[0]).slideDown("fast");
    $($('.card-timeline')[0]).fadeTo("fast", 1);
}
$('.add-file-post_maker').click(function () {
    $('.inputUpload').trigger('click');
});