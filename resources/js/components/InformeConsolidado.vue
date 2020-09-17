<template>   
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Informes especies existentes</div>
                      <!-- <a href="informe-excel"><button type="submit" class="btn btn-success" name="infoSiembras"><i class="fa fa-fw fa-download"></i> Generar Excel </button></a> -->                    
                    <div class="card-body">   
                      <div class="row text-left">
                        <h5>Filtrar por: </h5>
                      </div>
                      <div class="row">
                        <div class="form-group col-md-2">
                          <label for="siembra">Siembras</label>
                          <select class="form-control" id="siembra" v-model="f_siembra">
                            <option value="-1">Seleccionar</option>
                            <option :value="ls.id" v-for="(ls, index) in listadoSiembras" :key="index">{{ls.nombre_siembra}}</option>                        
                          </select>
                        </div>
                        <!-- <div class="form-group col-md-2">
                          <label for="especie">Especies</label>
                          <select class="form-control" id="especie" v-model="f_especie">
                            <option value="-1">Seleccionar</option>
                            <option :value="les.id" v-for="(les, index) in listadoEspecies" :key="index">{{les.especie}}</option>
                          </select>
                        </div> -->
                        
                        <div class="form-group col-md-2">
                          <label for="Biomasa hasta">Biomasa disponible(kg) hasta: </label>
                          <input type="number" class="form-control" id="f_biomasa_h" step="any"  v-model="f_biomasa_h">
                        </div>
                        <div class="form-group col-md-2">
                          <label for="Fecha desde">Fecha inicio desde: </label>
                          <input type="date" class="form-control" id="f_inicio_d">
                        </div>
                        <div class="form-group col-md-2">
                          <label for="fecha hasta">Fecha inicio hasta: </label>
                          <input type="date" class="form-control" id="f_inicio_h">
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
                          :before-generate = "startDownload"
                          name    = "informe-ciclo-productivo.xls"
                          type    = "xls">
                            <i class="fa fa-fw fa-download"></i> Generar Excel 
                          </downloadexcel>
                        </div>
                      </div>
                      <div>
                        <table class="table table-striped table-sm table-hover table-responsive">
                          <thead>
                            <tr>                           
                              <th>#</th>
                              <th>Siembra</th>                             
                              <th>Inicio siembra</th>
                              <th>Cant Ini</th>
                              <th>Peso Ini</th>
                              <th>Cant Actual</th>
                              <th>Peso Actual</th>                                 
                              <th>Biomasa dispo</th>
                              <th>Salida de biomasa</th>                 
                              
                              <th>Mortalidad</th>                              
                              <th>Mort. Kg</th>
                              <th>% Mortalidad</th>
                              <th>Salida animales</th>
                              
                              <th>Densidad Final (Animales/m<sup>2</sup>)</th>
                              <th>Carga Final (Kg/m<sup>2</sup>)</th>
                              <th>Hrs Hombre</th>             
                              <th>Costo Horas</th>
                              <th>Costo Recursos</th>
                              <th>Costo Alimentos</th>
                              <th>Costo Total</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(le, index) in listadoExistencias" :key="index">                              
                              <td v-text="index+1"></td>
                              <td v-text="le.nombre_siembra"></td>                             
                              <td v-text="le.fecha_inicio"></td>
                              <td v-text="le.cantidad_inicial"></td>
                              <td v-text="le.peso_inicial+' gr'"></td>
                              <td v-text="le.cant_actual"></td>
                              <td v-text="le.peso_actual+' gr'"></td>                                                               
                              <td v-text="le.biomasa_disponible+' kg'"></td> 
                              <td v-if="le.salida_biomasa">{{le.salida_biomasa}} kg</td>
                              <td v-else>0</td>
                              <td v-if="le.mortalidad">{{le.mortalidad}}</td>
                              <td v-else>0</td>
                              <td v-text="le.mortalidad_kg ? le.mortalidad_kg +' kg' : '0'"></td>
                              <td v-if="le.mortalidad_porcentaje">{{le.mortalidad_porcentaje}}</td>
                              <td v-else>0</td>
                              <td v-if="le.salida_animales">{{le.salida_animales}}</td>
                              <td v-else>0</td>
                              <td v-text="le.densidad_final"></td>
                              <td v-text="le.carga_final"></td>
                              <td v-text="le.horas_hombre"></td>
                              <td v-text="le.costo_horash"></td>
                              <td v-text="le.costo_r"></td>
                              <td v-text="le.costo_total_alimento"></td>
                              <td v-text="le.costo_tot"></td>
                              
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
          'Especie' : 'especie',
          'Fecha inicio siembra' : 'fecha_inicio',
          'Cantidad Inicial' : 'cantidad_inicial',
          'Peso inicial' : 'peso_inicial',
          'Cantidad actual' : 'cant_actual',
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
          'Costo horas Hombre':'costo_horash',
          'Costo total recursos':'costo_r',
          'Costo total alimentos':'costo_total_alimento',
          'Costo total':'costo_tot',
        },       
        listadoExistencias : [],
        listadoEspecies : [],
        listadoSiembras: [], 
        imprimirRecursos:[],
        f_siembra : '',
        f_especie: '', 
        f_inicio_d : '',
        f_inicio_h : '',
        f_biomasa_h : '',
      }
    },
    components: {
      downloadexcel,
    },
    methods:{
      async fetchData(){
        let me = this;
        // const response = await axios.get('api/informe-recursos');
        const response = await this.listadoExistencias
        // console.log(response);
        return this.listadoExistencias;
      },
      startDownload(){
          alert('show loading');
      },
      finishDownload(){
          alert('hide loading');
      },
      
      listar(){
        let me = this;      
        this.listarEspecies();
        this.listarSiembras();
        axios.get("api/traer-existencias-detalle")
        .then(function (response){
          console.log(response.data)
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
        // if(this.f_especie == ''){this.esp = '-1'}else{this.esp = this.f_especie}
        if(this.f_inicio_d == ''){this.fecd = '-1'}else{this.fecd = this.f_inicio_d}
        if(this.f_inicio_h == ''){this.fech = '-1'}else{this.fech = this.f_inicio_h}
        if(this.f_biomasa_h == ''){this.bh = '-1'}else{this.bh = this.f_biomasa_h}
        
        
        const data ={
          'f_siembra' : this.smb,
          'f_inicio_d' : this.fecd,
          'f_inicio_h' : this.fech,
          'f_biomasa_h' : this.bh
        }
        axios.post("api/filtro-existencias-detalle", data)
        .then(response=>{
          me.listadoExistencias = response.data.existencias;
          // console.log(response.data);
        });
        console.log('buscar')
      },
    },
    mounted() {
      this.listar();
    }
  }
</script>
