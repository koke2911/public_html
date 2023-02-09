<?php namespace Config;

use Kint\Renderer\Renderer;
use CodeIgniter\Config\BaseConfig;

class Kint extends BaseConfig {

  /*
  |--------------------------------------------------------------------------
  | Kint
  |--------------------------------------------------------------------------
  |
  | We use Kint's RichRenderer and CLIRenderer. This area contains options
  | that you can set to customize how Kint works for you.
  |
  | For details on these settings, see Kint's docs:
  |	https://kint-php.github.io/kint/
  |
  */

  /*
  |--------------------------------------------------------------------------
  | Global Settings
  |--------------------------------------------------------------------------
  */

  public $plugins = NULL;

  public $maxDepth = 6;

  public $displayCalledFrom = TRUE;

  public $expanded = FALSE;

  /*
  |--------------------------------------------------------------------------
  | RichRenderer Settings
  |--------------------------------------------------------------------------
  */
  public $richTheme = 'aante-light.css';

  public $richFolder = FALSE;

  public $richSort = Renderer::SORT_FULL;

  public $richObjectPlugins = NULL;

  public $richTabPlugins = NULL;

  /*
  |--------------------------------------------------------------------------
  | CLI Settings
  |--------------------------------------------------------------------------
  */
  public $cliColors = TRUE;

  public $cliForceUTF8 = FALSE;

  public $cliDetectWidth = TRUE;

  public $cliMinWidth = 40;
}
