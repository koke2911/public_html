<main>
    <div class="container-fluid">
        <h3 class="mt-4" align="center"><i class="fas fa-university"></i> Banco <span class="badge badge-secondary" style="font-size: 0.5em;">Módulo Transacciones</span></h3>
        <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>

        <div class="container-fluid">
            <br>
            <!-- Botones Principales de Control -->
            <div class="card shadow mb-12">
                <div class="card-body">
                    <div class="container-fluid">
                        <center>
                            <button type="button" name="btn_nueva_cuenta" id="btn_nueva_cuenta" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Nueva transaccion</button>
                            <button type="button" name="btn_procesar" id="btn_procesar" class="btn btn-success"><i class="fas fa-check-circle"></i> Procesar Transacción</button>
                            <button type="button" name="btn_cancelar" id="btn_cancelar" class="btn btn-danger"><i class="fas fa-ban"></i> Cancelar</button>
                            <!-- <button type="button" name="btn_reciclar" id="btn_reciclar" class="btn btn-warning"><i class="fas fa-recycle"></i> Reciclar Movimiento</button> -->
                        </center>
                    </div>
                </div>
            </div>
            <br>

            <!-- Selección de Cuenta y Métricas de Saldo -->
            <div class="row">
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 mb-4">
                    <div class="card shadow h-100 py-2">
                        <div class="card-body">
                            <div class="form-group mb-0">
                                <label class="small mb-1 font-weight-bold" for="cmb_cuenta_bancaria">SELECCIONAR CUENTA BANCARIA</label>
                                <select id="cmb_cuenta_bancaria" name="cmb_cuenta_bancaria" class="form-control">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-4">
                    <div class="card bg-dark text-white shadow h-100 py-2">
                        <div class="card-body text-center p-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Saldo Total Disponible</div>
                            <div class="h4 mb-0 font-weight-bold text-success" id="lbl_saldo_total">--</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-4">
                    <div class="card shadow h-100 py-2">
                        <div class="card-body text-center p-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">N° Transacciones</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800" id="lbl_num_transacciones">--</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección Principal: Operaciones e Historial -->
            <div class="row">
                <!-- Panel Izquierdo: Nueva Operación -->
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                    <div class="card mb-4 shadow">
                        <div class="card-header">
                            <i class="fas fa-exchange-alt"></i> Nueva Operación
                            <small class="d-block text-muted">Registrar abono o extracción</small>
                        </div>
                        <div class="card-body">
                            <form id="form_operacion" name="form_operacion" encType="multipart/form-data">
                                <div class="form-group">
                                    <label class="small mb-1">Tipo de Operación</label>
                                    <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                        <label class="btn btn-outline-success active">
                                            <input type="radio" name="opt_tipo_operacion" id="opt_abono" value="abono" checked> <i class="fas fa-arrow-down"></i> Abono (+)
                                        </label>
                                        <label class="btn btn-outline-secondary">
                                            <input type="radio" name="opt_tipo_operacion" id="opt_extraccion" value="extraccion"> <i class="fas fa-arrow-up"></i> Extracción (-)
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="small mb-1" for="txt_monto">Monto ($)</label>
                                    <input type="number" step="0.01" class="form-control" name="txt_monto" id="txt_monto" placeholder="$ 0.00" />
                                </div>
                                <div class="form-group">
                                    <label class="small mb-1" for="txt_concepto">Concepto / Cuenta Destino (X)</label>
                                    <input type="text" class="form-control" name="txt_concepto" id="txt_concepto" placeholder="Ej. Depósito por nómina, Pago servicio..." />
                                </div>
                                <button type="button" name="btn_procesar_transaccion" id="btn_procesar_transaccion" class="btn btn-success btn-block">
                                    <i class="fas fa-check-circle"></i> Procesar Transacción
                                </button>
                            </form>
                        </div>
                        <div class="card-footer small text-muted">
                            <i class="fas fa-info-circle"></i> Las operaciones actualizan de inmediato el historial y recalculan el saldo proyectado.
                        </div>
                    </div>
                </div>

                <!-- Panel Derecho: Historial de Movimientos -->
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
                    <div class="card mb-4 shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-history"></i> Historial de Movimientos
                                <small class="d-block text-muted">Registro detallado de ingresos y egresos</small>
                            </div>
                            <div class="form-inline">
                                <input type="text" class="form-control form-control-sm mr-2" id="txt_buscar_movimiento" placeholder="Buscar...">
                                <select id="cmb_filtro_tipo" class="form-control form-control-sm">
                                    <option value="todos">Todos</option>
                                    <option value="abono">Abono</option>
                                    <option value="extraccion">Extracción</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="grid_movimientos" class="table table-bordered table-hover" width="100%">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Fecha / Hora</th>
                                            <th>Tipo</th>
                                            <th>Concepto / Destino</th>
                                            <th>Monto</th>
                                            <th>Saldo Resultante</th>
                                            <th width="1%">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Filas dinámicas -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modales Estructurados -->
            <div id="dlg_traza_movimiento" class="modal fade" role="dialog">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Trazabilidad de la Transacción</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div id="divContenedorTrazaMovimiento"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="dlg_reciclar_movimiento" class="modal fade" role="dialog">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Reciclar Movimiento (Doble click para reciclar)</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div id="divContenedorReciclarMovimiento"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Finanzas/banco.js"></script>