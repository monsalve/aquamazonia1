<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Bienvenido a Aquamazonia</h3>
                        <h5>Panel de Administración</h5>

                    </div>

                    <div class="card-body">
                      <section class="content-dashboard">
                        <div class="card">
                          <router-link to="/siembras" class="box-option rounded">
                            <div class="cover-option bg-light text-center">
                              <img src="img/siembras.jpg" alt="" srcset="">
                            </div>

                            <div class="description-option bg-primary text-center">
                              <h4 class=" text-white">Siembras</h4>
                            </div>
                          </router-link>
                        </div>

                        <div class="card">
                          <router-link to="/recursos-necesarios" class="box-option rounded">
                            <div class="cover-option bg-light text-center">
                              <img src="img/recursos-necesarios.jpg" alt="" srcset="">
                            </div>
                            <div class="description-option bg-primary text-center" >
                            <h4 class=" text-white">Recursos necesarios</h4>
                            </div>
                          </router-link>
                        </div>

                        <div class="card">
                          <router-link to="/alimentacion" class="box-option rounded" >
                            <div class="cover-option bg-light text-center">
                              <img src="img/alimentacion.jpg" alt="" srcset="">
                            </div>
                            <div class="description-option bg-primary text-center" >
                                <h4 class=" text-white">Alimentación</h4>
                            </div>
                          </router-link>
                        </div>

                         <div class="card">
                          <router-link to="/informes-biomasa-alimento"  class="box-option rounded">
                            <div class="cover-option bg-light text-center">
                              <img src="img/biomasa-alimentacion.jpg" alt="" srcset="">
                            </div>
                            <div class="description-option bg-primary text-center" >
                                <h4 class=" text-white">Biomasa por alimentacion</h4>
                            </div>
                          </router-link>
                        </div>

                        <div class="card">
                          <router-link to="/calidad-agua" class="box-option rounded">
                            <div class="cover-option bg-light text-center">
                              <img src="img/parametros-calidad.jpg" alt="" srcset="">
                            </div>
                            <div class="description-option bg-primary text-center" >
                                <h4 class=" text-white">Párametros de calidad</h4>
                            </div>
                          </router-link>
                        </div>

                        <div class="card">
                          <router-link to="/especies" class="box-option rounded" >
                            <div class="cover-option bg-light text-center">
                              <img src="img/especies.jpg" alt="" srcset="">
                            </div>
                            <div class="description-option bg-primary text-center" >
                              <h4 class=" text-white">Especies</h4>
                            </div>
                          </router-link>
                        </div>

                         <div class="card">
                           <router-link to="/alimentos" class="box-option rounded" >
                              <div class="cover-option bg-light text-center">
                                <img src="img/alimentos.jpg" alt="" srcset="">
                              </div>
                              <div class="description-option bg-primary text-center" >
                                <h4 class=" text-white">Alimentos</h4>
                              </div>
                           </router-link>
                        </div>

                         <div class="card">
                          <router-link to="/recursos"  class="box-option rounded">
                            <div class="cover-option bg-light text-center">
                              <img src="img/tools.png" alt="" srcset="">
                            </div>
                            <div class="description-option bg-primary text-center" >
                                <h4 class=" text-white">Recursos</h4>
                            </div>
                          </router-link>
                        </div>

                         <div class="card">
                          <router-link to="/usuarios" class="box-option rounded">
                            <div class="cover-option bg-light text-center">
                              <img src="img/user.png" alt="" srcset="">
                            </div>
                            <div class="description-option bg-primary text-center" >
                                <h4 class=" text-white">Usuarios</h4>
                            </div>
                          </router-link>
                        </div>
                        
                      </section>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
        }
    }
</script>
