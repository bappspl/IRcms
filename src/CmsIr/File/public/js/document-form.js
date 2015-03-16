$(function () {
    $('#upload').uploadifive({
        'auto'             : false,
        'formData'         : {
        },
        'multi'         : false,
        'queueID'          : 'queue',
        'uploadScript'     : '/cms-ir/file/upload',
        'onUploadComplete' : function(file, data) {
            $('#filename').val(data);

            if($('#filename').val().length > 0) {
                var filename = $('#filename').val();
                $('.files img').remove();
                $('.files').append('<img class="img-responsive" src="/files/file/'+data+'" class="thumb" />')
            }

        }
    });

    if($('#filename').val().length > 0) {
        var filename = $('#filename').val();
        $('.files').append('<img class="img-responsive" src="/files/file/'+filename+'" class="thumb" />')
    }

});