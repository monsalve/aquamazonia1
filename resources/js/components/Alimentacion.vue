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
                    name    = "informe-alimentos.xls"
                    type    = "xls">
                      <i class="fa fa-fw fa-download"></i> Generar Excel 
                  </downloadexcel>    
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
                    <input type="checkbox" class="form-check-input" value="1" v-model="see_all" id="see_all">
                    <label for="see_all" class="form-check-label">
                      <span></span>
                      Ver todos los registros
                    </label>
                  </div>
                  <div class="form-group col-md-2">                                      
                    <button  class="btn btn-primary rounded-circle mt-4" type="button" @click="buscarResultados()"><i class="fas fa-search"></i></button>
                  </div>
                </form>
              </div>
              
            </div>
         
            <div>
              <table class="table table-sticky table-bordered table-striped table-sm table-sm-responsive">
                <thead class="thead-primary">
                  <tr>
                    <th>#</th>
                    <th>Tipo de Actividad</th>
                    <th>Siembras</th>
                    <th>Fecha</th>
                    <th>Minutos hombre</th>
                    <!-- <th>Total minutos hombre</th> -->
                    <th> Alimento</th>
                    <th>Cantidad Mañana</th>
                    <th>Cantidad Tarde</th>
                    <th>Total día</th>
                    <th>Costo Kg</th>
                    <th>Costo total</th>
                    <th>Conversión alimenticia teórica</th>
                    <th>Incremento biomasa acumulada por conversión</th>
                    <th>Detalles</th>
                    <!-- <th>Eliminar</th> -->
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in listado" :key="index">
                    <td v-text="index+1"></td>
                    <td v-text="item.actividad"></td>
                    <td v-text="item.nombre_siembra"></td>
                    <td v-text="item.fecha_ra"></td>                    
                    <td v-text="item.minutos_hombre"></td>
                    <td v-text="item.alimento"></td>
                    <td v-text="item.cant_manana == null ? '-' : item.cant_manana +' kg' "></td>
                    <td v-text="item.cant_tarde == null ? '-' : item.cant_tarde +' kg' "></td>                   
                    <td v-text="item.alimento_dia == null ? '-' : item.alimento_dia +' kg' "></td>  
                    <td v-text="item.costo_kg"></td>
                    <td v-text="item.costo_total_alimento"></td>
                    <td v-text="item.conv_alimenticia"></td>
                    <td v-text="item.incr_bio_acum_conver"></td>
                    <td v-text="item.detalles"></td>
              
                  </tr>
                 
                </tbody>
                <tfoot>
                   <tr>
                    <th colspan="4" class="text-right">TOTAL:</th>
                    <th v-text="promedios.tmh"></th>
                    <th></th>
                    <th v-text="promedios.cman"></th>                                        
                    <th v-text="promedios.ctar"></th>                    
                    <th v-text="promedios.alid"></th>
                    <th v-text="promedios.coskg"></th>
                    <th v-text="promedios.cta"></th>
                    <th></th>
                    <th v-text="promedios.icb"></th>
                  </tr>
   
                </tfoot>
              </table>
            </div>
            <nav v-show="showPagination" class="mt-5 navigation ">
              <ul class="pagination justify-content-center">
                <li class="page-item" v-if="pagination.current_page > 1">
                  <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                </li>
                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                  <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                </li>
                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                  <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                </li>
              </ul>
            </nav>
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
                  <label for="minutos hombre" class="col-md-4">Fecha</label>
                  <input type="date" class="form-control col-md-7" id="fecha_ra" aria-describedby="fecha_ra" placeholder="Minutos hombre" v-model="form.fecha_ra">                      
                </div>
                
                <div class="form-group row">   
                  <label for="minutos hombre" class="col-md-4">Minutos hombre</label>
                  <input type="number" class="form-control col-md-7" id="minutos_hombre" step="any" aria-describedby="minutos_hombre" placeholder="Minutos hombre" v-model="form.minutos_hombre">                      
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
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
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
          'Costo minutos hombre': 'total_minutos_hombre',
          'Alimento' : 'alimento',
          'Kg Mañana' : 'cant_manana', 
          'Kg tarde' : 'cant_tarde',
          'Kg día' : 'alimento_dia',
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
          minutos_hombre : '',
          cant_manana : '',
          cant_tarde : '',
          detalles : ''
        }),
        pagination : {
          'total' : 0,
          'current_page' : 0,
          'per_page' : 0,
          'last_page' : 0,
          'from' : 0,
          'to' : 0,
        },
        offset : 10,
        t_actividad:'',
        fecha_ra1 :'',
        fecha_ra2 :'',
        f_siembra : '',
        see_all : 0,
        alimento_s :'',
        recurso_s : '',
        busqueda:'',
        addSiembras :[],
        listado : [],
        promedios:[],
        listadoRS : [],
        listadorxs:[],
        listadoSiembras : [],
        listadoAlimentos:[],
        listadoRecursos:[],
        nombresRecursos:[],
        nombresAlimentos:[],
        showPagination : 1
      }
    },
    components: {
      downloadexcel,
    },
    computed:{
      isActived: function(){
          return this.pagination.current_page;
      },
      //Calcula los elementos de la paginación
      pagesNumber: function() {
        if(!this.pagination.to) {
          return [];
        }
        
        var from = this.pagination.current_page - this.offset; 
        if(from < 1) {
          from = 1;
        }

        var to = from + (this.offset * 2); 
        if(to >= this.pagination.last_page){
          to = this.pagination.last_page;
        }  

        var pagesArray = [];
        while(from <= to) {
          pagesArray.push(from);
          from++;
        }
        return pagesArray;
      }
    },
    methods:{
      async fetchData(){
        let me = this;
        const response = await this.listado
        return this.listado;
      },
      abrirCrear(){
        let me = this;
        $('#modalRecursos').modal('show');
      },

      buscarResultados(){
        let me = this;
        if(this.f_siembra == ''){this.f_s = '-1'}else{this.f_s = this.f_siembra}
        if(this.see_all == ''){this.check = 0}else{this.check = this.see_all}
        if(this.t_actividad == ''){ this.actividad = '1'}else{this.actividad  = this.t_actividad} 
        if(this.alimento_s == ''){this.ali = '-1'}else{this.ali = this.alimento_s}
        if(this.fecha_ra1 == ''){ this.fecha1 = '-3'}else{this.fecha1 = this.fecha_ra1}
        if(this.fecha_ra2 == ''){ this.fecha2 = '-1'}else{this.fecha2 = this.fecha_ra2}
        if(this.recurso_s == ''){this.rec = '-1'}else{this.rec = this.recurso_s}
     
        const data ={
          'f_siembra' : this.f_s,
          'see_all' : this.check,
          'tipo_actividad' : '1',
          'alimento_s' : this.ali,
          'recurso_s' : this.rec,
          'fecha_ra1' :this.fecha1,
          'fecha_ra2' : this.fecha2
        }
        axios.post("api/searchResults", data)
        .then(response=>{

          me.promedios = response.data.promedioRecursos;

          if(response.data.pagination) {
            this.showPagination = 1;
            me.listado = response.data.recursosNecesarios.data;
            me.pagination = response.data.pagination;
          }
          else{
            this.showPagination = 0;
            me.listado = response.data.recursosNecesarios;
            me.pagination = []
          }
           
        })
      },

      listar(page){
        let me = this;
        axios.get("api/lista-alimentacion?page=" + page)
        .then(function (response){
          me.listado = response.data.recursosNecesarios.data;         
          me.promedios = response.data.promedioRecursos;
          me.pagination = response.data.pagination;
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
              me.listar();
              
            })
          }
        });        
      },
      cambiarPagina(page){
        let me = this;
        //Actualiza la página actual
        me.pagination.current_page = page;
        me.listar(page);
      },
    },
    
    mounted() {
      this.listar(1);
      this.listarSiembras();
      this.listarAlimentos();
      this.listarRecursos();
    }
  }
</script>
