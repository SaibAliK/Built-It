var DatatableRemoteAjaxDemo = function () {
    $("#product_search").val("");

    var t = function () {

        var t = $("#product_datatable").mDatatable({
            data: {
                type: "remote",
                source: {
                    read: {
                        url: window.Laravel.baseUrl + "list/product"
                    }
                },
                pageSize: 10,
                saveState: {
                    cookie: !0,
                    webstorage: !0
                },
                serverPaging: !0,
                serverFiltering: !0,
                serverSorting: !0,
            },
            layout: {
                theme: "default",
                class: "",
                scroll: !1,
                footer: !1
            },
            sortable: false,
            ordering: false,
            filterable: !1,
            pagination: !0,
            columns: [{
                    field: "id",
                    title: "#",
                    width: 10
                },
                {
                    field: "title",
                    title: "Title",
                    width: 50
                },
                {
                    field: "store",
                    title: "Supplier Name",
                    width: 100
                },
                {
                    field: "price",
                    title: "Price",
                    width: 50
                },
                {
                    field: "quantity",
                    title: "Quantity",
                    width: 70
                },
                {
                    field: "status",
                    title: "status",
                    width: 150
                },
            ]
        });

        t.on('m-datatable--on-layout-updated', function (params) {
            $('.delete-record-button').on('click', function (e) {
                var url = $(this).data('url');
                swal({
                        title: "Are you sure you want to delete this?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Delete",
                        cancelButtonText: "No",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                    type: 'delete',
                                    url: url,
                                    dataType: 'json',
                                    headers: {
                                        'X-CSRF-TOKEN': window.Laravel.csrfToken
                                    }
                                })
                                .done(function (res) {
                                    toastr.success("Product deleted successfully!");
                                })
                                .fail(function (res) {
                                    toastr.success("Product deleted  successfully!");
                                });
                        } else {
                            swal.close()
                        }
                    });
            });
            $('.restore-record-button').on('click', function (e) {
                var url = $(this).data('url');
                swal({
                        title: "Are You Sure You Want To Restore This?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Restore",
                        cancelButtonText: "No",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                    type: 'get',
                                    url: url,
                                    dataType: 'json',
                                    headers: {
                                        'X-CSRF-TOKEN': window.Laravel.csrfToken
                                    }
                                })
                                .done(function (res) {
                                    toastr.success("Agency restored !");
                                    $('#show-trashed-users').click();
                                })
                                .fail(function (res) {
                                    toastr.success("Agency restored!");
                                });
                        } else {
                            swal.close()
                        }
                    });
            });
            $('.offer_allow').on('click', function (e) {
                e.preventDefault();
                var url = $(this).data('url');

                swal({
                        title              : "Are you sure you want to change this Offer?",
                        type               : "warning",
                        showCancelButton   : true,
                        confirmButtonColor : "#DD6B55",
                        confirmButtonText  : "Yes",
                        cancelButtonText   : "No",
                        closeOnConfirm     : true,
                        closeOnCancel      : true,
                        showLoaderOnConfirm: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                    type: 'get',
                                    url: url,
                                    dataType: 'json',
                                    headers: {
                                        'X-CSRF-TOKEN': window.Laravel.csrfToken
                                    }
                                })
                                .done(function (res) {
                                    toastr.success("Offer Changed!");
                                    t.reload();
                                })
                                .fail(function (res) {
                                    toastr.success("Fail to change offer");
                                });
                        }
                    });

            });

            $('.toggle-status-button').on('click', function (e) {
                var url = $(this).data('url');
                if (url.length > 0) {
                    $.ajax({
                            url: url,
                            type: 'PUT',
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': window.Laravel.csrfToken
                            }
                        })
                        .done(function (res) {
                            toastr.success("Your action is successfully!");
                        })
                        .fail(function (res) {
                            toastr.success("Your action is successfully!");
                        });
                }
            });
            $('.m-datatable__table tbody').on('click', 'tr', function (e) {
                var elem = $(this).children(':nth-child(8)').find('a');
                if (elem.length == 1) {
                    e.preventDefault();
                    e.stopPropagation();
                    window.location.href = $(elem[0]).attr('href');
                    return false;
                }
            })

        });
        $("#product-list").on("submit", function (a) {
            a.preventDefault();

            var searchParams = $('#product-list').serializeObject();

            t.setDataSourceQuery(searchParams);
            t.load()
        });
        $('#show-trashed-users').on('change', function () {
            $('#manage-agency-search').submit();
            if ($(this).is(":checked")) {
                $('#user-deleted-at').show(50, function () {
                    $('#user-created-at').hide('slow');
                    $('#user-updated-at').hide('slow');
                });
            } else {
                $('#user-deleted-at').hide(50, function () {
                    $('#user-created-at').show('slow');
                    $('#user-updated-at').show('slow');
                });

            }
        });

        $("#page-reset").on("click", function (a) {
            a.preventDefault();
            var dataTable             = t.getDataSourceQuery();
                dataTable.itemCode    = '';
                dataTable.title       = '';
                dataTable.stores_name = '';
                dataTable.stores_name = null;
                dataTable.min         = '';
                dataTable.min         = null;
                dataTable.max         = '';
                dataTable.max         = null;
                dataTable.category_id = '';
                dataTable.category_id = null;
                dataTable.createdAt   = '';
                dataTable.check       = '0';
                dataTable.updatedAt   = '';
                dataTable.speedIndex  = 0;

            $('#speedIndex').val(0);
            $('#brand').val(0);
            dataTable.brand = 0;
            dataTable.carModelId = 0;
            $('#carModelId').val(0);
            $('#vehicle').val(0);
            dataTable.vehicle = 0;
            dataTable.size = '';
            dataTable.season = '';
            $('#season').val('');
            $("#check").val(0).trigger('change');
            dataTable.trashedPages = null;
            $(this).closest('form').find("input[type=text]").val("");
            $("#stores_name").val('').trigger('change');
            $("#category_id").val('').trigger('change');
            t.setDataSourceQuery(dataTable);
            t.load()
        });




    };
    return {
        init: function () {
            t()
        }
    }
}();
jQuery(document).ready(function () {
    DatatableRemoteAjaxDemo.init()
});