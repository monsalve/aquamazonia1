<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Informes recursos y actividades Aquamazonia</div>
                      <!-- <a href="informe-excel"><button type="submit" class="btn btn-success" name="infoSiembras"><i class="fa fa-fw fa-download"></i> Generar Excel </button></a> -->
                    <div class="card-body">
                      <div class="row mb-1">
                        <div class="col-md-12">
                          <h2>Filtrar por:</h2>
                          <form class="row" method="POST" action="informe-excel" target="_blank">
                            <div class="form-group col-md-2">
                              <label for="Siembra">Siembra:</label>
                              <select class="form-control" id="f_siembra" v-model="f_siembra">
                                <option value="-1" selected>Seleccionar</option>
                                <option :value="ls.id" v-for="(ls, index) in listadoSiembras" :key="index">{{ls.nombre_siembra}}</option>
                              </select>
                            </div>
                            <div class="form-group col-md-2">
                              <label for="Estado">Estado siembra </label>
                              <select class="form-control" id="estado_s" v-model="estado_s" name="estado_s">
                                <option value="-1">Todos</option>
                                <option value="0" >Inactivo</option>
                                <option value="1" selected>Activo</option>
                              </select>
                            </div>
                            <div class="form-group col-md-2">
                              <label for="contenedor">Contenedor:</label>
                              <select class="custom-select" id="contenedor" v-model="f_contenedor">
                                <option value="-1">Seleccionar</option>
                                <option :value="cont.id" v-for="(cont, index) in listadoContenedores" :key="index">{{cont.contenedor}}</option>
                              </select>
                            </div>
                            <div class="form-group col-md-2">
                              <label for="actividad">Tipo actividad: </label>
                              <select class="form-control" id="actividad" v-model="actividad_s" name="tipo_actividad" @click="cambiarActividad()">
                                <option value="-1" selected> Seleccionar</option>
                                <option v-for="(actividad, index) in listadoActividades" :key="index" v-bind:value="actividad.id">{{actividad.actividad}}</option>
                              </select>
                            </div>
                            <div class="form-group col-md-2">
                             <label for="alimento">Alimento: </label>
                              <select class="form-control" id="alimento" v-model="alimento_s">
                                <option value="-1" selected> Seleccionar</option>
                                <option v-for="(alimento, index) in listadoAlimentos" :key="index" v-bind:value="alimento.id">{{alimento.alimento}}</option>
                              </select>
                            </div>
                            <div class="form-group col-md-2">
                             <label for="recurso">Recurso: </label>
                              <select class="form-control" id="recurso" v-model="recurso_s">
                                <option value="-1" selected> Seleccionar</option>
                                <option v-for="(recurso, index) in listadoRecursos" :key="index" v-bind:value="recurso.id">{{recurso.recurso}}</option>
                              </select>
                            </div>
                            <div class="form-group col-md-2">
                              <label for="search">Desde: </label>
                              <input class="form-control" type="date" placeholder="Search" aria-label="fecha_ra1" v-model="fecha_ra1">
                            </div>
                             <div class="form-group col-md-2">
                              <label for="search">Hasta: </label>
                              <input class="form-control" type="date" placeholder="Search" aria-label="fecha_ra2" v-model="fecha_ra2">
                            </div>
                            <div class="form-group col-md-1">
                              <label for="">Buscar</label>
                              <button  class="btn btn-primary form-control" type="button" @click="filtroResultados()"><i class="fas fa-search"></i></button>
                            </div>
                            <div class="form-group col-md-2">

                              <downloadexcel
                              class = "btn btn-success form-control"
                              :fetch   = "fetchData"
                              :fields = "json_fields"
                              name    = "informe-recursos.xls"
                              type    = "xls">
                                <i class="fa fa-fw fa-download"></i> Generar Excel
                              </downloadexcel>
                            </div>
                          </form>
                        </div>
                      </div>
                      <div>
                        <table class="table table-bordered table-hover table-sticky table-sm">
                          <thead class="thead-primary">
                            <tr>
                              <th>#</th>
                              <th>Siembra</th>
                              <th>Estado siembras</th>
                              <th>Tipo <br>actividad</th>
                              <th>Fecha</th>
                              <th>Minutos <br>hombre</th>
                              <th>Costo minutos </th>

                              <th v-if="tipoActividad != 'Alimentación'">Recursos</th>
                              <th v-if="tipoActividad != 'Alimentación'">Cantidad</th>
                              <th v-if="tipoActividad != 'Alimentación'">Costo Recurso</th>
                              <!-- <th v-if="tipoActividad != 'Alimentación'">Costo acumulado Recurso</th> -->
                              <th v-if="tipoActividad == 'Alimentación'">Alimentos</th>
                              <th v-if="tipoActividad == 'Alimentación'">Cantidada Mañana (KG)</th>
                              <th v-if="tipoActividad == 'Alimentación'">Cantidada Tarde (KG)</th>
                              <th v-if="tipoActividad == 'Alimentación'">Costo Alimento</th>
                              <!-- <th v-if="tipoActividad == 'Alimentación'">Costo <br>Acumulado</th> -->
                              <th>Costo actividad</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(lrn, index) in listadorn" :key="index">
                              <th v-text="index+1"></th>
                              <td v-text="lrn.nombre_siembra"></td>
                              <td v-text="estados[lrn.estado]"></td>
                              <td v-text="lrn.actividad"></td>
                              <td v-text="lrn.fecha_ra"></td>
                              <td v-text="lrn.minutos_hombre +'min'"></td>
                              <td v-text="lrn.costo_minutosh"></td>

                              <td v-text="lrn.recurso" v-if="tipoActividad != 'Alimentación'"></td>
                              <td v-text="lrn.cantidad_recurso" v-if="tipoActividad != 'Alimentación'"></td>
                              <td v-text="lrn.costo_total_recurso" v-if="tipoActividad != 'Alimentación'"></td>
                              <!-- <th v-text="lrn.costo_r_acum" v-if="tipoActividad != 'Alimentación'"></th> -->
                              <td v-text="lrn.alimento" v-if="tipoActividad == 'Alimentación'"></td>
                              <td v-text="lrn.cant_manana" v-if="tipoActividad == 'Alimentación'"></td>
                              <td v-text="lrn.cant_tarde" v-if="tipoActividad == 'Alimentación'"></td>
                              <th v-text="lrn.costo_total_alimento" v-if="tipoActividad == 'Alimentación'"></th>
                              <!-- <th v-text="lrn.costo_a_acum" v-if="tipoActividad == 'Alimentación'"></th> -->
                              <th v-text="lrn.costo_total_actividad"></th>
                            </tr>
                          </tbody>
                          <tfoot>
                            <tr>
                              <th colspan="4" class="text-right">TOTAL: </th>
                              <td colspan="2" class="text-right">Costo minutos: </td>
                              <th>{{calcularTotalMinutos}}</th>
                              <td colspan="2" v-if="tipoActividad != 'Alimentación'" class="text-right">Costo recursos: </td>
                              <th v-if="tipoActividad != 'Alimentación'">{{calcularTotalRecursos}}</th>
                              <td colspan="3" v-if="tipoActividad == 'Alimentación'">Costo alimentos: </td>
                              <th v-if="tipoActividad == 'Alimentación'">{{calcularTotalAlimentos}}</th>
                              <th>Costo total actividades: <br> {{calcularTotalActividades}}</th>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import downloadexcel from "vue-json-excel";
  export default {
    data(){

      return {
        json_fields: {
            'Nombre Siembra' : 'nombre_siembra',
            'Estado Siembra' : 'estado',
            'Tipo de Actividad' : 'actividad',
            'Fecha Registro' : 'fecha_ra',
            'Minutos hombre' : 'minutos_hombre',
            'Costo minutos hombre' : 'costo_minutosh',
            'Recurso' : 'recurso',
            'Cantidad Recurso' : 'cantidad_recurso',
            'Costo Recurso' : 'costo_total_recurso',
            'Alimento' : 'alimento',
            'Cantidad KG mañana' : 'cant_manana',
            'Cantidad KG tarde' : 'cant_tarde',
            'Costo Alimento' : 'costo_total_alimento',
            'Costo Actividad' : 'costo_total_actividad'

        },
        listadorn:[],
        listadoActividades:[],
        listadoAlimentos:[],
        listadoSiembras: [],
        listadoRecursos:[],
        listadoContenedores: [],
        imprimirRecursos: [],
        estados : [],
        f_siembra: '',
        f_contenedor : '',
        estado_s: '',
        actividad_s:'',
        alimento_s : '',
        recurso_s : '',
        fecha_ra1 : '',
        fecha_ra2: '',
        costo_acum : 0,
        tipoActividad : '',

      }
    },
    components: {
      downloadexcel,
    },
    computed : {
      calcularTotalMinutos: function(){
          var resultado=0.0;
          for(var i=0;i<this.listadorn.length;i++){
              resultado+=this.listadorn[i].costo_minutosh;
          }
          return resultado;
      },
      calcularTotalRecursos: function(){
         var resultado=0.0;
        for(var i=0;i<this.listadorn.length;i++){
            resultado+=this.listadorn[i].costo_total_recurso;
        }
        return resultado;
      },
      calcularTotalAlimentos: function(){
        var resultado=0.0;
        for(var i=0;i<this.listadorn.length;i++){
            resultado+=this.listadorn[i].costo_total_alimento;
        }
        return resultado;
      },
      calcularTotalActividades : function(){
        var resultado=0.0;
        for(var i=0;i<this.listadorn.length;i++){
            resultado+=this.listadorn[i].costo_total_actividad;
        }
        return resultado;
      }
    },
    methods:{
      async fetchData(){
        let me = this;
        // const response = await axios.get('api/informe-recursos');
        const response = await this.imprimirRecursos;
        return this.imprimirRecursos;
      },
      cambiarActividad () {
        if (this.actividad_s == 1) { this.tipoActividad = 'Alimentación' } else ( this.tipoActividad =  '');
      },
      listar(){
        let me = this;
        axios.get("api/informes")
        .then(function (response){
          me.listadorn = response.data.recursosNecesarios;
        })
        axios.get("api/traer-recursos")
        .then(response=>{
          me.imprimirRecursos = response.data.recursosNecesarios;
        })
      },

      listarActividades(){
        let me = this;
        axios.get("api/actividades")
        .then(function (response){
          me.listadoActividades = response.data;
        })
      },
      listarAlimentos(){
        let me = this;
        axios.get("api/alimentos")
        .then(function (response){
          me.listadoAlimentos = response.data;
        })
      },
      listarRecursos(){
        let me = this;
        axios.get("api/recursos")
        .then(function (response){
          me.listadoRecursos = response.data;
        })
      },
      listarSiembras(){
        let me = this;
        axios.get("api/siembras")
        .then(function (response){
          me.listadoSiembras = response.data.siembra;
        })
      },
      listarContenedores(){
        let me = this;
        axios.get("api/contenedores")
        .then(function (response){
          me.listadoContenedores = response.data;
        })
      },
      filtroResultados(){
        let me = this;
        if(this.f_siembra == ''){this.smb = '-1'}else{this.smb = this.f_siembra}
        if(this.estado_s == ''){this.est = '-1'}else{this.est = this.estado_s}
        if(this.f_contenedor == ''){this.cont = '-1'}else{this.cont = this.f_contenedor}
        if(this.actividad_s == ''){this.act = '-1'}else{this.act = this.actividad_s}
        if(this.alimento_s == ''){this.ali = '-1'}else{this.ali = this.alimento_s}
        if(this.recurso_s == ''){this.rec = '-1'}else{this.rec = this.recurso_s}
        if(this.fecha_ra1 == ''){this.fec1 = '-1'}else{this.fec1 = this.fecha_ra1}
        if(this.fecha_ra2 == ''){this.fec2 = '-1'}else{this.fec2 = this.fecha_ra2}

        const data ={
          'f_siembra' : this.smb,
          'estado_s': this.est,
          'f_contenedor' : this.cont,
          'actividad_s':this.act,
          'alimento_s' : this.ali,
          'recurso_s' : this.rec,
          'fecha_ra1' : this.fec1,
          'fecha_ra2' : this.fec2
        }
        axios.post("api/filtroInformes", data)
        .then(response=>{
          me.listadorn = response.data.recursosNecesarios;
          console.log(response);
        });
        axios.post("api/informe-recursos", data)
        .then(response=>{
          me.imprimirRecursos = response.data.recursosNecesarios;
        })
      },
    },
    mounted() {
      this.listar();
      this.listarSiembras();
      this.listarAlimentos();
      this.listarRecursos();
      this.listarActividades();
      this.listarContenedores();
      this.estados[0] = 'Inactivo';
      this.estados[1] = 'Activo';
    }
  }
</script>
