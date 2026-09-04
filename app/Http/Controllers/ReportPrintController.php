<?php

namespace App\Http\Controllers;

use App\Models\Capex;
use App\Models\ChecklistExecution;
use App\Models\MaintenanceReport;
use App\Models\Opex;
use App\Models\Schedule;
use App\Models\WorkOrder;
use App\Support\ReportBuilder;
use Illuminate\Http\Request;

class ReportPrintController extends Controller
{
    private const TITLES = [
        'maintenance_reports' => 'Laporan Maintenance',
        'capex' => 'Laporan CAPEX',
        'opex' => 'Laporan OPEX',
        'work_orders' => 'Laporan Work Order',
        'schedules' => 'Laporan Schedule Maintenance',
        'checklist_executions' => 'Laporan Checklist Execution',
    ];

    public function listPreview(Request $request, string $type)
    {
        abort_unless(isset(self::TITLES[$type]), 404);
        $filters = $request->only(['divisis_id','status','from','to','search','priority','type']);
        $data = $this->queryList($type, $filters);
        $title = self::TITLES[$type];
        $subtitle = $this->subtitle($request);
        $meta = $this->metaForList($request, $data['total']);
        $printedBy = ReportBuilder::printedBy();
        $printedAt = ReportBuilder::printedAt();
        $columns = $data['columns'];
        $rows = $data['rows'];
        $summary = $data['summary'] ?? null;
        $docNo = strtoupper($type).'/'.now()->format('Ymd').'/'.str_pad((string) random_int(1,999), 3, '0', STR_PAD_LEFT);
        $signatures = ReportBuilder::signatures();

        return view('reports.list', compact('title','subtitle','meta','columns','rows','summary','printedBy','printedAt','docNo','type','filters','signatures'));
    }

    public function single(Request $request, string $type, int $id)
    {
        abort_unless(isset(self::TITLES[$type]), 404);
        $record = $this->findRecord($type, $id);
        abort_if(! $record, 404);
        $title = self::TITLES[$type];
        $subtitle = 'Dokumen Detail — No: '.($record->wo_number ?? $record->title ?? '#'.$record->id);
        $meta = $this->metaForSingle($type, $record);
        $sections = $this->sectionsForSingle($type, $record);
        $table = $this->tableForSingle($type, $record);
        $printedBy = ReportBuilder::printedBy();
        $printedAt = ReportBuilder::printedAt();
        $docNo = strtoupper($type).'/'.$record->id.'/'.now()->format('Ymd');
        $signatures = ReportBuilder::signatures();

        return view('reports.single', compact('title','subtitle','meta','sections','table','printedBy','printedAt','docNo','record','type','signatures'));
    }

    private function subtitle(Request $request): string
    {
        $parts = [];
        if ($request->filled('from') || $request->filled('to')) {
            $parts[] = 'Periode: '.ReportBuilder::period($request->input('from'), $request->input('to'));
        }
        if ($request->filled('search')) $parts[] = 'Pencarian: "'.$request->input('search').'"';
        return $parts ? implode(' · ', $parts) : 'Periode: '.ReportBuilder::period(null, null).' · Data Terkini Sistem';
    }

    private function metaForList(Request $request, int $total): array
    {
        return [
            ReportBuilder::meta('Periode', ReportBuilder::period($request->input('from'), $request->input('to'))),
            ReportBuilder::meta('Divisi/Unit', $request->filled('divisis_id') ? ('Divisi ID: '.$request->input('divisis_id').' <small>· terfilter</small>') : 'Semua Divisi & Unit'),
            ReportBuilder::meta('Total Data', '<strong>'.$total.' baris</strong> <small>· sesuai filter</small>'),
            ReportBuilder::meta('Status / Kategori', ReportBuilder::safe($request->input('status') ?: $request->input('priority') ?: $request->input('type'), 'Semua Status')),
        ];
    }

