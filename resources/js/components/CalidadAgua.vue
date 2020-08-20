<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">Parametros de calidad del agua</div>

          <div class="card-body">
            <div class="row mb-1">
              <div class="col-md-10">
                <h5>Filtrar por:</h5>     
                <div class="row">                 
                  <div class="form-group col-md-3">
                    <label for="Fecha desde">Fecha inicio desde: </label>
                    <input type="date" class="form-control" id="f_inicio_d" v-model="f_inicio_d">
                  </div>
                  <div class="form-group col-md-3">
                    <label for="fecha hasta">Fecha inicio hasta: </label>
                    <input type="date" class="form-control" id="f_inicio_h" v-model="f_inicio_h">
                  </div>
                  <div class="form-group col-md-1">
                    <label for="fecha hasta">Buscar: </label>
                    <button class="btn btn-primary form-control" @click="filtrarParametros()"> <i class="fas fa-search"></i></button>
                  </div>
                  <div class="form-group  col-md-3">
                    <label for="Generar excel">Generar Excel:</label>
                    <downloadexcel
                      
                      class = "btn btn-success form-control"
                      :fetch   = "fetchData"
                      :fields = "json_fields"
                      :before-generate = "startDownload"
                      name    = "informe-parametros-calidad-agua.xls"
                      type    = "xls">
                        <i class="fa fa-fw fa-download"></i> Exportar Excel 
                    </downloadexcel>
                  </div>
                </div>
              </div>
              <div class="col-md-2 text-right ">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-success" @click="crearParametros()">Añadir párametros</button>
              </div>
            </div>
            <!-- Contenido de parametros -->
            <div v-if="mostrar == 1">   
              <div class="col-md-12 text-right">
                <button class="btn btn-primary" @click="ocultarParametros()">
                  Regresar
                </button>
              </div>
              <div> 
              <h2>Párametros de calidad del agua</h2>
                <table class="table table-striped table-hover">
                 
                  <thead class="">
                    <tr>                    
                      <th rowspan="2" data-field="id">#</th>                    
                      <th rowspan="2">ID registro párametros</th>
                      <th rowspan="2" data-field="id">Fecha</th>      
                      <th colspan="5" class="text-center">% Saturación de oxígeno</th>
                      <th rowspan="2" data-field="id">Temperatura</th>
                      <th rowspan="2" data-field="id">PH</th>
                      <th rowspan="2" data-field="id">Amonio</th>
                      <th rowspan="2" data-field="id">Nitrito</th>
                      <th rowspan="2" data-field="id">Nitrato</th>
                      <th rowspan="2" data-field="id">Otros</th>
                      <th rowspan="2" data-field="id">Editar/Eliminar</th>
                    </tr>
                    <tr>
                      <th data-field="" data-not-first-th="">12:00 am</th>
                      <th data-field="">4:00 am</th>
                      <th data-field="">7:00 am </th>
                      <th data-field="">4:00 pm </th>
                      <th data-field="">8:00 pm </th>
                    </tr>                  
                  </thead>
                  <tbody>
                    <tr v-for="(lp, index) in listadoParametros" :key="index">
                      <th v-text="index"></th>
                      <th v-text="lp.id"></th>
                      <td v-text="lp.fecha_parametro"></td>
                      <td v-text="lp['12_am']"></td>
                      <td v-text="lp['4_am']"></td>
                      <td v-text="lp['7_am']"></td>
                      <td v-text="lp['4_pm']"></td>
                      <td v-text="lp['8_pm']"></td>
                      <td v-text="lp.temperatura"></td>
                      <td v-text="lp.ph"></td>
                      <td v-text="lp.amonio"></td>
                      <td v-text="lp.nitrito"></td>
                      <td v-text="lp.nitrato"></td>
                      <td v-text="lp.otros"></td>
                      <td>
                        <button class="btn btn-success" type="button" @click="editarParametros(lp)">
                          <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger" type="button" @click="eliminarParametros(lp.id)">
                          <i class="fas fa-trash"></i>
                        </button>
                      </td>
                    </tr>
                    <tr class="bg-secondary text-white">
                      
                      <th colspan="3">PROMEDIO:</th>
                      <td v-text="promedios.promedio_12_am"></td>
                      <td v-text="promedios.promedio_4_am"></td>
                      <td v-text="promedios.promedio_7_am"></td>
                      <td v-text="promedios.promedio_4_pm"></td>
                      <td v-text="promedios.promedio_8_pm"></td>
                      <td v-text="promedios.promedio_temperatura"></td>
                      <td v-text="promedios.promedio_ph"></td>
                      <td v-text="promedios.promedio_amonio"></td>
                      <td v-text="promedios.promedio_nitrito"></td>
                      <td v-text="promedios.promedio_nitrato"></td>
                      <td v-text="promedios.promedio_otros"></td>
                      
                    </tr>                  
                  </tbody>
                </table>
              </div>
            </div>
            <div class="row" v-if="mostrar==0">
              <table class="table table-striped table-responsive-sm">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Contenedor</th>
                    <th scope="col">Siembra asociada</th>
                    <th scope="col">Capacidad</th>
                    <th scope="col">Estado contenedor</th>
                    <th scope="col">Ver párametros de calidad</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(contenedor,index) in listadoParametrosContenedores" :key="index">
                    <th scope="row" v-text="index+1"></th>
                    <td v-text="contenedor.contenedor"></td>
                    <td v-text="contenedor.nombre_siembra"></td>
                    <td v-text="contenedor.capacidad"></td>
                    <td v-text="estados[contenedor.estado]"></td>
                    <td>                     
                      <button class="btn btn-primary" @click="mostrarParametros(contenedor.id)">
                          <i class="far fa-eye"></i>
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
    <!-- Modal añadir Parametros a siembras -->
    <div class="modal fade" id="modalParametros" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title" >Párametros de calidad del agua</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">          
            <form class="row container"  @submit.prevent="editando == 0 ? guardar() : editar()">
              <div class="col-md-6">
                <div class="form-group row">
                  <label for="Fecha" class="col-sm-6 col-form-label"><i class="far fa-calendar-alt"></i>  Fecha registro: </label>
                  <div class="col-sm-6">
                    <input type="date" class="form-control" id="fecha_registro" placeholder="Fecha de registro" v-model="form.fecha_parametro" required>
                  </div>
                </div>
                <div class="col-sm-12 text-center">
                <h5>% Saturación oxígeno</h5>
                </div>
                <div class="border rounded p-3 mb-3">
                  <div class="form-group row">
                    <label for="" class="col-sm-6 col-form-label"><i class="far fa-clock"></i>  12:00 am: </label>
                    <div class="col-sm-6">
                      <input type="number" class="form-control" id="12am" placeholder="Párametros 12am " v-model="form['12_am']">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="" class="col-sm-6 col-form-label"><i class="far fa-clock"></i>  4:00 am: </label>
                    <div class="col-sm-6">
                      <input type="number" class="form-control" id="4am" placeholder="Párametros 4am" v-model="form['4_am']">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="" class="col-sm-6 col-form-label"><i class="far fa-clock"></i>  7:00 am: </label>
                    <div class="col-sm-6">
                      <input type="number" class="form-control" id="7am" placeholder="Párametros 7am" v-model="form['7_am']">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="" class="col-sm-6 col-form-label"><i class="far fa-clock"></i>  4:00pm: </label>
                    <div class="col-sm-6">
                      <input type="number" class="form-control" id="4pm" placeholder="Párametros 4pm" v-model="form['4_pm']">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="8pm" class="col-sm-6 col-form-label"><i class="far fa-clock"></i>  8:00pm: </label>
                    <div class="col-sm-6">
                      <input type="number" class="form-control" id="8pm" placeholder="Párametros 8pm" v-model="form['8_pm']">
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="Temperatura" class="col-sm-6 col-form-label">Temperatura: </label>
                  <div class="col-sm-6">
                    <input type="number" class="form-control" id="temperatura" placeholder="Temperatura" v-model="form.temperatura">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="ph" class="col-sm-6 col-form-label">PH: </label>
                  <div class="col-sm-6">
                    <input type="number" class="form-control" id="ph" placeholder="ph" v-model="form.ph">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                
                <div class="form-group row">
                  <label for="Amonio" class="col-sm-6 col-form-label">Amonio: </label>
                  <div class="col-sm-6">
                    <input type="number" class="form-control" id="amonio" placeholder="Amonio" v-model="form.amonio">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="Nitrito" class="col-sm-6 col-form-label">Nitrito: </label>
                  <div class="col-sm-6">
                    <input type="number" class="form-control" id="nitrito" placeholder="Nitrito" v-model="form.nitrito">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="Nitrato" class="col-sm-6 col-form-label">Nitrato: </label>
                  <div class="col-sm-6">
                    <input type="number" class="form-control" id="nitrato" placeholder="Nitrato" v-model="form.nitrato">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="" class="col-sm-6 col-form-label">Otros: </label>
                  <div class="col-sm-6">
                    <input type="number" class="form-control" id="otros" placeholder="Otros" v-model="form.otros">
                  </div>
                </div>
                <div v-for="(lc, index) in listadoContenedores" :key="index">                                 
                  <input type="checkbox" v-bind:value="lc.id" v-model="form.id_contenedor">
                  <label for="siembra">{{lc.contenedor}}</label>
                  <br>
                </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-12 text-right">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"  v-text="editando ==0 ? 'Crear' : 'Actualizar'"></button>
                  </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import downloadexcel from "vue-json-excel"
  import { Form, HasError, AlertError } from 'vform'
  export default {
    data(){
    
      return {
        json_fields: {   
          '#' : 'id',
          'Fecha ' : 'fecha_parametro',
          '12:00 a.m' : '12_am',
          '4:00 a.m' : '4_am',
          '7:00 a.m' : '7_am',
          '4:00 p.m' : '4_pm',
          '8:00 a.m' : '8_pm',
          'Temperatura' : 'temperatura', 
          'Ph' : 'ph',
          'Amonio' : 'amonio',
          'Nitrito' : 'nitrito', 
          'Nitrato' : 'nitrato',
          'Otros' : 'otros'
          
        },     
        editando : 0,
        mostrar : 0,
        idSiembra : 0,
        form : new Form({
          id : '',
          id_contenedor : [],
          id_especie : '',
          fecha_parametro : '',
          '12_am' : '',
          '4_am' : '',
          '7_am' : '',
          '4_pm' : '',
          '8_pm': '',
          temperatura : '',
          ph: '',
          amonio : '',
          nitrito : '',
          nitrato : '',
          otros : ''
        }),
        estados :[],
        listadoExistencias : [],
        listadoEspecies : [],
        listadoSiembras: [],
        listadoParametros : [],
        listadoParametrosContenedores : [],
        listadoContenedores : [],
        promedios : [],
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
        // const response = await axios.get('api/informe-Parametros');
        const response = await this.listadoParametros;
        // console.log(response);
        return this.listadoParametros;
      },
      startDownload(){
          alert('show loading');
      },
      finishDownload(){
          alert('hide loading');
      },
      filtrarParametros(){
        let me = this;
        if(this.f_inicio_d == ''){this.f_d = '-1'}else{this.f_d = this.f_inicio_d}
        if(this.f_inicio_h == ''){this.f_h = '-1'}else{this.f_h = this.f_inicio_h}
        
        const data = {
          'f_inicio_d' : this.f_d,
          'f_inicio_h' : this.f_h
        }
        axios.post("api/filtro-parametros", data)
        .then(response=>{
          console.log(response.data);
          me.listadoParametros = response.data.calidad_agua;
          me.promedios = response.data.promedios
        })
        
      },
      listar(){
        let me = this;      
        this.listarParametros();
        this.listarSiembras(); 
        this.listarParametrosContenedores();
        this.listarContenedores();
      },
      listarParametros(){
        let me = this;
        axios.get("api/parametros-calidad")
        .then(function (response){
          me.listadoParametros = response.data.calidad_agua;
          me.promedios = response.data.promedios
        })
      },

      listarSiembras(){
        let me = this;
        axios.get("api/siembras")
        .then(function (response){
          me.listadoSiembras = response.data.siembra;         
        })
      },
      listarParametrosContenedores(){
        let me = this;
        axios.get("api/parametros-contenedores")
        .then(function (response) {
            me.listadoParametrosContenedores = response.data
        });
      },
      listarContenedores(){
        let me = this;
        axios.get("api/contenedores")
        .then(function (response) {
            me.listadoContenedores = response.data
        });
      },
      mostrarParametros(objeto){
        
        this.idSiembra = objeto
        let me = this;
        const data = {
          'id_siembra' : this.idSiembra
        }
        axios.post('api/parametro-x-contenedor/'+objeto)
        .then(response=>{
          this.mostrar = 1
          this.listadoParametros = response.data.calidad_agua;
          this.promedios = response.data.promedios
        })
       
      },
      ocultarParametros(){
        let me = this;
        this.mostrar = 0;
        this.listar();
      },
      crearParametros(){
        this.editando = 0;
        let me = this;
        $('#modalParametros').modal('show');
      },
      
      guardar(){
        let me = this;        
        this.form.post("api/parametros-calidad")
        .then(({data})=>{
          editando: 0;
          console.log('guardado');
          me.listar();
         $('#modalParametros').modal('hide');
        })
      },
      editarParametros(objeto){
        let me = this;
        this.form.fill(objeto);
        this.editando = 1;
        $('#modalParametros').modal('show');
      },
      editar(){
        let me = this;
        
        this.form.put('api/parametros-calidad/'+this.form.id)
        .then(({data})=>{              
          $('#modalParametros').modal('hide');
          me.listar();
          this.form.reset();
        })          
        console.log('editando' + this.form.id)
        
      },
      eliminarParametros(objeto){
        let me = this;
        swal({
          title: "Estás seguro?",
          text: "Una vez eliminado, no se puede recuperar los registros asociados a este ID",
          icon: "warning",
          buttons: ["Cancelar", "Aceptar"],
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            axios.delete('api/parametros-calidad/'+objeto)
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
      this.estados[0] = 'Inactivo';
      this.estados[1] = 'Activo';
      this.estados[2] = 'Ocupado';
      this.estados[3] = 'Descanso';       
    }
  }
</script>