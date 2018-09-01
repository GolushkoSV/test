$('#upload-payments').on('click', function () {
    $('.cssload-loader').fadeIn();
    $.ajax({
        url: 'index.php?r=cabinet/upload',
        success: function (data) {
            $('.cssload-loader').fadeOut();
        }
    });
});