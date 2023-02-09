<?php namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Class ContentSecurityPolicyConfig
 *
 * Stores the default settings for the ContentSecurityPolicy, if you
 * choose to use it. The values here will be read in and set as defaults
 * for the site. If needed, they can be overridden on a page-by-page basis.
 *
 * Suggested reference for explanations:
 *    https://www.html5rocks.com/en/tutorials/security/content-security-policy/
 *
 * @package Config
 */
class ContentSecurityPolicy extends BaseConfig {

  // broadbrush CSP management

  public $reportOnly              = FALSE; // default CSP report context
  public $reportURI               = NULL;  // URL to send violation reports to
  public $upgradeInsecureRequests = FALSE; // toggle for forcing https

  // sources allowed; string or array of strings
  // Note: once you set a policy to 'none', it cannot be further restricted

  public $defaultSrc     = NULL; // will default to self if not over-ridden
  public $scriptSrc      = 'self';
  public $styleSrc       = 'self';
  public $imageSrc       = 'self';
  public $baseURI        = NULL;    // will default to self if not over-ridden
  public $childSrc       = 'self';
  public $connectSrc     = 'self';
  public $fontSrc        = NULL;
  public $formAction     = 'self';
  public $frameAncestors = NULL;
  public $mediaSrc       = NULL;
  public $objectSrc      = 'self';
  public $manifestSrc    = NULL;

  // mime types allowed; string or array of strings
  public $pluginTypes = NULL;

  // list of actions allowed; string or array of strings
  public $sandbox = NULL;

}
