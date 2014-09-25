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

});