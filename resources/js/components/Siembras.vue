<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">Gestión de Siembras</div>

          <div class="card-body">
            <div class="row mb-1">
              <div class="col-12 text-right">
                <button class="btn btn-success" @click="anadirItem()">
                  Nueva siembra
                </button>
              </div>
            </div>
            <div>
              <div class="row">
                <div class="form-row col-12">
                  <div class="form-group col-3">
                    <label for="estado_siembta">Estado de siembra</label>
                    <select
                      name="estado_siembra"
                      class="custom-select"
                      id="estado_siembra"
                      v-model="estado_siembra"
                      @click.prevent="filtrarSiembras(estado_siembra, '')"
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
                      @click.prevent="filtrarSiembras(1, siembra_activa)"
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
                      @click.prevent="filtrarSiembras(0, siembra_inactiva)"
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
                </div>
              </div>
              <table
                class="table table-bordered table-striped table-sm table-sticky"
              >
                <thead class="thead-primary">
                  <tr>
                    <th>#</th>
                    <th>
                      Nombre <br />
                      siembra
                    </th>
                    <th>Estanque</th>
                    <th
                      class="text-center d-sm-none d-none d-md-block"
                      style=""
                    >
                      <h5>Especie</h5>

                      <div
                        class="py-3"
                        style="width: max-content; margin: auto"
                      >
                        <span style="width: 80px; display: inline-block">
                          Especie
                        </span>
                        <span style="width: 80px; display: inline-block">
                          Lote
                        </span>
                        <span style="width: 80px; display: inline-block">
                          Cantidad
                        </span>
                        <span style="width: 60px; display: inline-block">
                          Peso gr
                        </span>
                      </div>
                    </th>
                    <th>Inicio siembra</th>

                    <th>Fecha Alimentación</th>
                    <th>Ingreso</th>

                    <th>Editar</th>
                    <th>Eliminar</th>
                    <th>Finalizar</th>
                    <th>
                      Inicio - fin de <br />
                      descanso estanque
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(siembra, index) in listadoSiembras"
                    :key="siembra.id"
                  >
                    <th v-text="index + 1" scope="row"></th>
                    <td v-text="siembra.nombre_siembra" scope="row"></td>
                    <td v-text="siembra.contenedor"></td>
                    <td class="d-sm-none d-none d-md-block">
                      <div
                        v-for="pez in siembra.peces"
                        :key="pez.id"
                        class="border-0"
                        style="width: max-content; margin: auto"
                      >
                        <span
                          v-text="pez.especie"
                          class="nav-item border-bottom"
                          style="width: 80px; display: inline-block"
                        >
                          Especie
                        </span>
                        <span
                          v-text="pez.lote"
                          class="nav-item border-bottom"
                          style="
                            width: 80px;
                            text-align: center;
                            display: inline-block;
                          "
                        >
                          Lote
                        </span>
                        <span
                          class="nav-item border-bottom"
                          style="
                            width: 80px;
                            text-align: center;
                            display: inline-block;
                          "
                        >
                          {{ Math.floor(pez.cant_actual) }}
                        </span>
                        <span
                          v-text="pez.peso_actual + 'Gr'"
                          class="nav-item border-bottom"
                          style="
                            width: 60px;
                            display: inline-block;
                            text-align: center;
                          "
                        >
                          Peso
                        </span>
                      </div>
                    </td>
                    <td v-text="siembra.fecha_inicio"></td>

                    <td>
                      <span
                        v-bind:class="[
                          fechaActual <= siembra.fecha_alimento
                            ? ''
                            : 'badge badge-warning',
                        ]"
                      >
                        {{ siembra.fecha_alimento }}
                      </span>
                      <br />
                      <button
                        type="button"
                        class="btn btn-success btn-sm"
                        @click="abrirCrear(siembra.id)"
                      >
                        Añadir Alimentos
                      </button>
                    </td>
                    <td>
                      <button
                        class="btn btn-primary"
                        @click="abrirIngreso(siembra.id)"
                      >
                        <i class="fas fa-list-ul"></i>
                      </button>
                    </td>

                    <td>
                      <button
                        class="btn btn-success"
                        @click="editarSiembra(siembra)"
                      >
                        <i class="fas fa-edit"></i>
                      </button>
                    </td>
                    <td>
                      <button
                        class="btn btn-danger"
                        @click="eliminarSiembra(siembra.id)"
                      >
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
                    <td>
                      <button
                        class="btn btn-warning"
                        data-toggle="tooltip"
                        title="Finalizar siembra"
                        data-placement="top"
                        @click="finalizarSiembra(siembra.id)"
                      >
                        <i class="fas fa-power-off"></i>
                      </button>
                    </td>
                    <td>
                      {{ siembra.ini_descanso }} - <br />
                      {{ siembra.fin_descanso }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal especies x siembras -->
    <div
      class="modal fade"
      id="modalSiembra"
      tabindex="-1"
      data-backdrop="static"
      role="dialog"
      aria-labelledby="modalSiembraLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalSiembralLabel">Crear siembra</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="container row">
              <div class="form-group row col-md-4">
                <div class="col-sm-12 col-md-12 text-left">
                  <label for="">Estanque disponible</label>
                  <div v-if="id_edita == ''">
                    <select
                      v-model="form.id_contenedor"
                      name="id_contenedor"
                      class="form-control"
                      id="id_contenedor"
                    >
                      <option
                        v-show="contenedor.estado == 1"
                        :value="contenedor.id"
                        v-for="(contenedor, index) in listadoEstanques"
                        :key="index"
                        selected
                      >
                        {{ contenedor.contenedor }}
                      </option>
                    </select>
                  </div>
                  <div v-else>
                    <select
                      disabled
                      v-model="form.id_contenedor"
                      name="id_contenedor"
                      class="form-control"
                      id="id_contenedor"
                    >
                      <option
                        :value="contenedor.id"
                        v-for="(contenedor, index) in listadoEstanques"
                        :key="index"
                        selected
                      >
                        {{ contenedor.contenedor }}
                      </option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group row col-md-4">
                <div class="col-sm-12 col-md-12 text-left">
                  <label for="nombre_siembra">Nombre de Siembra</label>
                  <!-- <input class="form-control" type="text" id="nombre_siembra" v-model="form.nombre_siembra"> -->
                  <input
                    v-if="id_edita == ''"
                    class="form-control"
                    type="text"
                    id="nombre_siembra"
                    v-model="form.nombre_siembra"
                  />
                  <input
                    v-else
                    disabled
                    class="form-control"
                    type="text"
                    id="nombre_siembra"
                    v-model="form.nombre_siembra"
                  />
                </div>
              </div>
              <div class="form-group row col-md-4">
                <div class="col-sm-12 col-md-12 text-left">
                  <label for="">Fecha Inicio</label>
                  <input
                    v-if="id_edita == ''"
                    type="date"
                    class="form-control"
                    id="fecha_inicio"
                    v-model="form.fecha_inicio"
                    required
                  />
                  <input
                    v-else
                    type="date"
                    class="form-control"
                    id="fecha_inicio"
                    v-model="form.fecha_inicio"
                    disabled
                  />
                </div>
              </div>
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col" style="width: 20%">Especie</th>
                    <th scope="col">Lote</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Peso gr</th>
                    <th scope="col">Añadir</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row" v-text="form.id"></th>
                    <td>
                      <select
                        v-model="newEspecie"
                        name=""
                        class="form-control"
                        id="id_especie"
                        required
                      >
                        <option
                          :value="especie.id"
                          v-for="especie in listadoEspecies"
                          :key="especie.id"
                          selected
                        >
                          {{ especie.especie }}
                        </option>
                      </select>
                    </td>
                    <td>
                      <input
                        type="text"
                        min="0"
                        class="form-control"
                        id="lote"
                        v-model="newLote"
                        required
                      />
                    </td>
                    <td>
                      <input
                        type="number"
                        step="any"
                        min="0"
                        class="form-control"
                        id="cantidad"
                        v-model="newCantidad"
                        required
                      />
                    </td>
                    <td>
                      <input
                        type="number"
                        step="any"
                        min="0"
                        class="form-control"
                        id="peso_inicial"
                        v-model="newPeso"
                        required
                      />
                      <span
                        style="
                          position: relative;
                          float: right;
                          right: 30px;
                          color: #ccc;
                          bottom: 30px;
                        "
                        >Gr
                      </span>
                    </td>

                    <td>
                      <button
                        class="btn btn-success"
                        @click="anadirEspecie()"
                        type="button"
                      >
                        <i class="fas fa-plus"></i>
                      </button>
                    </td>
                  </tr>
                  <tr v-for="(item, index) in listadoItems" :key="index">
                    <th scope="row">{{ index + 1 }}</th>
                    <td v-text="nombresEspecies[item.id_especie]"></td>
                    <td>
                      <span v-if="id_edit_item == ''" v-text="item.lote"></span>
                      <input
                        v-if="id_edit_item == item.id_especie"
                        type="text"
                        class="form-control"
                        name="aux_lote"
                        id="aux_lote"
                        v-model="aux_lote"
                      />
                    </td>
                    <td>
                      <span
                        v-if="id_edit_item == ''"
                        v-text="item.cantidad"
                      ></span>
                      <input
                        type="number"
                        step="any"
                        class="form-control"
                        v-if="id_edit_item == item.id_especie"
                        name="aux_cantidad"
                        id="aux_cantidad"
                        v-model="aux_cantidad"
                      />
                    </td>
                    <td>
                      <span
                        v-if="id_edit_item == ''"
                        v-text="item.peso_inicial"
                      ></span>
                      <input
                        type="number"
                        step="any"
                        class="form-control"
                        v-if="id_edit_item == item.id_especie"
                        name="aux_peso_inicial"
                        id="aux_peso_inicial"
                        v-model="aux_peso_inicial"
                      />
                    </td>
                    <td>
                      <button
                        v-if="!item.es_edita"
                        @click="removeItem(item.id_especie)"
                        class="btn btn-danger"
                      >
                        X
                      </button>
                      <button
                        v-if="
                          item.es_edita &&
                          id_edit_item == '' &&
                          item.cantidad == item.cant_actual
                        "
                        @click="editItem(item)"
                        class="btn btn-primary"
                      >
                        <i class="fas fa-edit"></i>
                      </button>
                      <button
                        v-if="item.es_edita && id_edit_item == item.id_especie"
                        @click="guardaEditItem(item.id)"
                        class="btn btn-success"
                      >
                        <i class="fas fa-check"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="modal-footer">
              <div class="form-group row">
                <div class="col-sm-12 text-right">
                  <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal"
                  >
                    Cancelar
                  </button>
                  <button
                    v-if="id_edita == ''"
                    type="submit"
                    @click="guardar()"
                    class="btn btn-primary"
                  >
                    Crear
                  </button>
                  <button
                    v-else
                    type="button"
                    @click="guardarEdita(form.id_contenedor)"
                    class="btn btn-primary"
                  >
                    Actualizar
                  </button>
                  <!-- <button type="submit" @click="guardar()" class="btn btn-primary">Crear</button> -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal añadir alimentos a siembras -->
    <div
      class="modal fade"
      id="modalRecursos"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div
        class="modal-dialog modal-dialog-scrollable modal-lg"
        role="document"
      >
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title" id="exampleModalLabel">
              Alimentos por siembra
            </h3>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form class="row" id="editarAlimentacion">
              <!-- <div class="col-md-6"> -->
              <div class="form-group col-md-3">
                <label for="fecha_registro_alimentacion" class=""
                  >Fecha (*)</label
                >
                <input
                  type="date"
                  class="form-control"
                  id="fecha_registro_alimentacion"
                  aria-describedby="fecha_registro_alimentacion"
                  placeholder="fecha_registro_alimentacion"
                  v-model="form.fecha_ra"
                  required
                />
              </div>

              <div class="form-group col-md-3">
                <label for="alimento" class="">Alimento (*)</label>
                <select
                  class="form-control custom-select"
                  id="alimento"
                  v-model="form.id_alimento"
                  required
                >
                  <option>--Seleccionar--</option>
                  <option
                    v-for="(alimento, index) in listadoAlimentos"
                    :key="index"
                    v-bind:value="alimento.id"
                  >
                    {{ alimento.alimento }}
                  </option>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="detalles" class="">Detalles</label>
                <textarea
                  class="form-control"
                  id="detalles"
                  aria-describedby="detalles"
                  placeholder="Detalles"
                  v-model="form.detalles"
                ></textarea>
              </div>

              <div class="form-group col-md-3">
                <label for="minutos hombre" class="">Minutos hombre</label>
                <input
                  type="number"
                  class="form-control"
                  step="any"
                  id="minutos_hombre"
                  aria-describedby="minutos_hombre"
                  placeholder="Minutos hombre"
                  v-model="form.minutos_hombre"
                />
              </div>

              <div class="form-group col-md-3">
                <label for="cant_manana" class="">Kg Mañana</label>
                <input
                  type="number"
                  step="any"
                  class="form-control"
                  id="kg_manana"
                  aria-describedby="cant_manana"
                  placeholder="Kg Mañana"
                  v-model="form.cant_manana"
                />
              </div>
              <div class="form-group col-md-3">
                <label for="cant_tarde" class="">Kg tarde</label>
                <input
                  type="number"
                  step="any"
                  class="form-control"
                  id="cant_tarde"
                  aria-describedby="cant_tarde"
                  placeholder="Kg tarde"
                  v-model="form.cant_tarde"
                />
              </div>
              <div class="form-group col-md-4">
                <label for="conv_alimenticia"
                  >Conversión alimenticia teórica</label
                >
                <input
                  type="number"
                  step="any"
                  class="form-control"
                  id="conv_alimenticia"
                  placeholder="Conversión alimenticia teórica"
                  v-model="form.conv_alimenticia"
                />
              </div>
              <div class="modal-footer">
                <button
                  type="submit"
                  class="btn btn-primary"
                  @click="guardarRecursos()"
                >
                  <span
                    v-text="editandoAlimento == 0 ? 'Guardar' : 'Actualizar'"
                  ></span>
                </button>
              </div>
            </form>

            <div class="container">
              <table
                class="
                  table table-sm table-hover table-responsive table-bordered
                "
              >
                <thead>
                  <tr>
                    <th>#</th>
                    <th>
                      Tipo de <br />
                      Actividad
                    </th>
                    <th>Fecha</th>
                    <th><br />Alimento</th>
                    <th>Cantidad<br />Mañana</th>
                    <th>Cantidad<br />Tarde</th>
                    <th width="15%">Detalles</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in listadoRN" :key="index">
                    <td v-text="index + 1"></td>
                    <td v-text="item.actividad"></td>
                    <td v-text="item.fecha_ra"></td>
                    <td>{{ item.alimento }}</td>
                    <td
                      v-text="
                        item.cant_manana == null
                          ? '-'
                          : item.cant_manana + ' kg'
                      "
                    ></td>
                    <td
                      v-text="
                        item.cant_tarde == null ? '-' : item.cant_tarde + ' kg'
                      "
                    ></td>
                    <td v-text="item.detalles"></td>
                    <td>
                      <a
                        href="#editarAlimentacion"
                        class="btn btn-success"
                        @click="editarAlimento(item)"
                      >
                        <i class="fas fa-edit"></i>
                      </a>
                    </td>
                    <td>
                      <a
                        class="btn btn-danger"
                        @click="eliminarAlimento(item.id_registro)"
                      >
                        <i class="fas fa-trash"></i>
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Cerrar
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal registros -->
    <div class="modal" tabindex="-1" role="dialog" id="modalIngreso">
      <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-center col-md-9">Registros</h5>
            <button
              type="button"
              class="btn btn-primary"
              @click="
                ver_registros == 1 ? (ver_registros = 0) : (ver_registros = 1)
              "
            >
              <span v-if="ver_registros == 1"
                >Crear Registros <i class="fas fa-arrow-right"></i
              ></span>
              <span v-if="ver_registros == 0"
                ><i class="fas fa-arrow-left"></i> Ver listado de
                registros</span
              >
            </button>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div id="mostrarRegistros" v-if="ver_registros == 1">
              <div class="col-md-12">
                <h5>Filtrar por:</h5>
                <form class="row">
                  <div class="form-group col-md-4">
                    <label for="tipo_registro">Tipo</label>
                    <select
                      class="form-control"
                      id="tipo_registro"
                      v-model="f_actividad"
                    >
                      <option value="3">Peso inicial</option>
                      <option value="0">Muestreo</option>
                      <option value="1">Pesca</option>
                      <option value="2">Mortalidad inicial</option>
                    </select>
                  </div>
                  <div class="form-group col-md-3">
                    <label for="search">Desde: </label>
                    <input
                      class="form-control"
                      type="date"
                      placeholder="Search"
                      aria-label="fecha_desde"
                      v-model="f_fecha_d"
                    />
                  </div>
                  <div class="form-group col-md-3">
                    <label for="search">Hasta: </label>
                    <input
                      class="form-control"
                      type="date"
                      placeholder="Search"
                      aria-label="fecha_hasta"
                      v-model="f_fecha_h"
                    />
                  </div>
                  <div class="form-group col-md-2">
                    <button
                      class="btn btn-primary rounded-circle mt-4"
                      @click="filtrarIngresos()"
                      type="button"
                    >
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </form>
              </div>
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Especie</th>
                    <th>Tipo de registro</th>
                    <th>Fecha</th>
                    <th>Peso ganado (gr)</th>
                    <th>Mortalidad</th>
                    <th>Biomasa(kg)</th>
                    <th>Cantidad</th>
                    <th>Eliminar</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(registro, index) in listadoRegistros"
                    :key="registro.id"
                  >
                    <th v-text="index + 1"></th>
                    <td v-text="registro.especie"></td>
                    <td v-text="tipoRegistro[registro.tipo_registro]"></td>
                    <td v-text="registro.fecha_registro"></td>
                    <td
                      v-text="
                        registro.peso_ganado == null
                          ? '-'
                          : registro.peso_ganado + 'gr'
                      "
                    ></td>
                    <td
                      v-text="
                        registro.mortalidad == null ? '-' : registro.mortalidad
                      "
                    ></td>
                    <td v-if="registro.biomasa != null">
                      {{ registro.biomasa.toFixed(2) }}
                    </td>
                    <td v-else>-</td>
                    <td v-if="registro.cantidad != null">
                      {{ Math.floor(registro.cantidad) }}
                    </td>
                    <td v-else>-</td>
                    <td v-if="registro.tipo_registro != 3">
                      <button
                        class="btn btn-danger"
                        @click="eliminarRegistro(registro.id, registro)"
                      >
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div id="crearRegistros" v-if="ver_registros == 0">
              <div class="row">
                <div class="form-group col-sm-6 col-lg-3">
                  <label for="fecha_registro">Fecha Registro</label>
                  <input
                    type="date"
                    class="form-control"
                    id="fecha_registro"
                    placeholder="Fecha"
                    v-model="fecha_registro"
                  />
                </div>
                <div class="form-group col-sm-6 col-lg-3">
                  <label for="tipo_registro">Tipo</label>
                  <select
                    class="form-control"
                    id="tipo_registro"
                    v-model="tipo_registro"
                  >
                    <option value="0">Muestreo</option>
                    <option value="1">Pesca</option>
                    <option value="2">Mortalidad inicial</option>
                    <option value="3">Peso inicial</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-12 col-lg-8 mx-auto">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th scope="col">Especie</th>
                      <th scope="col" v-if="tipo_registro == 0">
                        Peso actual (gr)
                      </th>
                      <th scope="col" v-if="tipo_registro == 0">Mortalidad</th>
                      <th scope="col" v-if="tipo_registro == 1">
                        Biomasa (kg)
                      </th>
                      <!-- <th scope="col" v-if="tipo_registro == 1">Cantidad  idSiembraRegistro  </th> -->
                      <th scope="col" v-if="tipo_registro == 2">
                        Mortalidad Inicial
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="pez in pecesxSiembra" :key="pez.id">
                      <th scope="row" v-text="pez.especie"></th>
                      <td v-if="tipo_registro == 0">
                        <input
                          type="number"
                          class="form-control"
                          v-bind:required="tipo_registro == 0 ? 'required' : ''"
                          step="any"
                          v-model="
                            campos[pez.id_siembra][pez.id]['peso_ganado']
                          "
                        />
                      </td>
                      <td v-if="tipo_registro == 0 || tipo_registro == 2">
                        <input
                          type="number"
                          step="any"
                          id="mortalidad"
                          value=""
                          class="form-control"
                          v-bind:required="
                            tipo_registro == 0 || 2 ? 'required' : ''
                          "
                          v-model="campos[pez.id_siembra][pez.id]['mortalidad']"
                        />
                      </td>
                      <td v-if="tipo_registro == 1">
                        <input
                          type="number"
                          step="any"
                          class="form-control"
                          v-bind:required="tipo_registro == 1 ? 'required' : ''"
                          v-model="campos[pez.id_siembra][pez.id]['biomasa']"
                        />
                      </td>
                      <!-- <td v-if="tipo_registro == 1 ">
                        <input type="number" class="form-control" v-bind:required="tipo_registro == 1 ? 'required' : ''" v-model="campos[pez.id_siembra][pez.id]['cantidad']">
                      </td>                                                  -->
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Cerrar
            </button>
            <button
              type="button"
              class="btn btn-primary"
              v-if="ver_registros == 0"
              @click="crearRegistro(idSiembraRegistro)"
            >
              Crear registro
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal Finalizar -->
    <div
      class="modal fade"
      id="modalFinalizar"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalFinalizarLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              Finalizar siembra
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="POST">
              <div class="row">
                <div class="col">
                  <h6>Inicio Descanso</h6>
                  <input
                    type="date"
                    class="form-control"
                    placeholder="First name"
                    v-model="ini_descanso"
                    required
                  />
                </div>
                <div class="col">
                  <h6>Fin descanso</h6>
                  <input
                    type="date"
                    class="form-control"
                    placeholder="Last name"
                    v-model="fin_descanso"
                  />
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Cerrar
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click="fechaDescanso(id_finalizar)"
            >
              Guardar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from "vue";
import { Form, HasError, AlertError } from "vform";

Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);

