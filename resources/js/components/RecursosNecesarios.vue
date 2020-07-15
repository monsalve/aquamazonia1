<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">Recursos necesarios</div>

          <div class="card-body">
            <div class="row mb-1">
                <div class="col-12 text-right ">
                  <!-- Button trigger modal -->
                  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">
                    Añadir registro
                  </button>
                </div>
            </div>
         
            <div>
              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Tipo de <br> Actividad</th>
                    <th>Siembras</th>
                    <th>Fecha</th>
                    <th>Recurso</th>
                    <th>Horas hombre</th>
                    <th>Cantidad Mañana</th>
                    <th>Cantidad Tarde</th>
                    <th>Detalles</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in listado" :key="index" v-if="item.id == item.id_registro">
                    <td v-text="index+1"></td>
                    <td v-text="item.tipo_actividad"></td>
                    <td v-text="item.nombre_siembra"></td>
                    <td v-text="item.fecha_ra"></td>
                    <td v-text="item.id_recurso"></td>
                    <td v-text="item.horas_hombre"></td>
                    <td v-text="item.cant_manana"></td>
                    <td v-text="item.cant_tarde"></td>
                    <td v-text="item.detalles"></td>
                    
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    
    <!-- Modal añadir recursos a siembras -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="Alimento">Alimento</label>
                  <select class="form-control" id="alimento" v-model="form.id_alimento">
                    <option>--Seleccionar--</option>
                    <option v-for="(alimento, index) in listadoAlimentos" :key="index" v-bind:value="alimento.id">{{alimento.alimento}}</option>                  
                  </select>
                </div>
                <div class="form-group">
                  <label for="exampleFormControlSelect2">Recurso</label>
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
                  <input type="number" class="form-control" id="exampleInputEmail1" aria-describedby="horas_hombre" placeholder="Horas hombre" v-model="form.horas_hombre">                      
                </div>
                    
                <div class="form-group">                    
                  <label for="exampleFormControlSelect2">Kg Mañana</label>
                  <input type="number" class="form-control" id="kg_manana" aria-describedby="cant_manana" placeholder="Kg Mañana" v-model="form.cant_manana">                      
                </div>
              </div>
              <div class="col-md-6"> 
                <div class="form-group">    
                  <label for="exampleFormControlSelect2">Kg tarde</label>
                  <input type="number" class="form-control" id="exampleInputEmail1" aria-describedby="cant_tarde" placeholder="Kg tarde" v-model="form.cant_tarde">                      
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
            <button type="button" class="btn btn-primary" @click="guardarRecursos">Guardar</button>
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
          id_alimento :'',
          tipo_actividad : '',
          fecha_ra : '',
          horas_hombre : '',
          cant_manana : '',
          cant_tarde : '',
          detalles : ''
          
        }),        
        addSiembras :[],
        listado : [],
        listadoSiembras : [],
        listadoAlimentos:[],
        listadoRecursos:[]
      }
    },
    methods:{
      listar(){
        let me = this;
        axios.get("api/recursos-necesarios")
        .then(function (response){
          me.listado = response.data;         
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
        })
      },
      listarRecursos(){
        let me = this;
        axios.get("api/recursos")
        .then(function (response){
          me.listadoRecursos = response.data;         
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
          console.log('guardado')
         
        })
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
