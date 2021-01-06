<template>
<div>
    <h3>Todo</h3>
    <div class="alert alert-danger" v-if="has_error">
        <p>Error.</p>
    </div>

     <button  class="btn btn-secondary" @click="newTodo()" >Add New Todo</button> 
    <table class="table">
        <tr>
            <th scope="col">Id</th>
            <th scope="col">Title</th>            
        </tr>
        <tr v-for="todo in todos" v-bind:key="todo.id" style="margin-bottom: 5px;">
            <th scope="row">{{ todo.id }}</th>
            <td>{{ todo.title }}</td>
            <td>  
            <button @click="editTodo(todo)">Edit</button>
                   
            <button @click="deleteRecord(todo)">Delete</button>  
            </td>
        </tr>
    </table>
    
            <!----------------------- Modal  ------------------------------------>
            <div class="modal fade" id="formTodo" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New Todo</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Todo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateTodo() : createTodo()">
                <div class="modal-body">
                     <div class="form-group">
                        <input v-model="formTodo.title" type="text" name="title"
                            placeholder="Title"
                            class="form-control" :class="{ 'is-invalid': formTodo.errors.has('title') }">
                        <has-error :form="formTodo" field="title"></has-error>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"  @click="formHideReset()">Close</button>
                    <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                    <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                </div>

                </form>

                </div>
            </div>
            </div> 
            <!----------------------- Modal ------------------------------------>
</div>
</template>
<script>
 import Vue from 'vue';
  export default {
    data() {
      return {
        editmode: false,  
        has_error: '',errors:'',
        todos: '',
        currentModel:'', currentForm:'',          
        formTodo: new Form({ 
        id:'',                  
        title : '',    
       }),    
      }
    },
    mounted() {
      this.getTodos()
    },
    methods: {
      getTodos() {
        this.$http({url: `auth/todos`,method: 'GET'})
            .then((res) => {
              this.todos = res.data.todos
            }, () => {
              this.has_error = true
            })
      },
    formHideReset(){ 
        this.formTodo.reset();             
        $('#formTodo').modal('hide');
        this.errors = ''
        this.has_error = ''
    },
   ///////////////////////////////////////////////New
    newTodo(){
                this.editmode = false;
                this.formTodo.reset();
                this.errors = ''
                this.has_error = ''
                $('#formTodo').modal('show');
    },
    /////////////////////////////////////////////Edit
    editTodo(todo){
                this.editmode = true;
                this.formTodo.reset();
                this.errors = ''
                this.has_error = ''
                $('#formTodo').modal('show');                
                this.formTodo.fill(todo);
                //console.log(this.form)
    },
    ////////////////////////////////////////////////////////CREATE POST
    createTodo(){                
                this.formTodo.post('/auth/todos/create')
                .then(()=>{
                    //Fire.$emit('AfterCreate');
                    $('#formTodo').modal('hide')
                    this.getTodos()
                    
                })
                .catch(()=>{})
    },
 //////////////////////////////////////////////////////// UPDATE  Post     
    updateTodo(){             
                this.formTodo.put('/auth/todos/update/'+this.formTodo.id)
                .then(() => {
                    // success
                    $('#formTodo').modal('hide');
                    this.getTodos()
                    
                })
                .catch(() => {
                    
                });

     },
 /////////////////////////////////////////////////////////////DELETE
     deleteRecord(item){
            swal.fire({title: 'Are you sure?',text: "You won't be able to revert this!",type: 'warning',showCancelButton: true,
            confirmButtonColor: '#3085d6',cancelButtonColor: '#d33',confirmButtonText: 'Yes, delete it!'})
                .then((result) => {                      
                if (result.value) {
                    this.formTodo.delete('/auth/todos/delete/'+item.id)
                        .then((res)=>{
                            if(res.data.deleted) { 
                            this.getTodos()   
                            }
                        }).catch((res)=> {
                            if(res.status === 422) {                            
                                console.log('error')
                            }
                        
                    });
                }
                })
        
    },   
 

       
    }
  }
</script>