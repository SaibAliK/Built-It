<template>
    <div :class="calledPage == 'dashboard' ? 'col-lg-8 col-md-8' : ''">

        <template v-if="calledPage == 'dashboard' && showNotifications">
            <div class="">
                <div class="d-flex align-items-center justify-content-between use-clas-noti-bordr">
                    <div class="shipping-heading-notipage">
                        <label class="switch">
                            <input id="flexSwitchCheckChecked"
                                   type="checkbox"
                                   v-bind:checked="notificationSet == 1"
                                   @click="notificationSetting">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <button class="clear-all"
                            @click="deleteMany()"
                            v-if="notifications.length > 0">
                        {{ trans.methods.__('delete All') }}
                    </button>
                </div>

                <div class="notificatopn-page">
                    <div class="row">
                        <div class="col-12">
                            <template v-if="notifications.length > 0">
                                <div class="notify-cover" v-for="notification in notifications">
                                    <div class="notification-box">
                                        <div class="d-flex w-100 d-flex align-items-center justify-content-between">
                                            <div class="text-content w-100">
                                                <div class="order-no">
                                                    <span class="noti-book-tittke"
                                                          @click="read(notification)"
                                                          v-html="$options.filters.language(notification.title)">
                                                    </span>
                                                    <span
                                                        class="time-tittle-ammm" @click="read(notification)">{{
                                                            notification.created_at | elapsed
                                                        }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="des" @click="read(notification)">
                                            {{
                                                $options.filters.language(notification.description)
                                            }}
                                        </div>

                                        <div class="leave-feedback-btn-not"
                                             v-if="notification.action == 'SUBSCRIPTION_REMINDER' || notification.action == 'SUBSCRIPTION_EXPIRED'">
                                            <button @click="read(notification)"
                                                    type="button" class="leave-back-cs">
                                                {{ trans.methods.__("Renew Subscription") }}
                                            </button>
                                        </div>

                                        <div class="leave-feedback-btn-not"
                                             v-if="(notification.action === 'PRODUCT_REVIEWS' || notification.action === 'STORE_REVIEWS') && notification.sender.user_type == 'supplier'">
                                            <button @click="read(notification)"
                                                    type="button" class="leave-back-cs">
                                                {{ trans.methods.__("Leave a Feedback") }}
                                            </button>
                                        </div>

                                    </div>
                                    <div class="text-right-absol">
                                        <button class="time-del-page-not btn-style-none p-0"
                                                @click="deleteOne(notification.id)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="8.515" height="8.515"
                                                 viewBox="0 0 8.515 8.515">
                                                <path id="Icon_metro-cross" data-name="Icon metro-cross"
                                                      d="M11.008,8.769h0L8.425,6.186,11.008,3.6h0a.267.267,0,0,0,0-.376l-1.22-1.22a.267.267,0,0,0-.376,0h0L6.828,4.589,4.245,2.006h0a.267.267,0,0,0-.376,0l-1.22,1.22a.267.267,0,0,0,0,.376h0L5.232,6.186,2.649,8.769h0a.267.267,0,0,0,0,.376l1.22,1.22a.267.267,0,0,0,.376,0h0L6.828,7.782l2.583,2.583h0a.267.267,0,0,0,.376,0l1.22-1.22a.267.267,0,0,0,0-.376Z"
                                                      transform="translate(-2.571 -1.928)" fill="#fff"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                            <div class="noti-detail" v-if="notifications.length == 0">
                                <div class="col-12">
                                    <div class="alert alert-danger w-100 mt-2" role="alert">
                                        {{ trans.methods.__("No Record Found") }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template v-if="!isLoading && calledPage == 'header'">
            <div aria-labelledby="dropdownMenuButton" class="dropdown-menu">
                <div class="header-notification-main">
                    <div class="header-notification-main-inner">
                        <template class="v-if=" notifications.length>
                            <div class="notification-box" v-for="notification in notifications">
                                <div
                                    class="d-flex w-100 d-flex align-items-center justify-content-between">
                                    <div class="img-box" @click="read(notification)"
                                         v-if="notification.sender_id == notification.receiver_id">
                                        <img :src="
                                         (base + 'assets/front/img/Logo.png')
                                         | resizeImage(40, 40)"
                                             class="img-fluid" alt="">
                                    </div>
                                    <div class="img-box" @click="read(notification)" v-else>
                                        <img src="notification.sender.image | resizeImage(66, 66)"
                                             class="img-fluid" alt="">
                                    </div>

                                    <div class="text-content w-100">

                                        <div class="order-no">
                                        <span class="noti-book-tittke" @click="read(notification)">{{
                                                $options.filters.language(notification.description)
                                            }}</span>
                                        </div>
                                        <div class="text-right-absol">
                                            <button class="time-del btn-style-none p-0">
                                                <i
                                                    class="fa-solid fa-xmark"></i>
                                            </button>
                                            <div class="time-noti" @click="read(notification)">{{
                                                    notification.created_at | elapsed
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="des" @click="read(notification)">
                                    {{
                                        $options.filters.language(notification.description)
                                    }}
                                </div>


                                <div class="button-noti-main-area d-flex"
                                     v-if="notification.action == 'SUBSCRIPTION_REMINDER' || notification.action == 'SUBSCRIPTION_EXPIRED'">
                                    <div class="add-basket-button notipage-page-bt">
                                        <button @click="read(notification)"
                                                type="button" class="btn btn-primary w-100">
                                            {{ trans.methods.__("Renew Subscription") }}
                                        </button>
                                    </div>
                                </div>

                                <div class="button-noti-main-area d-flex"
                                     v-if="(notification.action === 'PRODUCT_REVIEWS' || notification.action === 'STORE_REVIEWS') && notification.sender.user_type == 'supplier'">
                                    <div class="add-basket-button notipage-page-bt">
                                        <button @click="read(notification)"
                                                type="button" class="btn btn-primary w-100">
                                            {{ trans.methods.__("Leave a Feedback") }}
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </template>
                        <div class="noti-detail" v-if="notifications.length == 0">
                            <div class="col-12">
                                <div class="alert alert-danger w-100 mt-2" role="alert">
                                    {{ trans.methods.__("No Record Found") }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="button-header-not-main">
                    <button type="button" @click="deleteMany"
                            class="btn btn-head-not-1">{{ trans.methods.__('Clear All') }}
                    </button>
                    <a :href="baseUrl + 'dashboard/notification'"
                       class="btn btn-head-not-2">{{ trans.methods.__('View All') }}
                    </a>
                </div>
            </div>
        </template>

    </div>
</template>

<script>
export default {
    name: "notifications",
    props: [
        "class1",
        "class2",
        "calledfrom",
        "currentpagenumber",
        "usersettings",
    ],

    data() {
        return {
            baseUrl: window.Laravel.baseUrl,
            loggedInUserId: window.Laravel.user_id,
            loginUserType: window.Laravel.user_type,
            calledPage: this.calledfrom,
            notificationSet: this.usersettings,
            notifications: [],
            isLoading: false,
            showNotifications: false,
            base: window.Laravel.base,
        };
    },
    created() {
        console.log("Created");
        this.setSocketListeners();
        if (this.calledPage == "dashboard") {
            this.seenAll();
        }
        this.isLoading = true;
        this.list();
    },
    mounted() {
    },
    methods: {
        setSocketListeners() {
            Echo.channel(
                `seven-new-notification-` + this.loggedInUserId
            ).listen(".new-notification", (e) => {
                // this.seenAll()
                if (this.calledPage == "dashboard") {
                    this.seenAll();
                }
                this.list();
                console.log(e);
            });
        },
        list() {
            axios
                .get(`${window.Laravel.apiUrl}notifications`, {
                    params: {page: this.currentpagenumber},
                })
                .then((response) => {
                    if (response.data.success) {
                        this.notifications = response.data.data.collection;
                        this.isLoading = false;
                        this.showNotifications = true;
                        this.$emit("notifications-loaded", true);
                    } else {
                        console.error("Notifications Error =>", response);
                    }
                });
        },
        seenAll() {
            axios
                .get(`${window.Laravel.apiUrl}notification-seen`)
                .then((response) => {
                    if (response.data.success) {
                    } else {
                        console.error("Seen Notifications Eerror =>", response);
                    }
                });
        },
        read(data) {
            axios
                .get(`${window.Laravel.apiUrl}notification-view/${data.id}`)
                .then((response) => {
                    if (response.data.success) {
                        if (data.action === "ORDER") {
                            window.location = `${window.Laravel.baseUrl}dashboard/order/${data.extras.order_id}/detail`;
                        }

                        if (data.action === "PRODUCT_REVIEWS" || data.action === "CART") {
                            window.location = `${window.Laravel.baseUrl}product-detail/${data.extras.product_id}/add-review`;
                        }

                        if (data.action === "PRODUCT") {
                            window.location = `${window.Laravel.baseUrl}product-detail/${data.extras.product_id}`;
                        }

                        if (data.action === "STORE_REVIEWS") {
                            window.location = `${window.Laravel.baseUrl}supplier-detail/${data.extras.store_id}/add-review`;
                        }

                        if (
                            data.action === "SUBSCRIPTION_REMINDER" ||
                            data.action === "SUBSCRIPTION_EXPIRED"
                        ) {
                            window.location = `${window.Laravel.baseUrl}dashboard/subscription-packages`;
                        }

                        if (data.action === "WITHDRAW") {
                            window.location = `${window.Laravel.baseUrl}dashboard/payment-profile`;
                        }
                    } else {
                        console.error("Seen Notifications Error =>", response);
                    }
                });
        },
        getTimeCreatedTime(unix) {
            return moment.unix(unix).format("h:mm a");
        },
        deleteOne(id) {
            let that = this;
            swal(
                {
                    title: that.trans.methods.__("Are you sure you want to delete this?"),
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#1C4670",
                    confirmButtonText: that.trans.methods.__("Delete"),
                    cancelButtonText: that.trans.methods.__("No"),
                    closeOnConfirm: false,
                    closeOnCancel: false,
                    showLoaderOnConfirm: true,
                },
                function (isConfirm) {
                    if (isConfirm) {
                        axios
                            .post(`${window.Laravel.apiUrl}notification-delete`, {
                                notifications: [id],
                            })
                            .then((response) => {
                                if (response.data.success) {
                                    // console.log(that.calledPage)
                                    toastr.success(
                                        that.trans.methods.__("Success"),
                                        that.trans.methods.__(
                                            "Notification has been deleted successfully!"
                                        )
                                    );
                                    if (that.calledPage == "dashboard") {
                                        location.reload();
                                        // window.location = `${window.Laravel.baseUrl}dashboard/notifications`;
                                    } else if (that.calledPage == "header") {
                                        location.reload();
                                    } else {
                                        that.list();
                                    }
                                } else {
                                    console.error("Notifications Error =>", response);
                                }
                            });
                    } else {
                        swal.close();
                    }
                }
            );
        },
        ChangeSiteSettingAlert() {
            let that = this;
            swal(
                {
                    title: that.trans.methods.__(
                        "Are you sure you want to update setting?"
                    ),
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#1C4670",
                    confirmButtonText: that.trans.methods.__("Yes"),
                    cancelButtonText: that.trans.methods.__("No"),
                    closeOnConfirm: false,
                    closeOnCancel: false,
                    showLoaderOnConfirm: true,
                },
                function (isConfirm) {
                    if (isConfirm) {
                        axios
                            .get(`${window.Laravel.apiUrl}set/user/settings`)
                            .then((response) => {
                                if (response.data.success) {
                                    console.log(
                                        "this is response data",
                                        response,
                                        response.data.data.collection.settings
                                    );
                                    this.notificationSet = response.data.data.collection.settings;
                                    location.reload();
                                } else {
                                    console.error("Notifications Error =>", response);
                                }
                            });
                    } else {
                        this.notificationSet = 0;
                        swal.close();
                        location.reload();
                    }
                }
            );
        },
        deleteMany() {
            let that = this;
            swal(
                {
                    title: that.trans.methods.__("Are you sure you want to delete this?"),
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#1C4670",
                    confirmButtonText: that.trans.methods.__("Delete"),
                    cancelButtonText: that.trans.methods.__("No"),
                    closeOnConfirm: false,
                    closeOnCancel: false,
                    showLoaderOnConfirm: true,
                },
                function (isConfirm) {
                    if (isConfirm) {
                        axios
                            .post(`${window.Laravel.apiUrl}notification-delete`, {
                                notifications: [],
                            })
                            .then((response) => {
                                if (response.data.success) {
                                    toastr.success(
                                        that.trans.methods.__(
                                            "All Notifications has been deleted successfully!"
                                        )
                                    );
                                    if (that.calledPage == "dashboard") {
                                        location.reload();
                                        // window.location = `${window.Laravel.baseUrl}dashboard/notifications`;
                                    } else if (that.calledPage == "header") {
                                        location.reload();
                                    }
                                    that.list();
                                } else {
                                    console.error("Notifications Error =>", response);
                                }
                            });
                    } else {
                        swal.close();
                    }
                }
            );
        },
        notificationSetting() {
            this.ChangeSiteSettingAlert();
        },
    },
};
</script>
