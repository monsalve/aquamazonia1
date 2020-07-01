<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Gestión de Usuarios</div>

                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-12 ">
                                <button class="btn btn-success float-right" type="button" data-toggle="modal" data-target="#agregar">
                                    Agregar
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Correo</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">-</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="agregar" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="Agregar/Editar" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Datos Usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="/vuejs/form" class="form-horizontal" @submit.prevent="guardar" >
                        
                        <div class="modal-body">
                            
                            <div :class="['form-group', form.errors().has('name') ? 'has-error' : '']" >
                                <label for="name" class="col-sm-4 control-label">Nombre</label> 
                                <div class="col-sm-6">
                                    <input id="name" name="name" value=""  autofocus="autofocus" class="form-control" type="text" v-model="form.name" required>
                                    <span v-if="form.errors().has('name')" class="label label-danger" v-text="form.errors().get('name')"></span>
                                </div>
                            </div> 
                            <div :class="['form-group', form.errors().has('email') ? 'has-error' : '']" >
                                <label for="email" class="col-sm-4 control-label">Correo</label> 
                                <div class="col-sm-6">
                                    <input id="email" name="email"  class="form-control" type="email" v-model="form.email" required>
                                    <span v-if="form.errors().has('email')" class="label label-danger" v-text="form.errors().get('email')"></span>
                                </div>
                                
                            </div>
                            <div :class="['form-group', form.errors().has('password') ? 'has-error' : '']" >
                                <label for="password" class="col-sm-4 control-label">Password</label> 
                                <div class="col-sm-6">
                                    <input id="password" name="password"  class="form-control" type="password" v-model="form.password" required>
                                    <span v-if="form.errors().has('password')" class="label label-danger" v-text="form.errors().get('password')"></span>
                                </div>                                   
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary" v-text="editando==0 ? 'Guardar' : 'Actualizar'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>    
</template>

<script>
import form from 'vuejs-form'
    export default {
        data() {    
            return {
                editando: 0,
                form: form.default({
                    name : '',
                    email : '',
                    password: '',
                    estado: 1,
                })
                .rules({
                    email: 'email|min:7|required',
                    name: 'required|min:5|',
                    password: 'required|min:5|'
                })
                .messages({
                    'name.name': 'El nombre es requerido',
                    'email.email': 'Ingrese un correo válido',
                    'password.password': 'El password es requerido',
                }),
                errores: [],
                success : false,
                listado: []
            }
        },
       
        methods : {
                      
            guardar() {
                let me = this;
                if( !this.form.errors().any() ) {
                     axios.post("api/usuarios",this.form.all())
                    .then(function (response) {
                        
                        me.form.email='';
                        me.form.name='';
                        me.form.password='';
                        $('#agregar').modal('hide');
                        me.listar();
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }
                else 
                {
                    console.log('errors: ', this.form.errors().all());
                }
                
                
            },
            listar(){
                 axios.post("api/usuarios",this.form.all())
                    .then(function (response) {
                        
                    });
                
            },
            cargaEditar(objeto){

            }
        },
        mounted() {
            //console.log('Component mounted.')
        }
    }
</script>
