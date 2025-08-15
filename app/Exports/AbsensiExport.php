<?php
namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $absensi;

    public function __construct($absensi)
    {
        $this->absensi = $absensi;
    }

    public function collection()
    {
        return $this->absensi;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Pegawai',
            'Cabang/Lokasi',
            'Kategori/Jabatan',
            'Tanggal Absen',
            'Absen Pagi',
            'Absen Sore',
            'Keterlambatan (menit)',
        ];
    }

    public function map($item): array
    {
        // Calculate late minutes
        $lateMinutes = 0;
        if ($item->jam_masuk) {
            $startTime = \Carbon\Carbon::createFromFormat('H:i', '07:30');
            $checkInTime = \Carbon\Carbon::parse($item->jam_masuk);
            
            if ($checkInTime->gt($startTime)) {
                $lateMinutes = $checkInTime->diffInMinutes($startTime);
            }
        }

        // Format the data for export
        return [
            $item->id,
            $item->user->nama_pegawai ?? 'Tidak Ada Data',
            $item->user->cabang->nama_cabang ?? 'Tidak Ada Data',
            $item->user->jabatan->nama_jabatan ?? 'Tidak Ada Data',
            $item->tanggal_absen,
            $item->jam_masuk ?? 'Tidak Ada Data',
            $item->jam_masuk_sore ?? 'Tidak Ada Data',
            $lateMinutes > 0 ? $lateMinutes : 'Tepat Waktu',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
        ]);

        // Add borders to all cells
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:H' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Auto-size columns
        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return $sheet;
    }
}