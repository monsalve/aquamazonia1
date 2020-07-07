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
                                  <th >#</th>
                                  <th>Contenedor</th>
                                  <th >Especie</th>
                                  <th>Fecha Inicio</th>
                                  <th >Estado</th>
                                  <th >Registros</th>
                                  <th >Finalizar</th>
                                 
                                  <th >Acciones</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr v-for="siembra in listadoSiembras" :key="siembra.id">
                                  <td v-text="siembra.id">1</td>
                                  <td v-text="'Contenedor '+siembra.id_contenedor">Tilapia</td>
                                  <td>Especie</td>
                                  <td v-text="siembra.fecha_inicio">Lorem ipsum dolor .</td>
                                  <td v-text="siembra.estado">2020-09-20</td>
                                  <td>
                                  <button class="btn btn-success">Registrar</button>
                                  </td>
                                  <td>
                                  <button class="btn btn-danger">Finalizar
                                  </button>
                                  </td>
                                  
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
                <div class="container row">
                  <div class="form-group row   col-md-6">
                    <div class="col-sm-12 col-md-12 text-left">
                      <label for="">Contenedor</label>
                      <select v-model="form.id_contenedor" name="" class="form-control" id="id_contenedor" selected>
                        <option :value="contenedor.id" v-for="contenedor in listadoContenedores" :key="contenedor.id">{{contenedor.contenedor}}</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row  col-md-6">
                    <div class="col-sm-12 col-md-12 text-left">
                      <label for="">Fecha Inicio</label>
                      <input type="date" class="form-control" id="fecha_inicio" v-model="form.fecha_inicio" required>
                    </div>
                  </div>
                  <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col" style="width:20%">Especie</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Peso</th>
                        <th scope="col">Add</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th scope="row" v-text="form.id">
                        </th>
                        <td> 
                          <select  v-model="newEspecie" name="" class="form-control" id="id_especie" required>
                            <option :value="especie.id" v-for="especie in listadoEspecies" :key="especie.id"  >{{especie.especie}}</option>
                          </select>
                        </td>
                        <td>
                          <input  type="number" min="1" class="form-control" id="cantidad" v-model="newCantidad" required>                          
                        </td>
                        <td>
                          <input type="number" min="1" class="form-control" id="peso_inicial" v-model="newPeso" required>
                        </td>
                         
                        <td>
                          <button class="btn btn-light" @click='anadirEspecie()' type="button">
                            <span style="font-size: 1em;" class="btn btn-success"><i class="fas fa-plus"></i></span>
                          </button>
                        </td>
                      </tr>
                      <tr v-for="item in listadoItems" :key="item.id">
                        <th scope="col" v-text="item.id"></th>
                        <td v-text="nombresEspecies[item.id_especie]"></td>
                        <td v-text="item.cantidad"></td>
                        <td v-text="item.peso_inicial+ ' gr'"></td>
                      </tr>
                    </tbody>
                  </table>
                  
                </div>
                <div class="modal-footer">
                  <div class="form-group row">
                    <div class="col-sm-12 text-right">
                      <button type="button" class="btn btn-secondary " data-dismiss="modal">Cancelar</button>
                      <button type="submit" @click="guardar()" class="btn btn-primary">Crear</button>
                    </div>
                  </div>
                </div>
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
        form:{
          id : '',
          fecha_inicio:'',
          id_contenedor:'',
        },
        newEspecie: '',
        newCantidad: '',
        newPeso:'',
        listadoEspecies:[],
        listadoContenedores: [],
        listado : [],
        listadoItems : [],
        listadoSiembras : [],
        nombresEspecies : [],
        nombresContenedores: []
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
      listarSiembras(){
        let me = this;
        axios.get("api/siembras")
        .then(function (response){
          me.listadoSiembras = response.data
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
          if(this.newEspecie != '' && this.newCantidad != '' && this.newPeso != ''){
            me.listadoItems.push(
            {
              'id_especie' : this.newEspecie,
              'cantidad' : this.newCantidad,
              'peso_inicial' : this.newPeso
            });
            const idEspecie = (element) => element.id == this.newEspecie;
            var index = this.listadoEspecies.findIndex(idEspecie);
            this.listadoEspecies.splice(index,1);
            this.newEspecie = '';
            this.newCantidad = '';
            this.newPeso = ''
          }else{
          alert ('Debe diligenciar todos los campos');
          }
      },
      nombreEspecie(){
        let me = this;
        axios.get("api/especies")
        .then(function (response){
          var auxEspecie = response.data;
          auxEspecie.forEach(element => me.nombresEspecies[element.id] = element.especie);
        })
      },
      nombreContenedores(){
        let me = this;
        axios.get("api/contenedores")
        .then(function (response){
          var auxEspecie = response.data;
          auxEspecie.forEach(element => me.nombresContenedores[element.id] = element.contenedor);
        })
      },
      listar(){
        let me = this;
        axios.get("api/siembras")
        .then(function (response){
          me.listadoSiembras = response.data;
        })
      },
      abrirCrear(){
      },
      guardar(){
        let me = this;
        if(this.form.id_contenedor != '' && this.form.fecha_inicio != '' && this.listadoItems.length > 0){
            const data = {
              siembra: this.form, 
              especies : this.listadoItems
            }
            axios.post('api/siembras',data)
            .then(({response})=>{
              this.form.id_contenedor = '';
              this.form.fecha_inicio = '';
              this.newEspecie = '';
              this.newCantidad = '';
              this.newPeso = '';
              listadoItems = [];
              console.log(siembra);     
              this.listar();
               $('#modalSiembra').modal('hide');
            });
          
        }else{
          alert('Debe diligenciar todos los campos');
        }
       
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
      this.nombreEspecie();
      this.nombreContenedores();
    }
  }
</script>
