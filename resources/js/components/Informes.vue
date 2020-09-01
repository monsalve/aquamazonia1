<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Informes Aquamazonia</div>
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
                              <label for="actividad">Tipo actividad: </label>
                              <select class="form-control" id="actividad" v-model="actividad_s" name="tipo_actividad">
                                <option selected value="-1"> --Seleccionar--</option>
                                <option value="Encalado" selected>Encalado</option>
                                <option value="Alimentacion"> Alimentación</option>
                                <option value="Llenado">Llenado</option>
                                <option value="Siembra">Siembra</option>
                                <option value="Cultivo">Cultivo</option>
                                <option value="Pesca">Pesca</option>
                                <option value="Secado">Secado</option>
                                <option value="Lavado">Lavado</option>
                              </select>
                            </div>
                            <div class="form-group col-md-2">
                             <label for="alimento">Alimento: </label>
                              <select class="form-control" id="alimento" v-model="alimento_s">
                                <option selected> Seleccionar</option>
                                <option v-for="(alimento, index) in listadoAlimentos" :key="index" v-bind:value="alimento.id">{{alimento.alimento}}</option>
                              </select>
                            </div>
                            <div class="form-group col-md-2">
                             <label for="recurso">Recurso: </label>
                              <select class="form-control" id="recurso" v-model="recurso_s">
                                <option selected> Seleccionar</option>   
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
                              class = "btn btn-success"
                              :fetch   = "fetchData"
                              :fields = "json_fields"
                              :before-generate = "startDownload"
                              name    = "informe-recursos.xls"
                              type    = "xls">
                                <i class="fa fa-fw fa-download"></i> Generar Excel 
                              </downloadexcel>
                              
                            </div>
                          </form>
                        </div>
                      </div>
                      <div>
                        <table class="table table-sm table-responsive">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Siembra</th>
                              <th>Estado siembras</th>
                              <th>Tipo <br>actividad</th>
                              <th>Fecha</th>
                              <th>Horas <br>hombre</th>
                              <th>Costo horas </th>
                              <th>Costo Acumulado Horas</th>
                              <th>Recursos</th>
                              <th>Costo</th>
                              <th>Costo <br>Acumulado</th>
                              <th>Fecha</th>
                              <th>Alimentos</th>
                              <th>Costo</th>
                              <th>Costo <br>Acumulado</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(lrn, index) in listadorn" :key="index">
                              <th v-text="index+1"></th>
                              <td v-text="lrn.nombre_siembra"></td>
                              <td v-text="estados[lrn.estado]"></td>
                              <td v-text="lrn.tipo_actividad"></td>
                              <td v-text="lrn.fecha_ra"></td>
                              <td v-text="lrn.horas_hombre +'hr'"></td>
                              <td v-text="lrn.costo_horash +'hr'"></td>
                              <th v-text="lrn.costo_h_acum +'hr'"></th>
                              <td v-text="lrn.recurso"></td>
                              <td v-text="lrn.costo_r"></td>
                              <th v-text="lrn.costo_r_acum"></th>        
                              <td v-text="lrn.fecha_ra"></td>     
                              <td v-text="lrn.alimento"></td>
                              <td v-text="lrn.costo_total"></td>
                              <th v-text="lrn.costo_a_acum"></th>
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
            'Nombre Siembra' : 'nombre_siembra',
            'Estado Siembra' : 'estado',
            'Tipo de Actividad' : 'tipo_actividad',
            'Fecha Registro' : 'fecha_ra',
            'Horas hombre' : 'horas_hombre',
            'Costo horas hombre' : 'costo_horash',
            'Costo acumulado horas' : 'costo_h_acum',
            'Recurso' : 'recurso',
            'Costo' : 'costo_r',
            'Costo acumulado' : 'costo_r_acum',
            'Alimento' : 'alimento',
            'Costo Alimento' : 'costo_a',
            'Costo Total' : 'costo_total',
            'Costo acumulado Alimento' : 'costo_a_acum', 
        },       
        listados: [],
        listadors:[],
        listadorn:[],
        listadoe:[],
        listadoAlimentos:[],
        listadoSiembras: [], 
        listadoRecursos:[],
        imprimirRecursos: [],
        estados : [],
        f_siembra: '',
        estado_s: '',
        actividad_s:'',
        alimento_s : '',
        recurso_s : '',
        fecha_ra1 : '',
        fecha_ra2: '', 
        costo_acum : 0, 
        
      }
    },
    components: {
      downloadexcel,
    },
    methods:{
      async fetchData(){
        let me = this;
        // const response = await axios.get('api/informe-recursos');
        const response = await this.imprimirRecursos
        // console.log(response);
        return this.imprimirRecursos;
      },
      startDownload(){
          alert('show loading');
      },
      finishDownload(){
          alert('hide loading');
      },
      listar(){
        let me = this;        
        axios.get("api/informes")
        .then(function (response){
          me.listadors = response.data.recursosSiembras;
          me.listadorn = response.data.recursosNecesarios;
        })         
        axios.get("api/traer-recursos")
        .then(response=>{
          console.log(response.data.recursosNecesarios);
          me.imprimirRecursos = response.data.recursosNecesarios;
        })
      },
      incrementar(incremento){
        this.costo_acum += parseFloat(incremento);
        var aux_acum = parseFloat(this.costo_acum);
        console.log('aux_acum=' + aux_acum);
        return aux_acum;
      },
      listarAlimentos(){
        let me = this;
        axios.get("api/alimentos")
        .then(function (response){
          me.listadoAlimentos = response.data; 
          var auxAlimento = response.data;
          // auxAlimento.forEach(element => me.nombresAlimentos[element.id] = element.alimento);          
        })
      },
      listarRecursos(){
        let me = this;
        axios.get("api/recursos")
        .then(function (response){
          me.listadoRecursos = response.data;  
          var auxRecurso = response.data;
          // auxRecurso.forEach(element => me.nombresRecursos[element.id] = element.recurso);          
        })
      },
      listarSiembras(){
        let me = this;
        axios.get("api/siembras")
        .then(function (response){
          me.listadoSiembras = response.data.siembra;
        })
      },
      filtroResultados(){
        let me = this;
        if(this.f_siembra == ''){this.smb = '-1'}else{this.smb = this.f_siembra}
        if(this.estado_s == ''){this.est = '-1'}else{this.est = this.estado_s}
        if(this.actividad_s == ''){this.act = '-1'}else{this.act = this.actividad_s}
        if(this.alimento_s == ''){this.ali = '-1'}else{this.ali = this.alimento_s}
        if(this.recurso_s == ''){this.rec = '-1'}else{this.rec = this.recurso_s}
        if(this.fecha_ra1 == ''){this.fec1 = '-1'}else{this.fec1 = this.fecha_ra1}
        if(this.fecha_ra2 == ''){this.fec2 = '-1'}else{this.fec2 = this.fecha_ra2}
        
        const data ={
          'f_siembra' : this.smb,
          'estado_s': this.est,
          'actividad_s':this.act,
          'alimento_s' : this.ali,
          'recurso_s' : this.rec,
          'fecha_ra1' : this.fec1,
          'fecha_ra2' : this.fec2
        }
        axios.post("api/filtroInformes", data)
        .then(response=>{
          me.listadorn = response.data.recursosNecesarios;
          me.listadors = response.data.recursosSiembras;
          // console.log(response.data);
        });
        axios.post("api/informe-recursos", data)
        .then(response=>{
          console.log(response.data.recursosNecesarios);
          me.imprimirRecursos = response.data.recursosNecesarios;
        })
        console.log('buscar')
      },
    },
    mounted() {
      console.log('Component mounted.');
      this.listar();
      this.listarSiembras();
      this.listarAlimentos();
      this.listarRecursos();
      this.estados[0] = 'Inactivo';
      this.estados[1] = 'Activo';
    }
  }
</script>
