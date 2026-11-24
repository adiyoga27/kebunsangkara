<?php

namespace App\Enums;

enum ActivityAction: string
{
    case Login = 'login';
    case Logout = 'logout';
    case LoginFailed = 'login_failed';
    case Created = 'created';
    case Updated = 'updated';
    case Deleted = 'deleted';
    case Restored = 'restored';
    case ForceDeleted = 'force_deleted';

    public function label(): string
    {
        return match ($this) {
            self::Login => 'Login',
            self::Logout => 'Logout',
            self::LoginFailed => 'Login Gagal',
            self::Created => 'Tambah',
            self::Updated => 'Ubah',
            self::Deleted => 'Hapus',
            self::Restored => 'Pulihkan',
            self::ForceDeleted => 'Hapus Permanen',
        };
    }

    /**
     * Tailwind classes used for the badge of this action in the admin panel.
     */
    public function badgeClasses(): string
    {
        return match ($this) {
            self::Login => 'bg-emerald-100 text-emerald-800',
            self::Logout => 'bg-slate-100 text-slate-700',
            self::LoginFailed => 'bg-red-100 text-red-800',
            self::Created => 'bg-sky-100 text-sky-800',
            self::Updated => 'bg-amber-100 text-amber-800',
            self::Deleted => 'bg-rose-100 text-rose-800',
            self::Restored => 'bg-sky-100 text-sky-800',
            self::ForceDeleted => 'bg-red-100 text-red-800',
        };
    }
}
