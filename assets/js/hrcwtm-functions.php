<?php

function hrcwtm_get_path( $filename = '' ) {
	return HRCWTM_DIR . ltrim( $filename, '/' );
}

function hrcwtm_include( $filename = '' ) {
	$file_path = hrcwtm_get_path( $filename );
	if ( file_exists( $file_path ) ) {
		include_once $file_path;
	}
}