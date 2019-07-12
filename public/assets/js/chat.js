$('#send').click(function( event ) {
    event.preventDefault();
    var url = window.location.href;
    $.ajax({
        type: 'POST',
        url: url,
        data: new FormData(document.getElementById("formMessages")),
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function (data) {
            var msg  = $("<div class='message_user'></div>")
                .append(
                    $("<div></div>")
                        .addClass("message_user_manager")
                        .append(
                    $("<div></div>")
                        .addClass("message_user_container")
                        .append(
                    $("<span></span>")
                        .addClass("content")
                        .text(data.message),
                    $("<span></span>")
                        .addClass("date_user")
                        .text(" : " + data.time)
                )))
                .appendTo(".chat_conversation");

            $('#chat_message_content').val('');
        }
    });
});