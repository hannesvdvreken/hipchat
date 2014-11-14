<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pretend
    |--------------------------------------------------------------------------
    |
    | Use this switch to turn hipchat notifications on and off. Defaults to false.
    */
    'pretend' => false,

    /*
    |--------------------------------------------------------------------------
    | Notify
    |--------------------------------------------------------------------------
    |
    | Use this switch to warn room members of new notification or not. Defaults to false.
    */
    'notify' => false,

    /*
    |--------------------------------------------------------------------------
    | Rooms
    |--------------------------------------------------------------------------
    |
    | Specify hipchat rooms in which to post notifications.
    |
    */
    'default' => 'main',

    'rooms' => [

        'main' => [
            'room_id'    => 'your-room-id',
            'auth_token' => 'your-room-token',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Color
    |--------------------------------------------------------------------------
    |
    | Specify the color for the notifications background. Valid values are
    | 'yellow', 'red', 'green', 'purple', 'gray' and 'random'. Default to 'gray'
    |
    */

    'color' => 'gray',
];
