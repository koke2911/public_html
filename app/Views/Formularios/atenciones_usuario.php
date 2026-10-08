<div class="container-fluid px-4">
    <h1 class="mt-4">Registro de Atenciones SSR</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Formularios</a></li>
        <li class="breadcrumb-item active">Atenciones</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header font-weight-bold">
            <i class="fas fa-headset mr-1"></i> Formulario de Atención
        </div>
        <div class="card-body">
            <form id="form_atencion" autocomplete="off">
                <input type="hidden" id="id_atencion" name="id_atencion">
                <input type="hidden" id="id_socio" name="id_socio">

                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label for="numero_atencion" class="font-weight-bold">N° Atención (*)</label>
                        <input type="text" class="form-control form-control-sm" id="numero_atencion" name="numero_atencion" readonly required>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="fecha_atencion" class="font-weight-bold">Fecha (*)</label>
                        <input type="date" class="form-control form-control-sm" id="fecha_atencion" name="fecha_atencion" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="numero_contrato" class="font-weight-bold">N° Contrato o Rol (*)</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="numero_contrato" name="numero_contrato" required>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="btn_buscar_socio" title="Buscar Socio">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="nombre_usuario" class="font-weight-bold">Nombre Usuario (*)</label>
                        <input type="text" class="form-control form-control-sm" id="nombre_usuario" name="nombre_usuario" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="atencion_requerida" class="font-weight-bold">Atención Requerida (*)</label>
                        <textarea class="form-control form-control-sm" id="atencion_requerida" name="atencion_requerida" rows="3" required></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="respuesta_ssr" class="font-weight-bold">Respuesta del SSR</label>
                        <textarea class="form-control form-control-sm" id="respuesta_ssr" name="respuesta_ssr" rows="3"></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="fecha_respuesta" class="font-weight-bold">Fecha de Respuesta SSR</label>
                        <input type="date" class="form-control form-control-sm" id="fecha_respuesta" name="fecha_respuesta">
                    </div>
                </div>

                <div class="text-right">
                    <button type="button" class="btn btn-secondary btn-sm" id="btn_limpiar">
                        <i class="fas fa-eraser mr-1"></i> Limpiar
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" id="btn_guardar">
                        <i class="fas fa-save mr-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header font-weight-bold">
            <i class="fas fa-table mr-1"></i> Listado de Atenciones
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="f_desde"><strong>Fecha Desde:</strong></label>
                <input type="date" id="f_desde" class="form-control form-control-sm">
            </div>
            <div class="col-md-3">
                <label for="f_hasta"><strong>Fecha Hasta:</strong></label>
                <input type="date" id="f_hasta" class="form-control form-control-sm">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="button" id="btn_filtrar_fechas" class="btn btn-primary btn-sm mr-2">
                    <i class="fas fa-search"></i> Filtrar
                </button>
                <button type="button" id="btn_limpiar_fechas" class="btn btn-secondary btn-sm">
                    <i class="fas fa-undo"></i> Limpiar
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="grid_atenciones" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>N° Atención</th>
                            <th>Fecha</th>
                            <th>N° Contrato o Rol</th>
                            <th>Usuario</th>
                            <th>Atención Requerida</th>
                            <th>Fecha Resp. SSR</th>
                            <th>Respuesta SSR</th>
                            <th width="10%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL BÚSQUEDA DE SOCIOS -->
<div class="modal fade" id="modal_buscar_socio" tabindex="-1" role="dialog" aria-labelledby="modalSocioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalSocioLabel"><i class="fas fa-users mr-1"></i> Seleccionar Socio / Usuario</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="grid_socios_modal" width="100%">
                        <thead class="thead-dark">
                            <tr>
                                <th>RUT</th>
                                <th>N° Rol/Contrato</th>
                                <th>Nombre Completo</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>/js/Formularios/atenciones_usuario.js"></script>