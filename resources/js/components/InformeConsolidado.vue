<template>   
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Informe consolidado variables de producción</div>
                      <!-- <a href="informe-excel"><button type="submit" class="btn btn-success" name="infoSiembras"><i class="fa fa-fw fa-download"></i> Generar Excel </button></a> -->                    
                    <div class="card-body">   
                      <div class="row text-left">
                        <h5>Filtrar por: </h5>
                      </div>
                      <div class="row">
                        <div class="form-group col-md-2">
                          <label for="siembra">Siembras:</label>
                          <select class="custom-select" id="siembra" v-model="f_siembra">
                            <option value="-1">Seleccionar</option>
                            <option :value="ls.id" v-for="(ls, index) in listadoSiembras" :key="index">{{ls.nombre_siembra}}</option>                        
                          </select>
                        </div>
                        <div class="form-group col-md-2">
                          <label for="f_estado">
                            Estado:
                            <select class="custom-select" name="estado" id="estado" v-model="f_estado">
                              <option value="-1" disabled>--Seleccionar--</option>                              
                              <option value="0">Inactiva</option>
                              <option value="1">Activa</option>
                            </select>
                          </label>
                        </div>
                      
                        <div class="form-group col-md-2">
                          <button class="btn btn-primary" @click="filtroSiembra()">
                            Filtrar resultados
                          </button>
                        </div>
                        <div class="form-group col-md-2">
                          <downloadexcel
                          class = "btn btn-success form-control"
                          :fetch   = "fetchData"
                          :fields = "json_fields"
                          name    = "informe-ciclo-productivo.xls"
                          type    = "xls">
                            <i class="fa fa-fw fa-download"></i> Generar Excel 
                          </downloadexcel>
                        </div>
                      </div>
                      <div class="table-container" id="table-container2">
                        <table class="table-sticky table table-sm table-hover table-bordered">
                          <thead class="thead-primary">
                            <tr>
                              <th class="fixed-column">#</th>
                              <th class="fixed-column">Siembra</th>  
                              <th>Area</th>                           
                              <th>Inicio siembra</th>
                              <th>Tiempo de cultivo</th>
                              <th>Cant Inicial</th>
                              <th>Biomasa Inicial</th>
                              <th>Peso Inicial</th>
                              <th>Carga inicial</th>
                              <th>Animales final</th>
                              <th>Peso Actual</th>                                 
                              <th>Biomasa dispo</th>
                              <th>Salida de biomasa</th>
                              <th>Mort. Kg</th>
                              <th>% Mortalidad</th>
                              <th>Salida animales</th>                              
                              <th>Densidad Inicial (Animales/m<sup>2</sup>)</th>
                              <th>Densidad Final (Animales/m<sup>2</sup>)</th>
                              <th>Carga Final (Kg/m<sup>2</sup>)</th>
                              <th>Horas Hombre</th>             
                              <th>Costo Horas</th>
                              <th>Costo Recursos</th>
                              <th>Costo Alimentos</th>
                              <th>Total alimento (Kg)</th>
                              <th>Costo Total</th>
                              <th>Costo produccion final</th>
                              <th>Conversion alimenticia parcial</th>
                              <th>Conversion final</th>
                              <th>Ganancia peso día</th>
                              <th><b>%</b> Supervivencia final </th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(le, index) in listadoExistencias" :key="index">                              
                              <td v-text="index+1" class="fixed-column"></td>
                              <td v-text="le.nombre_siembra" class="fixed-column"></td>   
                              <td v-text="le.capacidad"></td>
                              <td v-text="le.fecha_inicio"></td>
                              <td v-text="le.intervalo_tiempo"></td>
                              <td v-text="le.cantidad_inicial"></td>
                              <td v-text="le.biomasa_inicial"></td>
                              <td v-text="le.peso_inicial+' gr'"></td>
                              <td v-text="le.carga_inicial"></td>
                              <td v-text="le.cant_actual"></td>
                              <td v-text="le.peso_actual+' gr'"></td>                                                               
                              <td v-text="le.biomasa_disponible+' kg'"></td> 
                              <td v-if="le.salida_biomasa">{{le.salida_biomasa}} kg</td>
                              <td v-else>0</td>                             
                              <td v-text="le.mortalidad_kg ? le.mortalidad_kg +' kg' : '0'"></td>
                              <td v-if="le.mortalidad_porcentaje">{{le.mortalidad_porcentaje}}</td>
                              <td v-else>0</td>
                              <td v-if="le.salida_animales">{{le.salida_animales}}</td>
                              <td v-else>0</td>
                              <td v-text="le.densidad_inicial"></td>
                              <td v-text="le.densidad_final"></td>
                              <td v-text="le.carga_final"></td>
                              <td v-text="le.horas_hombre"></td>
                              <td v-text="le.costo_minutosh"></td>
                              <td v-text="le.costo_total_recurso"></td>
                              <td v-text="le.costo_total_alimento"></td>
                              <td v-text="le.cantidad_total_alimento"></td>
                              <td v-text="le.costo_tot"></td>
                              <td v-text="le.costo_produccion_final"></td>
                              <td v-text="le.conversion_alimenticia_parcial"></td>
                              <td v-text="le.conversion_final"></td>
                              <td v-text="le.ganancia_peso_dia"></td>
                              <td v-text="le.porc_supervivencia_final"></td>
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
  import downloadexcel from "vue-json-excel";
  export default {
    data(){
      return {
        json_fields: {      
          'Siembra' : 'nombre_siembra',
          'Fecha inicio siembra' : 'fecha_inicio',
          'Cantidad Inicial' : 'cantidad_inicial',
          'Peso inicial' : 'peso_inicial',
          'Animales final' : 'cant_actual',
          'Peso actual' : 'peso_actual',
          'Intervalo de tiempo' : 'intervalo_tiempo',
          'Biomasa disponible' : 'biomasa_disponible',
          'Salida de biomasa' : 'salida_biomasa',
          'Biomasa acumulada' : 'biomasa_acumulada',
          'Mortalidad' : 'mortalidad',
          'Mortalidad kg' : 'mortalidad_kg',
          'Mortalidad %' : 'mortalidad_porcentaje',
          'Salida animales' : 'salida_animales',
          'Densidad final (Animales/m2)' : 'densidad_final',
          'Carga final (Kg/m2)' : 'carga_final',
          'Horas hombre':'horas_hombre',
          'Costo minutos Hombre':'costo_minutosh',
          'Costo total recursos':'costo_total_recurso',
          'Costo total alimentos':'costo_total_alimento',
          'Costo total':'costo_tot',
          'Total Kg Alimento' : 'cantidad_total_alimento',
          'Conversión alimenticia parcial' : 'conversion_alimenticia_siembra'
        },       
        listadoExistencias : [],
        listadoEspecies : [],
        listadoSiembras: [],
        imprimirRecursos:[],
        f_siembra : '',
        f_estado : '', 
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
        const response = await this.listadoExistencias;
        return this.listadoExistencias;
      },
      listar(){
        let me = this;      
        this.listarEspecies();
        this.listarSiembras();
        axios.get("api/traer-existencias-detalle")
        .then(function (response){
          me.listadoExistencias = response.data.existencias;
        })                 
      },
      listarEspecies(){
        let me = this;
        axios.get("api/especies")
        .then(function (response){
          me.listadoEspecies = response.data
        })
      },
      listarSiembras(){
        let me = this;
        axios.get("api/siembras")
        .then(function (response){
          me.listadoSiembras = response.data.siembra;
        })
      },
      
      filtroSiembra(){
        let me = this;
        
        if(this.f_siembra == ''){this.smb = '-1'}else{this.smb = this.f_siembra}
        if(this.f_estado == ''){this.est = '-1'}else{this.est = this.f_estado}
        if(this.f_inicio_d == ''){this.fecd = '-1'}else{this.fecd = this.f_inicio_d}
        if(this.f_inicio_h == ''){this.fech = '-1'}else{this.fech = this.f_inicio_h}        
        const data ={
          'f_siembra' : this.smb,
          'f_estado' : this.est,
          'f_inicio_d' : this.fecd,
          'f_inicio_h' : this.fech,
        }
        axios.post("api/filtro-existencias-detalle", data)
        .then(response=>{
          me.listadoExistencias = response.data.existencias;
        });
      },
    },
    mounted() {
      this.listar();
    }
  }
</script>
