<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Informes ciclo productivo</div>
                      <!-- <a href="informe-excel"><button type="submit" class="btn btn-success" name="infoSiembras"><i class="fa fa-fw fa-download"></i> Generar Excel </button></a> -->                    
                    <div class="card-body">   
                      <div class="row text-left">
                        <h5>Filtrar por: </h5>
                      </div>
                      <div class="row">
                        <div class="form-group col-md-2">
                          <label for="f_estado">
                            Estado:
                            <select class="custom-select" name="estado" id="estado" v-model="f_estado">
                              <option value="-1">--Seleccionar--</option>
                              <option value="0">Inactiva</option>
                              <option value="1">Activa</option>
                            </select>
                          </label>
                        </div>
                        <div class="form-group col-3" v-show="f_estado==1">
                          <label for="siembra_activa">Siembras Activas</label>
                          <select name="siembra_activa" class="custom-select" id="siembra_activa" v-model="f_siembra">
                            <option v-for="(siembraActiva, index) in siembrasActivas" :key="index"  :value="siembraActiva.id">{{siembraActiva.nombre_siembra}}</option>
                          </select>
                        </div>
                        <div class="form-group col-3" v-show="f_estado==0">
                          <label for="siembra_inactiva">Siembras Inactivas</label>
                          <select name="siembra_inactiva" class="custom-select" id="siembra_inactiva"  v-model="f_siembra">
                            <option v-for="(siembraInactiva, index) in siembrasInactivas" :key="index" :value="siembraInactiva.id">{{siembraInactiva.nombre_siembra}}</option>
                          </select>
                        </div>
                        <div class="form-group col-md-2">
                          <label for="lote">Lotes:</label>
                          <select class="custom-select" id="lote" v-model="f_lote">
                            <option value="-1">Seleccionar</option>
                            <option :value="lote.lote" v-for="(lote, index) in listadoLotes" :key="index">{{lote.lote}}</option>                        
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
                          <label for="peso desde">peso desde (gr): </label>
                          <input type="number" step="any" class="form-control" id="f_peso_d" v-model="f_peso_d">                          
                        </div>
                        <div class="form-group col-md-2">
                          <label for="peso hasta">peso hasta (gr): </label>
                          <input type="number" step="any" class="form-control" id="f_peso_h" v-model="f_peso_h">
                        </div>
                        <div class="form-group col-md-2">
                          <label for="Fecha desde">Fecha inicio desde: </label>
                          <input type="date" class="form-control" id="f_inicio_d" v-model="f_inicio_d">
                        </div>
                        <div class="form-group col-md-2">
                          <label for="fecha hasta">Fecha inicio hasta: </label>
                          <input type="date" class="form-control" id="f_inicio_h" v-model="f_inicio_h">
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
                          name    = "informe-ciclo-productivo.xls"
                          type    = "xls">
                            <i class="fa fa-fw fa-download"></i> Generar Excel 
                          </downloadexcel>
                        </div>
                      </div>
                      <div>
                        <table class="table table-bordered table-striped table-sm table-sticky">
                          <thead class="thead-primary">
                            <tr>                           
                              <th>#</th>
                              <th class="fixed-column">Siembra</th>
                              <th>Lote</th>
                              <th>Area</th>
                              <th>Especie</th>
                              <th>Inicio siembra</th>                             
                              <th>Cant Inicial</th>
                              <th>Peso Inicial</th>
                              <th>Cant Actual</th>
                              <th>Peso Actual</th>   
                               <th>Fecha último registro</th>
                              <th>Tiempo de cultivo</th>
                              <th>Biomasa dispo</th>
                              <th>Salida de biomasa</th>
                              <th>Mortalidad</th>                              
                              <th>Mort. Kg</th>
                              <th>% Mortalidad</th>
                              <th>Salida animales</th>
                              <th>Incremento de biomasa</th>
                              <th>Ganancia de peso por día</th>
                              <th>Densidad Final (Animales/m<sup>2</sup>)</th>
                              <th>Carga Final (Kg/m<sup>2</sup>)</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(le, index) in listadoExistencias" :key="index">                              
                              <td v-text="index+1"></td>
                              <td v-text="le.nombre_siembra" class="fixed-column"></td>
                              <td v-text="le.lote"></td>
                              <td v-text="le.capacidad"></td>
                              <td v-text="le.especie"></td>
                              <td v-text="le.fecha_inicio"></td>                              
                              <td v-text="le.cantidad_inicial"></td>
                              <td v-text="le.peso_inicial+' gr'"></td>
                              <td v-text="le.cantidad_actual"></td>
                              <td v-text="le.peso_actual+' gr'"></td>   
                              <td v-text="le.fecha_registro"></td>
                              <td v-if="le.intervalo_tiempo">{{le.intervalo_tiempo}} días</td>
                              <td v-else>0</td>
                              <td v-text="le.biomasa_disponible+' kg'"></td> 
                              <td v-if="le.salida_biomasa">{{le.salida_biomasa}} kg</td>
                              <td v-else>0</td>                                          
                              <td v-if="le.mortalidad">{{le.mortalidad}}</td>
                              <td v-else>0</td>
                              <td v-text="le.mortalidad_kg ? le.mortalidad_kg +' kg' : '0'"></td>
                              <td v-if="le.mortalidad_porcentaje">{{le.mortalidad_porcentaje}}</td>
                              <td v-else>0</td>
                              <td v-if="le.salida_animales">{{le.salida_animales}}</td>
                              <td v-else>0</td>
                              <td v-text="le.incremento_biomasa"></td>
                              <td v-text="le.ganancia_peso_dia"></td>
                              <td v-text="le.densidad_final"></td>
                              <td v-text="le.carga_final"></td>
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
          'Lote' : 'lote',
          'Area' : 'capacidad',
          'Especie' : 'especie',
          'Inicio siembra' : 'fecha_inicio',
          'Cantidad Inicial' : 'cantidad_inicial',
          'Peso inicial' : 'peso_inicial',
          'Cantidad actual' : 'cant_actual',
          'Peso actual' : 'peso_actual',
          'Fecha último registro' : 'fecha_registro',
          'Tiempo de cultivo' : 'intervalo_tiempo',
          'Biomasa disponible' : 'biomasa_disponible',
          'Salida de biomasa' : 'salida_biomasa',
          'Mortalidad' : 'mortalidad',
          'Mortalidad kg' : 'mortalidad_kg',
          '% Mortalidad' : 'mortalidad_porcentaje',
          'Salida animales' : 'salida_animales',
          'Incremento de biomasa': 'incremento_biomasa',
          'Gananacia de peso por día': 'ganancia_peso_dia',
          'Densidad final (Animales/m2)' : 'densidad_final',
          'Carga final (Kg/m2)' : 'carga_final'
        },       
        listadoExistencias : [],
        listadoEspecies : [],
        listadoSiembras: [], 
        imprimirRecursos:[],
        listadoLotes : [],
        siembrasActivas: [],
        siembrasInactivas: [],
        f_siembra : '',
        f_estado : '1',
        f_lote : '',
        f_especie: '', 
        f_inicio_d : '',
        f_inicio_h : '',
        f_peso_d : 0,
        f_peso_h : 0,
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
        this.listarLotes();
        axios.get("api/traer-existencias")
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
      listarSiembras(estado_siembra){
        let me = this;
        axios.get('api/siembras?estado_siembra='+estado_siembra)
        .then(function (response){
          me.siembrasActivas = response.data.listado_siembras;
          me.siembrasInactivas = response.data.listado_siembras_inactivas;
        })
      },
      listarLotes(){
        let me = this;
        axios.get("api/listadoLotes")
        .then(function (response){
          me.listadoLotes = response.data;
        })
      },
      filtroCiclo(){
        let me = this;
        
        if(this.f_siembra == ''){this.smb = '-1'}else{this.smb = this.f_siembra}
        if(this.f_estado == ''){this.est = '-1'}else{this.est = this.f_estado}
        if(this.f_lote == ''){this.lot = '-1'}else{this.lot = this.f_lote}
        if(this.f_especie == ''){this.esp = '-1'}else{this.esp = this.f_especie}
        if(this.f_inicio_d == ''){this.fecd = '-1'}else{this.fecd = this.f_inicio_d}
        if(this.f_inicio_h == ''){this.fech = '-1'}else{this.fech = this.f_inicio_h}
        if(this.f_peso_d == ''){this.pesod = '-1'}else{this.pesod = this.f_peso_d}
        if(this.f_peso_h == ''){this.pesoh = '-1'}else{this.pesoh = this.f_peso_h}
        
        const data ={
          'f_siembra' : this.smb,
          'f_estado' : this.est,
          'f_lote' : this.lot,
          'f_especie' : this.esp,
          'f_inicio_d' : this.fecd,
          'f_inicio_h' : this.fech,
          'f_peso_d' : this.pesod,
          'f_peso_h' : this.pesoh
        }
        axios.post("api/filtro-ciclos", data)
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
