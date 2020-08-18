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
                        <div class="form-group col-md-2">
                          <label for="especie">Especies</label>
                          <select class="form-control" id="especie" v-model="f_especie">
                            <option value="-1">Seleccionar</option>
                            <option :value="les.id" v-for="(les, index) in listadoEspecies" :key="index">{{les.especie}}</option>
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
                          <button class="btn btn-primary" @click="filtroCiclo()">
                            Filtrar resultados
                          </button>
                        </div>
                        <div class="form-group col-md-2">
                          <downloadexcel
                          class = "btn btn-success form-control"
                          :fetch   = "fetchData"
                          :fields = "json_fields"
                          :before-generate = "startDownload"
                          name    = "informe-recursos.xls"
                          type    = "xls">
                            <i class="fa fa-fw fa-download"></i> Generar Excel 
                          </downloadexcel>
                        </div>
                      </div>
                      <div>
                        <table class="table table-sm table-hover table-responsive">
                          <thead>
                            <tr>   
                              <!-- <th>Cantidad inicial</th>
                              <th>Mortalidad actual</th>
                              <th>Biomasa final</th>
                              <th>Cantidad final</th> -->
                              <th>#</th>
                              <th>Siembra</th>
                              <th>Especie</th>
                              <th>Fecha inicio siembra</th>
                              <th>Cantidad Inicial</th>
                              <th>Peso inicial</th>
                              <!-- <th>Mortalidad</th> -->
                              <th>Mortalidad Kg <br> Aumentada</th>
                              <th>Salida animales acumulado</th>
                              <!-- <th>Peso ganado</th> -->
                              <th>Peso actual</th>
                              <th>Cantidad Actual</th>
                              <th>Biomasa disponible kg</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(le, index) in listadoExistencias" :key="index">                              
                              <td v-text="index+1"></td>
                              <td v-text="le.nombre_siembra"></td>
                              <td v-text="le.especie"></td>
                              <td v-text="le.fecha_inicio"></td>
                              <td v-text="le.cantidad_inicial"></td>
                              <td v-text="le.peso_inicial"></td>
                              <!-- <td v-text="le.mortalidad"></td> -->
                              <td v-text="le.mortalidad_kg_au == null ? '-' : le.mortalidad_kg_au +' kg' "></td>
                              <td v-text="le.cantidad_pescas"></td>
                              <!-- <td v-text="le.peso_ganado"></td> -->
                              <td v-text="le.peso_actual"></td>                              
                              <td v-text="le.cant_actual"></td>
                              <td v-text="le.biomasa_final+' kg'"></td>
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
          'Mortalidad kg aumentada' : 'mortalidad_kg_au',
          'Salida animales acumulado' : 'cantidad_pescas',
          'Peso actual' : 'peso_actual',
          'Cantidad actual' : 'cant_actual',
          'Biomasa final' : 'biomasa_final'
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
        axios.get("api/traer-existencias")
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
      filtroCiclo(){
        let me = this;
        
        if(this.f_siembra == ''){this.smb = '-1'}else{this.smb = this.f_siembra}
        if(this.f_especie == ''){this.esp = '-1'}else{this.esp = this.f_especie}
        if(this.f_inicio_d == ''){this.fecd = '-1'}else{this.fecd = this.f_inicio_d}
        if(this.f_inicio_h == ''){this.fech = '-1'}else{this.fech = this.f_inicio_h}
        
        const data ={
          'f_siembra' : this.smb,
          'f_especie' : this.esp,
          'f_inicio_d' : this.fecd,
          'f_inicio_h' : this.fech
        }
        axios.post("api/filtro-ciclos", data)
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
