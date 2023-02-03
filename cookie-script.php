<?php

# Plugin Name:       Cookie-Script.com
# Description:       Cookie-Script.com WordPress plugin.
# Version:           0.6
# Author:            Cookie-Script.com
# Author URI:        https://cookie-script.com/
# Text Domain:       CookieScript
# License:           MIT License (Expat)
# Cookie vector by:  https://www.freepik.com/free-vector/friendship-day-background-with-cute-cartoons_2410968.htm

// Block direct access
if (!defined('ABSPATH')) {
	exit;
}

// Make sure there is no cookie script in document while plugin is deactivated
register_deactivation_hook( __FILE__, array("CookieScript", "cookie_script_deactivation") );

// Clean up DB after uninstalling plugin
register_uninstall_hook( __FILE__, array("CookieScript", "cookie_script_uninstall") );

class CookieScript {

	public function __construct() {

		$this->item_id = esc_attr(get_option("cookie_script_item_id"));

		$this->src = esc_attr(get_option("cookie_script_item_src"));

		$this->item_connection_type = (int)get_option("cookie_script_item_connection_type");

		add_action("admin_init", array($this, "cookie_script_register_settings"));

		add_action("admin_menu", array($this, "cookie_script_register_options_page"));

		// Connect plugin options' styling, script
		add_action("admin_enqueue_scripts", array($this, "cookie_script_admin_page_css_js"));

		// Inserts cookie-script item into a front-end view
		add_action("wp_footer", array($this, "cookie_script_insert"));

		// Show "settings" link in /wp-admin/plugins.php
		add_action("plugin_action_links_" . plugin_basename( __FILE__ ), array($this, "cookie_script_settings_link"));

		// Connect plugin's languages
		load_plugin_textdomain("CookieScript", false, dirname( plugin_basename( __FILE__ ) ) . "/languages");

	}

	// Inserts cookie-script item into a front-end view
	public function cookie_script_insert() {
			wp_register_script("cookie_script", $this->cookie_script_generate_url(), array(), null, true);
			wp_enqueue_script("cookie_script");

	}

	public function cookie_script_register_settings() {

		register_setting(
			"cookie_script_options_group",
			"cookie_script_item_id",
			array(
				"type" => "string",
				"sanitize_callback" => "sanitize_text_field",
				"default" => null
			)
		);

        register_setting(
            "cookie_script_options_group",
            "cookie_script_item_src",
            array(
                "type" => "string",
                "sanitize_callback" => "sanitize_text_field",
                "default" => null
            )
        );

		register_setting(
			"cookie_script_options_group",
			"cookie_script_item_connection_type",
			array(
				"type" => "integer",
				"sanitize_callback" => "sanitize_key",
				"default" => 1
			)
		);

	}

	public function cookie_script_register_options_page() {

		add_options_page(
			esc_html__("Cookie Script Settings 🍪", "CookieScript"),
			esc_html__("Cookie-Script.com", "CookieScript"),
			'manage_options',
			'cookie-script',
			array(
				$this,
				"cookie_script_options_page"
			)
		);

	}

