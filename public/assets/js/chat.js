$('#send').click(function( event ) {
    event.preventDefault();
    $.ajax({
        type: 'POST',
        url: '/messages',
        data: new FormData(document.getElementById("formMessages")),
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function (data) {
            alert('ohoh');
        }
    });
});
$('.start_conv')