$('.avatar').click(function () {
   $('.formAvatar input').trigger('click');
});
$('.formAvatar input').change(function () {
   $('.formAvatar').submit();
});
$('#banner').click(function () {
   $('.formBanner input').trigger('click');
});
$('.formBanner input').change(function () {
   $('.formBanner').submit();
});