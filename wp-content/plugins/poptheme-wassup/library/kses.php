<?php

// Hack GreenDrinks: allow to override these values
global $allowedposttags;

// Hack GreenDrinks: allow to override these values
$allowedposttags = apply_filters( 'gd_allowedposttags', $allowedposttags );

// Taken from wp-includes/kses.php
$allowedposttags = array_map( '_wp_add_global_attributes', $allowedposttags );