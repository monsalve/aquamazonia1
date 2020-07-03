<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Gestión de recursos</div>

                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-12 text-right">
                                 <button class="btn btn-success"  data-toggle="modal" data-target="#modalRecursos">Añadir Recurso</button>
                            </div>
                        </div>
                        <div class="row">
                            <table class="table table-striped">
                              <thead>
                                <tr>
                                  <th scope="col">#</th>
                                  <th scope="col">Recurso</th>
                                  <th scope="col">Unidad</th>
                                  <th scope="col">Costo</th>
                                  <th scope="col">Estado</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <th scope="row">1</th>
                                  <td>Recurso 1</td>
                                  <td>Lt</td>
                                  <td>$2305000</td>
                                  <td>
                                    <span style="font-size: 1.5em; color:#FFC107;"><i class="fas fa-user"></i></span>
                                    <span style="font-size: 1.5em; color:#28a745 ;"  ><i class="fas fa-edit"></i></span>
                                    <span style="font-size: 1.5em; color:#DC3545;"><i class="fas fa-trash"></i></span>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalRecursos" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modalRecursosLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalRecursosLabel">Crear recurso</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group row">
                                <label for="recurso" class="col-sm-12 col-md-4 col-form-label">Nombre recurso</label>
                                <div class="col-sm-12 col-md-8">
                                  <input type="text" class="form-control" id="especie">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="unidad" class="col-sm-12  col-md-4 col-form-label">Unidad
                                </label>
                                <div class="col-sm-12 col-md-8">
                                  <input type="text" class="form-control" id="unidad">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="costo" class="col-sm-12  col-md-4 col-form-label">Costo
                                </label>
                                <div class="col-sm-12 col-md-8">
                                  <input type="text" class="form-control" id="costo">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12 text-right">
                                  <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">Cancelar</button>
                                  <button type="submit" class="btn btn-primary btn-lg">Crear</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            //console.log('Component mounted.')
        }
    }
</script>
