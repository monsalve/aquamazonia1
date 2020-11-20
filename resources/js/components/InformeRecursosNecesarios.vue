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
                            <select class="form-control" id="f_siembra" v-model="f_siembra">
                              <option value="-1" selected>Seleccionar</option>                             
                              <option :value="ls.id" v-for="(ls, index) in listadoSiembras" :key="index">{{ls.nombre_siembra}}</option>
                            </select>
                          </div>
                          <div class="form-group col-md-2">
                           <label for="f_actividad">Tipo de Actividad: </label>
                            <select class="form-control" id="f_actividad" v-model="f_actividad">
                              <option  value="-1" selected> Seleccionar</option>   
                              <option v-for="(actividad, index) in listadoActividades" :key="index" v-bind:value="actividad.id">{{actividad.actividad}}</option>                 
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
                            name    = "informe-recursos-necesarios.xls"
                            type    = "xls">
                              <i class="fa fa-fw fa-download"></i> Generar Excel 
                            </downloadexcel>      
                          </div>
                        </form>
                      </div>
                      <div>
                        <table class="table">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Siembra</th>
                              <th>Tipo actividad</th>
                              <th>Horas hombre</th>
                              <th>Costo horas hombre</th>
                              <th>Cantidad Recurso</th>
                              <th>Costo Recurso</th>
                              <th>Cantidad Alimento</th>
                              <th>Costo Alimento</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(lrn, index) in listado" :key="index">
                              <td v-text="index+1"></td>
                              <td v-text="lrn.nombre_siembra"></td>
                              <td v-text="lrn.actividad"></td>
                              <td v-text="lrn.horas_hombre+' Hr'"></td>
                              <td v-text="lrn.costo_minutos"></td>
                              <td v-text="lrn.cantidad_recurso"></td>
                              <td v-text="lrn.costo_recurso"></td>
                              <td v-text="lrn.cantidad_alimento"></td>
                              <td v-text="lrn.costo_alimento"></td>
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
          'Tipo actividad' : 'actividad',
          'Minutos hombre' : 'minutos_hombre',
          'Costo total minutos' : 'costo_minutos',          
          'Cantidad total recurso' : 'cantidad_recurso',
          'Costo total recurso' : 'costo_recurso',
          'Cantidad total alimento' : 'cantidad_alimento',
          'Costo total alimento' : 'costo_alimento'
        }, 
        f_actividad:'',
        f_siembra:'',        
        listado : [],        
        listadoSiembras : [],        
        listadoActividades : [],        
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
      buscarResultados(){        
      },
      listar(){
        let me = this;
        axios.get("api/informes-recursos-necesarios")
        .then(function (response){
          me.listado = response.data.recursosNecesarios;         
          me.promedios = response.data.promedioRecursos;
        })
      },
      buscarResultados(){
        let me = this;
        if(this.f_siembra == ''){this.f_s = '-1'}else{this.f_s = this.f_siembra}
        if(this.f_actividad == ''){ this.actividad = '-1'}else{this.actividad  = this.f_actividad}               
     
        const data ={
          'f_siembra' : this.f_s,
          'f_actividad' : this.actividad          
        }
        axios.post("api/filtro-recursos", data)
        .then(response=>{
          me.listado = response.data.recursosNecesarios;
          me.promedios = response.data.promedioRecursos;
        })        
      },
      listarSiembras(){
        let me = this;
        axios.get("api/siembras")
        .then(function (response){
          me.listadoSiembras = response.data.siembra;         
        })
      },      
      listarActividades(){
        let me = this;
        axios.get("api/actividades")
        .then(function (response){
          me.listadoActividades = response.data; 
        })
      }
      
    },
    mounted() {
      this.listar();
      this.listarSiembras();
      this.listarActividades();
    }
  }
</script>
