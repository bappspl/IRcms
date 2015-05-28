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
                $('.files').append('<div class="deletePhoto">  <i class="fa fa-times" data-toggle="tooltip" title="Usuń zdjęcie"></i> <img src="/files/dictionary/'+data+'" class="thumb" /> </div>')
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
                    url: "/cms-ir/dictionary/" + category +  "/delete-photo",
                    dataType : 'json',
                    data: {
                        id: id,
                        name: name,
                        filePath: fullPathToImage
                    },
                    success: function(json)
                    {
                        $cache.parent().remove();
                        $('#filename').val(null);
                    }
                });

            });

        }
    });

    if($('#filename').val().length > 0) {
        var filename = $('#filename').val();
        $('.files').append('<div class="deletePhoto">  <i class="fa fa-times" data-toggle="tooltip" title="Usuń zdjęcie"></i> <img src="/files/dictionary/'+filename+'" class="thumb" /> </div>')
    }

    $('.deletePhoto i').on('click', function () {
        var category = $('#category').val();

        var fullPathToImage = $(this).next().attr('src');

        var id = $('input[name = "id"]').val();

        $cache = $(this);

        $.ajax({
            type: "POST",
            url: "/cms-ir/dictionary/" + category +  "/delete-photo",
            dataType : 'json',
            data: {
                id: id,
                name: name,
                filePath: fullPathToImage
            },
            success: function(json)
            {
                $cache.parent().remove();
                $('#filename').val(null);
            }
        });

    });

});