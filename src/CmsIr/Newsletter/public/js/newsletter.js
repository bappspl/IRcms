$(function () {

    /** BEGIN DATATABLE EXAMPLE **/
    if ($('#datatable-newsletter').length > 0){
        $('#datatable-newsletter').dataTable({
            "oLanguage": {
                "sProcessing":   "Przetwarzanie...",
                "sLengthMenu":   "Pokaż _MENU_ pozycji",
                "sZeroRecords":  "Nie znaleziono pasujących pozycji",
                "sInfoThousands":  " ",
                "sInfo":         "Pozycje od _START_ do _END_ z _TOTAL_ łącznie",
                "sInfoEmpty":    "Pozycji 0 z 0 dostępnych",
                "sInfoFiltered": "(filtrowanie spośród _MAX_ dostępnych pozycji)",
                "sInfoPostFix":  "",
                "sSearch":       "Szukaj:",
                "sUrl":          "",
                "oPaginate": {
                    "sFirst":    "Pierwsza",
                    "sPrevious": "Poprzednia",
                    "sNext":     "Następna",
                    "sLast":     "Ostatnia"
                },
                "sEmptyTable":     "Brak danych",
                "sLoadingRecords": "Wczytywanie...",
                "oAria": {
                    "sSortAscending":  ": aktywuj by posortować kolumnę rosnąco",
                    "sSortDescending": ": aktywuj by posortować kolumnę malejąco"
                }
            },
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "/cms-ir/newsletter",
            "sServerMethod": "POST",
            "bPaginate":true,
            "bSortable": true,
            "bSearchable": true,
            "aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": [ -1, -2, -3 ]
                }
            ]
        });
        // delete modal
        $('#datatable-newsletter tbody').on('click', '.btn-danger', function (e) {
            e.preventDefault();
            var entityId = $(this).attr('id');
            $('#deleteModal').on('show.bs.modal', function () {

                $('#deleteModal form').attr('action', 'users/delete/'+entityId);
                $('#deleteModal form input[name=id]').val(entityId);

                $('#deleteModal form input').click(function (ev) {
                    ev.preventDefault();
                    $('.spinner').show();
                    var del = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "/cms-ir/newsletter/delete-newsletter/"+entityId,
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

        // send mailas
        $('#datatable-newsletter tbody').on('click', '.btn-facebook', function (e) {
            e.preventDefault();
            var entityId = $(this).attr('id');
            $('#sendModal').on('show.bs.modal', function () {
                $('.spinner').show();
                $.ajax({
                    type: "POST",
                    url: "/cms-ir/newsletter/send-newsletter/"+entityId,
                    dataType : 'json',
                    data: {},
                    success: function(json)
                    {
                        window.location.reload();
                    }
                });
            }).modal('show');
        });

    }

    $('.chosen-select').chosen();

});