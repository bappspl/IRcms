$(function () {
    $('#upload').uploadifive({
        'auto'             : false,
        'formData'         : {
            'files': $('#filename').val()
        },
        'multi'         : false,
        'queueID'          : 'queue',
        'uploadScript'     : '/cms-ir/banner/upload',
        'onUploadComplete' : function(file, data) {
            $('#filename').val(data);

            if($('#filename').val().length > 0) {
                $('.files').append('<div class="deletePhoto">  <i class="fa fa-times" data-toggle="tooltip" title="Usuń zdjęcie"></i> <img src="/files/banner/'+data+'" class="thumb" /> </div>')
            }

            $('.deletePhoto i').on('click', function () {
                var id = $('#id').val();
                var fullPathToImage = $(this).next().attr('src');

                if($(this).parent().is("[id]"))
                {
                    var name = $(this).parent().attr('id');
                }
                $cache = $(this);
                $.ajax({
                    type: "POST",
                    url: "/cms-ir/banner/delete-photo",
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

    if($('#filename').val().length > 0) {
        var filename = $('#filename').val();
        $('.files').append('<div class="deletePhoto">  <i class="fa fa-times" data-toggle="tooltip" title="Usuń zdjęcie"></i> <img src="/files/banner/'+filename+'" class="thumb" /> </div>')
    }

    $('.deletePhoto i').on('click', function () {
        var fullPathToImage = $(this).next().attr('src');

        if($(this).parent().is("[id]"))
        {
            var id = $(this).parent().attr('id');
        }
        $cache = $(this);

        $.ajax({
            type: "POST",
            url: "/cms-ir/banner/delete-photo",
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