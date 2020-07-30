<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">Recursos necesarios</div>

          <div class="card-body">
            <div class="row mb-1">
              <div class="col-md-10">
                <h2>Filtrar por:</h2>
                <form class="row">
                  <div class="form-group mr-2">
                   <label for="t_actividad">Tipo de Actividad: </label>
                    <select class="form-control" id="t_actividad" v-model="t_actividad">
                      <option selected value="1"> Seleccionar</option>
                      <option value="Encalado">Encalado</option>
                      <option value="Llenado">Llenado</option>
                      <option value="Siembra">Siembra</option>
                      <option value="Cultivo">Cultivo</option>
                      <option value="Pesca">Pesca</option>
                      <option value="Secado">Secado</option>
                      <option value="Lavado">Lavado</option>
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
                    <button  class="btn btn-primary rounded-circle mt-4" type="submit" @click="buscarResultados()"><i class="fas fa-search"></i></button>
                  </div>
                </form>
              </div>
              <div class="col-md-2 text-right ">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-success" @click="abrirCrear()">Añadir registro</button>
              </div>
            </div>
         
            <div>
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Tipo de <br> Actividad</th>
                    <th>Siembras</th>
                    <th>Fecha</th>
                    <th>Recurso</th>
                    <th>Horas hombre</th>
                    <th>Cantidad<br>Mañana</th>
                    <th>Cantidad<br>Tarde</th>
                    <th width=15%>Detalles</th>
                    <th>Eliminar</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in listado" :key="index" v-if="item.tipo_actividad != 'Alimentacion'">
                    <td v-text="index+1"></td>
                    <td v-text="item.tipo_actividad"></td>
                    <td>
                      <span class="nav-item" v-for="rs in listadoRS" :key="rs.id" v-if="item.id == rs.id_registro">- {{rs.nombre_siembra}}<br></span>
                    </td>
                    <td v-text="item.fecha_ra"></td>
                    <td>
                      {{nombresRecursos[item.id_recurso]}}
                      <!-- {{nombresAlimentos[item.id_alimento]}} -->
                    </td>
                    <td v-text="item.horas_hombre"></td>
                    <td v-text="item.cant_manana+'kg'"></td>
                    <td v-text="item.cant_tarde+'kg'"></td>
                    <td v-text="item.detalles"></td>
                    <td>
                      <button class="btn btn-danger" @click="eliminarRegistro(item.id)">
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
      
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
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title" id="exampleModalLabel">Recursos por siembra</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">          
            <form class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Tipo de Actividad</label>
                  <select class="form-control" id="tipo_actividad" v-model="form.tipo_actividad">
                    <option value="Encalado" selected>Encalado</option>
                    <option value="Llenado">Llenado</option>
                    <option value="Siembra">Siembra</option>
                    <option value="Cultivo">Cultivo</option>
                    <option value="Pesca">Pesca</option>
                    <option value="Secado">Secado</option>
                    <option value="Lavado">Lavado</option>
                  </select>
                </div>
               
                <div class="form-group">
                  <label for="recurso">Recurso</label>
                  <select class="form-control" id="recurso" v-model="form.id_recurso">
                    <option selected>--Seleccionar--</option>
                    <option v-for="(recurso, index) in listadoRecursos" :key="index" v-bind:value="recurso.id">{{recurso.recurso}}</option>
                  </select>
                </div>
                 <div class="form-group">   
                  <label for="horas hombre">Fecha</label>
                  <input type="date" class="form-control" id="fecha_ra" aria-describedby="fecha_ra" placeholder="Horas hombre" v-model="form.fecha_ra">                      
                </div>
                
                <div class="form-group">   
                  <label for="horas hombre">Horas hombre</label>
                  <input type="number" class="form-control" id="horas_hombre" aria-describedby="horas_hombre" placeholder="Horas hombre" v-model="form.horas_hombre">                      
                </div>
                    
                <div class="form-group">                    
                  <label for="cant_manana">Kg Mañana</label>
                  <input type="number" class="form-control" id="kg_manana" aria-describedby="cant_manana" placeholder="Kg Mañana" v-model="form.cant_manana">                      
                </div>
              </div>
              <div class="col-md-6"> 
                <div class="form-group">    
                  <label for="cant_tarde">Kg tarde</label>
                  <input type="number" class="form-control" id="cant_tarde" aria-describedby="cant_tarde" placeholder="Kg tarde" v-model="form.cant_tarde">                      
                </div>
              
                <div class="form-group">   
                  <label for="exampleFormControlSelect2">Detalles</label>
                  <textarea class="form-control" id="detalles" aria-describedby="detalles" placeholder="Detalles" v-model="form.detalles"></textarea>
                </div>
                <h5> Seleccionar siembras</h5>
                <div v-for="(item, index) in listadoSiembras" :key="index">                                 
                  <input type="checkbox" v-bind:value="item.id" v-model="form.id_siembra">
                  <label for="siembra">{{item.nombre_siembra}}</label>
                  <br>
                </div>
                <span>Checked names: {{ form.id_siembra }}</span>
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
  export default {
    data(){
      return {
        form : new Form({
          id_siembra: [],
          id_recurso : '',
          id_alimento :1,
          tipo_actividad : '',
          fecha_ra : '',
          horas_hombre : '',
          cant_manana : '',
          cant_tarde : '',
          detalles : ''
        }),    
        t_actividad:'',
        fecha_ra1 :'',
        fecha_ra2 :'',
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
    methods:{
      abrirCrear(){
        let me = this;
        $('#modalRecursos').modal('show');
      },
      buscarResultados(){
        let me = this;
        
        if(this.t_actividad == ''){ this.actividad = '1'}else{this.actividad  = this.t_actividad}       
        if(this.fecha_ra1 == ''){ this.fecha1 = '-3'}else{this.fecha1 = this.fecha_ra1}
        if(this.fecha_ra2 == ''){ this.fecha2 = '-1'}else{this.fecha2 = this.fecha_ra2}
     
        const data ={
          'tipo_actividad' : this.actividad,
          'fecha_ra1' :this.fecha1,
          'fecha_ra2' : this.fecha2
        }
        axios.post("api/searchResults", data)
        .then(response=>{
          me.listado = response.data.recursosNecesarios;
        })
        console.log('buscar')
      },
      listar(){
        let me = this;
        axios.get("api/recursos-necesarios")
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
