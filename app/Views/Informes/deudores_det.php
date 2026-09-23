<main>
    <div class="container-fluid">
        <h3 class="mt-4" align="center"><i class="fas fa-user-clock"></i> Informe de Deudores <span class="badge badge-secondary" style="font-size: 0.5em;">Gestión de Morosidad</span></h3>
        <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>

        <div class="container-fluid">
            <br>
            <!-- Botones Principales de Control -->
            <div class="card shadow mb-12">
                <div class="card-body">
                    <div class="container-fluid">
                        <center>
                            <button type="button" name="btn_exportar_pdf" id="btn_exportar_pdf" class="btn btn-danger"><i class="fas fa-file-pdf"></i> Exportar a PDF</button>
                            <button type="button" name="btn_imprimir" id="btn_imprimir" class="btn btn-secondary"><i class="fas fa-print"></i> Imprimir Informe</button>
                        </center>
                    </div>
                </div>
            </div>
            <br>

            <!-- Selección de Filtro y Métricas -->
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-4">
                    <div class="card shadow h-100 py-2">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-md-5">
                                    <label class="small mb-1 font-weight-bold" for="cmb_tipo_criterio">CRITERIO</label>
                                    <select id="cmb_tipo_criterio" name="cmb_tipo_criterio" class="form-control">
                                        <option value="mayor_igual">Mayor o Igual a (≥)</option>
                                        <option value="exacto">Exactamente igual (=)</option>
                                    </select>
                                </div>
                                <div class="col-md-7">
                                    <label class="small mb-1 font-weight-bold" for="cmb_meses_mora">CANTIDAD DE MESES</label>
                                    <select id="cmb_meses_mora" name="cmb_meses_mora" class="form-control">
                                        <option value="1">1 Mes (30 Días)</option>
                                        <option value="2" selected>2 Meses (60 Días)</option>
                                        <option value="3">3 Meses (90 Días - Criterio Corte)</option>
                                        <option value="4">4 Meses o más (Mora Crítica)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 mb-4">
                    <div class="card bg-warning text-white shadow h-100 py-2">
                        <div class="card-body text-center p-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">N° Deudores</div>
                            <div class="h4 mb-0 font-weight-bold text-dark" id="lbl_cant_deudores">--</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 mb-4">
                    <div class="card bg-danger text-white shadow h-100 py-2">
                        <div class="card-body text-center p-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Adeudado</div>
                            <div class="h4 mb-0 font-weight-bold text-white" id="lbl_total_adeudado">$0</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 mb-4">
                    <div class="card bg-dark text-white shadow h-100 py-2">
                        <div class="card-body text-center p-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Promedio Deuda</div>
                            <div class="h4 mb-0 font-weight-bold text-info" id="lbl_promedio_deuda">$0</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección Principal: Tabla de Deudores -->
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="card mb-4 shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-users-slash"></i> Listado de Socios Deudores
                                <small class="d-block text-muted">Socios con facturación o boletas impagas pendientes</small>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="grid_deudores" class="table table-bordered table-hover" width="100%">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>id_socio</th>
                                            <th>N° Rol</th>
                                            <th>RUT</th>
                                            <th>Nombre Completo del Socio</th>
                                            <th class="text-center">Meses Pendientes</th>
                                            <th class="text-right">Total Deuda ($)</th>
                                            <th width="1%">Detalle</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Filas dinámicas -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer small text-muted">
                            <i class="fas fa-info-circle"></i> Los montos y meses pendientes se actualizan automáticamente según las lecturas y cobros vigentes.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Detalle Boletas del Socio -->
            <div id="dlg_detalle_deuda" class="modal fade" role="dialog">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-dark text-white">
                            <h4 class="modal-title"><i class="fas fa-file-invoice-dollar"></i> Detalle de Boletas y Documentos Impagos</h4>
                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <!-- Información Resumida del Socio -->
                            <div class="alert alert-secondary d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <strong id="lbl_detalle_socio">Socio: --</strong><br>
                                    <small id="lbl_detalle_rut">RUT: --</small>
                                </div>
                                <div class="text-right">
                                    <span class="text-muted small d-block">TOTAL DEUDA</span>
                                    <strong class="h5 text-danger mb-0" id="lbl_detalle_total">$0</strong>
                                </div>
                            </div>

                            <!-- Tabla de Detalle -->
                            <div class="table-responsive">
                                <table id="grid_detalle_deuda" class="table table-bordered table-striped table-hover" width="100%">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>N° Reg.</th>
                                            <th>Folio Doc.</th>
                                            <th>F. Vencimiento</th>
                                            <th class="text-right">Consumo (m³)</th>
                                            <th class="text-right">Monto Adeudado ($)</th>
                                            <th width="1%" class="text-center">Boleta</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Carga dinámica vía Ajax -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Informes/informe_deudores_det.js"></script>