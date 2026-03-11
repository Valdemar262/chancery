<?php

declare(strict_types=1);

namespace app\Enums;

enum StatementStatus: string
{
    case DRAFT = 'draft';
    case SUBMITTED = 'submitted';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Черновик',
            self::SUBMITTED => 'Отправлен',
            self::APPROVED => 'Утверждено',
            self::REJECTED => 'Отклонено',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::SUBMITTED => 'yellow',
            self::APPROVED => 'green',
            self::REJECTED => 'red',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
