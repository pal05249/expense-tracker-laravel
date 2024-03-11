<template>
    <div class="container">
        <div class="row justify-content-start ">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">User Profile</h2>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="updateProfile()">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" id="name" required class="form-control" v-model.trim="name">
                                <span class="text-danger">
                                    <ul class="list-unstyled">
                                        <li v-for="(error) in this.Error?.name" :key="error">{{ error }}</li>
                                    </ul>
                                </span>
                            </div>

                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" id="email" required class="form-control" v-model.trim="email">
                                <span class="text-danger">
                                    <ul class="list-unstyled">
                                        <li v-for="(error) in this.Error?.email" :key="error">{{ error }}</li>
                                    </ul>
                                </span>
                            </div>
                            <div class="alert alert-success alert-dismissible mb-1" role="alert"
                                v-if="showAlert == true">
                                {{ message }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <button type="submit" class="btn btn-primary mr-2 my-2">Update Profile</button>
                        </form>
                        <hr />
                        <button type="button" class="btn btn-outline-danger mr-2 my-2"
                            @click="this.showResetPassword = !this.showResetPassword">Update Password</button>
                        <div>
                            <form @submit.prevent="resetPassword()" v-if="showResetPassword">
                                <div class="form-group">
                                    <label for="password">New Password:</label>
                                    <input type="text" id="password" required class="form-control"
                                        v-model.trim="password">
                                    <span class="text-danger">
                                        <ul class="list-unstyled">
                                            <li v-for="(error) in this.Error?.password" :key="error">{{ error }}
                                            </li>
                                        </ul>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="confirmPassword">confirmPassword:</label>
                                    <input type="text" id="confirmPassword" required class="form-control"
                                        v-model.trim="confirmPassword">
                                    <span class="text-danger">
                                        <ul class="list-unstyled">
                                            <li v-for="(error) in this.Error?.confirmPassword" :key="error">{{ error }}
                                            </li>
                                        </ul>
                                    </span>
                                </div>
                                <div class="alert alert-success alert-dismissible mb-1" role="alert"
                                    v-if="showResetPasswordAlert == true">
                                    {{ message }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <button type="submit" class="btn btn-primary mr-2 my-2">Reset Password</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            name: '',
            email: '',
            password: '',
            confirmPassword: '',
            showResetPassword: false,
            showResetPasswordAlert: false,
            showAlert: false,
            userDetails: {},
            Error: {}
        };
    },
    mounted() {
        this.fetchLoggedInUserDetails()
    },
    methods: {
        fetchLoggedInUserDetails() {
            axios.get("/logged-in-user-details").then((response) => {
                this.userDetails = response.data.userDetails;
                this.email = this.userDetails.email
                this.name = this.userDetails.name;
            }).catch((err) => {
                console.log(err)
            })
        },
        updateProfile() {
            // Send update request to the backend
            axios
                .put(`/update-profile/${this.userDetails.id}`, { name: this.name, email: this.email })
                .then((response) => {
                    this.fetchLoggedInUserDetails()
                    this.message = response.data.message;
                    this.showAlert = true;
                    setTimeout(() => {
                        this.showAlert = false;
                    }, 1000);
                })

                .catch((error) => {
                    if (error.status == 401) {
                        alert(error.data);
                    } if (error.response.status === 422) {
                        this.Error = error.response.data.errors
                    }
                    if (error)
                        setTimeout(() => {
                            this.Error = "";
                        }, 5000);
                });
            this.loading = false;
        },
        validateResetPasswordForm() {
            if (this.password !== this.confirmPassword) {
                this.Error = { confirmPassword: ["New Password and confirm Password should be same"] };
                setTimeout(() => {
                    this.Error = "";
                }, 5000);
                return true;
            }
            return false;
        },
        resetPassword() {
            if (this.validateResetPasswordForm())
                return;
            // Send update request to the backend
            axios
                .put(`/reset-password/${this.userDetails.id}`, { password: this.password, confirmPassword: this.confirmPassword })
                .then((response) => {
                    this.clearForm()
                    this.message = response.data.message;
                    this.showResetPasswordAlert = true;
                    setTimeout(() => {
                        this.showResetPasswordAlert = false;
                    }, 1000);
                })

                .catch((error) => {
                    if (error.status == 401) {
                        alert(error.data);
                    } if (error?.response?.status === 422) {
                        this.Error = error.response.data.errors
                    }
                    if (error)
                        setTimeout(() => {
                            this.Error = "";
                        }, 5000);
                });
            this.loading = false;
        },
        clearForm() {
            this.password = this.confirmPassword = ""
        }
    }
};
</script>