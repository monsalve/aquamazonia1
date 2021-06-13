<template>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">Gestión de contenedores</div>
					<div class="card-body">
						<div class="row mb-1">
							<div class="col-12 text-right">
								<button class="btn btn-success" @click="abrirCrear()">Añadir Contenedor</button>
							</div>
						</div>
						<div class="row">
							<table class="table table-striped table-sm">
							  <thead>
								<tr>
								  <th scope="col">#</th>
								  <th scope="col">Contenedor</th>
								  <th scope="col">Capacidad</th>
								  <th scope="col">Estado</th>
								  <th scope="col">Opciones</th>
								</tr>
							  </thead>
							  <tbody>
								<tr v-for="contenedor in listado" :key="contenedor.id">
								  <th scope="row" v-text="contenedor.id"></th>
								  <td v-text="contenedor.contenedor"></td>
								  <td v-text="contenedor.capacidad"></td>
								  <td v-text="estados[contenedor.estado]"></td>
								  <td>
									<button class="btn btn-success" @click="cargaEditar(contenedor)">
										<i class="fas fa-edit"></i>
									</button>
									<button class="btn btn-danger" @click="eliminar(contenedor.id)" >
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
		<div class="modal fade" id="modalContenedor" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalContenedorLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						 <h5 class="modal-title" id="modalalimentosLabel" v-text="editando ==0 ? 'Crear contenedor' : 'Actualizar contenedor'"></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						  <span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form @submit.prevent="editando == 0 ? guardar() : editar()">
							<div class="form-group row">
								<label for="contenedor" class="col-sm-12 col-md-4 col-form-label">Nombre Contenedor</label>
								<div class="col-sm-12  col-md-8">
									<input type="text" class="form-control" id="contenedor"  :class="{ 'is-invalid': form.errors.has('contenedor') }" v-model="form.contenedor">
									<has-error :form="form" field="contenedor"></has-error>
								</div>
							</div>
							<div class="form-group row">
								<label for="Capacidad" class="col-sm-12  col-md-4 col-form-label">Capacidad</label>
								<div class="col-sm-12  col-md-8">
									<input type="number" step="any" class="form-control" id="capacidad"  :class="{ 'is-invalid': form.errors.has('capacidad') }" v-model="form.capacidad">
									<has-error :form="form" field="capacidad"></has-error>
								</div>
							</div>
							<div class="form-group row">
								<label for="estado" class="col-sm-12  col-md-4 col-form-label">Estado</label>
								<div class="col-sm-12  col-md-8">
									<select v-model="form.estado" class="form-control" :class="{ 'is-invalid': form.errors.has('estado') }">
										<!--  objeto literal en línea --> -->
										<option v-bind:value="0">Inactivo</option>
										<option v-bind:value="1">Disponible</option>
										<option v-bind:value="2">Ocupado</option>
										<option v-bind:value="3">Descanso</option>
									</select>
									 <has-error :form="form" field="estado"></has-error>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-12 text-right">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
									<button type="submit" class="btn btn-primary" v-text="editando ==0 ? 'Crear' : 'Actualizar'"></button>
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
	import Vue from 'vue'
	import { Form, HasError, AlertError } from 'vform'
	
	Vue.component(HasError.name, HasError)
	Vue.component(AlertError.name, AlertError)


	export default {
		data() {
					return {
						editando: 0,
						form: new Form({
							id : '',
							contenedor : '',
							capacidad : '',
							estado : '',
						}),
						
						listado: [],
						estados: []
					}
		},
		methods: {
			guardar(){
							let me = this;
							this.form.post('api/contenedores')
								.then(({data})=>{
									editando: 0,
									me.listar();
									$('#modalContenedor').modal('hide');
									me.form.contenedor = '';
									me.form.capacidad = '';
									me.form.estado = '';
								})
			},
			abrirCrear(){
				this.editando = 0;
				this.form.reset(); 
				$('#modalContenedor').modal('show');
			},
		 
			listar(){
							let me = this;
							axios.get("api/contenedores")
							.then(function (response) {
								me.listado = response.data
							});
			},
			cargaEditar(objeto){
							let me = this;
							this.form.fill(objeto);
							this.editando = 1;
							$('#modalContenedor').modal('show');
			},
			editar(){
							let me = this;
							this.form.put('api/contenedores/'+this.form.id)
								.then(({data})=>{ 
									$('#modalContenedor').modal('hide');
									me.listar();
								})
		  
			},
			eliminar(index){
				let me = this;
				Swal.fire({
				  title: "Estás seguro?",
				  text: "Una vez eliminado, no se puede recuperar este registro",
				  icon: "warning",
          showCancelButton: true,
				  confirmButtonColor: '#c7120c',
				  cancelButtonText: 'Cancelar',
				  confirmButtonText: 'Aceptar!',
				  reverseButtons: true
				})
				.then((result) => {
					if (result.isConfirmed) {
						me.form.delete('api/contenedores/'+index)
						.then(({data})=>{
							me.listar();
						})
					}
				});
				 
			}
		},
		mounted() {
			this.listar();
			this.estados[0] = 'Inactivo';
			this.estados[1] = 'Activo';
			this.estados[2] = 'Ocupado';
			this.estados[3] = 'Descanso';         
		}
	}
</script>
