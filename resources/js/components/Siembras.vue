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
                            <table class="table table-striped table-hover">
                              <thead>
                                <tr>
                                  <th scope="col">#</th>
                                  <th scope="col">Contenedor</th>
                                  <th scope="col" class="text-center" style="width:280px">
                                    <h5> Especie</h5>
                                    <div class="nav">
                                      <li class="nav-item" style="width:80px">Especie</li>
                                      <li class="nav-item" style="width:80px">Cantidad</li>
                                      <li class="nav-item" style="width:60px">Peso gr</li>
                                    </div>
                                  </th>
                                  <th scope="col">Fecha inicio siembra</th>
                                  <th scope="col">Inicio - fin de descanso estanque</th>
                                  <th scope="col">Estado</th>
                                  <th scope="col">Ingreso</th>
                                  <th scope="col">Finalizar</th>
                                  <th scope="col">Acciones</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr v-for="siembra in listadoSiembras" :key="siembra.id">
                                  <td v-text="siembra.id" scope="row"></td>
                                  <td v-text="siembra.contenedor"></td>
                                  <td>
                                    <div v-for="pez in pecesxSiembra" :key="pez.id" v-if="pez.id_siembra == siembra.id">
                                      <div class="nav text-center" >
                                        <li v-text="pez.especie" class="nav-item" style="width:80px">Especie</li>
                                        <li v-text="pez.cant_actual" class="nav-item" style="width:80px">Cantidad</li>
                                        <li v-text="pez.peso_actual+'Gr'" class="nav-item" style="width:60px">Peso</li>
                                      </div>
                                    </div>
                                  </td>
                                  <td v-text="siembra.fecha_inicio"></td>
                                  <td>{{siembra.ini_descanso}} - <br> {{siembra.fin_descanso}}</td>
                                  <td v-text="estados[siembra.estado]"></td>
                                  <td><button class="btn btn-success" @click="abrirIngreso(siembra.id)"><i class="fas fa-list-ul"></i>  Ingreso</button></td>
                                  <td><button class="btn btn-danger" @click="finalizarSiembra(siembra.id)"><i class="fas fa-power-off"></i>  Finalizar</button></td>
                                  <td>
                                    <button class="btn btn-light">
                                      <span style="font-size: 1em; color:#28a745;"><i class="fas fa-edit"></i></span>
                                    </button>
                                    <button class="btn btn-light">
                                      <span style="font-size: 1em; color:#DC3545;"><i class="fas fa-trash"></i></span>
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
                      <select v-model="form.id_contenedor" name="" class="form-control" id="id_contenedor">
                        <option :value="contenedor.id" v-for="contenedor in listadoContenedores" :key="contenedor.id" v-if="contenedor.estado == 1" selected>{{contenedor.contenedor}}</option>
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
                        <th scope="col">Peso gr</th>
                        <th scope="col">Add</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th scope="row" v-text="form.id">
                        </th>
                        <td> 
                          <select  v-model="newEspecie" name="" class="form-control" id="id_especie" required>
                            <option :value="especie.id" v-for="especie in listadoEspecies" :key="especie.id" selected >{{especie.especie}}</option>
                          </select>
                        </td>
                        <td>
                          <input  type="number" min="1" class="form-control" id="cantidad" v-model="newCantidad" required>                          
                        </td>
                        <td>
                          <input type="number" min="1" class="form-control" id="peso_inicial" v-model="newPeso" required>
                          <span style="
                            position: relative;
                            float: right;
                            right: 30px;
                            color: #ccc;
                            bottom: 30px;"
                          >Gr</span>
                        </td>
                         
                        <td>
                          <button class="btn btn-success" @click='anadirEspecie()' type="button">
                            <i class="fas fa-plus"></i>
                          </button>
                        </td>
                      </tr>
                      <tr v-for="( item, index) in listadoItems" :key="item.id">
                        <th scope="row">{{index + 1}}</th>
                        <td v-text="nombresEspecies[item.id_especie]"></td>
                        <td v-text="item.cantidad"></td>
                        <td v-text="item.peso_inicial"></td>
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
        <!-- Modal registros -->
        <div class="modal" tabindex="-1" role="dialog" id="modalIngreso">
          <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content">
              <div class="modal-header text-center">
                <h5 class="modal-title">Registros</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div id="mostrarRegistros">
                  
                  
                </div>
                <div id="crearRegistros">
                  <div class="row">
                     <div class="form-group col-md-4">
                      <label for="fecha_registro">Fecha Registro</label>
                      <input type="date" class="form-control" id="exampleInputPassword1" placeholder="Fecha" v-model="fecha_registro">
                    </div>
                    <div class="form-group col-md-4">
                      <label for="exampleInputPassword1">Tiempo (días)</label>
                      <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Tiempo" v-model="tiempo">
                    </div>
                    
                    <div class="form-group col-md-4">
                      <label for="exampleFormControlSelect1">Tipo</label>
                      <select class="form-control" id="exampleFormControlSelect1" v-model="tipo_registro">                      
                        <option value="0" >Muestreo</option>
                        <option value="1">Pesca</option>
                      </select>
                    </div>
                  </div>
                  <div>
                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">Especie</th>
                          <th scope="col" v-if="tipo_registro == 0">Peso Ganado (gr)</th>
                          <th scope="col" v-if="tipo_registro == 0">Mortalidad</th>                      
                          <th scope="col" v-if="tipo_registro == 1">Biomasa</th>
                          <th scope="col" v-if="tipo_registro == 1">Cantidad</th>
                          
                        </tr>
                      </thead>
                      <tbody>                      
                        <tr v-for="pez in pecesxSiembra" :key="pez.id" v-if="pez.id_siembra == idSiembraRegistro" >
                          <th scope="row" v-text="pez.especie">
                          </th>
                          
                          
                          <td v-if="tipo_registro == 0"> 
                            <input type="text" class="form-control" v-model="campos[pez.id_siembra][pez.id]['peso_ganado']">
                          </td>                        
                          <td v-if="tipo_registro == 0">
                            <input type="text" class="form-control" v-model="campos[pez.id_siembra][pez.id]['mortalidad']">
                          </td>
                          <td v-if="tipo_registro == 1">
                            <input type="text" class="form-control" v-model="campos[pez.id_siembra][pez.id]['biomasa']">
                          </td>
                          <td v-if="tipo_registro == 1">
                            <input type="text" class="form-control" v-model="campos[pez.id_siembra][pez.id]['cantidad']">
                          </td>                      
                        </tr>                      
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" @click="crearRegistro(idSiembraRegistro)">Crear Ingreso</button>
              </div>
            </div>
          </div>
        </div>
        <!-- Modal Finalizar -->
        <div class="modal fade" id="modalFinalizar" tabindex="-1" role="dialog" aria-labelledby="modalFinalizarLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Finalizar siembra</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form method="POST">
                  <div class="row">
                    <div class="col">
                      <h6>Inicio Descanso</h6>
                      <input type="date" class="form-control" placeholder="First name" v-model="ini_descanso" required>
                    </div>
                    <div class="col">
                      <h6>Fin descanso</h6>
                      <input type="date" class="form-control" placeholder="Last name" v-model="fin_descanso">
                    </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" @click="fechaDescanso(id_finalizar)">Guardar</button>
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
          id_contenedor:''          
        },
        itemRegistro : [],
        newEspecie: '',
        newCantidad: '',
        newPeso:'',
        listadoEspecies:[],
        listadoContenedores: [],
        listado : [],
        listadoItems : [],
        listadoSiembras : [],
        nombresEspecies : [],
        pecesxSiembra: [],
        // Registros
        anadirRegistro : 0,        
        id_siembra:'',
        id_especie : '',        
        fecha_registro:'',
        tiempo :'',
        tipo_registro:'',
        peso_ganado:'',
        mortalidad:'',
        biomasa:'',
        cantidad:'',        
        id_siembra:'',
        idSiembraRegistro:'',
        // Finalización de siembra
        ini_descanso:'',
        fin_descanso:'',
        id_finalizar: '',
        nombresContenedores: [],
        estados: [],
        campos: {
          camps_s: []
        },
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
      
      listar(){
        let me = this;
        axios.get("api/siembras")
        .then(function (response){
          me.listadoSiembras = response.data.siembra;
          me.pecesxSiembra = response.data.pecesSiembra;
          me.campos = response.data.campos; 
        })
      },
      abrirIngreso(id){
        let me = this;
        
        $("#modalIngreso").modal('show');
        console.log(id);
        this.idSiembraRegistro = id;
        this.tipo_registro = 0;
      },      
      crearRegistro(id){
        let me = this;
        this.idSiembraRegistro = id;
        let aux_campos = me.campos[id];
        console.log(me.campos);
        const data = {
          campos : aux_campos,
          id_siembra : id,        
          fecha_registro : this.fecha_registro,
          tipo_registro : this.tipo_registro,
          tiempo : this.tiempo
        }
        axios.post('api/registros', data)
        .then(({response})=>{          
          this.aux_campos = [];
           $("#modalIngreso").modal('hide');
           me.listar();
        });
      },
      finalizarSiembra(id){
        $("#modalFinalizar").modal('show');
        this.id_finalizar = id;
        
      },
      fechaDescanso(id){
        let me = this;
        if (this.ini_descanso != ''){
          if(this.fin_descanso != ''){
            const data = {
              'id' : this.id_finalizar,
              'ini_descanso' : this.ini_descanso,
              'fin_descanso' : this.fin_descanso          
            }
         
            axios.post('api/actualizarEstado/'+this.id_finalizar, data)
            .then(({response})=>{
              console.log(response);   
              this.id_finalizar = '';
              this.ini_descanso = '';
              this.fin_descanso = '';
              $('#modalFinalizar').modal('hide');
              this.listar();
            }); 
          }else{
            const data = {
              'id' : this.id_finalizar,
              'ini_descanso' : this.ini_descanso,             
            }
         
            axios.post('api/actualizarEstado/'+this.id_finalizar, data)
            .then(({response})=>{
              console.log(response);   
              this.id_finalizar = '';
              this.ini_descanso = '';              
              $('#modalFinalizar').modal('hide');
              this.listar();
            }); 
          }
        }else{
         swal("Advertencia", "Por favor, diligencia los datos restantes", "warning");
        }
        console.log('finalizar'+this.id_finalizar);
      
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
              this.listadoItems = [];              
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
      this.estados[0] = 'Inactivo';
      this.estados[1] = 'Activo';
      this.estados[2] = 'Ocupado';
      this.estados[3] = 'Descanso';
    }
  }
</script>
