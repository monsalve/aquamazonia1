<template>   
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Informes de producción</div>
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
                              <th>#</th>
                              <th>Siembra</th>
                              <th>Costo Horas</th>
                              <th>Costo Recursos</th>
                              <th>Costo Alimentos</th>
                              <th>Total alimento (Kg)</th>
                              <th>Costo total de siembra</th>
                              <th>Costo de producción parcial</th>
                              <th>Biomasa disponible por alimento</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr class="text-right" v-for="(le, index) in listadoExistencias" :key="index">
                              <td v-text="index+1"></td>
                              <td v-text="le.nombre_siembra"></td>
                              <td v-text="le.costo_minutosh"></td>
                              <td v-text="le.costo_total_recurso"></td>
                              <td v-text="le.costo_total_alimento"></td>
                              <td v-text="le.cantidad_total_alimento"></td>
                              <td v-text="le.costo_tot"></td>
                              <td v-text="le.costo_produccion_parcial"></td>
                              <td v-text="le.bio_dispo_alimen"></td>
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
          'Costo minutos Hombre':'costo_minutosh',
          'Costo total recursos':'costo_total_recurso',
          'Costo total alimentos':'costo_total_alimento',
          'Costo total':'costo_tot',
          'Total Kg Alimento' : 'cantidad_total_alimento',
          'Costo de producción parcial' : 'costo_produccion_parcial'
        },       
        listadoExistencias : [],
        listadoEspecies : [],
        listadoSiembras: [], 
        imprimirRecursos:[],
        f_siembra : '',
        f_especie: '', 
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
        // const response = await axios.get('api/informe-recursos');
        const response = await this.listadoExistencias
        return this.listadoExistencias;
      },            
      listar(){
        let me = this;      
        this.listarEspecies();
        this.listarSiembras();
        axios.get("api/informes-biomasa-alimento")
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
        // if(this.f_especie == ''){this.esp = '-1'}else{this.esp = this.f_especie}
        if(this.f_inicio_d == ''){this.fecd = '-1'}else{this.fecd = this.f_inicio_d}
        if(this.f_inicio_h == ''){this.fech = '-1'}else{this.fech = this.f_inicio_h}        
        const data ={
          'f_siembra' : this.smb,
          'f_inicio_d' : this.fecd,
          'f_inicio_h' : this.fech,
        }
        axios.post("api/filtro-biomasa-alimento", data)
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
