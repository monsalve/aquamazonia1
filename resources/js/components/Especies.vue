<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Gestión de especies</div>

                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-12 text-right ">
                              <button class="btn btn-success" @click="abrirCrear()">Añadir especie</button>
                            </div>
                        </div>
                        <div class="row">
                            <table class="table table-striped">
                              <thead>
                                <tr>
                                  <th scope="col">#</th>
                                  <th scope="col">Especie</th>
                                  <th scope="col">Descripción</th>
                                  <th scope="col">Estado</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr v-for="especie in listado" :key="especie.id">
                                  <th scope="row" v-text="especie.id"></th>
                                  <td v-text="especie.especie">Tilapia</td>
                                  <td v-text="especie.descripcion"></td>
                                  <td>
                                    <button @click="cargaEditar(especie)" class="btn btn-light" type="button">
                                      <span style="font-size: 1.5em; color:#28a745 ;"  ><i class="fas fa-edit"></i></span>
                                    </button>
                                    <button @click="eliminar(especie.id)" class="btn btn-light" type="button">
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
        <div class="modal fade" id="modalEspecies" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modalEspeciesLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalEspeciesLabel" v-text="editando ==0 ? 'Crear especie' : 'Actualizar especie'"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form @submit.prevent="editando == 0 ? guardar() : editar()">
                  <div class="form-group row">
                    <label for="contenedor" class="col-sm-12 col-md-4 col-form-label">Especie</label>
                    <div class="col-sm-12 col-md-8">
                      <input type="text" class="form-control" id="especie"  :class="{ 'is-invalid': form.errors.has('especie') }" v-model="form.especie">
                       <has-error :form="form" field="especie"></has-error>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="Descripción" class="col-sm-12  col-md-4 col-form-label">Descripción</label>
                    <div class="col-sm-12 col-md-8">
                      <input type="text" class="form-control" id="descripcion"  :class="{ 'is-invalid': form.errors.has('descripción') }" v-model="form.descripcion">
                       <has-error :form="form" field="descripcion"></has-error>
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
                    especie : '',
                    descripcion : '',
                }),
               
                listado: []
            }
        },
        methods: {
            guardar(){
                let me = this;
                this.form.post('api/especies')
                    .then(({data})=>{
                      editando: 0,
                      console.log(data);                        
                      me.listar();
                      $('#modalEspecies').modal('hide');
                      me.form.especie = '';
                      me.form.descripcion = '';
                    })
            },
            abrirCrear(){
                this.editando = 0;
                this.form.reset(); 
                $('#modalEspecies').modal('show');
            },
         
            listar(){
              let me = this;
              axios.get("api/especies")
              .then(function (response) {
                  me.listado = response.data
              });
            },
            cargaEditar(objeto){
              let me = this;
              this.form.fill(objeto);
              this.editando = 1;
                $('#modalEspecies').modal('show');
            },
            editar(){
                let me = this;
                this.form.put('api/especies/'+this.form.id)
                    .then(({data})=>{
                        console.log(data);   
                    
                        $('#modalEspecies').modal('hide');
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
                        me.form.delete('api/especies/'+index)
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
