<?php

namespace App\Enums;

enum UserRoleEnum: string
{
    case Admin = 'admin';
    case ClientAdmin = 'clientAdmin';
    case Employee = 'employee';
}
