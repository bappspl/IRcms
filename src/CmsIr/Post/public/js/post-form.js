$(function () {
    $('.datetimepicker').datetimepicker({
        locale: 'pl',
        format: 'YYYY-MM-DD HH:mm:ss'
    });
    var category = 'news';
    $('#upload').uploadifive({
        'auto'             : false,
        'formData'         : {
            'files': $('#filename').val()
        },
        'method'   : 'post',
        'multi'         : true,
        'queueID'          : 'queue',
        'uploadScript'     : '/cms-ir/post-list/'+ category +'/upload',
        'onUploadComplete' : function(file, data) {
            $('#filename').val(data);
            if($('#filename').val().length > 0) {
                $('.files').append('<div class="deletePhoto">  <i class="fa fa-times" data-toggle="tooltip" title="Usuń zdjęcie"></i> <img src="/temp_files/post/'+data+'" class="thumb" /> </div>')
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
                    url: "/cms-ir/post-list/"+ category +"/delete-photo",
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
            url: "/cms-ir/post-list/"+ category +"/delete-photo",
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
            'files': $('#filename-main').val()
        },
        'method'   : 'post',
        'multi'         : true,
        'queueID'          : 'queue-main',
        'uploadScript'     : '/cms-ir/post-list/'+ category +'/upload-main',
        'onUploadComplete' : function(file, data) {
            $('#filename-main').val(data);
            if($('#filename-main').val().length > 0) {
                $('.files-main img').remove();
                $('.files-main').append('<img src="/files/post/'+data+'" class="thumb" />')
            }

            $('.deletePhoto-main i').on('click', function () {

                var id = 0;
                var fullPathToImage = $(this).next().attr('src');

                if($(this).parent().is("[id]"))
                {
                    id = $(this).parent().attr('id');
                }
                $cache = $(this);
                $.ajax({
                    type: "POST",
                    url: "/cms-ir/post-list/"+ category +"/delete-photo-main",
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

    $('.deletePhoto_main i').on('click', function () {
        alert('asd');
        var id = 0;
        var fullPathToImage = $(this).next().attr('src');

        if($(this).parent().is("[id]"))
        {
            id = $(this).parent().attr('id');
        }
        $cache = $(this);
        $.ajax({
            type: "POST",
            url: "/cms-ir/post-list/"+ category +"/delete-photo-main",
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

    if($('#filename_main').val().length > 0) {
        var filename = $('#filename_main').val();
        $('.files_main').append('<img src="/files/post/'+filename+'" class="thumb" />')
    }

});