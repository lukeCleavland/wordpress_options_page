<?php
/*
Plugin Name: Blank Options Page 
Description: Barebones object oriented options page for wordpress
Author: Luke Cleavland
Version: 1
*/



class options_page {
    
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

	function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
                add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	function admin_menu() {
        $page_title = 'EC Customize Options';
        $menu_title = 'EC Customize';
        $capability = 'manage_options';
        $menu_slug = 'ec-customize';
		add_options_page(
			'Blank Options', //page title
			'Blank Options', //menu title
			'manage_options', //capability
			'blank-options', //menu slug
			array(
				$this,
				'blank_customize' //callback
			)
		);
	}

    /**
     * Options page callback
     */
    public function blank_customize()
    {
        // Set class property
        $this->options = get_option( 'option_name' );

        ?>
        <div class="wrap">
            <form method="post" action="options.php">
            <?php

                // This prints out all hidden setting fields
                settings_fields( 'option_group' );
                do_settings_sections( 'settings-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'option_group', // Option group
            'option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Section Title', // Title
            array( $this, 'print_section_info' ), // Callback
            'settings-admin' // Page
        );

        add_settings_field(
            'setting_field_id',
            'Field Title',
            array( $this, 'field_callback' ),
            'settings-admin',
            'setting_section_id'

);
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();

        if( isset( $input['field_name'] ) )
            $new_input['field_name'] = $input['field_name'];

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function field_callback()
    {

         printf(
            '<input type="text" id="field_name" name="option_name[field_name]" value="%s"/>',
            isset( $this->options['field_name'] ) ? esc_attr( $this->options['field_name']) : ''
        );
    }


}

new options_page;
?>