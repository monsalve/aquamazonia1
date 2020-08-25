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
                            <table class="table table-striped table-hover table-sm table-responsive">
                              <thead>
                                <tr>
                                  <th scope="col">#</th>
                                  <th>Nombre <br> siembra</th>
                                  <th scope="col">Contenedor</th>
                                  <th scope="col" class="text-center d-sm-none d-none d-md-block" style="width:340px">
                                    <h5> Especie</h5>
                                    <div class="nav">
                                      <li class="nav-item" style="width:80px">Especie</li>
                                      <li class="nav-item" style="width:80px">Lote</li>
                                      <li class="nav-item" style="width:80px">Cantidad</li>
                                      <li class="nav-item" style="width:60px">Peso gr</li>
                                    </div>
                                  </th>
                                  <th scope="col">Inicio siembra</th>
                                  <th scope="col">Inicio - fin de <br> descanso estanque</th>
                                  <th scope="col">Estado</th>
                                  <th scope="col">Fecha <br>Alimentación</th>
                                  <th scope="col">Ingreso</th>
                                  <th scope="col">Finalizar</th>
                                  <th scope="col">Acciones</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr v-for="(siembra, index) in listadoSiembras" :key="siembra.id">
                                  <th v-text="index+1" scope="row"></th>
                                  <td v-text="siembra.nombre_siembra" scope="row"></td>
                                  <td v-text="siembra.contenedor"></td>
                                  <td class="d-sm-none d-none d-md-block">
                                    <div v-for="pez in pecesxSiembra" :key="pez.id" >
                                      <div class="nav text-center" v-if="pez.id_siembra == siembra.id">
                                        <li v-text="pez.especie" class="nav-item border-bottom" style="width:80px">Especie</li>
                                        <li v-text="pez.lote" class="nav-item border-bottom" style="width:80px">Lote</li>
                                        <li v-text="pez.cant_actual" class="nav-item border-bottom" style="width:80px">Cantidad</li>
                                        <li v-text="pez.peso_actual+'Gr'" class="nav-item border-bottom" style="width:60px">Peso</li>
                                      </div>
                                    </div>
                                  </td>
                                  <td v-text="siembra.fecha_inicio"></td>
                                  <td>{{siembra.ini_descanso}} - <br> {{siembra.fin_descanso}}</td>
                                  <td v-text="estados[siembra.estado]"></td>
                                  <td v-bind:class="[fechaActual <= siembra.fecha_alimento ? '' : 'bg-warning']">
                                    {{siembra.fecha_alimento}}
                                    
                                    <button type="button" class="btn btn-success btn-sm" @click="abrirCrear(siembra.id)">Añadir Alimentos</button>
                                  </td>
                                  <td><button class="btn btn-primary" @click="abrirIngreso(siembra.id)"><i class="fas fa-list-ul"></i> </button></td>
                                  <td><button class="btn btn-warning" data-toggle="tooltip" title="Finalizar siembra" data-placement="top"  @click="finalizarSiembra(siembra.id)"><i class="fas fa-power-off"></i></button></td>
                                  <td>
                                    <button class="btn btn-danger" @click="eliminarSiembra(siembra.id)">
                                      <i class="fas fa-trash"></i>
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
        <!-- Modal Siembras -->
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
                  <div class="form-group row   col-md-4">
                    <div class="col-sm-12 col-md-12 text-left">
                      <label for="">Contenedor</label>
                      <select v-model="form.id_contenedor" name="" class="form-control" id="id_contenedor">
                        <option :value="contenedor.id" v-for="(contenedor, index) in listadoContenedores" :key="index" selected>
                          <span v-if="contenedor.estado == 1">{{contenedor.contenedor}}</span>
                        </option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row   col-md-4">
                    <div class="col-sm-12 col-md-12 text-left">
                      <label for="nombre_siembra">Nombre de Siembra</label>
                      <input class="form-control" type="text" id="nombre_siembra" v-model="form.nombre_siembra">
                    </div>
                  </div>
                  <div class="form-group row  col-md-4">
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
                        <th scope="col">Lote</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Peso gr</th>
                        <th scope="col">Añadir</th>
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
                          <input  type="text" min="1" class="form-control" id="lote" v-model="newLote" required>                          
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
                      <tr v-for="( item, index) in listadoItems" :key="index" >
                        <th scope="row">{{index + 1}}</th>
                        <td v-text="nombresEspecies[item.id_especie]"></td>
                        <td v-text="item.lote"></td>
                        <td v-text="item.cantidad"></td>
                        <td v-text="item.peso_inicial"></td>
                        <td><button @click="removeItem(item.id_especie)" class="btn btn-primary">X</button></td>
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
         <!-- Modal añadir alimentos a siembras -->
        <div class="modal fade" id="modalRecursos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Alimentos por siembra</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">          
                <form class="row">
                  <!-- <div class="col-md-6"> -->
                    <div class="form-group col-md-3 ">   
                      <label for="horas hombre" class="">Fecha</label>
                      <input type="date" class="form-control" id="fecha_ra" aria-describedby="fecha_ra" placeholder="Horas hombre" v-model="form.fecha_ra">                      
                    </div>
                   
                    <div class="form-group col-md-3">
                      <label for="Alimento" class="">Alimento</label>
                      <select class="form-control" id="alimento" v-model="form.id_alimento" >
                        <option>--Seleccionar--</option>
                        <option v-for="(alimento, index) in listadoAlimentos" :key="index" v-bind:value="alimento.id">{{alimento.alimento}}</option>                  
                      </select>
                    </div>        
                     <div class="form-group col-md-6">   
                      <label for="detalles" class="">Detalles</label>
                      <textarea class="form-control" id="detalles" aria-describedby="detalles" placeholder="Detalles" v-model="form.detalles"></textarea>
                    </div>     
                  
                    <div class="form-group col-md-3">   
                      <label for="horas hombre" class="">Horas hombre</label>
                      <input type="number" class="form-control" step="any" id="horas_hombre" aria-describedby="horas_hombre" placeholder="Horas hombre" v-model="form.horas_hombre">                      
                    </div>
              
                    <div class="form-group col-md-3">                    
                      <label for="cant_manana" class="">Kg Mañana</label>
                      <input type="number" class="form-control" id="kg_manana" aria-describedby="cant_manana" placeholder="Kg Mañana" v-model="form.cant_manana">                      
                    </div>
                    <div class="form-group col-md-3">    
                      <label for="cant_tarde" class="">Kg tarde</label>
                      <input type="number" class="form-control" id="cant_tarde" aria-describedby="cant_tarde" placeholder="Kg tarde" v-model="form.cant_tarde">                      
                    </div>
                </form>
                <div class="modal-footer">
                  <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                  <button type="button" class="btn btn-primary" @click="guardarRecursos()">Guardar</button>
                </div>
              </div>
              <div class="container">
                <table class="table table-sm table-hover table-responsive">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Tipo de <br> Actividad</th>
                      <!-- <th>Siembras</th> -->
                      <th>Fecha</th>
                      <th><br>Alimento</th>
                      <th>Horas hombre</th>
                      <th>Cantidad<br>Mañana</th>
                      <th>Cantidad<br>Tarde</th>
                      <th width=15%>Detalles</th>
                      <th>Eliminar</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in listadoRN" :key="index">
                      <td v-text="index+1"></td>
                      <td v-text="item.tipo_actividad"></td>                   
                      <td v-text="item.fecha_ra"></td>
                      <td> {{item.alimento}}</td>
                      <td v-text="item.horas_hombre"></td>
                      <td v-text="item.cant_manana == null ? '-' : item.cant_manana +' kg' "></td>
                      <td v-text="item.cant_tarde == null ? '-' : item.cant_tarde +' kg' "></td>
                      <td v-text="item.detalles"></td>
                      <td>
                        <button class="btn btn-danger" @click="eliminarRecurso(item.id_registro)">
                          <i class="fas fa-trash"></i>
                        </button>
                      </td>
        
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary" @click="guardarRecursos()">Guardar</button> -->
              </div>
            </div>
          </div>
        </div>
        <!-- Modal registros -->
        <div class="modal" tabindex="-1" role="dialog" id="modalIngreso">
          <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-center col-md-9">Registros</h5>
                <button type="button" class="btn btn-primary"  @click="ver_registros == 1 ? ver_registros = 0 : ver_registros = 1">
                  <span v-if="ver_registros == 1">Crear Registros  <i class="fas fa-arrow-right"></i></span>
                  <span v-if="ver_registros == 0"><i class="fas fa-arrow-left"></i>  Ver listado de registros</span>
                </button>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div id="mostrarRegistros" v-if="ver_registros == 1">
                  <table class="table table-sm">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Especie</th>
                        <th>Tipo de registro</th>
                        <th>Fecha</th>
                        <th>Tiempo (días)</th>
                        <th>Peso ganado (gr)</th>
                        <th>Mortalidad</th>
                        <th>Biomasa</th>
                        <th>Cantidad</th>
                        <th>Eliminar</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(registro, index) in listadoRegistros" :key="registro.id">
                        <th v-text="index+1"></th>
                        <td v-text="registro.especie"></td>
                        <td v-text="tipoRegistro[registro.tipo_registro]"></td>
                        <td v-text="registro.fecha_registro"></td>
                        <td v-text="registro.tiempo"></td>
                        <td v-text="registro.peso_ganado == null ? '-' : registro.peso_ganado+'gr'"></td>
                        <td v-text="registro.mortalidad  == null ? '-' : registro.mortalidad"></td>
                        <td v-text="registro.biomasa  == null ? '-' : registro.biomasa"></td>
                        <td v-text="registro.cantidad  == null ? '-' : registro.cantidad"></td>
                        <td>
                          <button class="btn btn-danger" @click="eliminarRegistro(registro.id, registro)">
                            <i class="fas fa-trash"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  
                </div>
                <div id="crearRegistros" v-if="ver_registros == 0">
                  <div class="row">
                     <div class="form-group col-md-4">
                      <label for="fecha_registro">Fecha Registro</label>
                      <input type="date" class="form-control" id="fecha_registro" placeholder="Fecha" v-model="fecha_registro">
                    </div>
                    <div class="form-group col-md-4">
                      <label for="tiempo">Tiempo (días)</label>
                      <input type="number" class="form-control" id="tiempo" placeholder="Tiempo" v-model="tiempo">
                    </div>
                    
                    <div class="form-group col-md-4">
                      <label for="tipo_registro">Tipo</label>
                      <select class="form-control" id="tipo_registro" v-model="tipo_registro">                      
                        <option value="0" >Muestreo</option>
                        <option value="1">Pesca</option>
                        <option value="2">Mortalidad inicial</option>
                      </select>
                    </div>
                  </div>
                  <div style="width:60%; margin:auto">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th scope="col">Especie</th>
                          <th scope="col" v-if="tipo_registro == 0">Peso Ganado actual (gr)</th>
                          <th scope="col" v-if="tipo_registro == 0">Mortalidad</th>                      
                          <th scope="col" v-if="tipo_registro == 1">Biomasa</th>
                          <th scope="col" v-if="tipo_registro == 1">Cantidad</th>
                          <th scope="col" v-if="tipo_registro == 2">Mortalidad Inicial</th>
                        </tr>
                      </thead>
                      <tbody>                      
                        <tr v-for="pez in pecesxSiembra" :key="pez.id" v-if="pez.id_siembra == idSiembraRegistro" >
                          <th scope="row" v-text="pez.especie">
                          </th>
                          <td v-if="tipo_registro == 0"> 
                            <input type="number" class="form-control" v-bind:required="tipo_registro == 0 ? 'required' : ''" step="any" v-model="campos[pez.id_siembra][pez.id]['peso_ganado']">
                          </td>                        
                          <td v-if="tipo_registro == 0 || tipo_registro == 2">
                            <input type="number" id="mortalidad" class="form-control" v-bind:required="tipo_registro == 0 || 2 ? 'required' : ''" v-model="campos[pez.id_siembra][pez.id]['mortalidad']">
                          </td>
                          <td v-if="tipo_registro == 1">
                            <input type="number" step="any" class="form-control" v-bind:required="tipo_registro == 1 ? 'required' : ''" v-model="campos[pez.id_siembra][pez.id]['biomasa']">
                          </td>
                          <td v-if="tipo_registro == 1 ">
                            <input type="number" class="form-control" v-bind:required="tipo_registro == 1 ? 'required' : ''" v-model="campos[pez.id_siembra][pez.id]['cantidad']">
                          </td>                                                 
                        </tr>                      
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" v-if="ver_registros == 0" @click="crearRegistro(idSiembraRegistro)">Crear registro</button>
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
  import downloadexcel from "vue-json-excel"
    
  export default {
    data(){
      return {
        json_fields: {
          'Siembras': 'nombre_siembra',
          'Contenedor' : 'contenedor',
          'Fecha Inicio' : 'fecha_inicio',
          'Estado' : 'estado',
          'Especies' : 'especie',
          'Lotes' : 'lote', 
          'Cantidad Inicial' : 'cantidad',
          'Peso Inicial' : 'peso_inicial',
          'Cantidad Actual': 'cant_actual',
          'Peso actual' : 'peso_actual'
        },
        form:{
          id : '',
          fecha_inicio:'',
          nombre_siembra:'',
          id_contenedor:'',
          id_siembra: '',
          id_recurso : 0,
          id_alimento :'',
          tipo_actividad : 'Alimentacion',
          fecha_ra : '',
          horas_hombre : '',
          cant_manana : '',
          cant_tarde : '',
          detalles : ''
          
        },
        fechaActual: [],
        ver_registros : 1,
        itemRegistro : [],
        newLote:'',
        newEspecie: '',
        newCantidad: '',
        newPeso:'',
        listadoEspecies:[],
        listadoContenedores: [],
        listado : [],
        listadoItems : [],
        listadoSiembras : [],
        listadoRegistros: [],
        nombresEspecies : [],
        listadoAlimentos:[],
        listadoRN :[],
        pecesxSiembra: [],
        lotes :[],
        // Registros
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
        mortalidad_inicial : '',
        idSiembraRegistro:'',
        idSiembraR: '',
        // Finalización de siembra
        ini_descanso:'',
        fin_descanso:'',
        id_finalizar: '',
        nombresContenedores: [],
        estados: [],
        tipoRegistro : [],
        imprimirSiembras : [],
        campos: {
          camps_s: []
        },
        
        // Filtros para exportar
        f_siembra : '',
        f_especie: '', 
        f_lote : '',
        f_inicio_d : '',
        f_inicio_h : '',
        
      }
    },
    components: {
      downloadexcel,
    },
    
    methods:{
      
      async fetchData(){
        let me = this;
        const response = await this.imprimirSiembras
        return this.imprimirSiembras;
        //  imprimirSiembras
      },
      startDownload(){
          alert('show loading');
      },
      finishDownload(){
          alert('hide loading');
      },
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
      listarRegistros(){
        let me = this;
        // axios.get("api/registros")
        // .then(function (response){
        //   me.listadoRegistros = response.data
        // })
      },
      listarAlimentos(){
        let me = this;
        axios.get("api/alimentos")
        .then(function (response){
          me.listadoAlimentos = response.data; 
          var auxAlimento = response.data;
       
        })
      },
      anadirItem(){
        let me = this;
        $('#modalSiembra').modal('show');
        this.listarEspecies();
        this.listarContenedores();
        console.log('añadir item') 
      },
      abrirCrear(id){
        let me = this;
        $('#modalRecursos').modal('show');
        this.form.id_siembra = id;
        this.idSiembraR= id;
        axios.post("api/siembras-alimentacion/"+id)
        .then(function (response){
          me.listadoRN = response.data.recursosNecesarios;         
        })
        console.log(id);
      },
     
      anadirEspecie(){
        let me = this;
        if(this.newEspecie != '' && this.newCantidad != '' && this.newPeso != ''){
          me.listadoItems.push(
          {
            'id_especie' : this.newEspecie,
            'lote' : this.newLote,
            'cantidad' : this.newCantidad,
            'peso_inicial' : this.newPeso
          });
          const idEspecie = (element) => element.id == this.newEspecie;
          var index = this.listadoEspecies.findIndex(idEspecie);
          this.listadoEspecies.splice(index,1);
          this.newEspecie = '';
          this.newLote = '',
          this.newCantidad = '';
          this.newPeso = ''
        }else{
          alert ('Debe diligenciar todos los campos');
        }
      },
       removeItem(index){
       console.log(index)
        let me =  this;
        me.listadoItems.pop(index,1)   
        this.listadoEspecies.push({
          'id':index,
          'especie' : this.nombresEspecies[index]
          });
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
        this.listarEspecies();
        this.listadoExcel();
        this.listarAlimentos();
        axios.get("api/siembras")
        .then(function (response){
          me.listadoSiembras = response.data.siembra;
          me.pecesxSiembra = response.data.pecesSiembra;
          me.campos = response.data.campos; 
          me.lotes = response.data.lotes; 
          me.fechaActual = response.data.fecha_actual
          
        })
      },
      listadoExcel(){
        let me = this;
        axios.get("api/traer-siembras")
        .then(function (response){
          me.imprimirSiembras = response.data.filtrarSiembras;                    
          
        })      
      },
      abrirIngreso(id){
        let me = this;
        this.ver_registros = 1;
        $("#modalIngreso").modal('show');
        this.idSiembraRegistro = id;
        this.tipo_registro = 0;
        axios.post("api/registros-siembra/"+id)
        .then(function (response){
          me.listadoRegistros = response.data
        })
      },      
      crearRegistro(id){        
        let me = this;
        this.ver_registros = 0;
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
          console.log(response)
          me.aux_campos = [];          
          me.ver_registros = 1;
          me.listarRegistros();
          me.listar();
        });
      },
      
      exportarSiembras(){
        let me = this;
        if(this.f_siembra == ''){this.f_s = '-1'}else{this.f_s = this.f_siembra}
        if(this.f_especie == ''){this.f_e = '-1'}else{this.f_e = this.f_especie}
        if(this.f_lote == ''){this.f_l = '-1'}else{this.f_l = this.f_lote}
        if(this.f_inicio_d == ''){this.f_d = '-1'}else{this.f_d = this.f_inicio_d}
        if(this.f_inicio_h == ''){this.f_h = '-1'}else{this.f_h = this.f_inicio_h}
        
        const data = {
          'f_siembra' : this.f_s,
          'f_especie' : this.f_e,
          'f_lote' : this.f_l,
          'f_inicio_d' : this.f_d,
          'f_inicio_h' : this.f_h
        }
        axios.post("api/filtro-siembras", data)
        .then(response=>{
          console.log(response.data);
          me.imprimirSiembras = response.data.filtrarSiembras;
          alert("La lista ya ha sido generada. Hacer clic en 'Generar excel', para exportar los datos");
        })
        
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
        if(this.form.id_contenedor != '' && this.form.nombre_siembra != '' && this.form.fecha_inicio != '' && this.listadoItems.length > 0){
            const data = {
              siembra: this.form, 
              especies : this.listadoItems
            }
            axios.post('api/siembras',data)
            .then(({response})=>{
              this.form.nombre_siembra = '';
              this.form.id_contenedor = '';
              this.form.fecha_inicio = '';
              this.newEspecie = '';
              this.newLote = '';
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
      guardarRecursos(id){
        let me = this;      
        axios.post("api/recursos-necesarios", this.form)
        .then(({data})=>{
          console.log('guardado');
          me.listar();
          // $('#modalRecursos').modal('hide');          
        })
      },
      eliminarRegistro(id, objeto){
        console.log(id, '+', objeto)
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
          
            const data = {
              campos :  objeto 
            }
            axios.put('api/registros/'+id, data)
            .then(({data})=>{
              
              console.log('eliminar'+id)
            })
          }
        });
      },
      eliminarRecurso(objeto){
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
            axios.delete('api/recursos-necesarios/'+objeto)
            .then(({data})=>{
              console.log('eliminar'+objeto);
              me.listar();
              
            })
          }
        });        
      },
     
      eliminarSiembra(index){
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
            axios.delete('api/siembras/'+index)
            .then(({data})=>{
              me.listarRegistros();
              me.listar();
              console.log('eliminar'+index)
            })
          }
        });
      }
      
    },
    mounted() {
      this.listar();
      this.nombreEspecie();
      this.estados[0] = 'Inactivo';
      this.estados[1] = 'Activo';
      this.estados[2] = 'Ocupado';
      this.estados[3] = 'Descanso';
      this.tipoRegistro[0] = 'Muestreo';
      this.tipoRegistro[1] = 'Pesca';
      this.tipoRegistro[2] = 'Mortalidad Inicial'
    }
  }
</script>
