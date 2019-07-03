$(document).click(function (e) {
    if (e.target.id === "user_post_content")
    {
        $('#user_post_content').height(66);
        $('.quacker-btn-displayer').show();
    }
    else
    {
        $('#user_post_content').height(22);
        $('.quacker-btn-displayer').hide();
    }
});
$('.submit-post_maker').click(function () {
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
            }
        }
    });
});