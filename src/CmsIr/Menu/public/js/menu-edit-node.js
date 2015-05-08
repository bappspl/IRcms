$(function () {
    // sorting nodes
    $('#nestable3').nestable({ maxDepth: 2 });
    $('.dd').on('change', function() {
        var json = $('.dd').nestable('serialize');
        $.ajax({
            type: "POST",
            url: "/cms-ir/menu/order",
            dataType : 'json',
            data: {
                data: json
            },
            success: function(json)
            {

            }
        });
    });
    //delete node

    $('#nestable3').on('click', '.btn-danger', function (e) {
        var nodeId = $(this).parent().parent().parent().attr('data-id');
        var node = $(this).parent().parent().parent().find('[data-id]');
        $('.tooltip').hide();
        var $cache = $(this);
        nodeArray = [];
        if(node.length > 0) {
            for(i=0; i<node.length;i++) {
                nodeArray.push(node[i].attributes[1].value);
            }
            nodeArray.push(nodeId);
        } else {
            nodeArray.push(nodeId);
        }
        $('#deleteModal').on('show.bs.modal', function () {

            $('#deleteModal form input[name=delete]').click(function (ev) {
                ev.preventDefault();
                $('.spinner').show();

                //$(this).
                $.ajax({
                    type: "POST",
                    url: "/cms-ir/menu/menu-delete-node",
                    dataType : 'json',
                    data: {
                        nodes: nodeArray
                    },
                    success: function(json)
                    {
                        $('.spinner').hide();
                        $cache.parent().parent().parent().remove();
                        $('#deleteModal').modal('hide');
                    }
                });
            });
        }).modal('show');
    });
    //edit node

    $('#nestable3').on('click', '.btn-primary', function (e) {
        var nodeId = $(this).parent().parent().parent().attr('data-id');

        var pageType = $(this).attr('id');
        var modal;
        if(pageType == 'page')
        {
            modal = 'editPageModal';
        } else {
            modal = 'editModal';
        }

        var label = $(this).parent().parent().find('.label').text();
        var url = $(this).parent().parent().find('.url').text();
        var subtitle = $(this).parent().parent().find('.subtitle').text();

        var $cache = $(this);
        $('#'+modal).on('show.bs.modal', function () {
            if(modal == 'editModal')
            {
                $('#'+modal+' #label').val(label);
                $('#'+modal+' #url').val(url);
            } else
            {
                $('#'+modal+' #page option[value="' + url +'"]').attr("selected","selected");
            }
            $('#'+modal+' #subtitle').val(subtitle);

            $('#'+modal+' form input[name=save]').click(function (ev) {
                ev.preventDefault();
                var newLabel, newUrl, newSubtitle;

                $('.spinner').show();

                if(modal == 'editModal')
                {
                    newLabel = $('#'+modal+' #label').val();
                    newUrl = $('#'+modal+' #url').val();
                } else
                {
                    newLabel = $('#'+modal+' select[name="page"] option:selected').text();
                    newUrl = $('#'+modal+' select[name="page"] option:selected').val();
                }

                newSubtitle = $('#'+modal+' #subtitle').val();
                $.ajax({
                    type: "POST",
                    url: "/cms-ir/menu/menu-edit-node",
                    dataType : 'json',
                    data: {
                        nodeId: nodeId,
                        label: newLabel,
                        url: newUrl,
                        subtitle: newSubtitle
                    },
                    success: function(json)
                    {
                        if(json == 'success')
                        {
                            $('.spinner').hide();

                            $cache.parent().parent().find('.label').text(newLabel);
                            $cache.parent().parent().find('.url').text(newUrl);

                            $('#'+modal+' .modal-body .form-group .group').removeClass('has-error');
                            $('#'+modal+' .modal-body .form-group .group .glyphicon-remove').hide();
                            $('#'+modal+' .modal-body .form-group .help-block').hide();

                            $('#'+modal+'').modal('hide');
                        } else {
                            $('.spinner').hide();

                            $('#'+modal+' .modal-body .form-group .group').addClass('has-error');
                            $('#'+modal+' .modal-body .form-group .group .glyphicon-remove').show();
                            $('#'+modal+' .modal-body .form-group .help-block').show();
                        }
                    }
                });
            });
        }).modal('show');
    });

    //create node
    $('.the-box').on('click', '.btn-facebook', function (e) {
        var pageType = $('#page-type').val();
        var modal;
        if(pageType == 'page')
        {
            modal = 'createPageModal';
        } else
        {
            modal = 'createModal';
        }

        $('#'+modal).on('show.bs.modal', function () {

            $('#'+modal+' form input[name=save]').click(function (ev) {
                ev.preventDefault();
                var newLabel, newUrl, newSubtitle;

                $('.spinner').show();

                if(modal == 'createModal')
                {
                    newLabel = $('#'+modal+' #label').val();
                    newUrl = $('#'+modal+' #url').val();
                } else
                {
                    newLabel = $('#'+modal+' select[name="page"] option:selected').text();
                    newUrl = $('#'+modal+' select[name="page"] option:selected').val();
                }
                newSubtitle = $('#'+modal+' #subtitle').val();
                var treeId = $('#treeId').val();

                $.ajax({
                    type: "POST",
                    url: "/cms-ir/menu/menu-create-node",
                    dataType : 'json',
                    data: {
                        label: newLabel,
                        url: newUrl,
                        treeId: treeId,
                        subtitle: newSubtitle,
                        pageProvider: pageType
                    },
                    success: function(json)
                    {
                        if(json == 'error')
                        {
                            $('.spinner').hide();
                            $('#'+modal+' .modal-body .form-group .group').addClass('has-error');
                            $('#'+modal+' .modal-body .form-group .group .glyphicon-remove').show();
                            $('#'+modal+' .modal-body .form-group .help-block').show();


                        } else {

                            $('.spinner').hide();

                            var template = '<li class="dd-item dd3-item" data-id="'+json+'"><div class="dd-handle dd3-handle">Drag</div><div class="dd3-content"><span class="label">'+newLabel+'</span> <span class="url">'+newUrl+'</span><div class="pull-right"><a href="#" class="btn btn-primary" data-toggle="tooltip" title="Edycja"><i class="fa fa-pencil"></i></a> <a href="#" class="btn btn-danger" data-toggle="tooltip" title="Usuwanie"><i class="fa fa-trash-o"></i></a></div></div></li>';
                            $('#nestable3 .dd-list:first-child').prepend(template);

                            $('#'+modal+' .modal-body .form-group .group').removeClass('has-error');
                            $('#'+modal+' .modal-body .form-group .group .glyphicon-remove').hide();
                            $('#'+modal+' .modal-body .form-group .help-block').hide();

                            $('#'+modal+'').modal('hide');
                        }
                    }
                });
            });
        }).modal('show');
    });
});