<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-6">
        <div class="card card-default">
          <div class="card-header">Register</div>
          <div class="card-body">
           <div class="alert alert-success" v-if="success">
                
                <p>Confirm your email now.</p>
            </div>
              
            <div class="alert alert-danger" v-if="has_error && !success">
                <p v-if="error == 'registration_validation_error'">Validation Errors.</p>
                <p v-else>Error.</p>
            </div>
              
            <form autocomplete="off" @submit.prevent="register" v-if="!success" method="post">
                <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.name }">
                    <label for="name">Name</label>
                    <input type="text" id="name" class="form-control" placeholder="Full Name" v-model="name" >                    
                    <div class="alert alert-danger" v-if="has_error && errors.name"> {{errors.name[0]}}</div>
                </div>
                <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.email }">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email"> 
                    <div class="alert alert-danger" v-if="has_error && errors.email"> {{errors.email[0]}}</div>
                </div>
                <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.password }">
                    <label for="password">Password</label>
                    <input type="password" id="password" class="form-control" v-model="password">                   
                    <div class="alert alert-danger" v-if="has_error && errors.password"> {{errors.password[0]}}</div>
                </div>
                <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.password }">
                    <label for="password_confirmation">Password confirmation</label>
                    <input type="password" id="password_confirmation" class="form-control" v-model="password_confirmation">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
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
        password_confirmation: '',
        has_error: false,
        error: '',
        errors: {},
        success: false
      }
    },
    methods: {
      register() {
        var app = this
        this.$auth.register({
          data: {
            name: app.name,
            email: app.email,
            password: app.password,
            password_confirmation: app.password_confirmation
          },
          success: function () {
            app.success = true  
            this.$router.push({name: 'register', params: {successRegistrationRedirect: true}})
            this.name = ''  
          },
          error: function (res) {            
            app.has_error = true
            app.error = res.response.data.error
            app.errors = res.response.data.errors || {}
          }
        })
      }
    }
  }
</script>