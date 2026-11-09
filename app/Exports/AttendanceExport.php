<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private Collection $rows)
    {
    }

    public function collection(): Collection
    {
        return $this->rows;
    }

    // Judul kolom di baris pertama Excel
    public function headings(): array
    {
        return [
            'Tanggal', 'Nama', 'Sekolah', 'Lokasi', 'Masuk', 'Pulang',
            'Status', 'Di luar area', 'Jarak (m)', 'Catatan',
        ];
    }

    // Isi satu baris Excel dari satu data absen
    public function map($a): array
    {
        return [
            $a->date->format('d-m-Y'),
            $a->student->name,
            $a->student->school_name,
            $a->placement->location->name,
            $a->check_in_time ? substr($a->check_in_time, 0, 5) : '-',
            $a->check_out_time ? substr($a->check_out_time, 0, 5) : '-',
            ucfirst($a->status),
            $a->is_outside_area ? 'Ya' : 'Tidak',
            $a->check_in_distance,
            $a->note,
        ];
    }
}