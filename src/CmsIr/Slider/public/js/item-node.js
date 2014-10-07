$(function () {
    // sorting nodes
    $('#nestable3').nestable({ maxDepth: 1 });
    $('.dd').on('change', function() {
        var json = $('.dd').nestable('serialize');
        $.ajax({
            type: "POST",
            url: "/cms-ir/slider/order",
            dataType : 'json',
            data: {
                data: json
            },
            success: function(json)
            {

            }
        });
    });

    // delete modal
    $('#nestable3').on('click', '.btn-danger', function (e) {
        e.preventDefault();
        var entityId = $(this).attr('id');
        var sliderId = $('input[name = "slider-id"]').attr('id');
        console.log(sliderId);
        var name = $(this).parent().prev().prev().prev().text();
        var surname = $(this).parent().prev().prev().text();
        $('#deleteModal').on('show.bs.modal', function () {

            $('#deleteModal form').attr('action', 'slider/items/'+ sliderId +'/delete/'+entityId);
            $('#deleteModal form input[name=id]').val(entityId);
            $('#deleteModal .modal-body p b').text(name + ' ' + surname);

            $('#deleteModal form input[value = "Tak"]').click(function (ev) {
                ev.preventDefault();
                $('.spinner').show();
                var del = $(this).val();
                $.ajax({
                    type: "POST",
                    url: sliderId +"/delete/"+entityId,
                    dataType : 'json',
                    data: {
                        modal: true,
                        id: entityId,
                        del: del
                    },
                    success: function(json)
                    {
                        window.location.reload();
                    }
                });

            });

        }).modal('show');
    });
});