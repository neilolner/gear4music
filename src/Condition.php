<?php

declare(strict_types=1);

namespace Gear4music;

enum Condition: string
{
    case New = 'new';
    case Refurbished = 'refurbished';
    case Used = 'used';
}
