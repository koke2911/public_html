<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <meta name="color-scheme" content="light">
  <title>Software APR – Iniciar Sesión</title>

  <link rel="icon" href="<?php echo base_url(); ?>/softwareapr-logo-full.png" type="image/png" />
  <script src="<?php echo base_url(); ?>/js/all.min.js" crossorigin="anonymous"></script>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap');

    :root {
      --water-deep: #FAF6F1;
      --water-mid: #FFFFFF;
      --water-surface: #FDE8D8;
      --aqua-bright: #FF5A1F;
      --aqua-glow: #FF8A3D;
      --aqua-deep: #C2410C;
      --cyan-soft: #FFB876;
      --brand-blue: #1D6FA8;
      --brand-blue-deep: #0B3C5D;
      --brand-blue-light: #6FC0EE;
      --gold: #DB9E1E;
      --text-primary: #271A0F;
      --text-secondary: #8A7362;
      --text-muted: #B7A290;
      --glass-border: rgba(255, 90, 31, 0.22);
      --card-bg: rgba(255, 255, 255, 0.78);
      --panel-light: #E9F3FA;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: var(--water-deep);
      color: var(--text-primary);
      min-height: 100vh;
      overflow: hidden;
    }

    /* Utilidades tomadas del login original */
    .hidden {
      display: none !important;
    }

    .alerta-fijo {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 9999;
      min-width: 280px;
      padding: 12px 20px;
      border-radius: 8px;
      font-size: 14px;
    }

    .shell {
      display: grid;
      grid-template-columns: 1.1fr 1fr;
      min-height: 100vh;
    }

    /* ─── LEFT: VISUAL PANEL ─── */
    .visual {
      position: relative;
      background:
        radial-gradient(circle at 30% 20%, rgba(255, 90, 31, 0.16), transparent 55%),
        radial-gradient(circle at 80% 85%, rgba(29, 111, 168, 0.14), transparent 50%),
        linear-gradient(160deg, #FFFFFF 0%, var(--water-deep) 55%, var(--water-surface) 100%);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 56px;
      overflow: hidden;
    }

    .visual::before {
      content: '';
      position: absolute;
      inset: 0;
      background-image:
        linear-gradient(rgba(255, 90, 31, 0.07) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 90, 31, 0.07) 1px, transparent 1px);
      background-size: 42px 42px;
      mask-image: radial-gradient(ellipse 70% 60% at 50% 45%, black 30%, transparent 75%);
      animation: gridDrift 30s linear infinite;
    }

    @keyframes gridDrift {
      0% {
        background-position: 0 0, 0 0;
      }

      100% {
        background-position: 42px 84px, 42px 84px;
      }
    }

    .visual-brand {
      position: relative;
      display: flex;
      align-items: center;
      gap: 12px;
      z-index: 2;
    }

    .visual-brand .brand-full-logo {
      height: 64px;
      display: inline-block;
      position: relative;
    }

    .visual-brand .brand-full-logo img {
      height: 100%;
      width: auto;
      display: block;
    }

    .visual-center {
      position: relative;
      z-index: 2;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      flex: 1;
      gap: 30px;
    }

    .tower-wrap {
      position: relative;
      width: 220px;
      height: 220px;
    }

    .ring {
      position: absolute;
      inset: 0;
      border-radius: 50%;
      border: 1px solid var(--glass-border);
    }

    .ring.r2 {
      inset: 22px;
      animation: spin 22s linear infinite;
      border-style: dashed;
      border-color: rgba(255, 90, 31, 0.26);
    }

    .ring.r3 {
      inset: 44px;
      animation: spin 16s linear infinite reverse;
      border-color: rgba(29, 111, 168, 0.28);
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }

    .tower-icon {
      position: absolute;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 64px;
      filter: drop-shadow(0 0 26px rgba(255, 90, 31, 0.5));
    }

    .pulse-dot {
      position: absolute;
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: var(--aqua-glow);
      box-shadow: 0 0 10px var(--aqua-glow);
      animation: orbit 22s linear infinite;
    }

    @keyframes orbit {
      from {
        transform: rotate(0deg) translateX(110px) rotate(0deg);
      }

      to {
        transform: rotate(360deg) translateX(110px) rotate(-360deg);
      }
    }

    .visual-copy {
      text-align: center;
      max-width: 380px;
    }

    .visual-copy h1 {
      font-family: 'Space Grotesk', sans-serif;
      font-weight: 600;
      font-size: 26px;
      line-height: 1.3;
      letter-spacing: -0.2px;
    }

    .visual-copy h1 em {
      color: var(--aqua-glow);
      font-style: normal;
    }

    .visual-copy p {
      margin-top: 10px;
      font-size: 13.5px;
      color: var(--text-secondary);
      line-height: 1.6;
    }

    .visual-stats {
      position: relative;
      z-index: 2;
      display: flex;
      align-items: center;
      gap: 32px;
    }

    .vstat-divider {
      width: 1px;
      height: 34px;
      background: var(--glass-border);
    }

    .vstat b {
      font-family: 'Space Grotesk', sans-serif;
      font-size: 22px;
      display: block;
      color: var(--aqua-glow);
    }

    .vstat span {
      font-size: 10.5px;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.8px;
    }

    /* ─── RIGHT: FORM PANEL ─── */
    .formside {
      background: var(--panel-light);
      color: var(--text-primary);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px;
    }

    .formcard {
      width: 100%;
      max-width: 380px;
    }

    .formcard .eyebrow {
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 1.2px;
      text-transform: uppercase;
      color: var(--aqua-deep);
      margin-bottom: 10px;
    }

    .formcard h2 {
      font-family: 'Space Grotesk', sans-serif;
      font-size: 27px;
      font-weight: 700;
      line-height: 1.25;
      color: var(--text-primary);
      margin-bottom: 6px;
    }

    .formcard .sub {
      font-size: 13.5px;
      color: var(--text-secondary);
      margin-bottom: 34px;
    }

    .field {
      margin-bottom: 22px;
      position: relative;
    }

    .field label {
      display: block;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      color: var(--text-secondary);
      margin-bottom: 7px;
    }

    .field input {
      width: 100%;
      border: none;
      border-bottom: 1.5px solid #C6DCEA;
      background: transparent;
      padding: 8px 30px 10px 2px;
      font-size: 15px;
      font-family: 'Inter', sans-serif;
      color: var(--text-primary);
      outline: none;
      transition: border-color 0.2s;
    }

    .field input::placeholder {
      color: var(--text-muted);
    }

    .field input:focus {
      border-bottom-color: var(--aqua-deep);
    }

    .field .toggle-eye {
      position: absolute;
      right: 0;
      bottom: 10px;
      cursor: pointer;
      color: var(--text-muted);
      font-size: 15px;
      user-select: none;
      background: none;
      border: none;
      padding: 4px;
    }

    .field .toggle-eye:hover {
      color: var(--aqua-deep);
    }

    .btn-ingresar {
      width: 100%;
      padding: 14px;
      margin-top: 10px;
      background: linear-gradient(135deg, var(--aqua-bright), var(--aqua-deep));
      color: #FFFFFF;
      border: none;
      border-radius: 999px;
      font-family: 'Space Grotesk', sans-serif;
      font-size: 14px;
      font-weight: 600;
      letter-spacing: 0.6px;
      cursor: pointer;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .btn-ingresar:hover {
      filter: brightness(1.08);
      transform: translateY(-1px);
      box-shadow: 0 10px 24px rgba(255, 90, 31, 0.35);
    }

    .btn-ingresar:active {
      transform: translateY(0);
    }

    .divider-line {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 30px 0 22px;
      color: var(--text-muted);
      font-size: 11.5px;
    }

    .divider-line::before,
    .divider-line::after {
      content: '';
      flex: 1;
      height: 1px;
      background: #D3E5F0;
    }

    .formcard .contact {
      text-align: center;
      font-size: 12px;
      color: var(--text-secondary);
      line-height: 1.7;
    }

    .formcard .contact a {
      color: var(--aqua-deep);
      text-decoration: none;
      font-weight: 500;
    }

    .formcard .contact a:hover {
      text-decoration: underline;
    }

    /* RESPONSIVE */
    @media (max-width: 840px) {
      .shell {
        grid-template-columns: 1fr 1fr;
      }

      .visual {
        padding: 36px;
      }

      .visual-copy h1 {
        font-size: 22px;
      }

      .visual-stats {
        gap: 20px;
      }

      .formside {
        padding: 32px;
      }
    }

    @media (max-width: 600px) {
      body {
        overflow: auto;
        overflow-x: hidden;
        background:
          radial-gradient(circle at 30% 12%, rgba(255, 90, 31, 0.16), transparent 55%),
          radial-gradient(circle at 85% 70%, rgba(29, 111, 168, 0.14), transparent 50%),
          linear-gradient(160deg, #FFFFFF 0%, var(--water-deep) 55%, var(--water-surface) 100%);
      }

      body::before {
        content: '';
        position: fixed;
        inset: 0;
        z-index: 0;
        pointer-events: none;
        background-image:
          linear-gradient(rgba(255, 90, 31, 0.07) 1px, transparent 1px),
          linear-gradient(90deg, rgba(255, 90, 31, 0.07) 1px, transparent 1px);
        background-size: 42px 42px;
        mask-image: radial-gradient(ellipse 120% 90% at 50% 35%, black 55%, transparent 96%);
        animation: gridDrift 30s linear infinite;
      }

      .shell {
        grid-template-columns: 1fr;
        grid-template-rows: auto 1fr;
        min-height: 100vh;
        position: relative;
        z-index: 1;
      }

      .visual {
        background: transparent;
        min-height: auto;
        height: auto;
        padding: 24px 24px 20px;
      }

      .visual::before {
        display: none;
      }

      .visual-center,
      .visual-stats {
        display: none;
      }

      .visual-brand .brand-full-logo {
        height: 44px;
      }

      .formside {
        background: transparent;
        padding: 12px 18px 36px;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .formcard {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 32px 24px;
        box-shadow: 0 16px 44px rgba(120, 60, 20, 0.12);
      }

      .formcard h2 {
        font-size: 23px;
        margin-bottom: 8px;
      }

      .formcard .sub {
        margin-bottom: 30px;
      }

      .field {
        margin-bottom: 26px;
      }

      .field label {
        margin-bottom: 9px;
      }

      .btn-ingresar {
        margin-top: 16px;
        padding: 15px;
      }

      .divider-line {
        margin: 34px 0 24px;
      }
    }
  </style>
</head>

<body>

  <!-- Alerta dinámica conservada del sistema original -->
  <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>

  <!-- Base URL para llamadas AJAX -->
  <input type="hidden" name="txt_base_url" id="txt_base_url" value="<?php echo base_url(); ?>">

  <div class="shell">

    <!-- VISUAL / BRAND PANEL -->
    <section class="visual">
      <div class="visual-brand">
        <div class="brand-full-logo">
          <img src="<?php echo base_url(); ?>/softwareapr-logo-full.png" alt="Software APR">
        </div>
      </div>

      <div class="visual-center">
        <div class="tower-wrap">
          <div class="ring r2"></div>
          <div class="ring r3"></div>
          <div class="pulse-dot"></div>
          <div class="tower-icon">
            <svg viewBox="0 0 100 120" width="72" height="86" fill="none">
              <ellipse cx="50" cy="30" rx="26" ry="7" fill="var(--brand-blue)" opacity="0.9" />
              <path d="M24 30 L24 58 Q24 68 50 68 Q76 68 76 58 L76 30" stroke="var(--brand-blue)" stroke-width="3" fill="rgba(29,111,168,0.10)" />
              <path d="M50 4 L70 30 L30 30 Z" fill="var(--aqua-bright)" opacity="0.9" />
              <line x1="38" y1="68" x2="18" y2="116" stroke="var(--aqua-glow)" stroke-width="3" />
              <line x1="62" y1="68" x2="82" y2="116" stroke="var(--aqua-glow)" stroke-width="3" />
              <line x1="50" y1="68" x2="50" y2="116" stroke="var(--aqua-glow)" stroke-width="3" />
              <line x1="18" y1="116" x2="82" y2="116" stroke="var(--aqua-glow)" stroke-width="3" />
              <line x1="24" y1="92" x2="50" y2="68" stroke="var(--aqua-glow)" stroke-width="2" opacity="0.6" />
              <line x1="76" y1="92" x2="50" y2="68" stroke="var(--aqua-glow)" stroke-width="2" opacity="0.6" />
              <line x1="18" y1="116" x2="38" y2="92" stroke="var(--aqua-glow)" stroke-width="2" opacity="0.6" />
              <line x1="82" y1="116" x2="62" y2="92" stroke="var(--aqua-glow)" stroke-width="2" opacity="0.6" />
            </svg>
          </div>
        </div>
        <div class="visual-copy">
          <h1>Cada gota, <em>bajo control</em>.</h1>
          <p>Socios, medidores, lecturas y cobranza de tu comité de agua potable rural — en un solo lugar, desde cualquier dispositivo.</p>
        </div>
      </div>

      <div class="visual-stats">
        <div class="vstat"><b>50.000+</b><span>Medidores en funcionamiento</span></div>
        <div class="vstat-divider"></div>
        <div class="vstat"><b>200+</b><span>APR utilizando la tecnología</span></div>
      </div>
    </section>

    <!-- FORM PANEL -->
    <section class="formside">
      <div class="formcard">
        <div class="eyebrow">Software APR · Acceso</div>
        <h2>Bienvenido, nos alegra tenerte aquí</h2>
        <p class="sub">Ingresa con tu RUT y contraseña para continuar.</p>

        <!-- Formulario con ID y campos originales -->
        <form id="form_login">
          <div class="field">
            <label for="txt_usuario">Usuario</label>
            <input id="txt_usuario" name="txt_usuario" type="text" placeholder="Ingresar usuario" autocomplete="username">
          </div>

          <div class="field" id="divClave">
            <label for="txt_clave">Clave</label>
            <input id="txt_clave" name="txt_clave" type="password" placeholder="Ingresar clave" autocomplete="current-password">
            <button type="button" class="toggle-eye" onclick="togglePass('txt_clave', this)" aria-label="Mostrar contraseña">
              <i class="fas fa-eye"></i>
            </button>
          </div>

          <!-- Campos ocultos conservados para activación de cuenta o reseteo -->
          <div class="field hidden" id="divClaveActivar">
            <label for="txt_clave_activar">Ingrese su clave</label>
            <input id="txt_clave_activar" name="txt_clave_activar" type="password" placeholder="Ingresar clave para activar">
            <button type="button" class="toggle-eye" onclick="togglePass('txt_clave_activar', this)" aria-label="Mostrar contraseña">
              <i class="fas fa-eye"></i>
            </button>
          </div>

          <div class="field hidden" id="divClaveRepetir">
            <label for="txt_clave_repetir">Repita su clave</label>
            <input id="txt_clave_repetir" name="txt_clave_repetir" type="password" placeholder="Ingresar clave">
            <button type="button" class="toggle-eye" onclick="togglePass('txt_clave_repetir', this)" aria-label="Mostrar contraseña">
              <i class="fas fa-eye"></i>
            </button>
          </div>

          <!-- Botón con ID original btn_login para el listener de jQuery/AJAX -->
          <button type="button" class="btn-ingresar" id="btn_login">
            <i class="fas fa-sign-in-alt"></i> INICIAR SESIÓN →
          </button>
        </form>

        <div class="divider-line">o</div>

        <p class="contact">
          ¿Necesitas ayuda o tienes alguna consulta?<br>
          Escríbenos a <b>contacto@medidorinteligente.cl</b>
        </p>
      </div>
    </section>

  </div>

  <!-- Scripts del sistema base conservados -->
  <script src="<?php echo base_url(); ?>/js/jquery-3.5.1.slim.min.js"></script>
  <script src="<?php echo base_url(); ?>/js/bootstrap.bundle.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url(); ?>/jquery-validation-1.19.2/dist/jquery.validate.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url(); ?>/js/alerta.js"></script>
  <script src="<?php echo base_url(); ?>/js/login.js" type="text/javascript"></script>

  <script>
    function togglePass(inputId, btn) {
      const input = document.getElementById(inputId);
      const icon = btn.querySelector('i');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    }
  </script>
</body>

</html>