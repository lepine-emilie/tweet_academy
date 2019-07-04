$(document).on('click', '.post-card__like-btn', function() {
    $postCard = $(this).parents('.post-card');
    postId = $(card).attr('data-post-id');

    $.ajax('/post/' + postId + '/like', {
        dataType: 'json',
        success: function(data) {

        }
    });
});

$(document).on('click', '.post-card__repost-btn', function() {
    $postCard = $(this).parents('.post-card');
    postId = $(card).attr('data-post-id');

    $.ajax('/post/' + postId + '/repost', {
        dataType: 'json',
        success: function(data) {

        }
    });
});

$(document).on('click', '.post-card__delete-btn', function() {
    $postCard = $(this).parents('.post-card');
    postId = $(card).attr('data-post-id');

    $.ajax('/post/' + postId + '/delete', {
        dataType: 'json',
        success: function(data) {

        }
    });
});