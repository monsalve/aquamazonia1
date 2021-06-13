<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">Historial de costos de recursos</div>

          <div class="card-body">
            <div class="row mb-1">
              <div class="form-group">
                <label for="id_recurso">Filtrar Recurso</label>
                <select
                  name="id_recurso"
                  id="id_recurso"
                  class="custom-select"
                  v-model="id_recurso"
                  @click.prevent="listar(id_recurso)"
                >
                  <option value="">--Seleccionar--</option>
                  <option
                    :value="recurso.id"
                    v-for="recurso in listadoRecursos"
                    :key="recurso.id"
                  >
                    {{ recurso.recurso }}
                  </option>
                </select>
              </div>
              <div class="form-group col-md-2">
                <downloadexcel
                  class="btn btn-success"
                  :fetch="fetchData"
                  :fields="json_fields"
                  name="historial-costos-recursos.xls"
                  type="xls"
                >
                  <i class="fa fa-fw fa-download"></i> Generar Excel
                </downloadexcel>
              </div>
            </div>
            <div class="row">
              <table class="table table-hover table-sm table-bordered">
                <thead class="thead-primary">
                  <tr>
                    <th scope="col">#</th>
                    <th>Fecha registro</th>
                    <th scope="col">Recurso</th>
                    <th scope="col">Costo Kl</th>
                    <th scope="col">Opciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(recurso, index) in listado" :key="index">
                    <th scope="row" v-text="index + 1"></th>
                    <th v-text="recurso.fecha_registro"></th>
                    <td v-text="recurso.recurso"></td>
                    <td v-text="recurso.costo"></td>
                    <td>
                      <button
                        @click="eliminar(recurso.id)"
                        class="btn btn-danger"
                        type="button"
                      >
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
  </div>
</template>

<script>
import downloadexcel from "vue-json-excel";
import Vue from "vue";
import { Form, HasError, AlertError } from "vform";

Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);

export default {
  data() {
    return {
      json_fields: {
        "Fecha Registro": "fecha_registro",
        'Recurso': "recurso",
        'Costo': "costo",
      },
      editando: 0,
      form: new Form({
        id: "",
        recurso: "",
        costo: "",
      }),
      id_recurso: "",
      listado: [],
      listadoCostos: [],
      listadoRecursos: [],
    };
  },
  components: {
    downloadexcel,
  },
  methods: {
    async fetchData() {
      let me = this;
      const response = await this.listado;
      return this.listado;
    },
    listar(idRecurso) {
      let me = this;
      axios
        .get("api/historial-recursos-costos?idRecurso=" + idRecurso)
        .then(function (response) {
          me.listado = response.data;
        });
    },
    listarRecursos() {
      let me = this;
      axios.get("api/recursos").then(function (response) {
        me.listadoRecursos = response.data;
      });
    },

    eliminar(index) {
      let me = this;
      Swal.fire({
        title: "Estás seguro?",
        text: "Una vez eliminado, no se puede recuperar este registro",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#c7120c",
        cancelButtonText: "Cancelar",
        confirmButtonText: "Aceptar!",
        reverseButtons: true,
        dangerMode: true,
      }).then((result) => {
        if (result.isConfirmed) {
          me.form
            .delete("api/historial-recursos-costos/" + index)
            .then(({ data }) => {
              me.listar("");
            });
        }
      });
    },
  },
  mounted() {
    this.listar("");
    this.listarRecursos();
  },
};
</script>
