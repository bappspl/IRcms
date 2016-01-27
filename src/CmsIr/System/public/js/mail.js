$(function() {

    $('#send-test-email').on('click', function (e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: "/cms-ir/send-test-email",
            dataType : 'json',
            success: function(json)
            {
                $('#test-mail-alert').fadeIn();
            }
        });

    });

});