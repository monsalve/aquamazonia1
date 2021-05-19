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
                  <div class="form-group col-md-2">
                    <label for="Siembra">Contenedor:</label>
                    <select class="form-control" id="f_siembra" v-model="f_contenedor">
                      <option value="-1" selected>Seleccionar</option>                             
                      <option :value="lc.id" v-for="(lc, index) in listadoContenedores" :key="index">{{lc.contenedor}}</option>
                    </select>
                  </div>
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
                      name    = "informe-parametros-calidad-agua.xls"
                      type    = "xls">
                        <i class="fa fa-fw fa-download"></i> Exportar Excel 
                    </downloadexcel>
                  </div>
                </div>
              </div>
            </div>
            <div class="table-container" id="table-container2">
              <table class="table-sticky table table-sm table-hover table-bordered">
                <thead class="thead-primary">
                  <tr>                    
                    <th rowspan="2" data-field="id">#</th>                    
                    <th rowspan="2">ID registro</th>
                    <th rowspan="2" data-field="id">Contenedor</th>      
                    <th rowspan="2" data-field="id">Fecha</th>      
                    <th colspan="5" class="text-center">% Saturación de oxígeno</th>
                    <th rowspan="2" data-field="id">Temperatura</th>
                    <th rowspan="2" data-field="id">PH</th>
                    <th rowspan="2" data-field="id">Amonio</th>
                    <th rowspan="2" data-field="id">Nitrito</th>
                    <th rowspan="2" data-field="id">Nitrato</th>
                    <th rowspan="2" data-field="id">Otros</th>
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
                    <th v-text="index+1"></th>
                    <th v-text="lp.id"></th>
                    <td v-text="lp.contenedor"></td>
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
                  </tr>
                  <tr class="bg-secondary text-white">
                    <th colspan="4">PROMEDIO:</th>
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
        </div>
      </div>
    </div>
    
  </div>
</template>

<script>
  import downloadexcel from "vue-json-excel";
  import { Form, HasError, AlertError } from 'vform'
  export default {
    data(){
    
      return {
        json_fields: {   
          '#' : 'id',
          'Fecha ' : 'fecha_parametro',
          'Contenedor' : 'contenedor',
          '12:00 a.m' : '12_am',
          '4:00 a.m' : '4_am',
          '7:00 a.m' : '7_am',
          '4:00 p.m' : '4_pm',
          '8:00 p.m' : '8_pm',
          'Temperatura' : 'temperatura', 
          'PH' : 'ph',
          'Amonio' : 'amonio',
          'Nitrito' : 'nitrito', 
          'Nitrato' : 'nitrato',
          'Otros' : 'otros'
          
        },     
        editando : 0,
        form : new Form({
          id : '',
          id_siembra : [],
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
        listadoExistencias : [],
        listadoContenedores:[],
        listadoEspecies : [],
        listadoSiembras: [],
        listadoParametros : [],
        promedios : [],
        f_contenedor: '',
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
        return this.listadoParametros;
      },
      filtrarParametros(){
        let me = this;
        if(this.f_contenedor == ''){this.f_c = '-1'}else{this.f_c = this.f_contenedor}
        if(this.f_inicio_d == ''){this.f_d = '-1'}else{this.f_d = this.f_inicio_d}
        if(this.f_inicio_h == ''){this.f_h = '-1'}else{this.f_h = this.f_inicio_h}
        
        const data = {
            'id_contenedor' : this.f_c,
            'f_inicio_d' : this.f_d,
            'f_inicio_h' : this.f_h
        }
        axios.post("api/filtro-parametros", data)
        .then(response=>{
          me.listadoParametros = response.data.calidad_agua;
          me.promedios = response.data.promedios
        })
        
      },
      listar(){
        let me = this;      
        this.listarParametros();
        this.listarSiembras();
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
       listarContenedores(){
        let me = this;
        axios.get("api/contenedores")
        .then(function (response) {
            me.listadoContenedores = response.data
        });
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
              me.listar();
              
            })
          }
        });
      }
    },
    mounted() {
      this.listar();
    }
  }
</script>