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
                          <div class="box-option rounded">
                            <div class="cover-option bg-light text-center">
                              <i class="fa fa-4x fa-fish"></i>
                            </div>
                            <router-link to="/siembras" class="description-option bg-primary text-center" >
                                <h4 class=" text-white">Siembras</h4>
                            </router-link>
                          </div>
                        </div>

                        <div class="card">
                          <div class="box-option rounded">
                            <div class="cover-option bg-light text-center">
                              <i class="fa fa-4x fa-plus-square"></i>
                            </div>
                            <router-link to="/recursos-necesarios" class="description-option bg-primary text-center" >
                                <h4 class=" text-white">Recursos necesarios</h4>
                            </router-link>
                          </div>
                        </div>

                        <div class="card">
                          <div class="box-option rounded">
                            <div class="cover-option bg-light text-center">
                              <i class="fa fa-4x fa-utensils"></i>
                            </div>
                            <router-link to="/alimentacion" class="description-option bg-primary text-center" >
                                <h4 class=" text-white">Alimentación</h4>
                            </router-link>
                          </div>
                        </div>
                         <div class="card">
                          <div class="box-option rounded">
                            <div class="cover-option bg-light text-center">
                              <i class="fa fa-4x fa-file-alt"></i>
                            </div>
                            <router-link to="/informes-biomasa-alimento" class="description-option bg-primary text-center" >
                                <h4 class=" text-white">Biomasa por alimentacion</h4>
                            </router-link>
                          </div>
                        </div>

                        <div class="card">
                          <div class="box-option rounded">
                            <div class="cover-option bg-light text-center">
                              <i class="fa fa-4x fa-tint"></i>
                            </div>
                            <router-link to="/calidad-agua" class="description-option bg-primary text-center" >
                                <h4 class=" text-white">Párametros de calidad</h4>
                            </router-link>
                          </div>
                        </div>

                        <div class="card">
                          <div class="box-option rounded">
                            <div class="cover-option bg-light text-center">
                              <i class="fa fa-4x fa-tools"></i>
                            </div>
                            <router-link to="/especies" class="description-option bg-primary text-center" >
                                <h4 class=" text-white">Especies</h4>
                            </router-link>
                          </div>
                        </div>

                         <div class="card">
                          <div class="box-option rounded">
                            <div class="cover-option bg-light text-center">
                              <i class="fa fa-4x fa-tools"></i>
                            </div>
                            <router-link to="/alimentos" class="description-option bg-primary text-center" >
                                <h4 class=" text-white">Alimentos</h4>
                            </router-link>
                          </div>
                        </div>

                         <div class="card">
                          <div class="box-option rounded">
                            <div class="cover-option bg-light text-center">
                              <i class="fa fa-4x fa-tools"></i>
                            </div>
                            <router-link to="/recursos" class="description-option bg-primary text-center" >
                                <h4 class=" text-white">Recursos</h4>
                            </router-link>
                          </div>
                        </div>

                         <div class="card">
                          <div class="box-option rounded">
                            <div class="cover-option bg-light text-center">
                              <i class="fa fa-4x fa-user"></i>
                            </div>
                            <router-link to="/usuarios" class="description-option bg-primary text-center" >
                                <h4 class=" text-white">Usuarios</h4>
                            </router-link>
                          </div>
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
