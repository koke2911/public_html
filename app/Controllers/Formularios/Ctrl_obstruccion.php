<?php

namespace App\Controllers\Formularios;

use App\Controllers\BaseController;
use App\Models\Formularios\Md_obstruccion;

class Ctrl_obstruccion extends BaseController
{
    protected $obstrucciones;
    protected $sesion;
    protected $db;

    public function __construct()
    {
        $this->sesion        = session();
        $this->obstrucciones = new Md_obstruccion();
        $this->db            = \Config\Database::connect();
    }

    public function validar_sesion()
    {
        if (!$this->sesion->has("id_usuario_ses")) {
            echo "La sesión expiró, actualice el sitio web con F5";
            exit();
        }
    }

    public function datatable_obstrucciones()
    {
        $this->validar_sesion();
        $id_apr = $this->sesion->id_apr_ses;

        return $this->response->setJSON($this->obstrucciones->datatable_obstrucciones($this->db, $id_apr));
    }

    public function guardar_obstruccion()
    {
        $this->validar_sesion();

        $id_obstruccion          = $this->request->getPost("id_obstruccion");
        $fecha_inicio            = $this->request->getPost("fecha_inicio");
        $hora_inicio             = $this->request->getPost("hora_inicio");
        $fecha_termino           = $this->request->getPost("fecha_termino");
        $hora_termino            = $this->request->getPost("hora_termino");
        $duracion                = $this->request->getPost("duracion");
        $motivo_obstruccion      = $this->request->getPost("motivo_obstruccion");
        $cant_clientes_afectados = $this->request->getPost("cant_clientes_afectados");
        $ubicacion_falla         = $this->request->getPost("ubicacion_falla");
        $afloramiento            = $this->request->getPost("afloramiento");
        $tipo_infraestructura    = $this->request->getPost("tipo_infraestructura");
        $curso_agua              = $this->request->getPost("curso_agua");
        $diametro_colector       = $this->request->getPost("diametro_colector");
        $material_colector       = $this->request->getPost("material_colector");

        $id_apr     = $this->sesion->id_apr_ses;
        $id_usuario = $this->sesion->id_usuario_ses;

        $data = [
            'id_apr'                  => $id_apr,
            'id_usuario'              => $id_usuario,
            'fecha_inicio'            => $fecha_inicio,
            'hora_inicio'             => $hora_inicio,
            'fecha_termino'           => !empty($fecha_termino) ? $fecha_termino : null,
            'hora_termino'            => !empty($hora_termino) ? $hora_termino : null,
            'duracion'                => $duracion,
            'motivo_obstruccion'      => $motivo_obstruccion,
            'cant_clientes_afectados' => $cant_clientes_afectados,
            'ubicacion_falla'         => $ubicacion_falla,
            'afloramiento'            => $afloramiento,
            'tipo_infraestructura'    => $tipo_infraestructura,
            'curso_agua'              => $curso_agua,
            'diametro_colector'       => $diametro_colector,
            'material_colector'       => $material_colector,
            'estado'                  => 1
        ];

        if (!empty($id_obstruccion)) {
            $data['id'] = $id_obstruccion;
        } else {
            $data['fecha_reg'] = date("Y-m-d H:i:s");
        }

        if ($this->obstrucciones->save($data)) {
            echo 1;
        } else {
            echo "Error al guardar el registro de obstrucción";
        }
    }

    public function eliminar_obstruccion()
    {
        $this->validar_sesion();
        $id = $this->request->getPost("id");

        $data = [
            'id'     => $id,
            'estado' => 0
        ];

        if ($this->obstrucciones->save($data)) {
            echo 1;
        } else {
            echo "Error al eliminar el registro";
        }
    }
}
