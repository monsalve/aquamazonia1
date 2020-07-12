<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Gestión de alimentos</div>

                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-12 text-right">
                                 <button class="btn btn-success" @click="abrirCrear()">Añadir Alimento</button>
                            </div>
                        </div>
                        <div class="row">
                            <table class="table table-hover">
                              <thead>
                                <tr>
                                  <th scope="col">#</th>
                                  <th scope="col">Alimento</th>
                                  <th scope="col">Costo</th>
                                  <th scope="col">Estado</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr v-for="alimento in listado" :key="alimento.id">
                                  <th scope="row" v-text="alimento.id"></th>
                                  <td v-text="alimento.alimento"></td>
                                  <td v-text="alimento.costo_kg"></td>
                                  <td>
                                    <!-- <span style="font-size: 1.5em; color:#FFC107;"><i class="fas fa-user"></i></span>-->
                                    <button @click="cargaEditar(alimento)" class="btn btn-light" type="button">
                                        <span style="font-size: 1em; color:#28a745 ;"  >
                                            <i class="fas fa-edit"></i>
                                        </span>
                                    </button>
                                    <button @click="eliminar(alimento.id)" class="btn btn-light" type="button">
                                        <span style="font-size: 1em; color:#DC3545;">
                                            <i class="fas fa-trash"></i>
                                        </span>
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
         <div class="modal fade" id="modalAlimentos" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modalAlimentosLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalalimentosLabel" v-text="editando ==0 ? 'Crear alimento' : 'Actualizar alimento'"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="editando == 0 ? guardar() : editar()">
                            <div class="form-group row">
                                <label for="alimento" class="col-sm-12 col-md-4 col-form-label">Nombre Alimento</label>
                                <div class="col-sm-12 col-md-8">
                                  <input type="text" class="form-control" id="alimento"  :class="{ 'is-invalid': form.errors.has('alimento') }" v-model="form.alimento">
                                 
                                   <has-error :form="form" field="alimento"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="costo" class="col-sm-12  col-md-4 col-form-label">Costo
                                </label>
                                <div class="col-sm-12 col-md-8">
                                    <input type="text" class="form-control" id="costo_kg" v-model="form.costo_kg" :class="{ 'is-invalid': form.errors.has('costo_kg') }">
                                     <has-error :form="form" field="costo_kg"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12 text-right">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                  <button type="submit" class="btn btn-primary" v-text="editando ==0 ? 'Crear' : 'Actualizar'"></button>
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
                    alimento : '',
                    costo_kg : '',
                }),
               
                listado: []
            }
        },
        methods: {
            guardar(){
                let me = this;
                this.form.post('api/alimentos')
                    .then(({data})=>{
                        editando: 0,
                        console.log(data);                        
                        me.listar();
                        $('#modalAlimentos').modal('hide');
                        me.form.alimento = '';
                        me.form.costo_kg = '';
                    })
            },
            abrirCrear(){
                this.editando = 0;
                this.form.reset(); 
                $('#modalAlimentos').modal('show');
            },
         
            listar(){
                let me = this;
                axios.get("api/alimentos")
                .then(function (response) {
                    me.listado = response.data
                });
            },
            cargaEditar(objeto){
                let me = this;
                this.form.fill(objeto);
                this.editando = 1;
                  $('#modalAlimentos').modal('show');
                   console.log('editandosssss')
            },
            editar(){
                let me = this;
                this.form.put('api/alimentos/'+this.form.id)
                    .then(({data})=>{
                        console.log(data);   
                        $('#modalAlimentos').modal('hide');
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
                        me.form.delete('api/alimentos/'+index)
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
