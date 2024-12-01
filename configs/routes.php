<?php

    $routes = array (
        'AuthController' => array(
            'auth' => 'main'
        ),
        'CinemasController' => array (
            'cinemas/([0-9]*)' => 'main/$1'
        ),
        'HallsController' => array (
            'halls/([0-9]*)' => 'main/$1'
        ),
        'SeancesController' => array (
            'seances/([0-9]*)' => 'main/$1'
        ),
        'PlacesController' => array (
            'places' => 'main'
        )
    );
