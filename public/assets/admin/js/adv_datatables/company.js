var DatatableRemoteAjaxDemo = function () {
    var t = function () {
        var t = $("#manage-company").mDatatable({
            data: {
                type: "remote",
                source: {read: {url: window.Laravel.baseUrl+"list/companies"}},
                pageSize: 10,
                saveState: {cookie: !0, webstorage: !0},
                serverPaging:true,
                serverFiltering: false,
                serverSorting: false,
            },
            layout: {theme: "default", class: "", scroll: !1, footer: !1},
            sortable: false,
            ordering: false,
            filterable: true,
            pagination: true,
            columns:
                [
                    {field: "id", title: "#",  width: 20 },
                    {field: "name", title: "Name",  width: 150},
                    {field: "email", title: "Email",  width: 200},
                    {field: "city", title: "City",  width: 100},
                    {field: "rating", title: "Ratings",  width: 70},
                    {field: "is_id_card_verified", title: "Id Card Verified",  width: 100},
                    {field: "actions", title: "Action",  width: 150}
                ]
        });
        t.on('m-datatable--on-layout-updated', function(params){
            $('.delete-record-button').on('click', function(e){
                var url = $(this).data('url');
                swal({
                        title: "Are you sure you want to delete this?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#1C4670",
                        confirmButtonText: "Delete",
                        cancelButtonText: "No",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm){
                        if (isConfirm) {
                            $.ajax({
                                type: 'delete',
                                url: url,
                                dataType: 'json',
                                headers: {
                                    'X-CSRF-TOKEN': window.Laravel.csrfToken
                                }
                            })
                                .done(function(res){ toastr.success("Store deleted successfully!"); location.reload(); })
                                .fail(function(res){ toastr.success("Store deleted  successfully!"); t.reload();  });
                        } else {
                            swal.close()
                        }
                    });
            });
            $('.restore-record-button').on('click', function(e){
                var url = $(this).data('url');
                swal({
                        title: "Are You Sure You Want To Restore This?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#1C4670",
                        confirmButtonText: "Restore",
                        cancelButtonText: "No",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm){
                        if (isConfirm) {
                            $.ajax({
                                type: 'get',
                                url: url,
                                dataType: 'json',
                                headers: {
                                    'X-CSRF-TOKEN': window.Laravel.csrfToken
                                }
                            })
                                .done(function(res){ toastr.success("Agency restored !");
                                    $('#show-trashed-users').click();
                                    location.reload(); })
                                .fail(function(res){ toastr.success("Agency restored!"); t.reload();  });
                        } else {
                            swal.close()
                        }
                    });
            });

            $('.toggle-status-button').on('click', function(e) {
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
                        .done(function(res){ toastr.success("Your action is successfully!"); t.reload(); })
                        .fail(function(res){ toastr.success("Your action is successfully!"); t.reload(); });
                }
            });
            $('.m-datatable__table tbody').on('click', 'tr', function(e) {
                var elem = $(this).children(':nth-child(8)').find('a');
                if (elem.length==1) {
                    e.preventDefault();
                    e.stopPropagation();
                    window.location.href = $(elem[0]).attr('href');
                    return false;
                }
            })

        });
        // $("#manage-store-search").on("submit", function (a) {
        //     a.preventDefault();
        //     var searchParams = $('#manage-store-search').serializeObject();
        //
        //     t.setDataSourceQuery(searchParams),
        //         t.load()
        // });
        // $('#show-trashed-users').on('change', function(){
        //     $('#manage-agency-search').submit();
        //     if ($(this).is(":checked")){
        //         $('#user-deleted-at').show(50,function(){
        //             $('#user-created-at').hide('slow');
        //             $('#user-updated-at').hide('slow');
        //         });
        //     }else{
        //         $('#user-deleted-at').hide(50,function(){
        //             $('#user-created-at').show('slow');
        //             $('#user-updated-at').show('slow');
        //         });
        //
        //     }
        // });
        $("#manage-companies-search").on("submit", function (a) {
            a.preventDefault();
            var searchParams = $('#manage-companies-search').serializeObject();

            t.setDataSourceQuery(searchParams),
                t.load()
        });


        $("#page-reset").on("click", function (a) {
            console.log('it is in agency');
            a.preventDefault();
            var dataTable = t.getDataSourceQuery();
            dataTable.name = '';
            dataTable.email = '';
            dataTable.createdAt = '';
            dataTable.updatedAt = '';

            $("#store-type option:eq(0)").prop("selected", true);
            $("#show-email-status option:eq(0)").prop("selected", true);
            $("#show-active-status option:eq(0)").prop("selected", true);
            $("#show-store-rating option:eq(0)").prop("selected", true);
            $(this).closest('form').find("input[type=text]").val("");
            t.setDataSourceQuery(dataTable);
            // t.load()
            $('#manage-store-search').submit();
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


