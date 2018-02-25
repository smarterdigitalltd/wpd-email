<?php

/**
 * Email setup
 *
 * @package     WPD\Toolset
 * @since       1.0.0
 * @author      smarterdigitalltd
 * @link        https://wpdevelopers.co.uk
 * @license     GNU-2.0+
 */

namespace WPD\Toolset;

class Email
{

	/**
	 * @var
	 */
	protected static $instance = null;

	/**
	 * Plugin init
	 *
	 * @since   1.0.0
	 *
	 * @return  void
	 */
	protected function __construct()
	{
		$this->registerHooks();
	}

	/**
	 * Get singleton instance
	 *
	 * @since   1.0.0.
	 *
	 * @return  mixed Plugin Instance of the plugin
	 */
	public static function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Run hooks
	 *
	 * @since   1.0.0
	 *
	 * @return  void
	 */
	private static function registerHooks()
	{
		add_action('phpmailer_init', [__CLASS__, 'setPhpMailerParameters'], 1, 999);
	}

	/**
	 * Use mailtrap for non production environments
	 *
	 * @since   1.0.0
	 *
	 * @param   object $phpmailer Instance of PHPMailer
	 *
	 * @return  void
	 */
	public static function setPhpMailerParameters($phpmailer)
	{
		if (!class_exists('Utils\SiteState') || SiteState::siteStateIsProduction()) {
			return;
		}

		$username = defined( 'WPD_MAILTRAP_USERNAME' ) ? WPD_MAILTRAP_USERNAME : null;
		$password = defined( 'WPD_MAILTRAP_PASSWORD' ) ? WPD_MAILTRAP_PASSWORD : null;

		$username = apply_filters('wpd/toolset/email/mailtrap_username', $username);
		$password = apply_filters('wpd/toolset/email/mailtrap_password', $password);

		$phpmailer->IsSMTP();
		$phpmailer->Host       = 'smtp.mailtrap.io';
		$phpmailer->SMTPAuth   = true;
		$phpmailer->Port       = 2525;
		$phpmailer->Username   = $username;
		$phpmailer->Password   = $password;
		$phpmailer->SMTPSecure = 'tls';
	}
}
