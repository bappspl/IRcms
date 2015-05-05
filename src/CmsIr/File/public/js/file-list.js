$(function () {

    /** BEGIN DATATABLE EXAMPLE **/
    if ($('#datatable-file').length > 0){
        var category = $('#category').val();
        $('#datatable-file').dataTable({
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
            "sAjaxSource": "/cms-ir/file/" + category,
            "sServerMethod": "POST",
            "bPaginate":true,
            "bSortable": true,
            "bSearchable": true,
            "aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": [ -1 ]
                },
            ]
        });
        // delete modal
        $('#datatable-file tbody').on('click', '.btn-danger', function (e) {
            e.preventDefault();
            var entityId = $(this).attr('id');
            $('#deleteModal').on('show.bs.modal', function () {

                $('#deleteModal form input').click(function (ev) {
                    ev.preventDefault();
                    var del = $(this).val();
                    del == 'Tak' ? $('.spinner').show() : $('.spinner').hide();

                    $.ajax({
                        type: "POST",
                        url: "/cms-ir/file/"  + category +  "/delete/" +entityId,
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

    }
});