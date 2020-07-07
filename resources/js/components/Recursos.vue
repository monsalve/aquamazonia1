<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Gestión de recursos</div>

                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-12 text-right">
                                 <button class="btn btn-success" @click="abrirCrear()">Añadir Recurso</button>
                            </div>
                        </div>
                        <div class="row">
                            <table class="table table-striped">
                              <thead>
                                <tr>
                                  <th scope="col">#</th>
                                  <th scope="col">Recurso</th>
                                  <th scope="col">Unidad</th>
                                  <th scope="col">Costo</th>
                                  <th scope="col">Opciones</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr v-for="recurso in listado" :key="recurso.id">
                                  <th scope="row" v-text="recurso.id"></th>
                                  <td v-text="recurso.recurso"></td>
                                  <td v-text="recurso.unidad"></td>
                                  <td v-text="recurso.costo"></td>
                                  <td>
                                    <button @click="cargaEditar(recurso)" class="btn btn-light" type="button">
                                      <span style="font-size: 1.5em; color:#28a745 ;"  ><i class="fas fa-edit"></i></span>
                                    </button>
                                    <button @click="eliminar(recurso.id)" class="btn btn-light" type="button">
                                      <span style="font-size: 1.5em; color:#DC3545;"><i class="fas fa-trash"></i></span>                                                                            
                                    </button>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalRecursos" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modalRecursosLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalRecursosLabel" v-text="editando ==0 ? 'Crear recurso' : 'Actualizar recurso'"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="editando == 0 ? guardar() : editar()">
                            <div class="form-group row">
                                <label for="recurso" class="col-sm-12 col-md-4 col-form-label">Nombre recurso</label>
                                <div class="col-sm-12 col-md-8">
                                  <input type="text" class="form-control" id="especie" :class="{ 'is-invalid': form.errors.has('especie') }" v-model="form.recurso">
                                  <has-error :form="form" field="especie"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="unidad" class="col-sm-12  col-md-4 col-form-label">Unidad
                                </label>
                                <div class="col-sm-12 col-md-8">
                                  <input type="text" class="form-control" id="unidad"  :class="{ 'is-invalid': form.errors.has('unidad') }" v-model="form.unidad">
                                   <has-error :form="form" field="unidad"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="costo" class="col-sm-12  col-md-4 col-form-label">Costo
                                </label>
                                <div class="col-sm-12 col-md-8">
                                  <input type="text" class="form-control" id="costo" v-model="form.costo" :class="{ 'is-invalid': form.errors.has('costo') }">
                                   <has-error :form="form" field="costo"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12 text-right">
                                  <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">Cancelar</button>
                                  <button type="submit" class="btn btn-primary btn-lg"  v-text="editando ==0 ? 'Crear' : 'Actualizar'"></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import Vue from 'vue'
  import { Form, HasError, AlertError } from 'vform'
    
  Vue.component(HasError.name, HasError)
  Vue.component(AlertError.name, AlertError)
    export default {
        data() {
            return {
                editando: 0,
                form: new Form({
                    id : '',
                    recurso : '',
                    unidad : '',
                    costo : '',
                }),
               
                listado: []
            }
        },
        methods: {
            guardar(){
                let me = this;
                this.form.post('api/recursos')
                    .then(({data})=>{
                        editando: 0,
                        console.log(data);                        
                        me.listar();
                        $('#modalRecursos').modal('hide');
                        me.form.recurso = '';
                        me.form.unidad = '';
                        me.form.costo = '';
                    })
            },
            abrirCrear(){
                this.editando = 0;
                this.form.reset(); 
                $('#modalRecursos').modal('show');
            },
         
            listar(){
              let me = this;
              axios.get("api/recursos")
              .then(function (response) {
                  me.listado = response.data
              });
            },
            cargaEditar(objeto){
              let me = this;
              this.form.fill(objeto);
              this.editando = 1;
                $('#modalRecursos').modal('show');
            },
            editar(){
                let me = this;
                this.form.put('api/recursos/'+this.form.id)
                    .then(({data})=>{
                        console.log(data);   
                    
                        $('#modalRecursos').modal('hide');
                        me.listar();
                    })
          
                console.log('editando')
            },
            eliminar(index){
                let me = this;
                swal({
                  title: "Estás seguro?",
                  text: "Una vez eliminado, no se puede recuperar este registro",
                  icon: "warning",
                  buttons: ["Cancelar", "Aceptar"],
                  dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        me.form.delete('api/recursos/'+index)
                        .then(({data})=>{
                            me.listar();
                            console.log('eliminar'+index)
                        })
                    }
                });
                 
            }
        },
        mounted() {
            this.listar();
            //console.log('Component mounted.')
        }
    }
</script>
