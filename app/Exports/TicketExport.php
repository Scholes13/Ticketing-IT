<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class TicketExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Ticket::with(['category', 'staff'])
            ->whereBetween('tickets.created_at', [$this->startDate, $this->endDate])
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No. Tiket',
            'Judul',
            'Pemohon',
            'Departemen',
            'Kategori',
            'Prioritas',
            'Status',
            'Staff Ditugaskan',
            'Tanggal Dibuat',
            'Tanggal Direspon',
            'Tanggal Diselesaikan',
            'Waktu Respon (Menit)',
            'Waktu Proses (Menit)',
            'Total Waktu (Menit)'
        ];
    }

    /**
     * @param Ticket $ticket
     * @return array
     */
    public function map($ticket): array
    {
        // Calculate response time
        $responseTime = null;
        if ($ticket->follow_up_at) {
            $responseTime = Carbon::parse($ticket->created_at)
                ->diffInMinutes(Carbon::parse($ticket->follow_up_at));
        }

        // Calculate processing time
        $processingTime = null;
        if ($ticket->follow_up_at && $ticket->resolved_at) {
            $processingTime = Carbon::parse($ticket->follow_up_at)
                ->diffInMinutes(Carbon::parse($ticket->resolved_at));
        }

        // Calculate total time
        $totalTime = null;
        if ($ticket->resolved_at) {
            $totalTime = Carbon::parse($ticket->created_at)
                ->diffInMinutes(Carbon::parse($ticket->resolved_at));
        }

        return [
            $ticket->ticket_number,
            $ticket->title,
            $ticket->requester_name,
            $ticket->department,
            $ticket->category ? $ticket->category->name : '-',
            ucfirst($ticket->priority),
            ucfirst($ticket->status),
            $ticket->staff ? $ticket->staff->name : 'Belum ditugaskan',
            $ticket->created_at->format('d/m/Y H:i'),
            $ticket->follow_up_at ? Carbon::parse($ticket->follow_up_at)->format('d/m/Y H:i') : '-',
            $ticket->resolved_at ? Carbon::parse($ticket->resolved_at)->format('d/m/Y H:i') : '-',
            $responseTime,
            $processingTime,
            $totalTime
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }
} 