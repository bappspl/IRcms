$(function () {
    $('#upload').uploadifive({
        'auto'             : false,
        'formData'         : {
        },
        'multi'         : false,
        'queueID'          : 'queue',
        'uploadScript'     : '/cms-ir/category/upload',
        'onUploadComplete' : function(file, data) {
            $('#filename').val(data);

            if($('#filename').val().length > 0) {
                var filename = $('#filename').val();
                $('.files img').remove();
                $('.files').append('<div class="deletePhoto">  <i class="fa fa-times" data-toggle="tooltip" title="Usuń zdjęcie"></i> <img src="/files/category/'+data+'" class="thumb" /> </div>')
            }

            $('.deletePhoto i').on('click', function () {

                var id = 0;
                var fullPathToImage = $(this).next().attr('src');

                if($(this).parent().is("[id]"))
                {
                    id = $(this).parent().attr('id');
                }

                $cache = $(this);
                $.ajax({
                    type: "POST",
                    url: "/cms-ir/category/delete-photo",
                    dataType : 'json',
                    data: {
                        id: id,
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

    if($('#filename').val().length > 0) {
        var filename = $('#filename').val();
        $('.files').append('<div class="deletePhoto">  <i class="fa fa-times" data-toggle="tooltip" title="Usuń zdjęcie"></i> <img src="/files/category/' + filename + '" class="thumb" /> </div>')
    }

    $('.deletePhoto i').on('click', function () {
        var category = $('#category').val();
        var id = 0;
        var fullPathToImage = $(this).next().attr('src');

        if($(this).parent().is("[id]"))
        {
            id = $(this).parent().attr('id');
        }

        $('#filename').val('');
        $cache = $(this);
        $.ajax({
            type: "POST",
            url: "/cms-ir/category/delete-photo",
            dataType : 'json',
            data: {
                id: id,
                filePath: fullPathToImage
            },
            success: function(json)
            {
                $cache.parent().remove();
            }
        });

    });

});