<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->model('EquiposModel');
	}

	public function index()
	{
		$equipos['equipos'] = $this->EquiposModel->mostrarEquipos();
		$this->load->view('welcome_message', $equipos);
	}

	public function crearEquipo()
	{
		$equipo['nombre'] = $this->input->post('nombre');
		$equipo['descripcion'] = $this->input->post('descripcion');
		$equipo['estado'] = $this->input->post('estado');
		$this->EquiposModel->crearEquipo($equipo);
		redirect('Welcome');
	}

	public function eliminarEquipo($idEquipo)
	{
		$this->EquiposModel->eliminarEquipo($idEquipo);
		redirect('Welcome');
	}

	public function editarEquipo($idEquipo)
	{
		$equipo['nombre'] = $this->input->post('nombre');
		$equipo['descripcion'] = $this->input->post('descripcion');
		$equipo['estado'] = $this->input->post('estado');
		$this->EquiposModel->editarEquipo($equipo, $idEquipo);
		redirect('Welcome');
	}

	public function verEstaciones()
	{
		$equipos['equipos'] = $this->EquiposModel->mostrarEquipos();
		$this->load->view('verEstaciones', $equipos);
	}
}
