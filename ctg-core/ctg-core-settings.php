<?php

function ctg_get_admin_url( $path = '', $scheme = 'admin' ) {

	$url = admin_url( $path, $scheme );

	return $url;
}