    private function metaForSingle(string $type, $record): array
    {
        $divisi = $record->divisi?->nama_divisi ?? $record->division?->nama_divisi ?? '-';
        $unit = $record->unit?->unit_name ?? '-';
        $dateField = $record->report_date ?? $record->expense_date ?? $record->scheduled_date ?? $record->schedule_date ?? $record->created_at;
        return [
            ReportBuilder::meta('Divisi', e($divisi)),
            ReportBuilder::meta('Unit / Equipment', e($unit)),
            ReportBuilder::meta('Tanggal Dokumen', ReportBuilder::date($dateField)),
            ReportBuilder::meta('Status', ReportBuilder::badge($record->status ?? '-')),
        ];
    }

    private function queryList(string $type, array $filters): array
    {
        return match ($type) {
            'maintenance_reports' => $this->listMaintenanceReports($filters),
            'capex' => $this->listCapex($filters, Capex::class),
            'opex' => $this->listCapex($filters, Opex::class),
            'work_orders' => $this->listWorkOrders($filters),
            'schedules' => $this->listSchedules($filters),
            'checklist_executions' => $this->listChecklistExecutions($filters),
        };
    }

    private function findRecord(string $type, int $id)
    {
        return match ($type) {
            'maintenance_reports' => MaintenanceReport::with(['divisi','unit','workOrder'])->find($id),
            'capex' => Capex::with('divisi')->find($id),
            'opex' => Opex::with('divisi')->find($id),
            'work_orders' => WorkOrder::with(['divisi','unit','reportedBy','assignedTo'])->find($id),
            'schedules' => Schedule::with(['divisi','unit','assignedUser'])->find($id),
            'checklist_executions' => ChecklistExecution::with(['template','unit','technician','supervisor','head','answers'])->find($id),
        };
    }

    private function listMaintenanceReports(array $f): array
    {
        $q = MaintenanceReport::with(['divisi','unit','workOrder'])->orderByDesc('report_date');
        if (!empty($f['divisis_id'])) $q->where('divisis_id', $f['divisis_id']);
        if (!empty($f['status'])) $q->where('status', $f['status']);
        if (!empty($f['from'])) $q->whereDate('report_date','>=',$f['from']);
        if (!empty($f['to'])) $q->whereDate('report_date','<=',$f['to']);
        if (!empty($f['search'])) $q->where('title','like','%'.$f['search'].'%');
        $rows = $q->get();
        $columns = ['No','Judul Laporan','Divisi','Unit','No WO','Tanggal','Biaya','Status'];
        $mapped = $rows->map(fn($r,$i)=>[
            $i+1,
            $r->title,
            $r->divisi?->nama_divisi ?? '-',
            $r->unit?->unit_name ?? '-',
            $r->workOrder?->wo_number ?? '-',
            ReportBuilder::date($r->report_date,'d/m/Y'),
            ReportBuilder::money($r->cost),
            $r->status,
        ])->toArray();
        $totalCost = $rows->sum('cost');
        return ['columns'=>$columns,'rows'=>$mapped,'total'=>$rows->count(),'summary'=>['Total Biaya', ReportBuilder::money($totalCost)]];
    }

    private function listCapex(array $f, string $model): array
    {
        $q = $model::with('divisi')->orderByDesc('expense_date');
        if (!empty($f['divisis_id'])) $q->where('divisis_id',$f['divisis_id']);
        if (!empty($f['status'])) $q->where('status',$f['status']);
        if (!empty($f['from'])) $q->whereDate('expense_date','>=',$f['from']);
        if (!empty($f['to'])) $q->whereDate('expense_date','<=',$f['to']);
        if (!empty($f['search'])) $q->where('title','like','%'.$f['search'].'%');
        $rows = $q->get();
        $columns = ['No','Judul','Divisi','Tanggal','Jumlah','Status'];
        $mapped = $rows->map(fn($r,$i)=>[$i+1,$r->title,$r->divisi?->nama_divisi ?? '-',$r->expense_date?->format('d/m/Y') ?? '-',ReportBuilder::money($r->amount),$r->status])->toArray();
        return ['columns'=>$columns,'rows'=>$mapped,'total'=>$rows->count(),'summary'=>['Total', ReportBuilder::money($rows->sum('amount'))]];
    }

