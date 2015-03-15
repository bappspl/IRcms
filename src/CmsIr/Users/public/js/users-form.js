$(function () {
    $('#upload').uploadifive({
        'auto'             : false,
        'formData'         : {
        },
        'multi'         : false,
        'queueID'          : 'queue',
        'uploadScript'     : '/cms-ir/users/upload',
        'onUploadComplete' : function(file, data) {
            $('#filename').val(data);

            if($('#filename').val().length > 0) {
                var filename = $('#filename').val();
                $('.files img').remove();
                $('.files').append('<img src="/files/users/'+data+'" class="thumb" />')
            }

        }
    });

    if($('#filename').val().length > 0) {
        var filename = $('#filename').val();
        $('.files').append('<img src="/files/users/'+filename+'" class="thumb" />')
    }

    if($('select[name="dictionary_position_id"]').val() == 1)
    {
        $('select[name="dictionary_group_id"]').attr('disabled', 'disabled');
    } else
    {
        $('select[name="dictionary_group_id"]').removeAttr('disabled');
    }

    if($('select[name="dictionary_position_id"]').length > 0)
    {
        $('select[name="dictionary_position_id"]').on('change', function () {
            if($(this).val() == 1)
            {
                $('select[name="dictionary_group_id"]').attr('disabled', 'disabled');
            } else
            {
                $('select[name="dictionary_group_id"]').removeAttr('disabled');
            }
        });
    }

});