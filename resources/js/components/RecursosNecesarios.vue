<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">Recursos necesarios</div>

          <div class="card-body">
            <div>
              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Tipo de Actividad</th>
                    <th>Fecha</th>
                    <th>Recurso</th>
                    <th>Horas hombre</th>
                    <th>Cantidad Mañana</th>
                    <th>Cantidad Tarde</th>
                    <th>Detalles</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
      Launch demo modal
    </button>
    
    <!-- Modal añadir recursos a siembras -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title" id="exampleModalLabel">Recursos por siembra</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- <table class="table">
              <thead>
                <tr>
                  <td>Seleccionar siembras</td>
                  <td>Tipo de Actividad</td>
                  <td>Alimento</td>
                  <td>Recurso</td>
                  <td>Fecha</td>
                  <td>Horas hombre</td>
                  <td>Kg Mañana</td>
                  <td>Kg Tarde</td>
                  <td>Detalles</td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <div v-for="(item, index) in listadoSiembras" :key="index">
                      <input type="checkbox" v-bind:value="item.id" v-model="newSiembra">
                      <label for="jack">{{item.nombre_siembra}}</label>
                      <br>
                    </div>
                    <span>Checked names: {{ newSiembra }}</span>
                  </td>
                  
                 
                  <td>
                    <div class="form-group">                    
                      <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Horas hombre">                      
                    </div>
                  </td>
                  <td>
                    <div class="form-group">                    
                      <input type="number" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Kg Mañana">                      
                    </div>
                  </td>
                  <td>
                    <div class="form-group">                    
                      <input type="number" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Kg tarde">                      
                    </div>
                  </td>
                  <td>
                    <div class="form-group">                    
                      <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Detalles">                      
                    </div>
                  </td>
                </tr>
              </tbody>
            </table> -->
            <form class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Tipo de Actividad</label>
                  <select class="form-control" id="exampleFormControlSelect1">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                  </select>
                </div>
                
                
                <div class="form-group">
                  <label for="exampleFormControlSelect2">Alimento</label>
                  <select class="form-control" id="exampleFormControlSelect2">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="exampleFormControlSelect2">Recurso</label>
                  <select class="form-control" id="exampleFormControlSelect1">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                  </select>
                </div>
                
                <div class="form-group">   
                  <label for="exampleFormControlSelect2">Horas hombre</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Horas hombre">                      
                </div>
                    
                <div class="form-group">                    
                  <label for="exampleFormControlSelect2">Kg Mañana</label>
                  <input type="number" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Kg Mañana">                      
                </div>
              
                <div class="form-group">    
                  <label for="exampleFormControlSelect2">Kg tarde</label>
                  <input type="number" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Kg tarde">                      
                </div>
              
                <div class="form-group">   
                  <label for="exampleFormControlSelect2">Detalles</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Detalles">                      
                </div>
              </div>
              <div class="col-md-6"> 
                <h3> Seleccionar siembras</h3>
                <div v-for="(item, index) in listadoSiembras" :key="index">                                 
                  <input type="checkbox" v-bind:value="item.id" v-model="newSiembra">
                  <label for="jack">{{item.nombre_siembra}}</label>
                  <br>
                </div>
                <span>Checked names: {{ newSiembra }}</span>
              </div>
              
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>
      </div>
</template>

<script>
  export default {
    data(){
      return {
        newSiembra: [],
        addSiembras :[],
        listadoSiembras : []
      }
    },
    methods:{
      listarSiembras(){
        let me = this;
        axios.get("api/siembras")
        .then(function (response){
          me.listadoSiembras = response.data.siembra;         
        })
      },
      checkSiembras(){
        let me = this;
        me.addSiembras({
          'id_siembra' : this.newSiembra
        })
      }
      
    },
    mounted() {
      this.listarSiembras();
      console.log('Component mounted.')
    }
  }
</script>
