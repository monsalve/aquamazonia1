<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">Historial de costos de alimentos</div>

          <div class="card-body">
            <div class="row mb-1">
              <div class="form-group">
                <label for="id_alimento">Filtrar Alimento</label>
                <select
                  name="id_alimento"
                  id="id_alimento"
                  class="custom-select"
                  v-model="id_alimento"
                  @click.prevent="listar(id_alimento)"
                >
                  <option value="">--Seleccionar--</option>
                  <option
                    :value="alimento.id"
                    v-for="alimento in listadoAlimentos"
                    :key="alimento.id"
                  >
                    {{ alimento.alimento }}
                  </option>
                </select>
              </div>
              <div class="form-group col-md-2">
                <downloadexcel
                  class="btn btn-success"
                  :fetch="fetchData"
                  :fields="json_fields"
                  name="historial-costos-alimentos.xls"
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
                    <th scope="col">Alimento</th>
                    <th scope="col">Costo Kl</th>
                    <th scope="col">Opciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(alimento, index) in listado" :key="index">
                    <th scope="row" v-text="index + 1"></th>
                    <th v-text="alimento.fecha_registro"></th>
                    <td v-text="alimento.alimento"></td>
                    <td v-text="alimento.costo"></td>
                    <td>
                      <button
                        @click="eliminar(alimento.id)"
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
        Alimento: "alimento",
        Costo: "costo",
      },
      editando: 0,
      form: new Form({
        id: "",
        alimento: "",
        costo: "",
      }),
      id_alimento: "",
      listado: [],
      listadoCostos: [],
      listadoAlimentos: [],
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
    listar(idAlimento) {
      let me = this;
      axios
        .get("api/historial-alimentos-costos?idAlimento=" + idAlimento)
        .then(function (response) {
          me.listado = response.data;
        });
    },
    listarAlimentos() {
      let me = this;
      axios.get("api/alimentos").then(function (response) {
        me.listadoAlimentos = response.data;
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
            .delete("api/historial-alimentos-costos/" + index)
            .then(({ data }) => {
              me.listar("");
            });
        }
      });
    },
  },
  mounted() {
    this.listar("");
    this.listarAlimentos();
  },
};
</script>
