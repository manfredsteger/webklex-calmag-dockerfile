<?php
/*
* File: GrowState.php
* Category: -
* Author: M.Goldenbaum
* Created: 08.09.24 16:29
* Updated: -
*
* Description:
*  -
*/

namespace Webklex\CalMag\Enums;

enum GrowState: string {
    case Propagation = "propagation";
    case Vegetation = "vegetation";
    case Flower = "flower";
    case LateFlower = "late_flower";
    public static function getStates(): array {
        return [
            self::Propagation,
            self::Vegetation,
            self::Flower,
            self::LateFlower,
        ];
    }

    public static function getLabels(): array {
        return [
            self::Propagation->value,
            self::Vegetation->value,
            self::Flower->value,
            self::LateFlower->value,
        ];
    }

    public static function fromString(string $state): self {
        return match ($state) {
            self::Propagation->value, self::Propagation->name, self::Propagation => self::Propagation,
            self::Vegetation->value, self::Vegetation->name, self::Vegetation => self::Vegetation,
            self::Flower->value, self::Flower->name, self::Flower => self::Flower,
            self::LateFlower->value, self::LateFlower->name, self::LateFlower => self::LateFlower,
            default => throw new \InvalidArgumentException("Invalid state: $state")
        };
    }
}
