<?php

namespace App\Enum;

enum TaskStatus: string
{
    case TODO = 'To Do';
    case DOING = 'Doing';
    case DONE = 'Done';
}