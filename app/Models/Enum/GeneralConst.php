<?php
namespace App\Models\Enum;

class GeneralConst
{
    const TRUE      = 1;
    const FALSE     = 0;
    const IN        = 'in';
    const OUT       = 'out';
    const GOCOSMILE = 'goco_smile';

    const SHIPPED = 'SHIPPED';

    const GOCO_PRODUCT_ACTIVE = 1;

    const LOCAL      = 'local';
    const STAGING    = 'staging';
    const PRODUCTION = 'production';

    const CREATED_AT_FORMAT = 'M d Y  H:i A';
    const CPANELENV         = [
        'production',
        'staging',
    ];
}

class DriverConst
{
    const API   = 'cpanel_api';
    const LOCAL = 'local';
}
