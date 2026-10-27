<div class="modal fade" id="modal_modulo_alertas" tabindex="-1" role="dialog" aria-labelledby="modalAlertasLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalAlertasLabel">
                    <i class="fas fa-bell mr-1"></i> Módulo de Indicadores y Alertas
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <!-- Tarjetas KPI Resumen -->
                <div class="row mb-3">
                    <div class="col-md-4 mb-2">
                        <div class="card border-left-danger shadow h-100 py-2 border-danger">
                            <div class="card-body py-2">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Lecturas Vencidas / Morosos</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="kpi_morosos">0</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-times fa-2x text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-2">
                        <div class="card border-left-warning shadow h-100 py-2 border-warning">
                            <div class="card-body py-2">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Vencen Hoy</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="kpi_vencen">0</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-2">
                        <div class="card border-left-info shadow h-100 py-2 border-info">
                            <div class="card-body py-2">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Consumos Anómalos (>50m³)</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="kpi_anomalos">0</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-tachometer-alt fa-2x text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pestañas de Selección -->
                <ul class="nav nav-pills mb-3" id="pills-tab-alertas" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" style="padding: 0.5rem 1rem" id="btn_tab_morosos" data-toggle="pill" href="#tab_detalle" role="tab" onclick="filtrarTablaModal('morosos')">
                            <i class="fas fa-exclamation-circle mr-1"></i> Morosos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="padding: 0.5rem 1rem" id="btn_tab_vencen" data-toggle="pill" href="#tab_detalle" role="tab" onclick="filtrarTablaModal('vencen_hoy')">
                            <i class="fas fa-calendar-times mr-1"></i> Vencen Hoy
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="padding: 0.5rem 1rem" id="btn_tab_anomalos" data-toggle="pill" href="#tab_detalle" role="tab" onclick="filtrarTablaModal('anomalos')">
                            <i class="fas fa-chart-line mr-1"></i> Consumos Anómalos
                        </a>
                    </li>
                </ul>

                <!-- Tabla DataTable de Detalle -->
                <!-- Tabla de Detalle -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover w-100" id="grid_alertas_detalle">
                        <thead class="thead-dark">
                            <tr>
                                <th>Socio</th>
                                <th>Mes Consumo</th>
                                <th>F. Vencimiento</th>
                                <th>Consumo Anterior</th>
                                <th>Consumo Actual</th>
                                <th>Diferencia (m³)</th>
                                <th>Total ($)</th>
                                <th>Tipo DTE</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>