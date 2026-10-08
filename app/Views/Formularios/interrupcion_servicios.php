<div class="container-fluid px-4">
    <h1 class="mt-4">Interrupción de Servicios SSR</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Formularios</a></li>
        <li class="breadcrumb-item active">Interrupción de Servicios</li>
    </ol>
    <input type="hidden" id="txt_base_url" value="<?php echo base_url(); ?>">
    <!-- FORMULARIO DE REGISTRO -->
    <div class="card mb-4">
        <div class="card-header font-weight-bold">
            <i class="fas fa-exclamation-triangle mr-1"></i> Formulario Interrupción de Servicio
        </div>
        <div class="card-body">
            <form id="form_interrupcion" autocomplete="off">
                <input type="hidden" id="id_interrupcion" name="id_interrupcion">

                <!-- Fila 1 -->
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label for="id_display" class="font-weight-bold">N° Registro (ID)</label>
                        <input type="text" class="form-control form-control-sm" id="id_display" readonly placeholder="Auto">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="tipo_interrupcion" class="font-weight-bold">Tipo Interrupción (*)</label>
                        <select class="form-control form-control-sm" id="tipo_interrupcion" name="tipo_interrupcion" required>
                            <option value="">Seleccione...</option>
                            <option value="Caso fortuito">Caso fortuito</option>
                            <option value="Programada">Programada</option>
                            <option value="Fuerza Mayor">Fuerza Mayor</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="nivel_afectacion" class="font-weight-bold">Nivel de Afectación (*)</label>
                        <select class="form-control form-control-sm" id="nivel_afectacion" name="nivel_afectacion" required>
                            <option value="">Seleccione...</option>
                            <option value="Parcial">Parcial</option>
                            <option value="Total">Total</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="fecha_inicio" class="font-weight-bold">Fecha Inicio (*)</label>
                        <input type="date" class="form-control form-control-sm" id="fecha_inicio" name="fecha_inicio" required>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="hora_inicio" class="font-weight-bold">Hora Inicio (*)</label>
                        <input type="time" class="form-control form-control-sm" id="hora_inicio" name="hora_inicio" required>
                    </div>
                </div>

                <!-- Fila 2 -->
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label for="fecha_termino" class="font-weight-bold">Fecha Término</label>
                        <input type="date" class="form-control form-control-sm" id="fecha_termino" name="fecha_termino">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="hora_termino" class="font-weight-bold">Hora Término</label>
                        <input type="time" class="form-control form-control-sm" id="hora_termino" name="hora_termino">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="cant_usuarios_afectados" class="font-weight-bold">Usuarios Afectados (*)</label>
                        <input type="number" class="form-control form-control-sm" id="cant_usuarios_afectados" name="cant_usuarios_afectados" min="0" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="diametro_matriz" class="font-weight-bold">Diámetro Matriz (mm)</label>
                        <input type="text" class="form-control form-control-sm" id="diametro_matriz" name="diametro_matriz">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="material_matriz" class="font-weight-bold">Material Matriz</label>
                        <input type="text" class="form-control form-control-sm" id="material_matriz" name="material_matriz">
                    </div>
                </div>

                <!-- Fila 3 -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="motivo_interrupcion" class="font-weight-bold">Motivo de la Interrupción (*)</label>
                        <textarea class="form-control form-control-sm" id="motivo_interrupcion" name="motivo_interrupcion" rows="3" required></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="ubicacion_falla" class="font-weight-bold">Ubicación Falla (Dirección / Referencia) (*)</label>
                        <textarea class="form-control form-control-sm" id="ubicacion_falla" name="ubicacion_falla" rows="3" required></textarea>
                    </div>
                </div>

                <!-- Botones de Acción -->
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

    <!-- LISTADO Y FILTROS POR FECHA -->
    <div class="card mb-4">
        <div class="card-header font-weight-bold">
            <i class="fas fa-table mr-1"></i> Listado de Interrupciones
        </div>
        <div class="card-body">
            <!-- Filtro Rango de Fechas -->
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
                <table class="table table-bordered table-striped table-hover" id="grid_interrupciones" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Tipo</th>
                            <th>Nivel</th>
                            <th>F. Inicio</th>
                            <th>H. Inicio</th>
                            <th>F. Término</th>
                            <th>H. Término</th>
                            <th>Usuarios</th>
                            <th>Diámetro (mm)</th>
                            <th>Material</th>
                            <th>Motivo</th>
                            <th>Ubicación</th>
                            <th width="10%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>/js/Formularios/interrupcion_servicios.js"></script>