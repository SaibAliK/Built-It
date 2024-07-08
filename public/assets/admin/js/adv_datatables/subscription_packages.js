var DatatableRemoteAjaxDemo = function () {
    var t = function () {
        var t = $("#manage-subscription-packages").mDatatable({
            data: {
                type: "remote",
                source: {read: {url: window.Laravel.baseUrl+"list/subscription_packages"}},
                pageSize: 10,
                saveState: {cookie: false, webstorage: false},
                serverPaging: !0,
                serverFiltering: !0,
                serverSorting: !0,

            },
            layout: {theme: "default", class: "", scroll: !1, footer: !1},
            sortable: false,
            ordering: false,
            filterable: !1,
            pagination: !0,
            columns:
                [
                    {field: "count", title: "#",  width: 30},
                    {field: "name", title: "Name",  width: 100},
                    {field: "duration", title: "Duration",  width: 100},
                    {field: "subscription_type", title: "Type",  width: 100},
                    {field: "is_free", title: "Is Free",  width: 100},
                    {field: "price", title: "Price",  width: 100},
                    {field: "actions", title: "Action",  width: 70}
                ]
        });
        a = t.getDataSourceQuery();
        $("#manage-subscription-packages-search").on("submit", function (a) {
            a.preventDefault();
            var searchParams = $('#manage-page-search').serializeObject();
            t.setDataSourceQuery(searchParams),
                t.load()
        });
        $("#page-reset").on("click", function (a) {
            a.preventDefault();
            var dataTable = t.getDataSourceQuery();
            dataTable.slug = '';
            dataTable.title = '';
            dataTable.createdAt = '';
            dataTable.updatedAt = '';
            dataTable.deletedAt = ''
            dataTable.trashedPages=null;
            $(this).closest('form').find("input[type=text]").val("");
            t.setDataSourceQuery(dataTable);
            t.load()
        });
        t.on('m-datatable--on-layout-updated', function(params){
            $('.delete-article-button').on('click', function(e){
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
                                .done(function(res){ toastr.success("Subscription deleted successfully"); location.reload(); })
                                .fail(function(res){ toastr.success("Subscription deleted successfully");location.reload(); });
                        } else {
                            swal.close();
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
