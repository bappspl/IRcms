$(function () {
    $('#upload').uploadifive({
        'auto'             : false,
        'formData'         : {
            'files': $('#filename').val()
        },
        'multi'         : true,
        'queueID'          : 'queue',
        'uploadScript'     : '/cms-ir/gallery/upload',
        'onUploadComplete' : function(file, data) {
            $('#filename').val(data);

            if($('#filename').val().length > 0) {
                $('.files').append('<div class="deletePhoto">  <i class="fa fa-times" data-toggle="tooltip" title="Usuń zdjęcie"></i> <img src="/temp_files/gallery/'+data+'" class="thumb" /> </div>')
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
                    url: "/cms-ir/gallery/delete-photo",
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
        var fullPathToImage = $(this).next().attr('src');

        if($(this).parent().is("[id]"))
        {
            var id = $(this).parent().attr('id');
        }
        $cache = $(this);

        $.ajax({
            type: "POST",
            url: "/cms-ir/gallery/delete-photo",
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