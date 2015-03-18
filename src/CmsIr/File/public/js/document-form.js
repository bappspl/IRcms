$(function () {
    var category = $('#category').val();
    $('#upload').uploadifive({
        'auto'             : false,
        'formData'         : {
        },
        'multi'         : false,
        'queueID'          : 'queue',
        'uploadScript'     : '/cms-ir/file/' + category +  '/upload',
        'onUploadComplete' : function(file, data) {
            $('#filename').val(data);

            if($('#filename').val().length > 0) {
                $('.files').append('<div class="deletePhoto">  <i class="fa fa-times" data-toggle="tooltip" title="Usuń zdjęcie"></i> <img data-src="/temp_files/file/'+data+'" src="/img/pdf.png" class="thumb" /> </div>')
            }

            $('.deletePhoto i').on('click', function () {
                var id = $('#id').val();
                var fullPathToImage = $(this).next().attr('data-src');

                if($(this).parent().is("[id]"))
                {
                    var name = $(this).parent().attr('id');
                }
                $cache = $(this);
                $.ajax({
                    type: "POST",
                    url: "/cms-ir/file/"+ category +"/delete-photo",
                    dataType : 'json',
                    data: {
                        id: id,
                        name: name,
                        filePath: fullPathToImage
                    },
                    success: function(json)
                    {
                        $cache.parent().remove();
                    }
                });

            });

        }
    });

    $('.deletePhoto i').on('click', function () {
        var id = $('#id').val();
        var fullPathToImage = $(this).next().attr('data-src');

        if($(this).parent().is("[id]"))
        {
            var name = $(this).parent().attr('id');
        }
        $cache = $(this);
        $.ajax({
            type: "POST",
            url: "/cms-ir/file/"+ category +"/delete-photo",
            dataType : 'json',
            data: {
                id: id,
                name: name,
                filePath: fullPathToImage
            },
            success: function(json)
            {
                $cache.parent().remove();
            }
        });

    });

});