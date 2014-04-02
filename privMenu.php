<?php
/**
 * Plugin Name: Privileged Menu
 * Plugin URI: http://www.fuzzguard.com.au/plugins/privileged-menu
 * Description: Used to provide Menu display to users based on their Privilege Level (Currently only either logged in/logged out)
 * Version: 1.2
 * Author: Benjamin Guy
 * Author URI: http://www.fuzzguard.com.au
 * License: GPL2

    Copyright 2014  Benjamin Guy  (email: beng@fuzzguard.com.au)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/


/**
* Don't display if wordpress admin class is not found
* Protects code if wordpress breaks
* @since 0.2
*/
if ( ! function_exists( 'is_admin' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}

/**
* Load Custom Walker class
* @since 0.2
*/
include('customWalker.php');



/**
* Create class privMenu() to prevent any function name conflicts with other WordPress plugins or the WordPress core.
* @since 0.1
*/
class privMenu {




    	/**
     	* Removes items from the menu displayed to the user if that menu item has been denied access to them in the admin panel
     	* @since 0.2
     	*/
	function remove_menu_items( $items, $menu, $args ) {

    		foreach ( $items as $key => $item ) {
			$meta_data = get_post_meta( $item->ID, '_priv_menu_role', true);
          		switch( $meta_data ) {
				case 'admin':
					$visible = current_user_can( 'manage_options' ) ? true : false;
					break;
            			case 'in' :
              				$visible = is_user_logged_in() ? true : false;
              				break;
            			case 'out' :
              				$visible = ! is_user_logged_in() ? true : false;
              				break;
            			default:
	      				$visible = true;
				/*
              				$visible = false;
              				if ( is_array( $item->roles ) && ! empty( $item->roles ) ) foreach ( $item->roles as $role ) {
                				if ( current_user_can( $role ) ) $visible = true;
              				}
				*/
              				break;
          		}
          		// add filter to work with plugins that don't use traditional roles
          		$visible = apply_filters( 'nav_menu_roles_item_visibility', $visible, $item );

          		if ( ! $visible ) unset( $items[$key] ) ;
    		}

    		return $items;
	}

    /**
     * Replace the default Admin Menu Walker
     * @since 0.2
     */
    function edit_priv_menu_walker( $walker, $menu_id ) {
        return 'Priv_Menu_Walker';
    }



    /**
     * Save users selection in DataBase as post_meta on return of data from users browser
     * @since 0.2
     */
    function save_extra_menu_opts( $menu_id, $menu_item_db_id, $args ) {
        global $wp_roles;

        $allowed_roles = apply_filters( 'priv_menu_roles', $wp_roles->role_names );

        // verify this came from our screen and with proper authorization.
        if ( ! isset( $_POST['priv-menu-role-nonce'] ) || ! wp_verify_nonce( $_POST['priv-menu-role-nonce'], 'priv-menu-nonce-name' ) )
            return;

        $saved_data = false;

        if ( isset( $_POST['priv-menu-logged-in-out'][$menu_item_db_id]  )  && in_array( $_POST['priv-menu-logged-in-out'][$menu_item_db_id], array( 'in', 'out', 'admin') ) ) {
              $saved_data = $_POST['priv-menu-logged-in-out'][$menu_item_db_id];
        } elseif ( isset( $_POST['priv-menu-role'][$menu_item_db_id] ) ) {
            $custom_roles = array();
            // only save allowed roles
            foreach( $_POST['priv-menu-role'][$menu_item_db_id] as $role ) {
                if ( array_key_exists ( $role, $allowed_roles ) ) $custom_roles[] = $role;
            }
            if ( ! empty ( $custom_roles ) ) $saved_data = $custom_roles;
        }

        if ( $saved_data ) {
            update_post_meta( $menu_item_db_id, '_priv_menu_role', $saved_data );
        } else {
            delete_post_meta( $menu_item_db_id, '_priv_menu_role' );
        }
    }



} //End of privMenu() class



/**
* Define the Class
* @since 0.1
*/
$myprivMenuClass = new privMenu();


/**
* Action of what function to call to save users selection when returned from their browser
* @since 0.1
*/
add_action( 'wp_update_nav_menu_item', array( $myprivMenuClass, 'save_extra_menu_opts'), 10, 3 );


/**
* Replace the default Admin Menu Walker with the custom one from the customWalker.php file
* @since 0.1
*/
add_filter( 'wp_edit_nav_menu_walker', array( $myprivMenuClass, 'edit_priv_menu_walker' ), 10, 2 );


/**
* If is_admin() is not defined (User not in admin panel) then filter the displayed menu through the below function.
* @since 0.2
*/
if ( ! is_admin() ) {
        // add meta to menu item
	add_filter( 'wp_get_nav_menu_items', array($myprivMenuClass, 'remove_menu_items'), null, 3 );
}
?>
