$(function () {
    $('#upload').uploadifive({
        'auto'             : false,
        'formData'         : {
            'files': $('#files').val()
        },
        'method'   : 'post',
        'multi'         : true,
        'queueID'          : 'queue',
        'uploadScript'     : '/cms-ir/page/upload',
        'onUploadComplete' : function(file, data) {
            $('#files').val(data);
            if($('#files').val().length > 0) {
                $('.files').append('<div class="deletePhoto">  <i class="fa fa-times" data-toggle="tooltip" title="Usuń zdjęcie"></i> <img src="/temp_files/page/'+data+'" class="thumb" /> </div>')
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
                    url: "/cms-ir/page/delete-photo",
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
            url: "/cms-ir/page/delete-photo",
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



    $('#upload-main').uploadifive({
        'auto'             : false,
        'formData'         : {
            'files': $('#filename').val()
        },
        'method'   : 'post',
        'multi'         : true,
        'queueID'          : 'queue-main',
        'uploadScript'     : '/cms-ir/page/upload-main',
        'onUploadComplete' : function(file, data) {
            $('#filename').val(data);
            if($('#filename').val().length > 0) {
                $('.files-main img').remove();
                $('.files-main').append('<div class="deletePhoto_main">  <i class="fa fa-times" data-toggle="tooltip" title="Usuń zdjęcie"></i> <img src="/files/page/'+data+'" class="thumb" /> </div>')
            }

            $('.deletePhoto_main i').on('click', function () {
                var id = $('#id').val();
                var fullPathToImage = $(this).next().attr('src');

                if($(this).parent().is("[id]"))
                {
                    var name = $(this).parent().attr('id');
                }

                $cache = $(this);
                $.ajax({
                    type: "POST",
                    url: "/cms-ir/page/delete-photo-main",
                    dataType : 'json',
                    data: {
                        id: id,
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
        var filename = $('#filename-main').val();
        $('.files-main').append('<div class="deletePhoto_main">  <i class="fa fa-times" data-toggle="tooltip" title="Usuń zdjęcie"></i> <img src="/files/page/'+filename+'" class="thumb" /> </div>')
    }

    $('.deletePhoto_main i').on('click', function () {
        var id = $('#id').val();
        var fullPathToImage = $(this).next().attr('src');

        if($(this).parent().is("[id]"))
        {
            var name = $(this).parent().attr('id');
        }

        $cache = $(this);
        $.ajax({
            type: "POST",
            url: "/cms-ir/page/delete-photo-main",
            dataType : 'json',
            data: {
                id: id,
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