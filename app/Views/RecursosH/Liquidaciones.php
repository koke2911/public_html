<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-briefcase"></i> Liquidaciones</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>

    <div class="container-fluid">
      <br>
      <div class="card shadow mb-12">
        <div class="card-body">
          <div class="container-fluid">
            <center>
              <button type="button" name="btn_nuevo" id="btn_nuevo" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo</button>
              <!-- <button type="button" name="btn_modificar" id="btn_modificar" class="btn btn-primary"><i class="fas fa-edit"></i> Modificar</button>
              <button type="button" name="btn_eliminar" id="btn_eliminar" class="btn btn-primary"><i class="fas fa-trash"></i> Eliminar</button> -->
              <button type="button" name="btn_aceptar" id="btn_aceptar" class="btn btn-success"><i class="fas fa-check"></i> Aceptar</button>
              <button type="button" name="btn_cancelar" id="btn_cancelar" class="btn btn-danger"><i class="fas fa-ban"></i> Cancelar</button>
              
            </center>
          </div>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header" data-toggle="collapse" data-target="#datosLiquidacion" aria-expanded="false" aria-controls="datosLiquidacion">
              <i class="fas fa-briefcase"></i> Generar liquidacion
            </div>
            <div class="card shadow mb-12 collapse" id="datosLiquidacion">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_liquidaciones" name="form_liquidaciones" encType="multipart/form-data">
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_id_funcionario">Identificador</label>
                          <input type="text" class="form-control" name="txt_id_funcionario" id="txt_id_funcionario"/>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_rut">RUT (Solo primeros digitos ej:11111111 )</label>
                          <input type='text' class="form-control" id='txt_rut' name="txt_rut"/>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_rut"></label>
                          <button type='button' class="btn btn-primary" id='btn_buscar' name="btn_buscar" style="margin-top: 7%;">Buscar</button>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_nombres">Nombre</label>
                          <input type='text' class="form-control" id='txt_nombres' name="txt_nombres"/>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_ape_pat">Apellido Paterno</label>
                          <input type='text' class="form-control" id='txt_ape_pat' name="txt_ape_pat"/>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_ape_mat">Apellido Materno</label>
                          <input type='text' class="form-control" id='txt_ape_mat' name="txt_ape_mat"/>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_prevision">Prevision (FONASA - ISAPRE)</label>
                          <input class="form-control" id="txt_prevision" name="txt_prevision"></input>
                        </div>
                      </div>
                      <div class="col-xl-1 col-lg-1 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_prevision_porcen">% o UF</label>
                          <input class="form-control" id="txt_prevision_porcen" name="txt_prevision_porcen"></input>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_afp">AFP</label>
                          <input class="form-control" id="txt_afp" name="txt_afp"></input>
                        </div>
                      </div>
                      <div class="col-xl-1 col-lg-1 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_afp_porcent">% AFP</label>
                          <input class="form-control" id="txt_afp_porcent" name="txt_afp_porcent"></input>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_sueldo_bruto">Sueldo Bruto</label>
                          <input class="form-control" id="txt_sueldo_bruto" name="txt_sueldo_bruto"></input>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="dt_contrato">Fecha Contrato</label>
                          <input class="form-control" id="dt_contrato" name="dt_contrato"></input>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_jornada">Jornada (Dias)</label>
                          <input class="form-control" id="txt_jornada" name="txt_jornada"></input>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="dt_mes">Mes Liquidacion</label>
                          <input class="form-control" id="dt_mes" name="dt_mes"></input>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_valor_uf">Valor de UF</label>
                          <input class="form-control" id="txt_valor_uf" name="txt_valor_uf"></input>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_dias_trabajados">Días Trabajados</label>
                          <input class="form-control" id="txt_dias_trabajados" name="txt_dias_trabajados"></input>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_sueldo">Sueldo Imponible</label>
                          <input class="form-control" id="txt_sueldo" name="txt_sueldo" disabled="true"></input>
                        </div>
                      </div>
                      
                    </div>
                    <strong><h>DESCUENTOS OBLIGATORIOS</h></strong><BR>
                    <div class="row">

                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_cotizacion_afp"> $ Cotizacion AFP</label>
                          <input class="form-control" id="txt_cotizacion_afp" name="txt_cotizacion_afp"></input>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_obligatorio"> 7% obligatorio$</label>
                          <input class="form-control" id="txt_obligatorio" name="txt_obligatorio"></input>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_cotizacion_pactada"> Cotizacion Pactada $</label>
                          <input class="form-control" id="txt_cotizacion_pactada" name="txt_cotizacion_pactada"></input>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_diferencia_isapre"> Diferencia Isapre $</label>
                          <input class="form-control" id="txt_diferencia_isapre" name="txt_diferencia_isapre" value=0></input>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_afc"> Cotización AFC Trabajador $</label>
                          <input class="form-control" id="txt_afc" name="txt_afc" value=0></input>
                        </div>
                      </div>
                      
                    </div>
                    <strong><h>OTROS DESCUENTOS</h></strong><BR>
                    <div class="row">
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_otros_descuentos"> Otros $</label>
                          <input class="form-control" id="txt_otros_descuentos" name="txt_otros_descuentos" value=0 ></input>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_total_prevision"> Total Previsión $</label>
                          <input class="form-control" id="txt_total_prevision" name="txt_total_prevision" disabled="true"></input>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_base_tributable"> Base Tributable</label>
                          <input class="form-control" id="txt_base_tributable" name="txt_base_tributable" disabled="true"></input>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_cargas"> Cargas</label>
                          <input class="form-control" id="txt_cargas" name="txt_cargas"  value=0></input>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_apagar"> A pagar</label>
                          <input class="form-control" id="txt_apagar" name="txt_apagar"  value=0></input>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="input-group-btn">
                          <button type="button" name="btn_calcular" id="btn_calcular" class="btn btn-primary" style="margin-top: 13%"><i class="fas fa-plus"></i> Calcular</button>
                        </div>
                      </div>                      
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header"><i class="fas fa-briefcase"></i> Listado de liquidaciones</div>
            <div class="card shadow mb-12">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_liquidaciones" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>id</th>
                          <th>rut</th>
                          <th>funcionario</th>
                          <th>mes</th>
                          <th>valor_uf</th>
                          <th>dias_trabajados</th>
                          <th>sueldo_bruto</th>
                          <th>afp</th>
                          <th>obligatorio</th>
                          <th>pactada</th>
                          <th>diferencia_isapre</th>
                          <th>afc</th>
                          <th>otros</th>
                          <th>total_prevision</th>
                          <th>base_tributable</th>
                          <th>cargas</th>
                          <th>a_pagar</th>
                          <th>id_apr</th>
                          <th>fecha_genera</th>
                          <th>usuario_genera</th>
                          <th>imprimir</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      
    </div>
  </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/RecursosH/Liquidaciones.js"></script>