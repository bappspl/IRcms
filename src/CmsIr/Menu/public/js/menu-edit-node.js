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
        var label = $(this).parent().parent().find('.label').text();
        var url = $(this).parent().parent().find('.url').text();
        var $cache = $(this);
        $('#editModal').on('show.bs.modal', function () {
            $('#editModal #label').val(label);
            $('#editModal #url').val(url);
            $('#editModal form input[name=save]').click(function (ev) {
                ev.preventDefault();
                $('.spinner').show();
                var newLabel = $('#editModal #label').val();
                var newUrl = $('#editModal #url').val();
                $.ajax({
                    type: "POST",
                    url: "/cms-ir/menu/menu-edit-node",
                    dataType : 'json',
                    data: {
                        nodeId: nodeId,
                        label: newLabel,
                        url: newUrl
                    },
                    success: function(json)
                    {
                        if(json == 'success')
                        {
                            $('.spinner').hide();

                            $cache.parent().parent().find('.label').text(newLabel);
                            $cache.parent().parent().find('.url').text(newUrl);

                            $('#editModal .modal-body .form-group .group').removeClass('has-error');
                            $('#editModal .modal-body .form-group .group .glyphicon-remove').hide();
                            $('#editModal .modal-body .form-group .help-block').hide();

                            $('#editModal').modal('hide');
                        } else {
                            $('.spinner').hide();

                            $('#editModal .modal-body .form-group .group').addClass('has-error');
                            $('#editModal .modal-body .form-group .group .glyphicon-remove').show();
                            $('#editModal .modal-body .form-group .help-block').show();
                        }
                    }
                });
            });
        }).modal('show');
    });
    //create node
    $('.the-box').on('click', '.btn-facebook', function (e) {

        $('#createModal').on('show.bs.modal', function () {

            $('#createModal form input[name=save]').click(function (ev) {
                ev.preventDefault();
                $('.spinner').show();
                var newLabel = $('#createModal #label').val();
                var newUrl = $('#createModal #url').val();
                var treeId = $('#treeId').val();
                $.ajax({
                    type: "POST",
                    url: "/cms-ir/menu/menu-create-node",
                    dataType : 'json',
                    data: {
                        label: newLabel,
                        url: newUrl,
                        treeId: treeId
                    },
                    success: function(json)
                    {
                        if(json == 'error')
                        {
                            $('.spinner').hide();
                            $('#createModal .modal-body .form-group .group').addClass('has-error');
                            $('#createModal .modal-body .form-group .group .glyphicon-remove').show();
                            $('#createModal .modal-body .form-group .help-block').show();


                        } else {

                            $('.spinner').hide();

                            var template = '<li class="dd-item dd3-item" data-id="'+json+'"><div class="dd-handle dd3-handle">Drag</div><div class="dd3-content"><span class="label">'+newLabel+'</span> <span class="url">'+newUrl+'</span><div class="pull-right"><a href="#" class="btn btn-primary" data-toggle="tooltip" title="Edycja"><i class="fa fa-pencil"></i></a> <a href="#" class="btn btn-danger" data-toggle="tooltip" title="Usuwanie"><i class="fa fa-trash-o"></i></a></div></div></li>';
                            $('#nestable3 .dd-list:first-child').prepend(template);

                            $('#createModal .modal-body .form-group .group').removeClass('has-error');
                            $('#createModal .modal-body .form-group .group .glyphicon-remove').hide();
                            $('#createModal .modal-body .form-group .help-block').hide();

                            $('#createModal').modal('hide');
                        }
                    }
                });
            });
        }).modal('show');
    });
});