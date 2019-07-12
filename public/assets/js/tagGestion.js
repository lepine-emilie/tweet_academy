(function checkForTags()
{
    let regex = /#([A-Za-z0-9])\w+/g;
    if (regex.test($('.timeline').find('p').html()))
    {
        $.each($('.timeline').find('p'), function (key, value) {
            let tags = $('.timeline').find('p')[key].innerHTML.match(regex);
            $.each(tags, function (index, tag) {
                makechanges(key, tag);
            });
        })

    }
})();
function makechanges(key, tag)
{
    let firstIndex =$('.timeline').find('p')[key].innerHTML
        .indexOf(tag);
    let endIndex = firstIndex + tag.length;
    let firstpart=$('.timeline').find('p')[key].innerHTML
        .substr(0, firstIndex);
    let secpart= $('.timeline').find('p')[key].innerHTML
        .substr(endIndex);
    let toMofify=$('.timeline').find('p')[key].innerHTML
        .substr(firstIndex, tag.length);
    $('.timeline').find('p')[key]
        .innerHTML = firstpart+"<a href='/hashtag/"+toMofify.substr(1)+
        "'>"+toMofify+"</a>"+secpart;
}
function checkForTagsActu()
{
    let regex = /#([A-Za-z0-9])\w+/g;
    if (regex.test($($('.wrapper_new-posts')[0]).find('p').html()))
    {
        $.each($($('.wrapper_new-posts')[0]).find('p'), function (key, value) {
            let tags = $('.timeline').find('p')[key].innerHTML.match(regex);
            $.each(tags, function (index, tag) {
                makechangesActu(key, tag);
            });
        })

    }
}
function makechangesActu(key, tag)
{
    let firstIndex =$($('.wrapper_new-posts')[0]).find('p')[key].innerHTML
        .indexOf(tag);
    let endIndex = firstIndex + tag.length;
    let firstpart=$($('.wrapper_new-posts')[0]).find('p')[key].innerHTML
        .substr(0, firstIndex);
    let secpart= $($('.wrapper_new-posts')[0]).find('p')[key].innerHTML
        .substr(endIndex);
    let toMofify=$($('.wrapper_new-posts')[0]).find('p')[key].innerHTML
        .substr(firstIndex, tag.length);
    $($('.wrapper_new-posts')[0]).find('p')[key]
        .innerHTML = firstpart+"<a href='/hashtag/"+toMofify.substr(1)+
        "'>"+toMofify+"</a>"+secpart;
}
function checkForTagsPost()
{
    let regex = /#([A-Za-z0-9])\w+/g;
    if (regex.test($($('.card-timeline')[0]).find('p').html()))
    {
        $.each($($('.card-timeline')[0]).find('p'), function (key, value) {
            let tags = $('.timeline').find('p')[key].innerHTML.match(regex);
            $.each(tags, function (index, tag) {
                makechangesPost(key, tag);
            });
        })

    }
}
function makechangesPost(key, tag)
{
    let firstIndex =$($('.card-timeline')[0]).find('p')[key].innerHTML
        .indexOf(tag);
    let endIndex = firstIndex + tag.length;
    let firstpart=$($('.card-timeline')[0]).find('p')[key].innerHTML
        .substr(0, firstIndex);
    let secpart= $($('.card-timeline')[0]).find('p')[key].innerHTML
        .substr(endIndex);
    let toMofify=$($('.card-timeline')[0]).find('p')[key].innerHTML
        .substr(firstIndex, tag.length);
    $($('.card-timeline')[0]).find('p')[key]
        .innerHTML = firstpart+"<a href='/hashtag/"+toMofify.substr(1)+
        "'>"+toMofify+"</a>"+secpart;
}