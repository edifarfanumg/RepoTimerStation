<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Inicio</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<style>
		body {
			background-color: #121212;
			color: #ffffff;
		}

		.container {
			margin-top: 50px;
		}

		.card {
			background-color: #1e1e1e;
			border: none;
		}

		.card-header {
			background-color: #2c2c2c;
			border-bottom: 1px solid #444;
		}

		.card-body {
			background-color: #1e1e1e;
		}

		.table {
			color: #ffffff;
		}

		.table thead {
			background-color: #333333;
		}

		.table tbody tr {
			background-color: #2a2a2a;
		}

		.btn {
			border: none;
		}

		.btn-success {
			background-color: #28a745;
		}

		.btn-primary {
			background-color: #007bff;
		}

		.btn-warning {
			background-color: #ffc107;
			color: #000;
		}

		.btn-danger {
			background-color: #dc3545;
		}

		.form-control {
			background-color: #2c2c2c;
			color: #ffffff;
			border: 1px solid #444;
		}

		.form-control::placeholder {
			color: #888888;
		}
	</style>
</head>

<body>
	<div class="container">
		<!--TITULO DE LA PAGINA -->
		<div class="row">
			<h2>CRUD</h2>
		</div>
		<!-- FORMULARIO -->
		<div class="mb-5">
			<?php echo form_open('welcome/crearEquipo', ['id' => 'form-equipo']); ?>
			<div class="row">
				<div class="form-group col-sm-4">
					<label for="">Nombre</label>
					<input type="text" name="nombre" class="form-control" required placeholder="Nombre" id="nombre">
				</div>
				<div class="form-group col-sm-4">
					<label for="">Descripción</label>
					<input type="text" name="descripcion" class="form-control" required placeholder="Descripción" id="descripcion">
				</div>
				<div class="form-group col-sm-4">
					<label for="estado">Estado</label>
					<select name="estado" class="form-control" required id="estado">
						<option value="1">Activa</option>
						<option value="0">Inactiva</option>
					</select>
				</div>
			</div>
			<button type="submit" class="btn btn-success btn-block">Guardar estación</button>
			<?php echo form_close(); ?>
			<a href="<?php echo base_url('welcome/verEstaciones'); ?>" class="btn btn-primary btn-block mt-3">Ver Estaciones</a>
		</div>
		<!-- TABLA DE DATOS -->
		<div class="row">
			<div class="card col-12">
				<div class="card-header">
					<h4>Tabla de estaciones</h4>
				</div>
				<div class="card-body">
					<table class="table">
						<thead class="thead-dark">
							<tr>
								<th scope="col">#</th>
								<th scope="col">Nombre</th>
								<th scope="col">Descripción</th>
								<th scope="col">Estado</th>
								<th scope="col">Editar</th>
								<th scope="col">Eliminar</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$contador = 0;
							foreach ($equipos as $equipo) {
								echo '
										<tr>
											<td>' . ++$contador . '</td>
											<td>' . $equipo->nombre . '</td>
											<td>' . $equipo->descripcion . '</td>
											<td>' . ($equipo->estado == 1 ? 'Activa' : 'Inactiva') . '</td>
											<td><button type="button" class="btn btn-warning text-white" onclick="llenarDatosActualizar(' . $equipo->idEquipo . ', `' . $equipo->nombre . '`, `' . $equipo->descripcion . '`, `' . $equipo->estado . '`)">Editar</button></td>
											<td><a href="' . base_url('welcome/eliminarEquipo/' . $equipo->idEquipo) . '" type="button" class="btn btn-danger">Eliminar</a></td>
										</tr>
									';
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<script>
		let url = "<?php echo base_url('welcome/editarEquipo'); ?>";
		const llenarDatosActualizar = (idEquipo, nombre, descripcion, estado) => {
			let path = url + "/" + idEquipo;
			console.log(path);
			document.getElementById('form-equipo').setAttribute('action', path);
			document.getElementById('nombre').value = nombre;
			document.getElementById('descripcion').value = descripcion;
			document.getElementById('estado').value = estado;
		}
	</script>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>