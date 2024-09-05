<?php
class EquiposModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function crearEquipo($equipo)
    {
        $this->db->insert('equipos', $equipo);
    }

    public function mostrarEquipos()
    {
        $this->db->select('*');
        $this->db->from('equipos');
        return $this->db->get()->result();
    }

    public function eliminarEquipo($idEquipo)
    {
        $this->db->where('idEquipo', $idEquipo);
        $this->db->delete('equipos');
    }

    public function editarEquipo($equipo, $idEquipo)
    {
        $this->db->where('idEquipo', $idEquipo);
        $this->db->update('equipos', $equipo);
    }
}
