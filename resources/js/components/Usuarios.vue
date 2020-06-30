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
                            
                            <div :class="['form-group', allerros.name ? 'has-error' : '']" >
                                <label for="name" class="col-sm-4 control-label">Nombre</label> 
                                <div class="col-sm-6">
                                    <input id="name" name="name" value=""  autofocus="autofocus" class="form-control" type="text" v-model="form.name" required>
                                    <span v-if="allerros.name" :class="['label label-danger']">@{{ allerros.name[0] }}</span>
                                </div>
                            </div> 
                            <div :class="['form-group', allerros.email ? 'has-error' : '']" >
                                <label for="email" class="col-sm-4 control-label">Correo</label> 
                                    <div class="col-sm-6">
                                        <input id="email" name="email"  class="form-control" type="email" v-model="form.email" required>
                                        <span v-if="allerros.email" :class="['label label-danger']">@{{ allerros.email[0] }}</span>
                                    </div>
                                </div>   
                            </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
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
                form: new form({
                    name : '',
                    email : '',
                    estado: 1,
                }),
                allerros: [],
                success : false,
            }
        },
        methods : {
            guardar() {
                /*
                this.form.post('api/contenedores')                
                .then(()=>{
                    /*Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast({
                        type: 'success',
                        title: 'Curso creado correctamente'
                        })
                    this.$Progress.finish();

                })
                .catch(function (error) {
                    console.log(error);
                });
                */
                axios.post("api/contenedores")
                .then(function (response) {
                    var respuesta= response.data;
                    console.log("ajam");
                    console.log(respuesta);
                   
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        },
        mounted() {
            //console.log('Component mounted.')
        }
    }
</script>
