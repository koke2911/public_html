<main id="main" class="main-content p-3">
  <div class="page active" id="page-dashboard">

    <!-- Encabezado con sesión y fecha -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h3 class="font-weight-bold mb-1">
          <i class="fas fa-chart-line text-primary mr-2"></i>Bienvenido,
          <?php
          $sesión = session();
          echo htmlspecialchars($sesión->apr_ses ?? 'Usuario', ENT_QUOTES, 'UTF-8');
          ?>
        </h3>
        <span class="text-muted small">Resumen operacional · <?php echo date('d/m/Y'); ?></span>
      </div>
    </div>

    <!-- Indicadores Rápidos (Resumen Estadístico) -->
    <div class="stats-grid">
      <!-- Card 1: Socios Activos -->
      <div class="stat-card" id="card_indicador_socios" style="cursor: pointer;">
        <div class="stat-value" id="indicador_socios_total">--</div>
        <div class="stat-label">Socios Activos</div>
        <div class="stat-icon">👤</div>
        <div class="stat-delta up" id="indicador_socios_delta">▲ +0 este mes</div>
      </div>

      <!-- Card 2: Recaudación Mensual -->
      <div class="stat-card gold" id="card_indicador_recaudacion" style="cursor: pointer;">
        <div class="stat-value" id="recaudacion_total" style="color:#DC9F1F">$0</div>
        <div class="stat-label">Recaudación Mensual</div>
        <div class="stat-icon">💰</div>
        <div class="stat-delta up" id="recaudacion_delta">▲ 0% vs mes anterior</div>
      </div>

      <!-- Card 3: Tasa de Pago -->
      <div class="stat-card green" id="card_indicador_tasa" style="cursor: pointer;">
        <div class="stat-value" id="tasa_pago_total" style="color:#0DAD69">0%</div>
        <div class="stat-label">Tasa de Pago</div>
        <div class="stat-icon">✅</div>
        <div class="stat-delta up" id="tasa_pago_delta">▲ +0% vs promedio</div>
      </div>

      <!-- Card 4: Deudas Pendientes -->
      <div class="stat-card red" id="card_indicador_deudas" style="cursor: pointer;">
        <div class="stat-value" id="deudas_total" style="color:#E3415E">0</div>
        <div class="stat-label">Deudas Pendientes</div>
        <div class="stat-icon">⚠️</div>
        <div class="stat-delta down" id="deudas_delta">▼ −0 desde la semana pasada</div>
      </div>
    </div>


    <!-- Sección Inferior: Gráfico + Grid Últimos Pagos -->
    <div class="row">
      <!-- Gráfico de Mensualidad -->
      <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border: 1px solid rgba(255,109,57,0.15) !important;">
          <div class="card-header bg-transparent border-0 pt-3 pb-0 d-flex align-items-center">
            <span style="width: 4px; height: 16px; background-color: #FF6D39; border-radius: 2px; display: inline-block; margin-right: 8px;"></span>
            <h6 class="font-weight-bold mb-0 text-uppercase text-muted" style="letter-spacing: 0.5px; font-size: 0.8rem;">
              Recaudación Mensual (Últimos 8 Meses)
            </h6>
          </div>
          <div class="card-body">
            <div style="position: relative; width: 100%; height: 280px;">
              <canvas id="graf_mensual"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Grid / Tabla de Últimos Pagos Registrados -->
      <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-transparent border-0 font-weight-bold pt-3 pb-0 d-flex justify-content-between align-items-center">
            <span><i class="fas fa-receipt text-success mr-1"></i> Últimos Pagos Registrados</span>
            <a href="#" id="card_historial_pagos" class="text-primary text-xs font-weight-bold">Ver Todos</a>
          </div>
          <div class="card-body p-0 px-3 px-md-0 mt-3">
            <div class="table-responsive">
              <table class="table table-hover align-items-center table-flush mb-0" id="tabla_ultimos_pagos">
                <thead class="thead-light">
                  <tr>
                    <th class="border-0">Socio</th>
                    <th class="border-0">Monto</th>
                    <th class="border-0">Fecha</th>
                    <th class="border-0 text-center">Estado</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Los registros dinámicos se renderizan mediante JavaScript -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</main>

<script type="text/javascript" src="<?php echo base_url(); ?>/js/dashboard.js"></script>