<input type="hidden" id="txt_base_url" value="<?php echo base_url(); ?>">

<div class="container-fluid px-4">
    <h1 class="mt-4">Control de Presión SSR</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Formularios</a></li>
        <li class="breadcrumb-item active">Control de Presión</li>
    </ol>

    <!-- FORMULARIO DE REGISTRO -->
    <div class="card mb-4">
        <div class="card-header font-weight-bold">
            <i class="fas fa-tachometer-alt mr-1"></i> Formulario Control de Presión
        </div>
        <div class="card-body">
            <form id="form_presion" autocomplete="off">
                <input type="hidden" id="id_presion" name="id_presion">

                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label for="id_display" class="font-weight-bold">N° Registro (ID)</label>
                        <input type="text" class="form-control form-control-sm" id="id_display" readonly placeholder="Auto">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="fecha_medicion" class="font-weight-bold">Fecha (*)</label>
                        <input type="date" class="form-control form-control-sm" id="fecha_medicion" name="fecha_medicion" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="punto_medicion" class="font-weight-bold">Punto de Medición (*)</label>
                        <input type="text" class="form-control form-control-sm" id="punto_medicion" name="punto_medicion" placeholder="Ej: Red Central / Estación 1" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="tipo_punto" class="font-weight-bold">Tipo Punto (*)</label>
                        <select class="form-control form-control-sm" id="tipo_punto" name="tipo_punto" required>
                            <option value="">Seleccione...</option>
                            <option value="Mínima">Mínima</option>
                            <option value="Máxima">Máxima</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="valor_medido" class="font-weight-bold">Valor Medido (mca) (*)</label>
                        <input type="number" step="0.01" min="0" class="form-control form-control-sm" id="valor_medido" name="valor_medido" placeholder="0.00" required>
                    </div>

                    <div class="col-md-9 mb-3">
                        <label for="observacion" class="font-weight-bold">Observación</label>
                        <textarea class="form-control form-control-sm" id="observacion" name="observacion" rows="2" placeholder="Indicar si hubo un evento particular..."></textarea>
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

    <!-- LISTADO Y FILTROS -->
    <div class="card mb-4">
        <div class="card-header font-weight-bold">
            <i class="fas fa-table mr-1"></i> Registro de Mediciones de Presión
        </div>
        <div class="card-body">
            <!-- Filtro por Rango de Fechas -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="f_desde" class="font-weight-bold">Fecha Desde:</label>
                    <input type="date" id="f_desde" class="form-control form-control-sm">
                </div>
                <div class="col-md-3">
                    <label for="f_hasta" class="font-weight-bold">Fecha Hasta:</label>
                    <input type="date" id="f_hasta" class="form-control form-control-sm">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" id="btn_filtrar_fechas" class="btn btn-primary btn-sm mr-2">
                        <i class="fas fa-search mr-1"></i> Filtrar
                    </button>
                    <button type="button" id="btn_limpiar_fechas" class="btn btn-secondary btn-sm">
                        <i class="fas fa-undo mr-1"></i> Limpiar
                    </button>
                </div>
            </div>

            <!-- Tabla DataTables -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="grid_presiones" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Punto Medición</th>
                            <th>Tipo Punto</th>
                            <th>Valor (mca)</th>
                            <th>Observación</th>
                            <th width="10%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>/js/Formularios/control_presion.js"></script>