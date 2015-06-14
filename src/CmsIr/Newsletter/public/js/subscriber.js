$(function () {

    /** BEGIN DATATABLE EXAMPLE **/
    if ($('#datatable-subscriber-group').length > 0) {
        selectedIds = [];
        var table = $('#datatable-subscriber-group').DataTable({
            "dom": '<"top"fl>t<"bottom"ip>r',
            "lengthMenu": [[10, 25, 50, 100], ["10 pozycji", "25 pozycji", "50 pozycji", "100 pozycji"]],
            "language": {
                "url": "/datatables/pl_PL.json"
            },
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "ajaxSource": "/cms-ir/newsletter/subscriber-group",
            "serverMethod": "POST",
            "paginate": true,
            "sortable": true,
            "searchable": true,
            "order": [[1, "asc"]],
            "columnDefs": [
                {
                    "targets": [0],
                    "render": function (data, type, row) {   // o, v contains the object and value for the column
                        if (type === 'display') {
                            return '<div class="checkbox"><label><input type="checkbox" class="check-row i-grey" /></label></div>';
                        }
                        return data;
                    },
                    "className": "dt-body-center",
                    "sortable": false
                },
                {
                    "targets": [3],
                    "render": function (data, type, row) {   // o, v contains the object and value for the column
                        if (type === 'display') {
                            return '<a href="/cms-ir/newsletter/subscriber-group/preview-group/' + data + '" class="btn btn-info" data-toggle="tooltip" title="Podgląd"><i class="fa fa-eye"></i></a> ' +
                                '<a href="/cms-ir/newsletter/subscriber-group/edit-group/' + data + '" class="btn btn-primary" data-toggle="tooltip" title="Edycja"><i class="fa fa-pencil"></i></a> ' +
                                '<a href="/cms-ir/newsletter/subscriber-group/delete-group/' + data + '" id="' + data + '" class="btn btn-danger" data-toggle="tooltip" title="Usuwanie"><i class="fa fa-trash-o"></i></a>';
                        }
                        return data;
                    },
                    "className": "dt-body-action",
                    "sortable": false
                },
                {
                    "sortable": false,
                    "targets": [-1]
                }
            ],
            "drawCallback": function (data) {
                selectedIds = [];

                $('input.i-grey').iCheck({
                    checkboxClass: 'icheckbox_minimal-grey',
                    radioClass: 'iradio_minimal-grey',
                    increaseArea: '20%'
                });

                setTimeout(function () {
                    // select row
                    $('.check-row').on('ifChecked', function (event) {
                        var tr = $(this).parent().parent().parent().parent().parent();
                        var data = table.row(tr).data();
                        var id = data[0];
                        selectedIds.push(id);
                        tr.addClass('selected-row');
                        //console.log(selectedIds);
                    });

                    // deselect row
                    $('.check-row').on('ifUnchecked', function (event) {
                        var tr = $(this).parent().parent().parent().parent().parent();
                        var data = table.row(tr).data();
                        var id = data[0];
                        var index = selectedIds.indexOf(id);
                        selectedIds.splice(index, 1);
                        tr.removeClass('selected-row');
                        //console.log(selectedIds);
                    });

                    //check all
                    $('.check-all').on('ifChecked', function (event) {
                        selectedIds = [];
                        $('#datatable-subscriber-group tbody tr td .check-row').iCheck('check');
                        //console.log(selectedIds);
                    });

                    //uncheck all
                    $('.check-all').on('ifUnchecked', function (event) {
                        selectedIds = [];
                        $('#datatable-subscriber-group tbody tr td .check-row').iCheck('uncheck');
                        //console.log(selectedIds);
                    });
                }, 0);

                //massive action select
                if ($('#datatable-subscriber-group_length select[name="massive-action"]').length === 0) {
                    $('#datatable-subscriber-group_length').append($('.massive-action').html());
                }

                //add new button
                if ($('.the-box .bottom .btn-facebook').length === 0) {
                    $('.the-box .bottom').append($('.btn-facebook').parent().html());
                    $('.row .col-sm-12 .btn-facebook').remove();
                }

                //massive actions
                $('select[name="massive-action"]').off('change').on('change', function () {
                    var action = $(this).val();
                    if (action.length > 0) {
                        var modal = action + 'MassiveModal';
                        $('#' + modal).on('show.bs.modal', function () {
                            if (selectedIds.length > 0) {
                                $('#' + modal + ' .content').show();
                                $('#' + modal + ' .modal-footer input[type="submit"][value="Tak"]').show();
                                $('#' + modal + ' .message').hide();

                                if (action === 'delete') // massive delete
                                {
                                    $('#' + modal + ' form input').off('click').on('click', function (ev) {
                                        ev.preventDefault();
                                        var del = $(this).val();
                                        del == 'Tak' ? $('.spinner').show() : $('.spinner').hide();

                                        $.ajax({
                                            type: "POST",
                                            url: "/cms-ir/newsletter/subscriber-group/delete-group/1",
                                            dataType: 'json',
                                            data: {
                                                modal: true,
                                                id: selectedIds,
                                                del: del
                                            },
                                            success: function (json) {
                                                $('.spinner').hide();
                                                if(json['status'] == 'error')
                                                {

                                                } else
                                                {
                                                    $('.check-all').iCheck('uncheck');
                                                    $('#' + modal).modal('hide');
                                                    $('select[name="massive-action"]').val('');
                                                    table.ajax.reload();
                                                }
                                            }
                                        });

                                    });
                                }
                            } else {
                                $('#' + modal + ' .content').hide();
                                $('#' + modal + ' .modal-footer input[type="submit"][value="Tak"]').hide();
                                $('#' + modal + ' .modal-footer input[type="submit"][value="Zapisz"]').hide();
                                $('#' + modal + ' .message').show();
                            }
                        }).modal('show');
                    }
                });

                $('#deleteMassiveModal, #statusMassiveModal').off().on('hidden.bs.modal', function () {
                    $('select[name="massive-action"]').val('');
                }).modal('hide');
            }
        });


        //delete group modal
        $('#datatable-subscriber-group tbody').on('click', '.btn-danger', function (e) {
            e.preventDefault();
            var entityId = $(this).attr('id');
            $('#deleteModal').on('show.bs.modal', function () {

                $('#deleteModal form input').click(function (ev) {
                    ev.preventDefault();
                    $('.spinner').show();
                    var del = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "/cms-ir/newsletter/subscriber-group/delete-group/" + entityId,
                        dataType: 'json',
                        data: {
                            modal: true,
                            id: entityId,
                            del: del
                        },
                        success: function (json) {
                            $('.spinner').hide();
                            if(json['status'] == 'error')
                            {

                            } else
                            {
                                $('#deleteModal').modal('hide');
                                table.ajax.reload();
                            }

                        }
                    });

                });

            }).modal('show');
        });
    }

    if ($('#datatable-subscriber').length > 0)
    {
        selectedIds = [];
        var table = $('#datatable-subscriber').DataTable({
            "dom": '<"top"fl>t<"bottom"ip>r',
            "lengthMenu": [[10, 25, 50, 100], ["10 pozycji", "25 pozycji", "50 pozycji", "100 pozycji"]],
            "language": {
                "url": "/datatables/pl_PL.json"
            },
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "ajaxSource": "/cms-ir/newsletter/subscriber-list",
            "serverMethod": "POST",
            "paginate": true,
            "sortable": true,
            "searchable": true,
            "order": [[1, "asc"]],
            "columnDefs": [
                {
                    "targets": [0],
                    "render": function (data, type, row) {   // o, v contains the object and value for the column
                        if (type === 'display') {
                            return '<div class="checkbox"><label><input type="checkbox" class="check-row i-grey" /></label></div>';
                        }
                        return data;
                    },
                    "className": "dt-body-center",
                    "sortable": false
                },
                { "orderData": 3, "targets": [ 4 ] },
                {
                    "targets": [5],
                    "render": function (data, type, row) {   // o, v contains the object and value for the column
                        if (type === 'display') {
                            return  '<a href="/cms-ir/newsletter/subscriber-list/preview-subscriber/' + data + '" class="btn btn-info" data-toggle="tooltip" title="Podgląd"><i class="fa fa-eye"></i></a> ' +
                                    '<a href="/cms-ir/newsletter/subscriber-list/edit-subscriber/' + data + '" class="btn btn-primary" data-toggle="tooltip" title="Edycja"><i class="fa fa-pencil"></i></a> ' +
                                    '<a href="/cms-ir/newsletter/subscriber-list/delete-subscriber/' + data + '" id="' + data + '" class="btn btn-danger" data-toggle="tooltip" title="Usuwanie"><i class="fa fa-trash-o"></i></a>';
                        }
                        return data;
                    },
                    "className": "dt-body-action",
                    "sortable": false
                },
                {
                    "sortable": false,
                    "targets": [-1]
                },
                {
                    "targets": [ 3 ],
                    "visible": false,
                    "className": "never"
                }
            ],
            "drawCallback": function (data) {
                selectedIds = [];

                $('input.i-grey').iCheck({
                    checkboxClass: 'icheckbox_minimal-grey',
                    radioClass: 'iradio_minimal-grey',
                    increaseArea: '20%'
                });

                setTimeout(function () {
                    // select row
                    $('.check-row').on('ifChecked', function (event) {
                        var tr = $(this).parent().parent().parent().parent().parent();
                        var data = table.row(tr).data();
                        var id = data[0];
                        selectedIds.push(id);
                        tr.addClass('selected-row');
                        //console.log(selectedIds);
                    });

                    // deselect row
                    $('.check-row').on('ifUnchecked', function (event) {
                        var tr = $(this).parent().parent().parent().parent().parent();
                        var data = table.row(tr).data();
                        var id = data[0];
                        var index = selectedIds.indexOf(id);
                        selectedIds.splice(index, 1);
                        tr.removeClass('selected-row');
                        //console.log(selectedIds);
                    });

                    //check all
                    $('.check-all').on('ifChecked', function (event) {
                        selectedIds = [];
                        $('#datatable-subscriber tbody tr td .check-row').iCheck('check');
                        //console.log(selectedIds);
                    });

                    //uncheck all
                    $('.check-all').on('ifUnchecked', function (event) {
                        selectedIds = [];
                        $('#datatable-subscriber tbody tr td .check-row').iCheck('uncheck');
                        //console.log(selectedIds);
                    });
                }, 0);

                //massive action select
                if ($('#datatable-subscriber_length select[name="massive-action"]').length === 0) {
                    $('#datatable-subscriber_length').append($('.massive-action').html());
                }

                //add new button
                if ($('.the-box .bottom .btn-facebook').length === 0) {
                    $('.the-box .bottom').append($('.btn-facebook').parent().html());
                    $('.row .col-sm-12 .btn-facebook').remove();
                }

                //massive actions
                $('select[name="massive-action"]').off('change').on('change', function () {
                    var action = $(this).val();
                    if (action.length > 0) {
                        var modal = action + 'MassiveModal';
                        $('#' + modal).on('show.bs.modal', function () {
                            if (selectedIds.length > 0) {
                                $('#' + modal + ' .content').show();
                                $('#' + modal + ' .modal-footer input[type="submit"][value="Tak"]').show();
                                $('#' + modal + ' .message').hide();

                                if (action === 'delete') // massive delete
                                {
                                    $('#' + modal + ' form input').off('click').on('click', function (ev) {
                                        ev.preventDefault();
                                        var del = $(this).val();
                                        del == 'Tak' ? $('.spinner').show() : $('.spinner').hide();

                                        $.ajax({
                                            type: "POST",
                                            url: "/cms-ir/newsletter/subscriber-list/delete-subscriber/1",
                                            dataType: 'json',
                                            data: {
                                                modal: true,
                                                id: selectedIds,
                                                del: del
                                            },
                                            success: function (json) {
                                                $('.spinner').hide();
                                                $('.check-all').iCheck('uncheck');
                                                $('#' + modal).modal('hide');
                                                $('select[name="massive-action"]').val('');
                                                table.ajax.reload();
                                            }
                                        });

                                    });
                                } else // massive change status
                                {
                                    $('#'+modal+' form input').off('click').on('click', function (ev) {
                                        ev.preventDefault();
                                        var del = $(this).val();
                                        del == 'Zapisz' ? $('.spinner').show() : $('.spinner').hide();
                                        var statusId = $('#'+modal+' select[name="status"]').val();
                                        $.ajax({
                                            type: "POST",
                                            url: "/cms-ir/newsletter/subscriber-list/change-status/1",
                                            dataType : 'json',
                                            data: {
                                                modal: true,
                                                id: selectedIds,
                                                statusId: statusId,
                                                del: del
                                            },
                                            success: function(json)
                                            {
                                                $('.spinner').hide();
                                                $('.check-all').iCheck('uncheck');
                                                $('#'+modal).modal('hide');
                                                $('select[name="massive-action"]').val('');
                                                $('#'+modal+' select[name="status"]').val(1);
                                                table.ajax.reload();
                                            }
                                        });

                                    });
                                }
                            } else {
                                $('#' + modal + ' .content').hide();
                                $('#' + modal + ' .modal-footer input[type="submit"][value="Tak"]').hide();
                                $('#' + modal + ' .modal-footer input[type="submit"][value="Zapisz"]').hide();
                                $('#' + modal + ' .message').show();
                            }
                        }).modal('show');
                    }
                });

                $('#deleteMassiveModal, #statusMassiveModal').off().on('hidden.bs.modal', function () {
                    $('select[name="massive-action"]').val('');
                }).modal('hide');
            }
        });

        //delete group modal
        $('#datatable-subscriber tbody').on('click', '.btn-danger', function (e) {
            e.preventDefault();
            var entityId = $(this).attr('id');
            $('#deleteModal').on('show.bs.modal', function () {

                $('#deleteModal form input').click(function (ev) {
                    ev.preventDefault();

                    var del = $(this).val();
                    del == 'Tak' ? $('.spinner').show() : $('.spinner').hide();

                    $.ajax({
                        type: "POST",
                        url: "/cms-ir/newsletter/subscriber-list/delete-subscriber/"+entityId,
                        dataType : 'json',
                        data: {
                            modal: true,
                            id: entityId,
                            del: del
                        },
                        success: function(json)
                        {
                            $('.spinner').hide();
                            $('#deleteModal').modal('hide');
                            table.ajax.reload();
                        }
                    });

                });

            }).modal('show');
        });
    }

    if( $('.chosen-select').length > 0)
    {
        $('.chosen-select').chosen();
    }

});