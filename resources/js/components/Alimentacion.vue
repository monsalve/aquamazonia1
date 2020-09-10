<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">Alimentación</div>

          <div class="card-body">
            <div class="row mb-1">
            <div class="col-md-12 text-right ">
                <div class="form-group col-md-2">                            
                  <downloadexcel                                
                    class = "btn btn-success form-control"
                    :fetch   = "fetchData"
                    :fields = "json_fields"
                    :before-generate = "startDownload"
                    :before-finish = "finishDownload"
                    name    = "informe-alimentos.xls"
                    type    = "xls">
                      <i class="fa fa-fw fa-download"></i> Generar Excel 
                  </downloadexcel>      
                  <!-- Button trigger modal -->
                  <!-- <button type="button" class="btn btn-success" @click="abrirCrear()">Añadir registro</button> -->
                </div>
              </div>
              <div class="col-md-12">
                <h5>Filtrar por:</h5>
                <form class="row">
                  <input type="hidden" value="1" v-model="t_actividad">
                  <div class="form-group col-md-2">
                    <label for="Siembra">Siembra:</label>
                    <select class="form-control" id="f_siembra" v-model="f_siembra">
                      <option value="-1" selected>Seleccionar</option>                             
                      <option :value="ls.id" v-for="(ls, index) in listadoSiembras" :key="index">{{ls.nombre_siembra}}</option>
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
                    <label for="search">Desde: </label>
                    <input class="form-control" type="date" placeholder="Search" aria-label="fecha_ra1" v-model="fecha_ra1">
                  </div>
                   <div class="form-group col-md-2">
                    <label for="search">Hasta: </label>
                    <input class="form-control" type="date" placeholder="Search" aria-label="fecha_ra2" v-model="fecha_ra2">                                        
                  </div>
                  <div class="form-group col-md-2">                                      
                    <button  class="btn btn-primary rounded-circle mt-4" type="submit" @click="buscarResultados()"><i class="fas fa-search"></i></button>
                  </div>
                </form>
              </div>
              
            </div>
         
            <div>
              <table class="table table-sm table-hover table-responsive-sm">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Tipo de <br> Actividad</th>
                    <th>Siembras</th>
                    <th>Fecha</th>
                    <th>Horas hombre</th>
                    <th>Total horas hombre</th>
                    <th><br>Alimento</th>
                    <th>Cantidad<br>Mañana</th>
                    <th>Cantidad<br>Tarde</th>
                    <th>Costo Kg</th>
                    <th>Costo total</th>
                    <th width=15%>Detalles</th>
                    <!-- <th>Eliminar</th> -->
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in listado" :key="index">
                    <td v-text="index+1"></td>
                    <td v-text="item.actividad"></td>
                    <td v-text="item.nombre_siembra"></td>
                    <td v-text="item.fecha_ra"></td>                    
                    <td v-text="item.horas_hombre"></td>
                    <td v-text="item.total_horas_hombre"></td>
                    <td v-text="item.alimento"></td>
                    <td v-text="item.cant_manana == null ? '-' : item.cant_manana +' kg' "></td>
                    <td v-text="item.cant_tarde == null ? '-' : item.cant_tarde +' kg' "></td>                   
                    <td v-text="item.costo_kg"></td>
                    <td v-text="item.costo_total_alimento"></td>
                     <td v-text="item.detalles"></td>
                    <!-- <td>
                      <button class="btn btn-danger" @click="eliminarRegistro(item.id)">
                        <i class="fas fa-trash"></i>
                      </button>
                    </td> -->
      
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    
    <!-- Modal añadir recursos a siembras -->
    <div class="modal fade" id="modalRecursos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title" id="exampleModalLabel">Alimentos por siembra</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">          
            <form class="row">
              <div class="col-md-6">
              
                <div class="form-group row">
                  <label for="Alimento" class="col-md-4">Alimento</label>
                  <select class="form-control col-md-7" id="alimento" v-model="form.id_alimento" >
                    <option>--Seleccionar--</option>
                    <option v-for="(alimento, index) in listadoAlimentos" :key="index" v-bind:value="alimento.id">{{alimento.alimento}}</option>                  
                  </select>
                </div>               
                <div class="form-group row ">   
                  <label for="horas hombre" class="col-md-4">Fecha</label>
                  <input type="date" class="form-control col-md-7" id="fecha_ra" aria-describedby="fecha_ra" placeholder="Horas hombre" v-model="form.fecha_ra">                      
                </div>
                
                <div class="form-group row">   
                  <label for="horas hombre" class="col-md-4">Horas hombre</label>
                  <input type="number" class="form-control col-md-7" id="horas_hombre" step="any" aria-describedby="horas_hombre" placeholder="Horas hombre" v-model="form.horas_hombre">                      
                </div>
                    
              </div>
              <!-- Segunda columna -->
              <div class="col-md-6"> 
                <div class="form-group row">                    
                  <label for="cant_manana" class="col-md-4">Kg Mañana</label>
                  <input type="number" class="form-control col-md-7" id="kg_manana" aria-describedby="cant_manana" placeholder="Kg Mañana" v-model="form.cant_manana">                      
                </div>
                <div class="form-group row">    
                  <label for="cant_tarde" class="col-md-4">Kg tarde</label>
                  <input type="number" class="form-control col-md-7" id="cant_tarde" aria-describedby="cant_tarde" placeholder="Kg tarde" v-model="form.cant_tarde">                      
                </div>
              
                <div class="form-group row">   
                  <label for="detalles" class="col-md-4">Detalles</label>
                  <textarea class="form-control col-md-7" id="detalles" aria-describedby="detalles" placeholder="Detalles" v-model="form.detalles"></textarea>
                </div>
                <fieldset class="form-group">
                  <div class="row">
                    <legend for="detalles" class="col-md-4 col-form-label" >Seleccionar siembras</legend>
                    <div class="col-md-7">
                      <div v-for="(item, index) in listadoSiembras" :key="index" class="form-check">                                 
                        <input class="form-check-input" type="checkbox" v-bind:value="item.id" v-model="form.id_siembra">
                        <label class="form-check-label" for="siembra">{{item.nombre_siembra}}</label>
                        <br>                        
                      </div>
                    </div>
                    
                  </div>
                </fieldset>
              </div>
              
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" @click="guardarRecursos()">Guardar</button>
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
          'Tipo actividad' : 'actividad',
          'Siembra' : 'nombre_siembra',
          'Fecha' : 'fecha_ra',
          'Alimento' : 'alimento',
          'Kg Mañana' : 'cant_manana', 
          'Kg tarde' : 'cant_tarde',
          'Costo' : 'costo_kg',
          'Costo total' : 'costo_total_alimento',
          'Detalles' : 'detalles'
        },
        form : new Form({
          id_siembra: [],
          id_recurso : '',
          id_alimento :'',
          tipo_actividad : '1',
          fecha_ra : '',
          horas_hombre : '',
          cant_manana : '',
          cant_tarde : '',
          detalles : ''
        }),    
        t_actividad:'',
        fecha_ra1 :'',
        fecha_ra2 :'',
        f_siembra : '',
        alimento_s :'',
        recurso_s : '',
        busqueda:'',
        addSiembras :[],
        listado : [],
        listadoRS : [],
        listadorxs:[],
        listadoSiembras : [],
        listadoAlimentos:[],
        listadoRecursos:[],
        nombresRecursos:[],
        nombresAlimentos:[]
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
      startDownload(){
          alert('show loading');
      },
      finishDownload(){
          alert('hide loading');
      },
      abrirCrear(){
        let me = this;
        $('#modalRecursos').modal('show');
      },
      buscarResultados(){
        let me = this;
        if(this.f_siembra == ''){this.f_s = '-1'}else{this.f_s = this.f_siembra}
        if(this.t_actividad == ''){ this.actividad = '1'}else{this.actividad  = this.t_actividad} 
        if(this.alimento_s == ''){this.ali = '-1'}else{this.ali = this.alimento_s}
        if(this.fecha_ra1 == ''){ this.fecha1 = '-3'}else{this.fecha1 = this.fecha_ra1}
        if(this.fecha_ra2 == ''){ this.fecha2 = '-1'}else{this.fecha2 = this.fecha_ra2}
        if(this.recurso_s == ''){this.rec = '-1'}else{this.rec = this.recurso_s}
     
        const data ={
          'f_siembra' : this.f_s,
          'tipo_actividad' : '1',
          'alimento_s' : this.ali,
          'recurso_s' : this.rec,
          'fecha_ra1' :this.fecha1,
          'fecha_ra2' : this.fecha2
        }
        axios.post("api/searchResults", data)
        .then(response=>{
          me.listado = response.data.recursosNecesarios;
          console.log(response)
        })
        console.log('buscar')
      },
      listar(){
        let me = this;
        axios.get("api/lista-alimentacion")
        .then(function (response){
          me.listado = response.data.recursosNecesarios;         
          me.listadoRS = response.data.recursosSiembra;
          me.listadorxs = response.data.registrosxSiembra;
          
        })
      },
      listarSiembras(){
        let me = this;
        axios.get("api/siembras")
        .then(function (response){
          me.listadoSiembras = response.data.siembra;         
        })
      },
      listarAlimentos(){
        let me = this;
        axios.get("api/alimentos")
        .then(function (response){
          me.listadoAlimentos = response.data; 
          var auxAlimento = response.data;
          auxAlimento.forEach(element => me.nombresAlimentos[element.id] = element.alimento);
          
        })
      },
      listarRecursos(){
        let me = this;
        axios.get("api/recursos")
        .then(function (response){
          me.listadoRecursos = response.data;  
          var auxRecurso = response.data;
          auxRecurso.forEach(element => me.nombresRecursos[element.id] = element.recurso);          
        })
      },
      checkSiembras(){
        let me = this;
        me.addSiembras({
          'id_siembra' : this.form.id_siembra
        })
      },
      guardarRecursos(){
        let me = this;        
        this.form.post("api/recursos-necesarios")
        .then(({data})=>{
          console.log('guardado');
          me.listar();
         $('#modalRecursos').modal('hide');
        })
      },
      eliminarRegistro(objeto){
        let me = this;
        swal({
          title: "Estás seguro?",
          text: "Una vez eliminado, no se puede recuperar este registro",
          icon: "warning",
          buttons: ["Cancelar", "Aceptar"],
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            axios.delete('api/recursos-necesarios/'+objeto)
            .then(({data})=>{
              console.log('eliminar'+objeto);
              me.listar();
              
            })
          }
        });        
      }
    },
    mounted() {
      this.listar();
      this.listarSiembras();
      this.listarAlimentos();
      this.listarRecursos();
      console.log('Component mounted.')
    }
  }
</script>
