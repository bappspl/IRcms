$(function () {
    /** BEGIN DATATABLE EXAMPLE **/
    if ($('#datatable-users').length > 0){
        $('#datatable-users').dataTable({
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
            "sAjaxSource": "/cms-ir/users",
            "sServerMethod": "POST",
            "bPaginate":true
        });
    }
});