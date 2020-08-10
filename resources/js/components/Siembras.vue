<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Gestión de Siembras</div>

                    <div class="card-body">
                        <h6>Filtros de exportación: </h6>
                        
                        <div class="row">
                          <div class="form-group">
                            <label for="Siembra">Siembra:</label>
                            <select class="form-control" id="f_siembra" v-model="f_siembra">
                              <option value="-1" selected>Seleccionar</option>                             
                              <option :value="ls.id" v-for="(ls, index) in listadoSiembras" :key="index">{{ls.nombre_siembra}}</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="Especie">Especie</label>
                            <select class="form-control" id="f_especie" v-model="f_especie">
                              <option value="-1" selected>Seleccionar</option>                             
                              <option :value="especie.id" v-for="especie in listadoEspecies" :key="especie.id">{{especie.especie}}</option>                            
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="Lote"> Lote</label>
                            <select class="form-control" id="f_lote" v-model="f_lote">
                              <option value="-1" selected>Seleccionar</option>                             
                              <option :value="lote.lote" v-for="(lote, index) in lotes" :key="index">{{lote.lote}}</option>                                                        </select>
                          </div>
                          <div class="form-group">
                            <label for="Fecha desde">Fecha inicio desde: </label>
                            <input type="date" class="form-control" id="f_inicio_d" v-model="f_inicio_d">
                          </div>
                          <div class="form-group">
                            <label for="fecha hasta">Fecha inicio hasta: </label>
                            <input type="date" class="form-control" id="f_inicio_h" v-model="f_inicio_h">
                          </div>
                          <div class="form-group">
                            <label for="fecha hasta">Hacer click antes de exportar: </label>
                            <button class="btn btn-primary form-control" @click="exportarSiembras()"> Filtrar Por criterios</button>
                          </div>
                          
                          
                        </div>
                        
                        <div class="row text-right">
                          
                          <downloadexcel
                            
                            class = "btn btn-success"
                            :fetch   = "fetchData"
                            :fields = "json_fields"
                            :before-generate = "startDownload"
                            :before-finish = "finishDownload"
                            name    = "informe-siembras-especies.xls"
                            type    = "xls">
                              <i class="fa fa-fw fa-download"></i> Generar Excel 
                          </downloadexcel>
                        </div>
                        <div class="row mb-1">
                            <div class="col-12 text-right ">
                              <button class="btn btn-success" @click="anadirItem()">Nueva siembra</button>                             
                            </div>
                        </div>
                        <div class="row">
                            <table class="table table-striped table-hover table-sm">
                              <thead>
                                <tr>
                                  <th scope="col">#</th>
                                  <th>Nombre <br> siembra</th>
                                  <th scope="col">Contenedor</th>
                                  <th scope="col" class="text-center" style="width:340px">
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
                                  <td>
                                    <div v-for="pez in pecesxSiembra" :key="pez.id" v-if="pez.id_siembra == siembra.id">
                                      <div class="nav text-center" >
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
                                  <td><button class="btn btn-primary" @click="abrirIngreso(siembra.id)"><i class="fas fa-list-ul"></i> </button></td>
                                  <td><button class="btn btn-danger" data-toggle="tooltip" title="Finalizar siembra" data-placement="top"  @click="finalizarSiembra(siembra.id)"><i class="fas fa-power-off"></i></button></td>
                                  <td>
                                    <!-- <button class="btn btn-primary">
                                      <i class="fas fa-edit"></i>
                                    </button> -->
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
                        <option :value="contenedor.id" v-for="contenedor in listadoContenedores" :key="contenedor.id" v-if="contenedor.estado == 1" selected>{{contenedor.contenedor}}</option>
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
                      <tr v-for="( item, index) in listadoItems" :key="item.id">
                        <th scope="row">{{index + 1}}</th>
                        <td v-text="nombresEspecies[item.id_especie]"></td>
                        <td v-text="item.lote"></td>
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
                      <tr v-for="(registro, index) in listadoRegistros" :key="registro.id" v-if="registro.id_siembra == idSiembraRegistro">
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
                          <button class="btn btn-danger" @click="eliminarRegistro(registro.id)">
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
                            <input type="number" class="form-control" v-model="campos[pez.id_siembra][pez.id]['peso_ganado']">
                          </td>                        
                          <td v-if="tipo_registro == 0 || tipo_registro == 2">
                            <input type="number" id="mortalidad" class="form-control" v-model="campos[pez.id_siembra][pez.id]['mortalidad']">
                          </td>
                          <td v-if="tipo_registro == 1">
                            <input type="number" class="form-control" v-model="campos[pez.id_siembra][pez.id]['biomasa']">
                          </td>
                          <td v-if="tipo_registro == 1 ">
                            <input type="number" class="form-control" v-model="campos[pez.id_siembra][pez.id]['cantidad']">
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
          id_contenedor:''         
        },
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
        pecesxSiembra: [],
        lotes :[],
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
        mortalidad_inicial : '',
        idSiembraRegistro:'',
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
        axios.get("api/registros")
        .then(function (response){
          me.listadoRegistros = response.data
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
        axios.get("api/siembras")
        .then(function (response){
          me.listadoSiembras = response.data.siembra;
          me.pecesxSiembra = response.data.pecesSiembra;
          me.campos = response.data.campos; 
          me.lotes = response.data.lotes; 
          
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
        console.log(id);
        this.idSiembraRegistro = id;
        this.tipo_registro = 0;
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
          me.aux_campos = [];
          //  $("#modalIngreso").modal('hide');
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
      eliminarRegistro(index){
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
            axios.delete('api/registros/'+index)
            .then(({data})=>{
              me.listarRegistros();
              me.listar();
              console.log('eliminar'+index)
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
      this.listarRegistros();
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
