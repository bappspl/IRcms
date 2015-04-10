$(function () {
    var category = $('#category').val();
    $('#upload').uploadifive({
        'auto'             : false,
        'formData'         : {
        },
        'multi'         : false,
        'queueID'          : 'queue',
        'uploadScript'     : '/cms-ir/dictionary/' + category +  '/upload',
        'onUploadComplete' : function(file, data) {
            $('#filename').val(data);

            if($('#filename').val().length > 0) {
                var filename = $('#filename').val();
                $('.files img').remove();
                $('.files').append('<img class="img-responsive" src="/files/dictionary/'+data+'" class="thumb" />')
            }

        }
    });

    if($('#filename').val().length > 0) {
        var filename = $('#filename').val();
        $('.files').append('<img class="img-responsive" src="/files/dictionary/'+filename+'" class="thumb" />')
    }

});