	// Plugin options' view
	public function cookie_script_options_page() {
		?>
		<div id="CookieScript">
			<h3><?php esc_html_e("Cookie Script Settings 🍪", "CookieScript"); ?></h3>
			<form class="CookieScript__adminForm" method="post" action="options.php">
				<?php settings_fields("cookie_script_options_group"); ?>
				<label for="cookie_script_item_id" style="display: none">
					<?php esc_html_e("Item ID", "CookieScript"); ?>
					<input
							type="text"
							id="cookie_script_item_id"
							name="cookie_script_item_id"
							value="<?php echo $this->item_id ?>"
							minLength="32"
							maxLength="32"
							placeholder="2f52e9e40086852947abec4fb3297d57"
					>
					<span
							class="CookieScript__help"
							data-tooltip="<?php esc_html_e("You can get your Item ID in your account on Cookie-Script.com. Item ID is located below the name of each item on the account page.", "CookieScript"); ?>">
                        ?
                    </span>
				</label>
                <label for="cookie_script_item_src">
                    <?php esc_html_e("Cookie Script Source URL", "CookieScript"); ?>
                    <div  class="CookieScript__src-wrapper">
                        <input
                                type="text"
                                id="cookie_script_item_src"
                                name="cookie_script_item_src"
                                value="<?php echo $this->src ?>"
                                maxLength="225"
                                minlength="60"
                                placeholder="//cdn.cookie-script.com/s/********************************.js"
                                required
                        >
                        <span
                                class="CookieScript__help"
                                data-tooltip="<?php esc_html_e("You can get your Source in your account on Cookie-Script.com. Under the Installation tab.", "CookieScript"); ?>">
                        ?
                    </span>
                    </div>
                </label>
                <div style="display: none">
                    <h4>GEO-targeting:</h4>
                    <div class="CookieScript__connectionType">
                        <label for="cookie_script_item_connection_type_1">
                            <?php esc_html_e("GLOBAL", "CookieScript"); ?>
                            <input
                                    type="radio"
                                    id="cookie_script_item_connection_type_1"
                                    name="cookie_script_item_connection_type"
                                    value="1"
                                <?php echo ($this->item_connection_type === 1) ? "checked" : ""; ?>
                            >
                        </label>
                        <label for="cookie_script_item_connection_type_2">
                            <?php esc_html_e("EU", "CookieScript"); ?>
                            <input
                                    type="radio"
                                    id="cookie_script_item_connection_type_2"
                                    name="cookie_script_item_connection_type"
                                    value="2"
                                <?php echo ($this->item_connection_type === 2) ? "checked" : ""; ?>
                            >
                        </label>
                        <label for="cookie_script_item_connection_type_3">
                            <?php esc_html_e("CALIFORNIA", "CookieScript"); ?>
                            <input
                                    type="radio"
                                    id="cookie_script_item_connection_type_3"
                                    name="cookie_script_item_connection_type"
                                    value="3"
                                <?php echo ($this->item_connection_type === 3) ? "checked" : ""; ?>
                            >
                        </label>
                        <label for="cookie_script_item_connection_type_4">
                            <?php esc_html_e("EU + CALIFORNIA", "CookieScript"); ?>
                            <input
                                    type="radio"
                                    id="cookie_script_item_connection_type_4"
                                    name="cookie_script_item_connection_type"
                                    value="4"
                                <?php echo ($this->item_connection_type === 4) ? "checked" : ""; ?>
                            >
                        </label>
                    </div>
				</div>
				<div class="CookieScript__adminForm--footer">
					<input type="submit" value="<?php esc_html_e("Save", "CookieScript") ?>">
					<span></span>
				</div>

                <aside>
                    <p><b><?php esc_html_e("How to use this plugin:", "CookieScript"); ?></b></p>
                    <ol>
                        <li>
                            <?php
                            printf(
                                esc_html__("Register account on %1\$s", "CookieScript"),
                                sprintf(
                                    "<a href='%s' target='_blank'>%s</a>",
                                    esc_url("https://cookie-script.com", array("https")),
                                    esc_html__("CookieScript", "CookieScript")
                                )
                            ); ?>
                        </li>
                        <li>
                            <?php
                            printf(
                                esc_html__("Create a banner for your website", "CookieScript")
                            ); ?>
                        </li>
                        <li>
                            <?php
                            printf(
                                esc_html__("Copy your banner code and insert it in the field above", "CookieScript")
                            ); ?>
                        </li>
                        <li>
                            <?php
                            printf(
                                esc_html__("All done, your website will now show the cookie banner", "CookieScript")
                            ); ?>
                        </li>
                    </ol>
                    <p><?php printf(esc_html_e("If needed, you can adjust your banner settings in your CookieScript dashboard.", "CookieScript")); ?></p>
                    <p>
                        <?php
                        printf(
                            esc_html__("You can also check our %1\$s.", "CookieScript"),
                            sprintf(
                                "<a href='%s' target='_blank'>%s</a>",
                                esc_url("https://cookie-script.com/blog/cookie-consent-for-wordpress", array("https")),
                                esc_html__("detailed instructions with video guide", "CookieScript")
                            )
                        ); ?>
                    </p>
                    <p>
                        <?php
                        printf(
                            esc_html__("To block third-party cookies you might still have to make these changes: %1\$s.", "CookieScript"),
                            sprintf(
                                "<a href='%s' target='_blank'>%s</a>",
                                esc_url("https://cookie-script.com/how-to-block-third-party-cookies.html", array("https")),
                                esc_html__("How to block third-party cookies", "CookieScript")
                            )
                        ); ?>
                    </p>
                </aside>

            </form>
		</div>
		<?php
	}

	// Connect plugin options' styling, script
	public function cookie_script_admin_page_css_js() {

		wp_enqueue_style(
			"cookie_script_admin",
			plugin_dir_url( __FILE__ ) . "assets/css/cookie_script_admin.min.css",
			array(),
			null
		);
		
		if(empty($cookie_script_admin_exchanger)) $cookie_script_admin_exchanger = false;
		wp_localize_script("cookie_script_admin", "cookie_script_admin_exchanger", [$cookie_script_admin_exchanger]);

	}

	// Generates item url with provided settings (item id, connection type)
	public function cookie_script_generate_url() {

        $url = null;

		switch ($this->item_connection_type) {
			case 1:
				$conn = "//cdn.";
				break;
			case 2:
				$conn = "//eu.";
				break;
			case 3:
				$conn = "//ca.";
				break;
			case 4:
				$conn = "//ca-eu.";
				break;
		}

		if ($this->src && !is_null($this->src)) {
            return $this->src;
        } else {
            $url = $conn . "cookie-script.com/s/" . $this->item_id . ".js";

            return esc_url($url, array('http', 'https'));
        }
	}


	// Show "settings" link in /wp-admin/plugins.php
	public function cookie_script_settings_link( $links ) {

		$settings_link = "<a href='options-general.php?page=cookie-script'>" . esc_html__("Settings", "CookieScript") . "</a>";

		array_push($links, $settings_link);

		return $links;

	}

	// Make sure there is no cookie script in document while plugin is deactivated
	public function cookie_script_deactivation() {
		wp_dequeue_script("cookie_script");
	}

	// Clean up DB after uninstalling plugin
	public function cookie_script_uninstall() {

		if (!current_user_can("activate_plugins")) {
			return null;
		}

		delete_option("cookie_script_item_id");
		delete_option("cookie_script_item_src");
		delete_option("cookie_script_item_connection_type");

		wp_dequeue_script("cookie_script");
		wp_deregister_script("cookie_script");

	}

}

new CookieScript();

?>
