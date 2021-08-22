<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">Informes Actividades (Muestreo y Pesca)</div>
          <!-- <a href="informe-excel"><button type="submit" class="btn btn-success" name="infoSiembras"><i class="fa fa-fw fa-download"></i> Generar Excel </button></a> -->
          <div class="card-body">
            <div class="row mb-1">
              <div class="col-md-12">
                <h2>Filtrar por:</h2>
                <form
                  class="row"
                  method="POST"
                  action="informe-excel"
                  target="_blank"
                >
                  <div class="form-group col-md-2">
                    <label for="contenedor">Contenedor:</label>
                    <select
                      class="custom-select"
                      id="contenedor"
                      v-model="id_contenedor"
                    >
                      <option value="-1">Seleccionar</option>
                      <option
                        :value="cont.id"
                        v-for="(cont, index) in listadoContenedores"
                        :key="index"
                      >
                        {{ cont.contenedor }}
                      </option>
                    </select>
                  </div>
                  <div class="form-group col-md-2">
                    <label for="Siembra">Siembra:</label>
                    <select
                      class="form-control"
                      id="f_siembra"
                      v-model="f_siembra"
                    >
                      <option value="-1" selected>Seleccionar</option>
                      <option
                        :value="ls.id"
                        v-for="(ls, index) in listadoSiembras"
                        :key="index"
                      >
                        {{ ls.nombre_siembra }}
                      </option>
                    </select>
                  </div>
                  <div class="form-group col-md-2">
                    <label for="f_estado">
                      Estado siembra:
                      <select
                        class="custom-select"
                        name="estado"
                        id="estado"
                        v-model="f_estado"
                      >
                        <option value="-1" disabled>--Seleccionar--</option>
                        <option value="0">Inactiva</option>
                        <option value="1">Activa</option>
                      </select>
                    </label>
                  </div>
                  <div class="form-group col-md-2">
                    <label for="lote">Lotes:</label>
                    <select class="custom-select" id="lote" v-model="f_lote">
                      <option value="-1">Seleccionar</option>
                      <option
                        :value="lote.lote"
                        v-for="(lote, index) in listadoLotes"
                        :key="index"
                      >
                        {{ lote.lote }}
                      </option>
                    </select>
                  </div>
                  <div class="form-group col-md-2">
                    <label for="Especie">Especie</label>
                    <select
                      class="form-control"
                      id="f_especie"
                      v-model="f_especie"
                    >
                      <option value="-1" selected>Seleccionar</option>
                      <option
                        :value="especie.id"
                        v-for="especie in listadoEspecies"
                        :key="especie.id"
                      >
                        {{ especie.especie }}
                      </option>
                    </select>
                  </div>
                  <div class="form-group col-md-2">
                    <label for="actividad">Tipo actividad: </label>
                    <select
                      class="form-control"
                      id="actividad"
                      v-model="f_actividad"
                      name="tipo_actividad"
                    >
                      <option selected value="">Seleccionar</option>
                      <option value="0">Muestreo</option>
                      <option value="1">Pesca</option>
                      <option value="2">Mortalidad Inicial</option>
                      <option value="3">Peso Inicial</option>
                    </select>
                  </div>
                  <div class="form-group col-md-2">
                    <label for="peso desde">peso desde (gr): </label>
                    <input
                      type="number"
                      step="any"
                      class="form-control"
                      id="f_peso_d"
                      v-model="f_peso_d"
                    />
                  </div>
                  <div class="form-group col-md-2">
                    <label for="peso hasta">peso hasta (gr): </label>
                    <input
                      type="number"
                      step="any"
                      class="form-control"
                      id="f_peso_h"
                      v-model="f_peso_h"
                    />
                  </div>
                  <div class="form-group col-md-2">
                    <label for="search">Desde: </label>
                    <input
                      class="form-control"
                      type="date"
                      placeholder="Search"
                      aria-label="f_fecha_d"
                      v-model="f_fecha_d"
                    />
                  </div>
                  <div class="form-group col-md-2">
                    <label for="search">Hasta: </label>
                    <input
                      class="form-control"
                      type="date"
                      placeholder="Search"
                      aria-label="f_fecha_h"
                      v-model="f_fecha_h"
                    />
                  </div>
                  <div class="form-group col-md-1">
                    <label for="">Buscar</label>
                    <button
                      class="btn btn-primary form-control"
                      type="button"
                      @click="filtroResultados()"
                    >
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                  <div class="form-group col-md-2">
                    <downloadexcel
                      class="btn btn-success"
                      :fetch="fetchData"
                      :fields="json_fields"
                      name="informe-muestreos.xls"
                      type="xls"
                    >
                      <i class="fa fa-fw fa-download"></i> Generar Excel
                    </downloadexcel>
                  </div>
                </form>
              </div>
            </div>
            <div class="table-container" id="table-container2">
              <table
                class="table-sticky table table-sm table-hover table-bordered"
              >
                <thead class="thead-primary">
                  <tr>
                    <th>#</th>
                    <th>Siembra</th>
                    <th>Lote</th>
                    <th>Fecha de registro</th>
                    <th>Especie</th>
                    <th>Tipo <br />actividad</th>
                    <th>Peso actual</th>
                    <th>KG cosecha</th>
                    <th>Biomasa muestreo</th>
                    <th>Cantidad Actual</th>
                    <th>Biomasa disponible por alimento</th>
                    <th>Cantidad Animales</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(lr, index) in listadoRegistros" :key="index">
                    <td v-text="index + 1"></td>
                    <td v-text="lr.nombre_siembra"></td>
                    <td v-text="lr.lote"></td>
                    <td v-text="lr.fecha_registro"></td>
                    <td v-text="lr.especie"></td>
                    <td v-text="lr.nombre_registro"></td>
                    <td v-text="lr.peso_ganado"></td>
                    <td v-text="lr.biomasa"></td>
                    <td v-text="lr.biomasa_disponible"></td>
                    <td v-text="lr.cantidad_actual"></td>
                    <td v-text="lr.bio_dispo_alimen"></td>
                    <td v-text="lr.salida_animales"></td>
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
  data() {
    return {
      json_fields: {
        Siembra: "nombre_siembra",
        Lote: "lote",
        "Fecha de registro": "fecha_registro",
        Especie: "especie",
        "Tipo actividad": "nombre_registro",
        "Peso Actual": "peso_ganado",
        "KG cosecha": "biomasa",
        "biomasa muestreo": "biomasa_disponible",
        "Cantidad Actual": "cantidad_actual",
        "Biomasa disponible": "bio_dispo_alimen",
        "Cantidadad Animales": "salida_animales",
      },

      listadoSiembras: [],
      listadoRegistros: [],
      listadoEspecies: [],
      listadoLotes: [],
      listadoContenedores: [],
      // filtros
      f_siembra: "",
      f_lote: "",
      f_estado: "",
      f_especie: "",
      f_actividad: "",
      f_fecha_d: "",
      f_fecha_h: "",
      f_peso_d: 0,
      f_peso_h: 0,
      id_contenedor: 0,
    };
  },
  components: {
    downloadexcel,
  },
  methods: {
    async fetchData() {
      let me = this;
      const response = await this.listadoRegistros;
      return this.listadoRegistros;
    },
    listar() {
      let me = this;
      me.listarSiembras();
      me.listarRegistros();
      me.listarEspecies();
      me.listarLotes();
      me.listarContenedores();
    },
    listarSiembras() {
      let me = this;
      axios.get("api/siembras").then(function (response) {
        me.listadoSiembras = response.data.listado_siembras;
      });
    },
    listarRegistros() {
      let me = this;
      axios.get("api/informes-registros").then(function (response) {
        me.listadoRegistros = response.data;
      });
    },
    listarEspecies() {
      let me = this;
      axios.get("api/especies").then(function (response) {
        me.listadoEspecies = response.data;
      });
    },
    listarLotes() {
      let me = this;
      axios.get("api/listadoLotes").then(function (response) {
        me.listadoLotes = response.data;
      });
    },
    filtroResultados() {
      let me = this;

      if (this.f_siembra == "") {
        this.smb = "-1";
      } else {
        this.smb = this.f_siembra;
      }
    
      if (this.f_estado == "") {
        this.est = "-1";
      } else {
        this.est = this.f_estado;
      }
      if (this.f_lote == "") {
        this.lot = "-1";
      } else {
        this.lot = this.f_lote;
      }
      if (this.f_especie == "") {
        this.f_e = "-1";
      } else {
        this.f_e = this.f_especie;
      }
      if (this.f_actividad == "") {
        this.act = "-1";
      } else {
        this.act = this.f_actividad;
      }
      if (this.f_peso_d == "") {
        this.pesod = "-1";
      } else {
        this.pesod = this.f_peso_d;
      }
      if (this.f_peso_h == "") {
        this.pesoh = "-1";
      } else {
        this.pesoh = this.f_peso_h;
      }
      if (this.f_fecha_d == "") {
        this.fec1 = "-1";
      } else {
        this.fec1 = this.f_fecha_d;
      }
      if (this.f_fecha_h == "") {
        this.fec2 = "-1";
      } else {
        this.fec2 = this.f_fecha_h;
      }

      const data = {
        f_siembra: this.smb,
        f_estado: this.est,
        f_lote: this.lot,
        f_especie: this.f_e,
        f_actividad: this.act,
        f_peso_d: this.pesod,
        f_peso_h: this.pesoh,
        f_fecha_d: this.fec1,
        f_fecha_h: this.fec2,
        id_contenedor : this.id_contenedor
      };
      axios.post("api/filtro-registros-siembras", data).then((response) => {
        me.listadoRegistros = response.data;
      });
    },
    listarContenedores() {
      let me = this;
      axios.get("api/contenedores").then(function (response) {
        me.listadoContenedores = response.data;
      });
    },
  },
  mounted() {
    this.listar();
  },
};
</script>