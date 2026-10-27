<input type="hidden" id="txt_base_url" value="<?php echo base_url(); ?>">

<div class="container-fluid px-4">
    <h1 class="mt-4">Registro de Obstrucciones Alcantarillado</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Formularios</a></li>
        <li class="breadcrumb-item active">Obstrucciones</li>
    </ol>

    <!-- FORMULARIO DE REGISTRO -->
    <div class="card mb-4">
        <div class="card-header font-weight-bold">
            <i class="fas fa-exclamation-circle mr-1"></i> Formulario de Obstrucciones
        </div>
        <div class="card-body">
            <form id="form_obstruccion" autocomplete="off">
                <input type="hidden" id="id_obstruccion" name="id_obstruccion">

                <!-- Fila 1 -->
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label for="id_display" class="font-weight-bold">N° Registro (ID)</label>
                        <input type="text" class="form-control form-control-sm" id="id_display" readonly placeholder="Auto">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="fecha_inicio" class="font-weight-bold">Fecha Inicio (*)</label>
                        <input type="date" class="form-control form-control-sm" id="fecha_inicio" name="fecha_inicio" required>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="hora_inicio" class="font-weight-bold">Hora Inicio (*)</label>
                        <input type="time" class="form-control form-control-sm" id="hora_inicio" name="hora_inicio" required>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="fecha_termino" class="font-weight-bold">Fecha Término</label>
                        <input type="date" class="form-control form-control-sm" id="fecha_termino" name="fecha_termino">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="hora_termino" class="font-weight-bold">Hora Término</label>
                        <input type="time" class="form-control form-control-sm" id="hora_termino" name="hora_termino">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="duracion" class="font-weight-bold">Duración (h:mm)</label>
                        <input type="text" class="form-control form-control-sm" id="duracion" name="duracion" readonly placeholder="Calculado">
                    </div>
                </div>

                <!-- Fila 2 -->
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label for="cant_clientes_afectados" class="font-weight-bold">N° Clientes Afectados (*)</label>
                        <input type="number" min="0" class="form-control form-control-sm" id="cant_clientes_afectados" name="cant_clientes_afectados" required>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="afloramiento" class="font-weight-bold">Afloramiento (*)</label>
                        <select class="form-control form-control-sm" id="afloramiento" name="afloramiento" required>
                            <option value="">Seleccione...</option>
                            <option value="SI">SI</option>
                            <option value="NO">NO</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="tipo_infraestructura" class="font-weight-bold">Infraestructura Afectada (*)</label>
                        <select class="form-control form-control-sm" id="tipo_infraestructura" name="tipo_infraestructura" required>
                            <option value="">Seleccione...</option>
                            <option value="UD">UD</option>
                            <option value="Colector">Colector</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="curso_agua" class="font-weight-bold">Curso de Agua (*)</label>
                        <select class="form-control form-control-sm" id="curso_agua" name="curso_agua" required>
                            <option value="">Seleccione...</option>
                            <option value="SI">SI</option>
                            <option value="NO">NO</option>
                        </select>
                    </div>

                    <div class="col-md-1 mb-3">
                        <label for="diametro_colector" class="font-weight-bold">Diámetro (mm)</label>
                        <input type="text" class="form-control form-control-sm" id="diametro_colector" name="diametro_colector">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="material_colector" class="font-weight-bold">Material</label>
                        <input type="text" class="form-control form-control-sm" id="material_colector" name="material_colector">
                    </div>
                </div>

                <!-- Fila 3 -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="motivo_obstruccion" class="font-weight-bold">Motivo de la Obstrucción (*)</label>
                        <textarea class="form-control form-control-sm" id="motivo_obstruccion" name="motivo_obstruccion" rows="2" required></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="ubicacion_falla" class="font-weight-bold">Ubicación Falla (Dirección / Referencia) (*)</label>
                        <textarea class="form-control form-control-sm" id="ubicacion_falla" name="ubicacion_falla" rows="2" required></textarea>
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
            <i class="fas fa-table mr-1"></i> Listado de Obstrucciones
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
                <table class="table table-bordered table-striped table-hover" id="grid_obstrucciones" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>F. Inicio</th>
                            <th>H. Inicio</th>
                            <th>F. Término</th>
                            <th>H. Término</th>
                            <th>Duración</th>
                            <th>Clientes</th>
                            <th>Afloramiento</th>
                            <th>Infraest.</th>
                            <th>Curso Agua</th>
                            <th>Diámetro</th>
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

<script src="<?php echo base_url(); ?>/js/Formularios/obstruccion.js"></script>