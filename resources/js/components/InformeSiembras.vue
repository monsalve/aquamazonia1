<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Gestión de Siembras</div>

                    <div class="card-body">
                        <h6>Filtros de exportación: </h6>
                        
                        <div class="row">

                          <div class="form-group col-md-2">
                            <label for="Siembra">Siembra:</label>
                            <select class="form-control" id="f_siembra" v-model="f_siembra">
                              <option value="-1" selected>Seleccionar</option>                             
                              <option :value="ls.id" v-for="(ls, index) in listadoSiembras" :key="index">{{ls.nombre_siembra}}</option>
                            </select>
                          </div>
                          <div class="form-group col-md-2">
                            <label for="Siembra">Estado Siembra:</label>
                            <select class="form-control" id="f_estado_s" v-model="f_estado_s">
                              <option value="-1" selected>Todos</option>                             
                              <option value="0">Inactivo</option>                             
                              <option value="1">Activo</option>                             
                            </select>
                          </div>
                          <div class="form-group col-md-2">
                            <label for="Especie">Especie</label>
                            <select class="form-control" id="f_especie" v-model="f_especie">
                              <option value="-1" selected>Seleccionar</option>                             
                              <option :value="especie.id" v-for="especie in listadoEspecies" :key="especie.id">{{especie.especie}}</option>                            
                            </select>
                          </div>
                          <div class="form-group col-md-2">
                            <label for="Lote"> Lote</label>
                            <select class="form-control" id="f_lote" v-model="f_lote">
                              <option value="-1" selected>Seleccionar</option>                             
                              <option :value="lote.lote" v-for="(lote, index) in lotes" :key="index">{{lote.lote}}</option>                                                        </select>
                          </div>
                          <div class="form-group col-md-2">
                            <label for="Fecha desde">Fecha inicio desde: </label>
                            <input type="date" class="form-control" id="f_inicio_d" v-model="f_inicio_d">
                          </div>
                          <div class="form-group col-md-2">
                            <label for="fecha hasta">Fecha inicio hasta: </label>
                            <input type="date" class="form-control" id="f_inicio_h" v-model="f_inicio_h">
                          </div>
                          <div class="form-group col-md-2">                            
                            <button class="btn btn-primary form-control" @click="exportarSiembras()"> Filtrar</button>
                          </div>
                          <div class="form-group col-md-2">                            
                              <downloadexcel                                
                                class = "btn btn-success form-control"
                                :fetch   = "fetchData"
                                :fields = "json_fields"
                                name    = "informe-siembras-especies.xls"
                                type    = "xls">
                                  <i class="fa fa-fw fa-download"></i> Generar Excel 
                              </downloadexcel>                            
                          </div>
                        </div>
                        <div class="table-container" id="table-container2">
                          <table class="table-sticky table table-sm table-hover table-bordered">
                            <thead class="thead-primary">
                                <tr>
                                  <th scope="col">#</th>
                                  <th>Nombre<br>siembra</th>
                                  <th scope="col">Inicio siembra</th>                                  
                                  <th scope="col">Estado</th>   
                                  <th scope="col">Contenedor</th>
                                  <th>Especie</th>
                                  <th>Lote</th>
                                  <th>Cantidad actual</th>
                                  <th>Peso actual</th>
                                  <th>Mortalidad</th>
                                  <th>Cantidad Pesca</th>                        
                                </tr>
                              </thead>
                              <tbody>
                                <tr v-for="(siembra, index) in listado" :key="index">
                                  <th v-text="index+1" scope="row"></th>
                                  <td v-text="siembra.nombre_siembra" scope="row"></td>
                                  <td v-text="siembra.fecha_inicio"></td>                              
                                  <td v-text="estados[siembra.estado]"></td>                                   
                                  <td v-text="siembra.contenedor"></td>
                                  <td v-text="siembra.especie"></td>
                                  <td v-text="siembra.lote" ></td>
                                  <td v-text="siembra.cant_actual"></td>
                                  <td v-text="siembra.peso_actual+'Gr'"></td>                                    
                                  <!-- <td v-if="list_mortalidad[siembra.id][siembra.id_esp]">{{list_mortalidad[siembra.id][siembra.id_esp]}}</td> -->
                                  <td> 
                                    <div v-if="list_mortalidad[siembra.id]">                                    
                                      {{list_mortalidad[siembra.id][siembra.id_esp]}}
                                    </div>
                                    <div v-else>0
                                    </div>
                                  </td>
                                  <td> 
                                    <div v-if="list_pesca[siembra.id]">                                    
                                      {{list_pesca[siembra.id][siembra.id_esp]}}
                                    </div>
                                    <div v-else>0
                                    </div>
                                  </td>
                                  <!-- <td v-else>0</td>-->
                                  
                                </tr>
                              </tbody>
                            </table>
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
        listadoSiembras: [],
        listadoItems : [],
        listado : [],
        listadoRegistros: [],
        nombresEspecies : [],
        listadoAlimentos:[],
        listadoRN :[],
        pecesxSiembra: [],
        lotes :[],
        // Registros
        anadirRegistro : 0,        
        id_siembra:'',
        id_especie : '',        
        fecha_registro:'',
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
       
        // Filtros para exportar
        f_siembra : '',
        f_especie: '', 
        f_lote : '',
        f_inicio_d : '',
        f_inicio_h : '',
        f_estado_s : '',
        list_mortalidad : [],
        list_pesca :[]
      }
    },
    components: {
      downloadexcel,
    },
    methods:{
    
     async fetchData(){
      let me = this;
      const response = await this.listado
      return this.listado;
      //  imprimirSiembras
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
      listarAlimentos(){
        let me = this;
        axios.get("api/alimentos")
        .then(function (response){
          me.listadoAlimentos = response.data; 
          var auxAlimento = response.data;
       
        })
      },
      listarSiembras(){
        let me = this;
        this.listarEspecies();
        this.listadoExcel();
        this.listarAlimentos();
        axios.get("api/siembras")
        .then(function (response){
          me.listadoSiembras = response.data.siembra;          
        })
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
        this.listarSiembras();
        axios.get("api/informes-siembras")
        .then(function (response){
          me.listado = response.data.siembras;
          me.lotes = response.data.lotes; 
          me.fechaActual = response.data.fecha_actual;
          me.list_mortalidad = response.data.mortalidad_siembra;
          me.list_pesca = response.data.cantidad_pesca;
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
      },      
     
      exportarSiembras(){
        let me = this;
        if(this.f_siembra == ''){this.f_s = '-1'}else{this.f_s = this.f_siembra}
        if(this.f_estado_s == ''){this.f_e_s = '-1'}else{this.f_e_s = this.f_estado_s}
        if(this.f_especie == ''){this.f_e = '-1'}else{this.f_e = this.f_especie}
        if(this.f_lote == ''){this.f_l = '-1'}else{this.f_l = this.f_lote}
        if(this.f_inicio_d == ''){this.f_d = '-1'}else{this.f_d = this.f_inicio_d}
        if(this.f_inicio_h == ''){this.f_h = '-1'}else{this.f_h = this.f_inicio_h}
        
        const data = {
          'f_siembra' : this.f_s,
          'f_estado_s' : this.f_e_s,
          'f_especie' : this.f_e,
          'f_lote' : this.f_l,
          'f_inicio_d' : this.f_d,
          'f_inicio_h' : this.f_h
        }
        axios.post("api/filtro-siembras", data)
        .then(response=>{
          me.listado = response.data.siembras;          
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
              this.id_finalizar = '';
              this.ini_descanso = '';              
              $('#modalFinalizar').modal('hide');
              this.listar();
            }); 
          }
        }else{
         Swal.fire("Advertencia", "Por favor, diligencia los datos restantes", "warning");
        }
      
      },
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
