$(document).on("click", ".follow",( function (e){
    e.preventDefault();
    var username = window.location.pathname;

    if(username.substr(-6) === "/likes"){
        username = username.slice(0,-6);
    }
    else if(username.substr(-10) === "/followers"){
        username = username.slice(0,-10)
    }
    else if (username.substr(-10)=== "/following"){
        username = username.slice(0,-10)
    }
    $.ajax({
        url: username+"/follow",
        success(data){
            if (data.success === true){
                $('.button-profile').html("<button class=\"btn btn-primary profile-nav-btn unfollow\">Unfollow</button>\n");
            }
        }
    })
}));
$(document).on("click", ".unfollow",(function (e) {
    e.preventDefault();
    var username = window.location.pathname;

    if(username.substr(-6) === "/likes"){
        username = username.slice(0,-6);
    }
    else if(username.substr(-10) === "/followers"){
        username = username.slice(0,-10)
    }
    else if (username.substr(-10)=== "/following"){
        username = username.slice(0,-10)
    }
    $.ajax({
        url: username+"/unfollow",
        success(){
            $('.button-profile').html("<button class=\"btn btn-primary profile-nav-btn follow\">Follow</button>\n");
        }
    })
}));
$(document).on("click", ".follow-home",(function (e) {
    e.preventDefault();
    var username = $(this).attr("data-user-id");
    var self = $(this);
    $.ajax({
        url: username+"/follow",
        success(){
            self.parent(".list-item").fadeOut(300);
        }
    })
}));