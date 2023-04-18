<?php 
defined( 'ABSPATH' ) || exit;

function ctg_setup_sales() {
	ctg()->sales = new CTG_Sales_Component();
}
add_action( 'ctg_loaded', 'ctg_setup_sales', 3 );