    private function listWorkOrders(array $f): array
    {
        $q = WorkOrder::with(['divisi','unit'])->orderByDesc('created_at');
        if (!empty($f['divisis_id'])) $q->where('divisis_id',$f['divisis_id']);
        if (!empty($f['status'])) $q->where('status',$f['status']);
        if (!empty($f['priority'])) $q->where('priority',$f['priority']);
        if (!empty($f['from'])) $q->whereDate('scheduled_date','>=',$f['from']);
        if (!empty($f['to'])) $q->whereDate('scheduled_date','<=',$f['to']);
        if (!empty($f['search'])) $q->where(fn($qq)=>$qq->where('wo_number','like','%'.$f['search'].'%')->orWhere('description','like','%'.$f['search'].'%'));
        $rows = $q->get();
        $columns = ['No','WO Number','Divisi','Unit','Priority','Tipe','Jadwal','Status'];
        $mapped = $rows->map(fn($r,$i)=>[$i+1,$r->wo_number,$r->divisi?->nama_divisi ?? '-',$r->unit?->unit_name ?? '-',$r->priority,$r->type,$r->scheduled_date?->format('d/m/Y') ?? '-', $r->status])->toArray();
        return ['columns'=>$columns,'rows'=>$mapped,'total'=>$rows->count()];
    }

    private function listSchedules(array $f): array
    {
        $q = Schedule::with(['divisi','unit','assignedUser'])->orderByDesc('schedule_date');
        if (!empty($f['divisis_id'])) $q->where('divisis_id',$f['divisis_id']);
        if (!empty($f['status'])) $q->where('status',$f['status']);
        if (!empty($f['from'])) $q->whereDate('schedule_date','>=',$f['from']);
        if (!empty($f['to'])) $q->whereDate('schedule_date','<=',$f['to']);
        if (!empty($f['search'])) $q->where('title','like','%'.$f['search'].'%');
        $rows = $q->get();
        $columns = ['No','Judul','Divisi','Unit','Assigned','Tanggal','Jam','Status'];
        $mapped = $rows->map(fn($r,$i)=>[$i+1,$r->title,$r->divisi?->nama_divisi ?? '-',$r->unit?->unit_name ?? '-',$r->assignedUser?->name ?? '-', ReportBuilder::date($r->schedule_date,'d/m/Y'), ($r->start_time?->format('H:i') ?? '-').' - '.($r->end_time?->format('H:i') ?? '-'), $r->status])->toArray();
        return ['columns'=>$columns,'rows'=>$mapped,'total'=>$rows->count()];
    }

    private function listChecklistExecutions(array $f): array
    {
        $q = ChecklistExecution::with(['template','unit','technician'])->orderByDesc('created_at');
        if (!empty($f['status'])) $q->where('status',$f['status']);
        if (!empty($f['search'])) $q->whereHas('template', fn($qq)=>$qq->where('name','like','%'.$f['search'].'%'));
        $rows = $q->get();
        $columns = ['No','Template','Unit','Technician','Status','Submitted'];
        $mapped = $rows->map(fn($r,$i)=>[$i+1,$r->template?->name ?? '-', $r->unit?->unit_name ?? '-', $r->technician?->name ?? '-', $r->status, $r->submitted_at?->format('d/m/Y H:i') ?? '-'])->toArray();
        return ['columns'=>$columns,'rows'=>$mapped,'total'=>$rows->count()];
    }

