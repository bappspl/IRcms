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
        }
    });

});