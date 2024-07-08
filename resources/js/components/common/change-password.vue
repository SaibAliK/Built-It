<template>
    <div class="col-lg-8 col-md-8">
        <div class="box-shadow-cards-login change-pass-side-form-block">
            <div class="w-100">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-12 mb-25">
                                <h3 class="change-pass-tittle">{{ trans.methods.__("Old Password") }}</h3>
                                <div class="input-style">
                                    <div class="type-pass">
                                          <span class="icon-front-input">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="17.006" height="19.435"
                                                 viewBox="0 0 17.006 19.435">
                                              <path id="Icon_awesome-lock" data-name="Icon awesome-lock"
                                                    d="M15.184,8.5h-.911V5.77a5.77,5.77,0,0,0-11.54,0V8.5H1.822A1.822,1.822,0,0,0,0,10.325v7.288a1.822,1.822,0,0,0,1.822,1.822H15.184a1.823,1.823,0,0,0,1.822-1.822V10.325A1.823,1.823,0,0,0,15.184,8.5Zm-3.948,0H5.77V5.77a2.733,2.733,0,0,1,5.466,0Z"
                                                    fill="#45cea2"></path>
                                            </svg>

                                          </span>
                                        <input :type="old_pass_type" class="ctm-input border-full-cst"
                                               minlength="6" v-model="formData.current_password"
                                               :placeholder="trans.methods.__('Password*')"
                                               required>
                                        <div class="icon-eye d-flex align-items-center justify-content-center">
                                            <h2 class="pass-show-tittle" @click="showOldPass()">
                                                {{ trans.methods.__("Show") }}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h3 class="change-pass-tittle">{{ trans.methods.__("New Password") }}</h3>
                        <div class="inputs-border-wrapper">
                            <div class="input-style">
                                <div class="type-pass">
                                      <span class="icon-front-input">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17.006" height="19.435"
                                             viewBox="0 0 17.006 19.435">
                                          <path id="Icon_awesome-lock" data-name="Icon awesome-lock"
                                                d="M15.184,8.5h-.911V5.77a5.77,5.77,0,0,0-11.54,0V8.5H1.822A1.822,1.822,0,0,0,0,10.325v7.288a1.822,1.822,0,0,0,1.822,1.822H15.184a1.823,1.823,0,0,0,1.822-1.822V10.325A1.823,1.823,0,0,0,15.184,8.5Zm-3.948,0H5.77V5.77a2.733,2.733,0,0,1,5.466,0Z"
                                                fill="#45cea2"></path>
                                        </svg>
                                      </span>
                                    <input :type="new_pass_type" class="ctm-input"
                                           v-model="formData.password"
                                           :placeholder="trans.methods.__('New Password*')" minlength="6" required>
                                    <div class="icon-eye d-flex align-items-center justify-content-center">
                                        <h2 class="pass-show-tittle" @click="showNewPass()">
                                            {{ trans.methods.__("Show") }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="input-style">
                                <div class="type-pass">
                                      <span class="icon-front-input">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17.006" height="19.435"
                                             viewBox="0 0 17.006 19.435">
                                          <path id="Icon_awesome-lock" data-name="Icon awesome-lock"
                                                d="M15.184,8.5h-.911V5.77a5.77,5.77,0,0,0-11.54,0V8.5H1.822A1.822,1.822,0,0,0,0,10.325v7.288a1.822,1.822,0,0,0,1.822,1.822H15.184a1.823,1.823,0,0,0,1.822-1.822V10.325A1.823,1.823,0,0,0,15.184,8.5Zm-3.948,0H5.77V5.77a2.733,2.733,0,0,1,5.466,0Z"
                                                fill="#45cea2"></path>
                                        </svg>


                                      </span>
                                    <input :type="confirm_pass_type" class="ctm-input" minlength="6"
                                           v-model="formData.password_confirmation"
                                           :placeholder="trans.methods.__('Confirm New Password*')" required>
                                    <div class="icon-eye d-flex align-items-center justify-content-center">
                                        <h2 class="pass-show-tittle" @click="showConfPass()">
                                            {{ trans.methods.__("Show") }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <div class="edit-profile-button-dash">
                            <button type="button" class="btn btn-primary w-100" @click="submitChangePassword()">
                                {{ trans.methods.__("Change") }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import trans from '../../trans';

export default {
    name: "change-password",
    props: [],
    data() {
        return {
            old_pass_type: "password",
            new_pass_type: "password",
            confirm_pass_type: "password",
            formData: {
                current_password: "",
                password: "",
                password_confirmation: "",
            }
        };
    },
    created() {
        console.log('created function is running');
    },
    mounted() {
        console.log('mounted function is running');
    },
    methods: {
        abc() {
            console.log("Working")
        },
        provideValue(key) {
            return key === 'password' ? 'text' : 'password';
        },
        submitChangePassword() {
            if (this.formData.current_password == "") {
                toastr.error(trans.methods.__('Error'), trans.methods.__('Please Enter old Password'))
            } else if (this.formData.password == "") {
                toastr.error(trans.methods.__('Error'), trans.methods.__('Please enter New Password'))
            } else if (this.formData.password_confirmation == "") {
                toastr.error(trans.methods.__('Error'), trans.methods.__('Please enter Confirm Password'))
            } else {
                let url = window.Laravel.apiUrl + 'change-password'
                axios.post(url, this.formData)
                    .then(res => {
                        if (res.data.success == false) {
                            toastr.error(trans.methods.__('Error'), res.data.message)
                        } else {
                            toastr.success(trans.methods.__('Success'), res.data.message)
                        }
                    })
            }
        },

        showOldPass() {
            this.old_pass_type = this.provideValue(this.old_pass_type);
        },
        showNewPass() {
            this.new_pass_type = this.provideValue(this.new_pass_type);
        },
        showConfPass() {
            this.confirm_pass_type = this.provideValue(this.confirm_pass_type);
        },

    },
}
</script>

<style scoped>

</style>
