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
                    $("<span></span>")
                        .addClass("date")
                        .text(data.time + " : "),
                    $("<span></span>")
                        .addClass("content")
                        .text(data.message)
                )
                .appendTo(".chat_conversation");

            $('#chat_message_content').val('');
        }
    });
});