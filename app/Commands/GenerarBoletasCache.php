<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class GenerarBoletasCache extends BaseCommand
{
    protected $group       = 'Boletas';
    protected $name        = 'boletas:generar';
    protected $description = 'Genera boletas cacheadas masivamente';

    public function run(array $params)
    {
        ini_set('max_execution_time', 0);

        ini_set('memory_limit', '4096M');

        // =====================================
        // VALIDAR PARAMETRO
        // =====================================

        if (!isset($params[0])) {

            CLI::error(
                'Debe indicar período MM-YYYY'
            );

            return;
        }

        $periodo = $params[0];

        // =====================================
        // VALIDAR FORMATO
        // =====================================

        if (
            !preg_match(
                '/^(0[1-9]|1[0-2])-[0-9]{4}$/',
                $periodo
            )
        ) {

            CLI::error(
                'Formato inválido. Ej: 04-2026'
            );

            return;
        }

        CLI::write('');
        CLI::write(
            '====================================',
            'yellow'
        );

        CLI::write(
            'GENERANDO BOLETAS: ' . $periodo,
            'green'
        );

        CLI::write(
            '====================================',
            'yellow'
        );

        // =====================================
        // MODELOS
        // =====================================

        $metrosModel =
            model('App\Models\MetrosModel');

        // =====================================
        // OBTENER BOLETAS
        // =====================================

        $boletas = $metrosModel
            ->select("
                id,
                url_boleta,
                folio_bolect
            ")
            ->where("
                DATE_FORMAT(
                    fecha_ingreso,
                    '%m-%Y'
                ) = '{$periodo}'
            ")
            ->where("url_boleta !=", "")
            ->where("url_boleta IS NOT NULL")
            ->findAll();

        if (!$boletas) {

            CLI::error(
                'No existen boletas'
            );

            return;
        }

        CLI::write(
            'Total boletas: '
                . count($boletas),
            'cyan'
        );

        // =====================================
        // CONTROLLER
        // =====================================

        $controller =
            new \App\Controllers\Pagos\Ctrl_boleta_electronica();

        $ok = 0;
        $error = 0;

        foreach ($boletas as $b) {

            try {

                $folio =
                    $b["id"];

                $url =
                    $b["url_boleta"];

                $folio_sii =
                    $b["folio_bolect"];

                CLI::write(
                    'Generando: '
                        . $folio_sii,
                    'yellow'
                );

                // =====================================
                // GENERAR CACHE
                // =====================================

                $controller->imprimir_boleta_nueva(
                    $url,
                    $folio,
                    true
                );

                $ok++;

                CLI::write(
                    'OK: '
                        . $folio_sii,
                    'green'
                );
            } catch (\Throwable $e) {

                $error++;

                CLI::error(
                    'ERROR: '
                        . $folio_sii
                );

                CLI::error(
                    $e->getMessage()
                );
            }
        }

        CLI::write('');
        CLI::write(
            '====================================',
            'yellow'
        );

        CLI::write(
            'FINALIZADO',
            'green'
        );

        CLI::write(
            'OK: ' . $ok,
            'green'
        );

        CLI::write(
            'ERROR: ' . $error,
            'red'
        );

        CLI::write(
            '====================================',
            'yellow'
        );
    }
}
