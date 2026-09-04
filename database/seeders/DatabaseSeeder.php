<?php

namespace Database\Seeders;

use App\Models\Capex;
use App\Models\ChecklistAnswer;
use App\Models\ChecklistExecution;
use App\Models\ChecklistQuestion;
use App\Models\ChecklistTemplate;
use App\Models\Department;
use App\Models\Divisi;
use App\Models\Event;
use App\Models\MaintenanceReport;
use App\Models\Opex;
use App\Models\Schedule;
use App\Models\Unit;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['Admin', 'Supervisor', 'Teknisi', 'Viewer', 'Asst. Head of Engineering'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        $deptEng = Department::firstOrCreate(
            ['code' => 'ENG'],
            ['name' => 'Department Engineering', 'description' => 'Pusat operasional dan pemeliharaan gedung.', 'is_active' => true]
        );

        $deptMep = Department::firstOrCreate(
            ['code' => 'MEP'],
            ['name' => 'Department MEP', 'description' => 'Mechanical, Electrical, and Plumbing.', 'is_active' => true]
        );

        $divEscalator = Divisi::firstOrCreate(
            ['slug' => 'escalator-lift'],
            ['department_id' => $deptEng->id, 'nama_divisi' => 'Escalator & Lift', 'warna' => '#3498db', 'is_active' => true]
        );

        $divHvac = Divisi::firstOrCreate(
            ['slug' => 'hvac'],
            ['department_id' => $deptMep->id, 'nama_divisi' => 'HVAC', 'warna' => '#2ecc71', 'is_active' => true]
        );

        $admin = User::firstOrCreate(['email' => 'admin@engineering.com'], ['name' => 'Administrator', 'password' => Hash::make('password'), 'phone' => '081234567890', 'division_id' => $divEscalator->id, 'status' => 'tetap']);
        $admin->assignRole('Admin');

        $supervisor = User::firstOrCreate(['email' => 'supervisor@engineering.com'], ['name' => 'Budi Supervisor', 'password' => Hash::make('password'), 'phone' => '081234567891', 'division_id' => $divEscalator->id, 'status' => 'tetap']);
        $supervisor->assignRole('Supervisor');

        $technician = User::firstOrCreate(['email' => 'teknisi@engineering.com'], ['name' => 'Naufal Teknisi', 'password' => Hash::make('password'), 'phone' => '081234567892', 'division_id' => $divEscalator->id, 'status' => 'tetap']);
        $technician->assignRole('Teknisi');

        $head = User::firstOrCreate(['email' => 'head@engineering.com'], ['name' => 'Ir. Hartono (Head)', 'password' => Hash::make('password'), 'phone' => '081234567893', 'division_id' => $divEscalator->id, 'status' => 'tetap']);
        $head->assignRole('Asst. Head of Engineering');

        $unitEsc1 = Unit::firstOrCreate(['unit_code' => 'ESC-01'], ['divisis_id' => $divEscalator->id, 'unit_name' => 'Escalator Utama Lobby', 'location' => 'Main Lobby East', 'floor' => 'GF', 'area' => 'Zone A', 'status' => 'active']);
        $unitLift1 = Unit::firstOrCreate(['unit_code' => 'LIFT-01'], ['divisis_id' => $divEscalator->id, 'unit_name' => 'Passenger Lift 01', 'location' => 'Tower 1 Lobby', 'floor' => 'All Floors', 'area' => 'Tower 1', 'status' => 'active']);
        $unitHvac1 = Unit::firstOrCreate(['unit_code' => 'AHU-01'], ['divisis_id' => $divHvac->id, 'unit_name' => 'AHU Hall Utama', 'location' => 'Rooftop Mezzanine', 'floor' => 'Roof', 'area' => 'HVAC Central', 'status' => 'active']);

        $templateEsc = ChecklistTemplate::firstOrCreate(['name' => 'PM Harian Escalator & Lift'], ['division_id' => $divEscalator->id, 'description' => 'Pemeriksaan rutin harian.', 'is_active' => true]);

        $q1 = ChecklistQuestion::firstOrCreate(['checklist_template_id' => $templateEsc->id, 'order' => 1], ['label' => 'Kondisi Handrail & Belt', 'type' => 'condition', 'options' => ['Baik', 'Aus', 'Rusak'], 'is_required' => true]);
        $q2 = ChecklistQuestion::firstOrCreate(['checklist_template_id' => $templateEsc->id, 'order' => 2], ['label' => 'Sistem Emergency Stop Berfungsi Normal?', 'type' => 'yes_no', 'options' => null, 'is_required' => true]);

        $execution = ChecklistExecution::firstOrCreate(
            ['checklist_template_id' => $templateEsc->id, 'unit_id' => $unitEsc1->id],
            [
                'technician_id' => $technician->id,
                'status' => 'supervisor_approved',
                'notes' => 'Pemeriksaan rutin berjalan lancar.',
                'submitted_at' => now()->subDay(),
                'supervisor_id' => $supervisor->id,
                'supervisor_approved_at' => now()->subHours(10),
            ]
        );

        ChecklistAnswer::firstOrCreate(['checklist_execution_id' => $execution->id, 'question_snapshot' => $q1->label], ['type_snapshot' => $q1->type, 'options_snapshot' => $q1->options, 'is_required_snapshot' => $q1->is_required, 'order' => 1, 'answer_text' => 'Baik']);
        ChecklistAnswer::firstOrCreate(['checklist_execution_id' => $execution->id, 'question_snapshot' => $q2->label], ['type_snapshot' => $q2->type, 'options_snapshot' => $q2->options, 'is_required_snapshot' => $q2->is_required, 'order' => 2, 'answer_text' => 'Ya']);

        Schedule::firstOrCreate(['title' => 'PM Bulanan Escalator Utama'], ['divisis_id' => $divEscalator->id, 'unit_id' => $unitEsc1->id, 'assigned_user_id' => $technician->id, 'description' => 'Pelumasan gear.', 'schedule_date' => now()->toDateString(), 'start_time' => '09:00:00', 'end_time' => '11:00:00', 'status' => 'in_progress']);
        Schedule::firstOrCreate(['title' => 'Maintenance AHU Hall Utama'], ['divisis_id' => $divHvac->id, 'unit_id' => $unitHvac1->id, 'assigned_user_id' => $technician->id, 'description' => 'Cek filter.', 'schedule_date' => now()->addDays(2)->toDateString(), 'start_time' => '13:00:00', 'end_time' => '15:00:00', 'status' => 'scheduled']);

        WorkOrder::firstOrCreate(['wo_number' => 'WO-20260903-TEST1'], ['divisis_id' => $divEscalator->id, 'unit_id' => $unitLift1->id, 'reported_by' => $admin->id, 'assigned_to' => $technician->id, 'priority' => 'high', 'type' => 'corrective', 'description' => 'Lift berhenti mendadak.', 'scheduled_date' => now()->toDateString(), 'started_at' => now()->subHours(2), 'status' => 'in_progress']);

        Event::firstOrCreate(['judul' => 'Inspeksi Lift oleh Disnaker'], ['divisi_id' => $divEscalator->id, 'deskripsi' => 'Inspeksi berkala.', 'tanggal_mulai' => now()->addDays(5)->setTime(10, 0), 'tanggal_selesai' => now()->addDays(5)->setTime(14, 0), 'all_day' => false]);

        Capex::firstOrCreate(['title' => 'Penggantian Main Inverter Lift 01'], ['divisis_id' => $divEscalator->id, 'description' => 'Investasi inverter.', 'amount' => 45000000.00, 'expense_date' => now()->subDays(10), 'status' => 'approved']);
        Opex::firstOrCreate(['title' => 'Pembelian Pelumas & Filter'], ['divisis_id' => $divHvac->id, 'description' => 'Belanja rutin.', 'amount' => 3500000.00, 'expense_date' => now()->subDays(3), 'status' => 'approved']);
        MaintenanceReport::firstOrCreate(['title' => 'Laporan Perbaikan Lift 01'], ['divisis_id' => $divEscalator->id, 'unit_id' => $unitLift1->id, 'report_date' => now()->toDateString(), 'description' => 'Perbaikan relay.', 'cost' => 750000.00, 'status' => 'published']);
    }
}
