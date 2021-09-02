<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">Informes consolidado de alimentos</div>
          <div class="card-body">
            <div class="row">
              <div class="form-row col-12">
                <div class="form-group col-3">
                  <label for="estado_siembra">Estado de siembra</label>
                  <select
                    name="estado_siembra"
                    class="custom-select"
                    id="estado_siembra"
                    v-model="estado_siembra"
                    @click.prevent="listar(1, '', estado_siembra, '', '')"
                  >
                    <option value="-1">Todas</option>
                    <option value="1">Activas</option>
                    <option value="0">Inactivas</option>
                  </select>
                </div>
                <div class="form-group col-3" v-show="estado_siembra == 1">
                  <label for="estado_siembta">Siembras Activas</label>
                  <select
                    name="estado_siembra"
                    class="custom-select"
                    id="estado_siembra"
                    v-model="siembra_activa"
                    @click.prevent="listar(1, siembra_activa, '', '', '')"
                  >
                    <option value="">Todas</option>
                    <option
                      v-for="(siembraActiva, index) in siembrasActivas"
                      :key="index"
                      :value="siembraActiva.id"
                    >
                      {{ siembraActiva.nombre_siembra }}
                    </option>
                  </select>
                </div>
                <div class="form-group col-3" v-show="estado_siembra == 0">
                  <label for="estado_siembta">Siembras Inactivas</label>
                  <select
                    name="estado_siembra"
                    class="custom-select"
                    id="estado_siembra"
                    v-model="siembra_inactiva"
                    @click.prevent="listar(1, siembra_inactiva, '', '', '')"
                  >
                    <option value="">Todas</option>
                    <option
                      v-for="(siembraInactiva, index) in siembrasInactivas"
                      :key="index"
                      :value="siembraInactiva.id"
                    >
                      {{ siembraInactiva.nombre_siembra }}
                    </option>
                  </select>
                </div>
                <div class="form-group col-md-2">
                  <label for="alimento">Alimento: </label>
                  <select
                    class="form-control"
                    id="alimento"
                    v-show="estado_siembra == 1"
                    v-model="alimento_s"
                    @click.prevent="
                      listar(1, siembra_activa, '', alimento_s, '')
                    "
                  >
                    <option selected>Seleccionar</option>
                    <option
                      v-for="(alimento, index) in listadoAlimentos"
                      :key="index"
                      v-bind:value="alimento.id"
                    >
                      {{ alimento.alimento }}
                    </option>
                  </select>
                  <select
                    class="form-control"
                    id="alimento"
                    v-show="estado_siembra == 0"
                    v-model="alimento_s"
                    @click.prevent="
                      listar(1, siembra_inactiva, '', alimento_s, '')
                    "
                  >
                    <option value="-1" selected>Seleccionar</option>
                    <option
                      v-for="(alimento, index) in listadoAlimentos"
                      :key="index"
                      v-bind:value="alimento.id"
                    >
                      {{ alimento.alimento }}
                    </option>
                  </select>
                </div>
                <div class="form-group col-md-2">
                  <label for="contenedor">Contenedor:</label>
                  <select
                    class="custom-select"
                    id="contenedor"
                    v-model="id_contenedor"
                    @click.prevent="
                      listar(1, '', '', '', id_contenedor)
                    "
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
              </div>

              <div class="col-md-2">
                <downloadexcel
                  class="btn btn-success form-control"
                  :fetch="fetchData"
                  :fields="json_fields"
                  name="informe-consolidado-alimentos.xls"
                  type="xls"
                >
                  <i class="fa fa-fw fa-download"></i> Generar Excel
                </downloadexcel>
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
                    <th>Estado</th>
                    <th>Tipo actividad</th>
                    <th>Alimento</th>
                    <th>Costo Kg</th>
                    <th>Alimento Mañana</th>
                    <th>Alimento Tarde</th>
                    <th>Cantidad Total Alimento</th>
                    <th>% Cantidad Alimento</th>
                    <th>Costo Alimento</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(lrn, index) in listado" :key="index">
                    <td v-text="index + 1"></td>
                    <td v-text="lrn.nombre_siembra"></td>
                    <td v-if="lrn.estado == 1">Activa</td>
                    <td v-else>Inactiva</td>
                    <td>
                      {{
                        lrn.tipo_actividad == 1
                          ? (lrn.actividad = "Alimentacion")
                          : ""
                      }}
                    </td>
                    <td>{{ lrn.alimento }}</td>
                    <td>$ {{ lrn.costoUnitarioAlimento }}</td>
                    <td>{{ lrn.c_manana }}</td>
                    <td>{{ lrn.c_tarde }}</td>
                    <th v-text="lrn.cantidadTotalAlimento"></th>
                    <td
                      class="text-right"
                      v-text="lrn.porcCantidadAlimento + '%'"
                    ></td>
                    <th class="text-right">$ {{ lrn.costoAlimento }}</th>
                  </tr>
                </tbody>
              </table>
              <nav class="mt-5 navigation">
                <ul class="pagination justify-content-center">
                  <li class="page-item" v-if="pagination.current_page > 1">
                    <a
                      class="page-link"
                      href="#"
                      @click.prevent="
                        cambiarPagina(pagination.current_page - 1)
                      "
                      >Ant</a
                    >
                  </li>
                  <li
                    class="page-item"
                    v-for="page in pagesNumber"
                    :key="page"
                    :class="[page == isActived ? 'active' : '']"
                  >
                    <a
                      class="page-link"
                      href="#"
                      @click.prevent="cambiarPagina(page)"
                      v-text="page"
                    ></a>
                  </li>
                  <li
                    class="page-item"
                    v-if="pagination.current_page < pagination.last_page"
                  >
                    <a
                      class="page-link"
                      href="#"
                      @click.prevent="
                        cambiarPagina(pagination.current_page + 1)
                      "
                      >Sig</a
                    >
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Form, HasError, AlertError } from "vform";
import downloadexcel from "vue-json-excel";
export default {
  data() {
    return {
      json_fields: {
        Siembra: "nombre_siembra",
        Estado: "estado",
        "Tipo actividad": "actividad",
        Alimento: "alimento",
        "Costo Kg": "costoUnitarioAlimento",
        "Alimento Mañana": "c_manana",
        "Alimento Tarde": "c_tarde",
        "Cantidad total alimento": "cantidadTotalAlimento",
        "% Cantidad Alimento": "porcCantidadAlimento",
        "Costo total alimento": "costoAlimento",
      },
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
      },
      offset: 10,
      tipoActividad: "",
      alimento_s: "",
      f_siembra: "",
      id_contenedor: 0,
      listado: [],
      listadoSiembras: [],
      listadoAlimentos: [],
      listadoContenedores: [],

      estado_siembra: "1",
      siembra_activa: "",
      siembra_inactiva: "",
      siembrasActivas: [],
      siembrasInactivas: [],
    };
  },
  components: {
    downloadexcel,
  },
  computed: {
    isActived: function () {
      return this.pagination.current_page;
    },
    //Calcula los elementos de la paginación
    pagesNumber: function () {
      if (!this.pagination.to) {
        return [];
      }

      var from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }

      var to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }

      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    },
  },
  methods: {
    async fetchData() {
      let me = this;
      const response = await this.listado;
      return this.listado;
    },
    listar(page, id_siembra, estado_siembra, id_alimento, id_contenedor) {
      let me = this;
      axios
        .get(
          "api/informes-alimentos?page=" +
            page +
            "&id_siembra=" +
            id_siembra +
            "&estado_siembra=" +
            estado_siembra +
            "&id_alimento=" +
            id_alimento +
            "&id_contenedor=" +
            id_contenedor
        )
        .then(function (response) {
          me.listado = response.data.recursosNecesarios.data;
          me.pagination = response.data.pagination;
        });
    },
    listarSiembras(estado_siembra) {
      let me = this;
      axios
        .get("api/siembras?estado_siembra=" + estado_siembra)
        .then(function (response) {
          me.siembrasActivas = response.data.listado_siembras;
          me.siembrasInactivas = response.data.listado_siembras_inactivas;
        });
    },
    listarAlimentos() {
      let me = this;
      axios.get("api/alimentos").then(function (response) {
        me.listadoAlimentos = response.data;
      });
    },
    listarContenedores() {
      let me = this;
      axios.get("api/contenedores").then(function (response) {
        me.listadoContenedores = response.data;
      });
    },
    cambiarPagina(page) {
      let me = this;
      //Actualiza la página actual
      me.pagination.current_page = page;
      me.listar(page);
    },
  },
  mounted() {
    this.listar(1, "", -1, "", "");
    this.listarSiembras(-1);
    this.listarAlimentos();
    this.listarContenedores();
  },
};
</script>