    private function sectionsForSingle(string $type, $r): array
    {
        return match($type){
            'maintenance_reports' => [
                ['title'=>'Informasi Laporan','fields'=>[
                    ['Judul', $r->title],
                    ['Deskripsi', $r->description ?: '-'],
                    ['Tanggal Laporan', ReportBuilder::date($r->report_date)],
                    ['Biaya', ReportBuilder::money($r->cost)],
                    ['Status', $r->status],
                    ['Work Order', $r->workOrder?->wo_number ?? '-'],
                ]],
                ['title'=>'Identitas Unit','fields'=>[
                    ['Divisi', $r->divisi?->nama_divisi ?? '-'],
                    ['Unit', $r->unit?->unit_name ?? '-'],
                    ['Kode Unit', $r->unit?->unit_code ?? '-'],
                    ['Lokasi', $r->unit?->location ?? '-'],
                ]],
            ],
            'capex','opex' => [
                ['title'=>'Informasi Pengeluaran','fields'=>[
                    ['Judul', $r->title],
                    ['Deskripsi', $r->description ?: '-'],
                    ['Tanggal', ReportBuilder::date($r->expense_date)],
                    ['Jumlah', ReportBuilder::money($r->amount)],
                    ['Status', $r->status],
                    ['Divisi', $r->divisi?->nama_divisi ?? '-'],
                ]],
            ],
            'work_orders' => [
                ['title'=>'Work Order','fields'=>[
                    ['WO Number', $r->wo_number],
                    ['Tipe', $r->type ?? '-'],
                    ['Priority', $r->priority ?? '-'],
                    ['Status', $r->status ?? '-'],
                    ['Jadwal', ReportBuilder::date($r->scheduled_date)],
                    ['Mulai', $r->started_at?->format('d F Y H:i') ?? '-'],
                    ['Selesai', $r->completed_at?->format('d F Y H:i') ?? '-'],
                ]],
                ['title'=>'Penugasan','fields'=>[
                    ['Dilaporkan Oleh', $r->reportedBy?->name ?? '-'],
                    ['Ditugaskan Ke', $r->assignedTo?->name ?? '-'],
                    ['Divisi', $r->divisi?->nama_divisi ?? '-'],
                    ['Unit', $r->unit?->unit_name ?? '-'],
                ]],
                ['title'=>'Deskripsi','fields'=>[
                    ['Uraian Pekerjaan', $r->description ?: '-'],
                    ['Catatan', $r->notes ?: '-'],
                ]],
            ],
            'schedules' => [
                ['title'=>'Schedule','fields'=>[
                    ['Judul', $r->title],
                    ['Deskripsi', $r->description ?: '-'],
                    ['Tanggal', ReportBuilder::date($r->schedule_date)],
                    ['Jam', ($r->start_time?->format('H:i') ?? '-') . ' — ' . ($r->end_time?->format('H:i') ?? '-')],
                    ['Status', $r->status],
                    ['Assigned', $r->assignedUser?->name ?? '-'],
                    ['Divisi', $r->divisi?->nama_divisi ?? '-'],
                    ['Unit', $r->unit?->unit_name ?? '-'],
                ]],
            ],
            'checklist_executions' => [
                ['title'=>'Checklist Execution','fields'=>[
                    ['Template', $r->template?->name ?? '-'],
                    ['Unit', $r->unit?->unit_name ?? '-'],
                    ['Technician', $r->technician?->name ?? '-'],
                    ['Status', $r->status],
                    ['Submitted', $r->submitted_at?->format('d F Y H:i') ?? '-'],
                    ['Catatan', $r->notes ?: '-'],
                ]],
            ],
            default => [],
        };
    }

    private function tableForSingle(string $type, $r): ?array
    {
        if ($type === 'checklist_executions' && $r->answers) {
            $cols = ['No','Pertanyaan','Jawaban','Keterangan'];
            $rows = $r->answers->map(fn($a,$i)=>[$i+1, $a->question ?? $a->checklistQuestion?->question ?? '-', $a->answer ?? '-', $a->notes ?? '-'])->toArray();
            return ['columns'=>$cols,'rows'=>$rows];
        }
        return null;
    }
}
