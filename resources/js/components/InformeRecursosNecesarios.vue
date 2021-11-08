<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Informes recursos necesarios</div>
                    <div class="card-body">
                      <div class="col-md-12">
                        <form class="row">
                          <div class="form-group col-md-2">
                            <label for="Siembra">Siembra:</label>
                            <select class="custom-select" id="Siembra" v-model="f_siembra">
                              <option value="-1" selected>Seleccionar</option>
                              <option :value="ls.id" v-for="(ls, index) in listadoSiembras" :key="index">{{ls.nombre_siembra}}</option>
                            </select>
                          </div>
                          <div class="form-group col-md-2">
                            <label for="estado">
                              Estado:
                            </label>
                            <select class="custom-select" name="estado" id="estado" v-model="f_estado">
                              <option value="-1" disabled>--Seleccionar--</option>
                              <option value="0">Inactiva</option>
                              <option value="1">Activa</option>
                            </select>
                          </div>
                          <div class="form-group col-md-2">
                            <label for="contenedor">Estanque:</label>
                            <select class="custom-select" id="contenedor" v-model="f_contenedor">
                              <option value="-1">Seleccionar</option>
                              <option :value="cont.id" v-for="(cont, index) in listadoEstanques" :key="index">{{cont.contenedor}}</option>
                            </select>
                          </div>
                          <div class="form-group col-md-2">
                           <label for="f_actividad">Tipo de Actividad: </label>
                            <select class="custom-select" id="f_actividad" v-model="f_actividad" @click="cambiarActividad()">
                              <option  value="-1" selected> Seleccionar</option>
                              <option v-for="(actividad, index) in listadoActividades" :key="index" v-bind:value="actividad.id">
                                {{actividad.actividad}}
                              </option>
                            </select>
                          </div>
                          <div class="form-group col-md-2">
                            <button  class="btn btn-primary rounded-circle mt-4" type="button" @click="buscarResultados()"><i class="fas fa-search"></i></button>
                          </div>
                          <div class="col-md-2">
                            <downloadexcel
                            class = "btn btn-success form-control"
                            :fetch   = "fetchData"
                            :fields = "json_fields"
                            name    = "informe-consolidado-recursos-necesarios.xls"
                            type    = "xls">
                              <i class="fa fa-fw fa-download"></i> Generar Excel
                            </downloadexcel>
                          </div>
                        </form>
                      </div>
                      <div class="table-container" id="table-container2">
                        <table class="table-sticky table table-sm table-hover table-bordered">
                          <thead class="thead-primary">
                            <tr>
                              <th>#</th>
                              <th>Siembra</th>
                              <th>Estado</th>
                              <th>Tipo actividad</th>
                              <th>Minutos hombre</th>
                              <th>Costo minutos hombre</th>
                              <th v-if="tipoActividad != 'Alimentación'">Cantidad Recurso</th>
                              <th v-if="tipoActividad != 'Alimentación'">Costo Recurso</th>
                              <th v-if="tipoActividad == 'Alimentación'">Cantidad Alimento</th>
                              <th v-if="tipoActividad == 'Alimentación'">Costo Alimento</th>
                              <th>Costo total actividad</th>
                              <th>% Costo total de producción</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(lrn, index) in listado" :key="index">
                              <td v-text="index+1"></td>
                              <td v-text="lrn.nombre_siembra"></td>
                              <td v-if="lrn.estado == 1">Activa</td>
                              <td v-else>Inactiva</td>
                              <td v-text="lrn.actividad"></td>
                              <td v-text="lrn.minutos_hombre+' min'"></td>
                              <td class="text-right" v-text="lrn.costo_minutos"></td>
                              <td v-text="lrn.cantidad_recurso" v-if="tipoActividad != 'Alimentación'"></td>
                              <td class="text-right" v-text="lrn.costo_recurso" v-if="tipoActividad != 'Alimentación'"></td>
                              <td v-text="lrn.cantidad_alimento" v-if="tipoActividad == 'Alimentación'"></td>
                              <td class="text-right" v-text="lrn.costo_alimento" v-if="tipoActividad == 'Alimentación'"></td>
                              <td class="text-right" v-text="lrn.costo_total_actividad"></td>
                              <td class="text-right">
                                <!-- {{ lrn.por_total_produccion = ((lrn.costo_total_actividad * 100)/(lrn.costoTotalSiembra)) }} -->
                                <span v-if="lrn.porcentaje_total_produccion">
                                  {{lrn.porcentaje_total_produccion}} %
                                </span>
                              </td>
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
import { Form, HasError, AlertError } from 'vform'
import downloadexcel from "vue-json-excel"
  export default {
    data(){
      return {
        json_fields : {
          'Siembra' : 'nombre_siembra',
          'Estado' : 'estado',
          'Tipo actividad' : 'actividad',
          'Minutos hombre' : 'minutos_hombre',
          'Costo total minutos' : 'costo_minutos',
          'Cantidad total recurso' : 'cantidad_recurso',
          'Costo total recurso' : 'costo_recurso',
          'Cantidad total alimento' : 'cantidad_alimento',
          'Costo total alimento' : 'costo_alimento',
          'Costo total actividad' : 'costo_total_actividad',
          '%Costo total producción': 'porcentaje_total_produccion'
        },
        tipoActividad : '',
        f_actividad:'',
        f_siembra:'',
        f_contenedor : '',
        f_estado : '',
        listado : [],
        listadoSiembras : [],
        listadoActividades : [],
        listadoEstanques: [],
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
      },
      cambiarActividad () {
        if (this.f_actividad == 1) { this.tipoActividad = 'Alimentación' } else ( this.tipoActividad =  '');
      },
      listar(){
        let me = this;
        axios.get("api/informes-recursos-necesarios")
        .then(function (response){
          me.listado = response.data.recursosNecesarios.data;
          me.promedios = response.data.promedioRecursos;
        })
      },
      buscarResultados(){
        let me = this;
        if(this.f_siembra == ''){this.f_s = '-1'}else{this.f_s = this.f_siembra}
        if(this.f_estado == ''){this.f_e = '-1'}else{this.f_e = this.f_estado}
        if(this.f_contenedor == ''){this.cont = '-1'}else{this.cont = this.f_contenedor}
        if(this.f_actividad == ''){ this.actividad = '-1'}else{this.actividad  = this.f_actividad}

        const data ={
          'f_siembra' : this.f_s,
          'f_estado' : this.f_e,
          'f_actividad' : this.actividad,
          'f_contenedor' : this.cont,
        }
        axios.post("api/filtro-recursos", data)
        .then(response=>{
          me.listado = response.data.recursosNecesarios.data;
          me.promedios = response.data.promedioRecursos;
        })
      },
      listarSiembras(){
        let me = this;
        axios.get("api/siembras")
        .then(function (response){
          me.listadoSiembras = response.data.listado_siembras;
        })
      },
      listarActividades(){
        let me = this;
        axios.get("api/actividades")
        .then(function (response){
          me.listadoActividades = response.data;
        })
      },
      listarEstanques(){
        let me = this;
        axios.get("api/contenedores")
        .then(function (response){
          me.listadoEstanques = response.data;
        })
      }
    },
    mounted() {
      this.listar();
      this.listarSiembras();
      this.listarActividades();
      this.listarEstanques();
    }
  }
</script>