export default {
  data() {
    return {
      form: new Form({
        id: "",
        fecha_inicio: "",
        nombre_siembra: "",
        id_contenedor: "",
        id_siembra: "",
        id_recurso: 0,
        id_registro: "",
        id_alimento: "",
        tipo_actividad: "1",
        fecha_ra: "",
        minutos_hombre: "",
        cant_manana: "",
        cant_tarde: "",
        conv_alimenticia: "",
        detalles: "",
      }),
      editandoAlimento: 0,
      fechaActual: [],
      ver_registros: 1,
      id_edita: "",
      itemRegistro: [],
      newLote: "",
      newEspecie: "",
      newCantidad: "",
      newPeso: "",
      listadoEspecies: [],
      listadoEstanques: [],
      listado: [],
      listadoItems: [],
      listadoSiembras: [],
      listadoRegistros: [],
      nombresEspecies: [],
      listadoAlimentos: [],
      listadoRN: [],
      pecesxSiembra: [],
      lotes: [],
      // Registros
      id_siembra: "",
      id_especie: "",
      fecha_registro: "",

      tipo_registro: "",
      peso_ganado: "",
      mortalidad: 0,
      biomasa: "",
      cantidad: "",

      aux_lote: "",
      aux_cantidad: "",
      aux_peso_inicial: "",
      id_edit_item: "",

      id_siembra: "",
      mortalidad_inicial: "",
      idSiembraRegistro: "",
      idSiembraR: "",
      // Finalización de siembra
      ini_descanso: "",
      fin_descanso: "",
      id_finalizar: "",
      estados: [],
      tipoRegistro: [],

      campos: {
        camps_s: [],
      },
      // Filtros ingresos
      f_siembra: "",
      f_actividad: "",
      f_fecha_d: "",
      f_fecha_h: "",

      //Filtro siembras
      estado_siembra: "-1",
      siembra_activa: "",
      siembra_inactiva: "",
      siembrasActivas: "",
      siembrasInactivas: "",
    };
  },

  methods: {
    editItem(especie) {
      this.id_edit_item = especie.id_especie;
      this.aux_lote = especie.lote;
      this.aux_cantidad = especie.cantidad;
      this.aux_peso_inicial = especie.peso_inicial;
    },
    guardaEditItem(id) {
      let me = this;
      const data = {
        especie: this.id_edit_item,
        lote: this.aux_lote,
        cantidad: this.aux_cantidad,
        cant_actual: this.aux_cantidad,
        peso_inicial: this.aux_peso_inicial,
      };
      axios.put("api/siembras/" + id, data).then(({ data }) => {
        (this.id_edit_item = ""),
          (this.aux_lote = ""),
          (this.aux_cantidad = ""),
          (this.aux_peso_inicial = "");
      });
    },
    listarEspecies() {
      let me = this;
      axios.get("api/especies").then(function (response) {
        me.listadoEspecies = response.data;
      });
    },
    listarEstanques() {
      let me = this;
      axios.get("api/contenedores").then(function (response) {
        me.listadoEstanques = response.data;
      });
    },

    listarAlimentos() {
      let me = this;
      axios.get("api/alimentos").then(function (response) {
        me.listadoAlimentos = response.data;
        var auxAlimento = response.data;
      });
    },
    listar(estado_siembra, id_siembra) {
      let me = this;
      axios
        .get(
          "api/siembras?estado_siembra=" +
            estado_siembra +
            "&id_siembra=" +
            id_siembra
        )
        .then(function (response) {
          me.listadoSiembras = response.data.siembra;
          // me.pecesxSiembra = response.data.pecesSiembra
          me.campos = response.data.campos;
          me.lotes = response.data.lotes;
          me.fechaActual = response.data.fecha_actual;
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
    filtrarSiembras(estado_siembra, id_siembra) {
      let me = this;
      me.listar(estado_siembra, id_siembra);
    },
    anadirItem() {
      let me = this;
      $("#modalSiembra").modal("show");
      this.listarEspecies();
      this.listarEstanques();
      this.id_edita = "";
      this.listadoItems = [];
    },
    editarSiembra(siembra) {
      let me = this;
      $("#modalSiembra").modal("show");
      me.listarEstanques();
      me.form.nombre_siembra = siembra.nombre_siembra;
      me.form.id_contenedor = siembra.id_contenedor;
      me.form.fecha_inicio = siembra.fecha_inicio;
      me.form.id_siembra = siembra.id;
      me.idSiembraR = siembra.id;
      me.id_edita = siembra.id;
      axios
        .get("api/especies-siembra-edita/" + siembra.id)
        .then(function (response) {
          me.listadoEspecies = response.data.especies;
          me.listadoItems = response.data.espxsiembra;
        });
    },
    editarAlimento(objeto) {
      let me = this;
      this.editandoAlimento = 1;
      this.form.fill(objeto);
    },
    guardarEdita(objeto) {
      let me = this;
      const data = {
        siembra: this.form,
        especies: this.listadoItems,
      };
      axios.post("api/anadir-especie-siembra", data).then(({ response }) => {
        this.form.nombre_siembra = "";
        this.form.id_contenedor = "";
        this.form.fecha_inicio = "";
        this.newEspecie = "";
        this.newLote = "";
        this.newCantidad = "";
        this.newPeso = "";
        this.listadoItems = [];
        this.listar(1, "");
        $("#modalSiembra").modal("hide");
      });
    },
    abrirCrear(id) {
      let me = this;
      $("#modalRecursos").modal("show");
      this.form.id_siembra = id;
      this.idSiembraR = id;

      axios.post("api/siembras-alimentacion/" + id).then(function (response) {
        me.listadoRN = response.data.recursosNecesarios;
      });
    },
    anadirEspecie() {
      let me = this;
      if (
        this.newEspecie != "" &&
        this.newCantidad != "" &&
        this.newPeso != ""
      ) {
        me.listadoItems.push({
          id_especie: this.newEspecie,
          lote: this.newLote,
          cantidad: this.newCantidad,
          peso_inicial: this.newPeso,
        });

        const idEspecie = (element) => element.id == this.newEspecie;
        var index = this.listadoEspecies.findIndex(idEspecie);
        this.listadoEspecies.splice(index, 1);
        this.newEspecie = "";
        (this.newLote = ""), (this.newCantidad = "");
        this.newPeso = "";
      } else {
        alert("Debe diligenciar todos los campos");
      }
    },
    removeItem(index) {
      let me = this;
      me.listadoItems.pop(index, 1);
      this.listadoEspecies.push({
        id: index,
        especie: this.nombresEspecies[index],
      });
    },
    nombreEspecie() {
      let me = this;
      axios.get("api/especies").then(function (response) {
        var auxEspecie = response.data;
        auxEspecie.forEach(
          (element) => (me.nombresEspecies[element.id] = element.especie)
        );
      });
    },
    abrirIngreso(id) {
      this.idSiembraRegistro = id;
      let me = this;
      this.ver_registros = 1;
      $("#modalIngreso").modal("show");

      this.tipo_registro = 0;
      axios.post("api/registros-siembra/" + id).then(function (response) {
        me.listadoRegistros = response.data;
      });
      me.especiesSiembra(id);
    },

    especiesSiembra(idSiembra) {
      let me = this;
      axios
        .get("api/especies-siembra?idSiembra=" + idSiembra)
        .then(function (response) {
          me.pecesxSiembra = response.data;
        });
    },

    crearRegistro(id) {
      let me = this;
      this.ver_registros = 0;
      // this.idSiembraRegistro = id;
      let aux_campos = me.campos[id];

      const data = {
        campos: aux_campos,
        id_siembra: id,
        fecha_registro: this.fecha_registro,
        tipo_registro: this.tipo_registro,
      };
      axios.post("api/registros", data).then(({ response }) => {
        me.aux_campos = [];
        me.ver_registros = 1;
        me.abrirIngreso(id);
        me.listar(1, "");
      });
    },
    filtrarIngresos() {
      let me = this;

      // if(this.f_siembra == ''){this.smb = '-1'}else{this.smb = this.f_siembra}
      if (this.f_actividad == "") {
        this.act = "-1";
      } else {
        this.act = this.f_actividad;
      }
      if (this.f_fecha_d == "") {
        this.f_d = "-1";
      } else {
        this.f_d = this.f_fecha_d;
      }
      if (this.f_fecha_h == "") {
        this.f_h = "-1";
      } else {
        this.f_h = this.f_fecha_h;
      }

      const data = {
        f_siembra: this.idSiembraRegistro,
        f_actividad: this.act,
        f_fecha_d: this.f_d,
        f_fecha_h: this.f_h,
      };
      axios.post("api/filtro-registros", data).then((response) => {
        me.listadoRegistros = response.data;
      });
    },

    finalizarSiembra(id) {
      $("#modalFinalizar").modal("show");
      this.id_finalizar = id;
    },
    fechaDescanso(id) {
      let me = this;
      if (this.ini_descanso != "") {
        if (this.fin_descanso != "") {
          const data = {
            id: this.id_finalizar,
            ini_descanso: this.ini_descanso,
            fin_descanso: this.fin_descanso,
          };
          axios
            .post("api/actualizarEstado/" + this.id_finalizar, data)
            .then(({ response }) => {
              this.id_finalizar = "";
              this.ini_descanso = "";
              this.fin_descanso = "";
              $("#modalFinalizar").modal("hide");
              this.listar(1, "");
            });
        } else {
          const data = {
            id: this.id_finalizar,
            ini_descanso: this.ini_descanso,
          };

          axios
            .post("api/actualizarEstado/" + this.id_finalizar, data)
            .then(({ response }) => {
              this.id_finalizar = "";
              this.ini_descanso = "";
              $("#modalFinalizar").modal("hide");
              this.listar(1, "");
            });
        }
      } else {
        Swal.fire(
          "Advertencia",
          "Por favor, diligencia los datos restantes",
          "warning"
        );
      }
    },
    guardar() {
      let me = this;
      if (
        this.form.id_contenedor != "" &&
        this.form.nombre_siembra != "" &&
        this.form.fecha_inicio != "" &&
        this.listadoItems.length > 0
      ) {
        const data = {
          siembra: this.form,
          especies: this.listadoItems,
        };
        axios.post("api/siembras", data).then(({ response }) => {
          this.form.nombre_siembra = "";
          this.form.id_contenedor = "";
          this.form.fecha_inicio = "";
          this.newEspecie = "";
          this.newLote = "";
          this.newCantidad = "";
          this.newPeso = "";
          this.listadoItems = [];
          this.listar(1, "");
          $("#modalSiembra").modal("hide");
        });
      } else {
        alert("Debe diligenciar todos los campos");
      }
    },
    guardarRecursos() {
      let me = this;
      if (this.editandoAlimento == 0) {
        if (this.form.id_alimento != "" && this.form.fecha_ra != "") {
          axios.post("api/recursos-necesarios", this.form).then(({ data }) => {
            me.listar(1, "");
            me.abrirCrear(this.form.id_siembra);
            Swal.fire(
              "Excelente!",
              "Los datos se guardaron correctamente!",
              "success"
            );
          });
        } else {
          Swal.fire({
            title: "Completar registro",
            text: "Rellenar los campos requeridos",
            icon: "error",
            showCancelButton: true,
            confirmButtonColor: "#c7120c",
            confirmButtonText: "Aceptar!",
            reverseButtons: true,
          });
        }
      } else {
        this.form
          .put("api/recursos-necesarios/" + this.form.id_registro)
          .then(({ data }) => {
            this.form.reset();
            this.editandoAlimento = 0;
            me.abrirCrear(this.form.id_siembra);
          });
      }
    },
    eliminarRegistro(id, objeto) {
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
      }).then((result) => {
        if (result.isConfirmed) {
          const data = {
            campos: objeto,
          };
          axios.put("api/registros/" + id, data).then(({ data }) => {
            me.abrirIngreso(objeto.id_siembra);
            me.listar(1, "");
          });
        }
      });
    },

    eliminarAlimento(objeto) {
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
      }).then((result) => {
        if (result.isConfirmed) {
          axios.delete("api/recursos-necesarios/" + objeto).then(({ data }) => {
            me.abrirCrear(this.idSiembraR);
            me.listar(1, "");
          });
        }
      });
    },

    eliminarSiembra(index) {
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
      }).then((result) => {
        if (result.isConfirmed) {
          axios.delete("api/siembras/" + index).then(({ data }) => {
            me.listar(1, "");
          });
        }
      });
    },
  },
  mounted() {
    this.listar(1, "");
    this.listarEspecies();
    this.listarAlimentos();
    this.nombreEspecie();
    this.listarSiembras();
    this.estados[0] = "Inactivo";
    this.estados[1] = "Activo";
    this.estados[2] = "Ocupado";
    this.estados[3] = "Descanso";
    this.tipoRegistro[0] = "Muestreo";
    this.tipoRegistro[1] = "Pesca";
    this.tipoRegistro[2] = "Mortalidad Inicial";
    this.tipoRegistro[3] = "Peso Inicial";
  },
};
</script>
