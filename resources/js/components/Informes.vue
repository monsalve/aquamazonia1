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
                          <form class="row">
                            <div class="form-group mr-2">
                              <label for="Estado">Estado siembra </label>
                              <select class="form-control" id="estado_s" v-model="estado_s">
                                <option value="-1">Todos</option>
                                <option value="0" >Inactivo</option>
                                <option value="1" selected>Activo</option>                                
                              </select>
                            </div>
                            <div class="form-group mr-2">
                              <label for="actividad">Tipo actividad: </label>
                              <select class="form-control" id="actividad" v-model="actividad_s">
                                <option selected value="-1"> --Seleccionar--</option>
                                <option value="Encalado" selected>Encalado</option>
                                <option value="Llenado">Llenado</option>
                                <option value="Siembra">Siembra</option>
                                <option value="Cultivo">Cultivo</option>
                                <option value="Pesca">Pesca</option>
                                <option value="Secado">Secado</option>
                                <option value="Lavado">Lavado</option>
                              </select>
                            </div>
                            <div class="form-group mr-2">
                             <label for="alimento">Alimento: </label>
                              <select class="form-control" id="alimento" v-model="alimento_s">
                                <option selected> Seleccionar</option>
                                <option v-for="(alimento, index) in listadoAlimentos" :key="index" v-bind:value="alimento.id">{{alimento.alimento}}</option>
                              </select>
                            </div>
                            <div class="form-group mr-2">
                             <label for="recurso">Recurso: </label>
                              <select class="form-control" id="recurso" v-model="recurso_s">
                                <option selected> Seleccionar</option>   
                                <option v-for="(recurso, index) in listadoRecursos" :key="index" v-bind:value="recurso.id">{{recurso.recurso}}</option>
                              </select>
                            </div>
                            <div class="form-group mr-2">
                              <label for="search">Desde: </label>
                              <input class="form-control" type="date" placeholder="Search" aria-label="fecha_ra1" v-model="fecha_ra1">
                            </div>
                             <div class="form-group mr-2">
                              <label for="search">Hasta: </label>
                              <input class="form-control" type="date" placeholder="Search" aria-label="fecha_ra2" v-model="fecha_ra2">                                        
                            </div>
                            <div class="form-group">                                      
                              <button  class="btn btn-primary rounded-circle mt-4" type="submit" @click="filtroResultados()"><i class="fas fa-search"></i></button>
                               <button type="submit" class="btn btn-success" @click="imprimirResultados()"><i class="fa fa-fw fa-download"></i> Generar Excel </button>
                            </div>
                          </form>
                        </div>
                      </div>
                      <div>
                        <table class="table table-sm">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Siembra</th>
                              <th>Estado siembras</th>
                              <th>Tipo <br>actividad</th>
                              <th>Fecha</th>
                              <th>Horas <br>hombre</th>
                              <th>Recursos</th>
                              <th>Costo</th>
                              <th>Costo <br>Acumulado</th>
                              <th>Fecha</th>
                              <th>Horas <br>hombre</th>
                              <th>Alimentos</th>
                              <th>Costo</th>
                              <th>Costo <br>Acumulado</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(lrn, index) in listadorn" :key="index">
                              <th v-text="index+1"></th>
                              <td>
                                <span class="nav-item" v-for="lrs in listadors" :key="lrs.id" v-if="lrn.id == lrs.id_registro">- {{lrs.nombre_siembra}}<br></span>
                              </td>
                               <td>
                                <span class="nav-item" v-for="lrs in listadors" :key="lrs.id" v-if="lrn.id == lrs.id_registro">-{{estados[lrs.estado]}}<br></span>
                              </td>
                              <td v-text="lrn.tipo_actividad"></td>
                              <td v-text="lrn.fecha_ra"></td>
                              <td v-text="lrn.horas_hombre +'hr'"></td>
                              <td v-text="lrn.recurso"></td>
                              <td v-text="lrn.costo_r"></td>
                              <td v-text="lrn.costo_r_acum"></td>        
                              <td v-text="lrn.fecha_ra"></td>
                              <td v-text="lrn.horas_hombre +'hr'"></td>
                              <td v-text="lrn.alimento"></td>
                              <td v-text="lrn.costo_a"></td>
                              <td v-text="lrn.costo_a_acum"></td>
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
  export default {
    data(){
      return {
        listados: [],
        listadors:[],
        listadorn:[],
        listadoe:[],
        listadoAlimentos:[],
        listadoRecursos:[],
        estados : [],
        estado_s: '',
        actividad_s:'',
        alimento_s : '',
        recurso_s : '',
        fecha_ra1 : '',
        fecha_ra2: '', 
        costo_acum : 0, 
      }
    },
    methods:{
      listar(){
        let me = this;        
        axios.get("api/informes")
        .then(function (response){
          me.listadors = response.data.recursosSiembras;
          me.listadorn = response.data.recursosNecesarios;
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
      
      filtroResultados(){
        let me = this;
        
        if(this.estado_s == ''){this.est = '-1'}else{this.est = this.estado_s}
        if(this.actividad_s == ''){this.act = '-1'}else{this.act = this.actividad_s}
        if(this.alimento_s == ''){this.ali = '-1'}else{this.ali = this.alimento_s}
        if(this.recurso_s == ''){this.rec = '-1'}else{this.rec = this.recurso_s}
        if(this.fecha_ra1 == ''){this.fec1 = '-1'}else{this.fec1 = this.fecha_ra1}
        if(this.fecha_ra2 == ''){this.fec2 = '-1'}else{this.fec2 = this.fecha_ra2}
        
        const data ={
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
          console.log(response.data);
        })
        console.log('buscar')
      },
      imprimirResultados(){
        let me = this;
        
        if(this.estado_s == ''){this.est = '-1'}else{this.est = this.estado_s}
        if(this.actividad_s == ''){this.act = '-1'}else{this.act = this.actividad_s}
        if(this.alimento_s == ''){this.ali = '-1'}else{this.ali = this.alimento_s}
        if(this.recurso_s == ''){this.rec = '-1'}else{this.rec = this.recurso_s}
        if(this.fecha_ra1 == ''){this.fec1 = '-1'}else{this.fec1 = this.fecha_ra1}
        if(this.fecha_ra2 == ''){this.fec2 = '-1'}else{this.fec2 = this.fecha_ra2}
        
        const data ={
          'estado_s': this.est,
          'actividad_s':this.act,
          'alimento_s' : this.ali,
          'recurso_s' : this.rec,
          'fecha_ra1' : this.fec1,
          'fecha_ra2' : this.fec2
        }
        axios.get("informe-excel", data)
        .then(response=>{
          // me.listadorn = response.data.recursosNecesarios;
          // me.listadors = response.data.recursosSiembras;
          console.log(response.data);
        })
        console.log('buscar')
      },
    },
    mounted() {
      console.log('Component mounted.');
      this.listar();
      this.listarAlimentos();
      this.listarRecursos();
      this.estados[0] = 'Inactivo';
      this.estados[1] = 'Activo';
    }
  }
</script>
