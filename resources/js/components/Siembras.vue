<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Gestión de Siembras</div>

                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-12 text-right ">
                                 <button class="btn btn-success" @click="anadirItem()">Nueva siembra</button>
                            </div>
                        </div>
                        <div class="row">
                            <table class="table table-striped">
                              <thead>
                                <tr>
                                  <th scope="col">#</th>
                                  <th scope="col">Contenedor</th>
                                  <th scope="col">Especie</th>
                                  <th scope="col">Fecha Inicio</th>
                                  <th scope="col">Ingreso</th>
                                  <th scope="col">Finalizar</th>
                                  <th>Estado</th>
                                  <th scope="col">Acciones</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <th scope="row">1</th>
                                  <td>Tilapia</td>
                                  <td>Lorem ipsum dolor .</td>
                                  <td>2020-09-20</td>
                                  <td>Ingreso</td>
                                  <td>Finalizar</td>
                                   <td>Estado</td>
                                  <td>
                                    <button class="btn btn-light">
                                      <span style="font-size: 1.5em; color:#28a745 ;"  ><i class="fas fa-edit"></i></span>
                                    </button>
                                    <button class="btn btn-light">
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
        <div class="modal fade" id="modalSiembra" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modalSiembraLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalSiembralLabel">Crear siembra</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form>
                  <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Contenedor</th>
                        <th scope="col">Especie</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Peso</th>
                        <th>Add</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th scope="row" v-text="form.id">
                          
                        </th>
                        <td>
                          <select v-model="form.id_siembra" name="" class="form-control" id="">
                            <option :value="contenedor.id" v-for="contenedor in listadoContenedores" :key="contenedor.id"  >{{contenedor.contenedor}}</option>
                          </select>
                        </td>
                        <td> 
                          <select v-model="form.id_especie" name="" class="form-control" id="">
                            <option :value="especie.id" v-for="especie in listadoEspecies" :key="especie.id"  >{{especie.especie}}</option>
                          </select>
                        </td>
                        <td>
                          <input type="number" class="form-control" id="cantidad" v-model="form.cantidad">                          
                        </td>
                        <td>
                          <input type="number" class="form-control" id="peso_inicial" v-model="form.peso_inicial">
                        </td>
                        <td>
                          <button class="btn btn-light" @click='anadirEspecie()' type="submit">
                            <span style="font-size: 1em;" class="btn btn-success"><i class="fas fa-plus"></i></span>
                          </button>
                        </td>
                      </tr>
                      <tr v-for="item in listadoItems" :key="item.id">
                        <th scope="col" v-text="item.id"></th>
                        <td v-text="item.id_siembra"></td>
                        <td v-text="item.id_especie"></td>
                        <td v-text="item.cantidad"></td>
                        <td v-text="item.peso_inicial"></td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="form-group row">
                    <div class="col-sm-12 text-right">
                      <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">Cancelar</button>
                      <button type="submit" @click="guardar()" class="btn btn-primary btn-lg">Crear</button>
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
    data(){
      return {
        form: new Form({
          id : '',
          id_siembra:'',
          id_especie:'',
          cantidad:'',
          peso_inicial:''
        }),
        
        listadoEspecies:[],
        listadoContenedores: [],
        listado : [],
        listadoItems : []
      }
    },
    methods:{
      listarEspecies(){
        let me = this;
        axios.get("api/especies")
        .then(function (response){
          me.listadoEspecies = response.data
        })
      },
      listarContenedores(){
        let me = this;
        axios.get("api/contenedores")
        .then(function (response){
          me.listadoContenedores = response.data
        })
      },
      anadirItem(){
        let me = this;
        $('#modalSiembra').modal('show');
        this.listarEspecies();
        this.listarContenedores();
        console.log('añadir item') 
       
      },
      anadirEspecie(){
        let me = this;
        me.listadoItems.push(
          {'id': this.form.id++,
          'id_siembra': this.form.id_siembra,
          'id_especie' : this.form.id_especie,
          'cantidad' : this.form.cantidad,
          'peso_inicial' : this.form.peso_inicial}
        );
      },
      listar(){
      },
      abrirCrear(){
      },
      guardar(){
       let me = this;
          this.form.post('api/siembras')
            .then(({response})=>{
              console.log(response);                        
              this.listadoItems.id_siembra = '';
              this.listadoItems.id_especie = '';
              this.listadoItems.cantidad = '';
              this.listadoItems.peso_inicial = '';
            });
          console.log('guardar') ;
      },
      cargarEditar(){
      },
      editar(){
      },
      eliminar(){
      }
      
    },
    mounted() {
      this.listar();
    }
  }
</script>
