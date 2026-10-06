<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\EventChecklistItem;
use App\Models\Facility;
use App\Models\FacilityMaintenanceLog;
use App\Models\NotificationPreference;
use App\Models\Player;
use App\Models\Staff;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeds the literal sample data shown throughout public/project-ui/*.html
 * so every admin/public screen matches the reference out of the box.
 */
class StadiumSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'y.alhassan@aliumahamastadium.gh'],
            [
                'name' => 'Yakubu Alhassan',
                'first_name' => 'Yakubu',
                'last_name' => 'Alhassan',
                'password' => Hash::make('password'),
                'phone' => '+233 24 000 0000',
                'role' => 'facilities_manager',
                'access_level' => 'full',
                'email_verified_at' => now(),
            ]
        );

        $ticketingLead = User::updateOrCreate(
            ['email' => 'k.mensah@aliumahamastadium.gh'],
            [
                'name' => 'Kofi Mensah',
                'first_name' => 'Kofi',
                'last_name' => 'Mensah',
                'password' => Hash::make('password'),
                'role' => 'ticketing_lead',
                'access_level' => 'bookings_only',
                'email_verified_at' => now(),
            ]
        );

        $headGroundskeeper = User::updateOrCreate(
            ['email' => 'f.mohammed@aliumahamastadium.gh'],
            [
                'name' => 'Fati Mohammed',
                'first_name' => 'Fati',
                'last_name' => 'Mohammed',
                'password' => Hash::make('password'),
                'role' => 'facilities_manager',
                'access_level' => 'facilities_only',
                'email_verified_at' => now(),
            ]
        );

        NotificationPreference::updateOrCreate(['user_id' => $admin->id], [
            'new_booking_requests' => true,
            'maintenance_reminders' => true,
            'weekly_summary' => false,
        ]);

        // ---------------------------------------------------------------
        // Facilities
        // ---------------------------------------------------------------
        $mainPitch = Facility::updateOrCreate(['slug' => 'main-pitch'], [
            'name' => 'Main Pitch',
            'type' => 'pitch',
            'description' => 'FIFA-standard natural turf, floodlit for evening Premier League fixtures.',
            'capacity' => 22600,
            'hourly_rate' => 1500,
            'status' => 'operational',
            'utilisation_percent' => 78,
            'next_maintenance_at' => now()->addDays(14)->toDateString(),
            'icon' => 'fa-solid fa-futbol',
        ]);

        $athleticsTrack = Facility::updateOrCreate(['slug' => 'athletics-track'], [
            'name' => 'Athletics Track',
            'type' => 'track',
            'description' => '8-lane synthetic track certified for regional and inter-school athletics.',
            'capacity' => 6200,
            'hourly_rate' => 800,
            'status' => 'operational',
            'utilisation_percent' => 54,
            'next_maintenance_at' => now()->addDays(30)->toDateString(),
            'icon' => 'fa-solid fa-person-running',
        ]);

        $trainingAnnex = Facility::updateOrCreate(['slug' => 'training-annex'], [
            'name' => 'Training Annex',
            'type' => 'gym',
            'description' => 'Indoor gym and conditioning hall available for team bookings.',
            'capacity' => null,
            'hourly_rate' => 300,
            'status' => 'maintenance',
            'utilisation_percent' => 31,
            'next_maintenance_at' => now()->addDays(5)->toDateString(),
            'icon' => 'fa-solid fa-dumbbell',
        ]);

        $conferenceSuite = Facility::updateOrCreate(['slug' => 'conference-suite'], [
            'name' => 'Conference Suite',
            'type' => 'hall',
            'description' => 'Press briefings, club functions and community events, 300-seat capacity.',
            'capacity' => 300,
            'hourly_rate' => 500,
            'status' => 'operational',
            'utilisation_percent' => 62,
            'next_maintenance_at' => now()->addDays(25)->toDateString(),
            'icon' => 'fa-solid fa-users',
        ]);

        FacilityMaintenanceLog::query()->delete();
        FacilityMaintenanceLog::create(['facility_id' => $trainingAnnex->id, 'task' => 'HVAC & equipment servicing', 'assigned_to' => 'Groundskeeping Team', 'scheduled_at' => now()->toDateString(), 'status' => 'in_progress']);
        FacilityMaintenanceLog::create(['facility_id' => $mainPitch->id, 'task' => 'Turf reseeding & line marking', 'assigned_to' => 'Pitch Crew A', 'scheduled_at' => now()->addDays(14)->toDateString(), 'status' => 'scheduled']);
        FacilityMaintenanceLog::create(['facility_id' => $athleticsTrack->id, 'task' => 'Synthetic surface inspection', 'assigned_to' => 'Facilities Team', 'scheduled_at' => now()->subDays(14)->toDateString(), 'status' => 'completed']);
        FacilityMaintenanceLog::create(['facility_id' => $conferenceSuite->id, 'task' => 'AV system upgrade', 'assigned_to' => 'IT & AV Vendor', 'scheduled_at' => now()->addDays(25)->toDateString(), 'status' => 'scheduled']);

        // ---------------------------------------------------------------
        // Teams & Players
        // ---------------------------------------------------------------
        $rtu = Team::updateOrCreate(['name' => 'Real Tamale United'], [
            'category' => 'Ghana Premier League',
            'logo_url' => 'https://ui-avatars.com/api/?name=RTU&background=0B6E4F&color=fff&bold=true&size=64',
            'type' => 'resident',
        ]);
        Team::updateOrCreate(['name' => 'Northern FC Academy'], [
            'category' => 'Youth Academy',
            'logo_url' => 'https://ui-avatars.com/api/?name=Northern+FC&background=16A075&color=fff&bold=true&size=64',
            'type' => 'resident',
        ]);
        Team::updateOrCreate(['name' => 'GES Athletics Unit'], [
            'category' => 'Regional Athletics',
            'logo_url' => 'https://ui-avatars.com/api/?name=GES+Athletics&background=FFC72C&color=063D2C&bold=true&size=64',
            'type' => 'affiliate',
        ]);

        Player::where('team_id', $rtu->id)->delete();
        $roster = [
            [1, 'Salifu Ibrahim', 'Goalkeeper', 27, 'Ghana', 'fit', '0B6E4F', 'fff'],
            [4, 'Yussif Mumuni', 'Centre Back', 24, 'Ghana', 'fit', '16A075', 'fff'],
            [9, 'Abdul Rahman', 'Striker', 22, 'Ghana', 'injured', 'FFC72C', '063D2C'],
            [11, 'Kwame Boateng', 'Winger', 25, 'Ghana', 'fit', '0B6E4F', 'fff'],
            [17, 'Fuseini Adams', 'Midfielder', 21, 'Ghana', 'fit', '16A075', 'fff'],
        ];
        foreach ($roster as [$jersey, $name, $position, $age, $nationality, $fitness, $bg, $color]) {
            Player::create([
                'team_id' => $rtu->id,
                'jersey_number' => $jersey,
                'name' => $name,
                'avatar_url' => 'https://ui-avatars.com/api/?name=' . urlencode($name) . "&background={$bg}&color={$color}",
                'position' => $position,
                'age' => $age,
                'nationality' => $nationality,
                'fitness_status' => $fitness,
            ]);
        }

        // ---------------------------------------------------------------
        // Staff
        // ---------------------------------------------------------------
        Staff::query()->delete();
        $staff = [
            ['Yakubu Alhassan', 'administration', 'Facilities Manager', 'Mon-Fri, 8AM-6PM', 'Overall coordination', 'on_shift', 'FFC72C', '063D2C', $admin->id],
            ['Fati Mohammed', 'groundskeeping', 'Head Groundskeeper', 'Daily, 6AM-2PM', 'Pitch readiness', 'on_shift', '0B6E4F', 'fff', $headGroundskeeper->id],
            ['Iddrisu Baba', 'security', 'Head of Security', 'Matchday only', 'Crowd control lead', 'off_duty', '16A075', 'fff', null],
            ['Dr. Amina Sulley', 'medical', 'Team Physician', 'Matchday only', 'Pitchside first aid', 'off_duty', 'D64545', 'fff', null],
            ['Kofi Mensah', 'administration', 'Ticketing Lead', 'Mon-Sat, 9AM-5PM', 'Gate & ticket ops', 'on_shift', 'FFC72C', '063D2C', $ticketingLead->id],
        ];
        foreach ($staff as [$name, $department, $role, $shift, $duty, $status, $bg, $color, $userId]) {
            Staff::create([
                'user_id' => $userId,
                'name' => $name,
                'avatar_url' => 'https://ui-avatars.com/api/?name=' . urlencode($name) . "&background={$bg}&color={$color}",
                'department' => $department,
                'role_title' => $role,
                'shift' => $shift,
                'matchday_duty' => $duty,
                'status' => $status,
            ]);
        }

        // ---------------------------------------------------------------
        // Events
        // ---------------------------------------------------------------
        Event::query()->delete();
        $rtuFixture = Event::create([
            'title' => 'Real Tamale United vs Asante Kotoko',
            'type' => 'league_fixture',
            'facility_id' => $mainPitch->id,
            'date' => now()->addDays(3)->toDateString(),
            'start_time' => '15:00',
            'end_time' => '17:00',
            'capacity' => 22600,
            'expected_attendance' => 20000,
            'tickets_sold' => 18400,
            'ticket_price' => 50,
            'status' => 'confirmed',
            'ticket_status' => 'open',
        ]);
        Event::create([
            'title' => 'Northern Regional Athletics Meet',
            'type' => 'athletics',
            'facility_id' => $athleticsTrack->id,
            'date' => now()->addDays(9)->toDateString(),
            'start_time' => '09:00',
            'end_time' => '16:00',
            'capacity' => 6200,
            'expected_attendance' => 6200,
            'tickets_sold' => 6200,
            'ticket_price' => 20,
            'status' => 'prep',
            'ticket_status' => 'selling_fast',
        ]);
        Event::create([
            'title' => 'Independence Cup Final',
            'type' => 'tournament',
            'facility_id' => $mainPitch->id,
            'date' => now()->addDays(16)->toDateString(),
            'start_time' => '16:00',
            'end_time' => '18:30',
            'capacity' => 22600,
            'expected_attendance' => 22600,
            'tickets_sold' => 22600,
            'ticket_price' => 60,
            'status' => 'confirmed',
            'ticket_status' => 'sold_out',
        ]);
        Event::create([
            'title' => 'Inter-School Relay Finals',
            'type' => 'youth',
            'facility_id' => $athleticsTrack->id,
            'date' => now()->addDays(23)->toDateString(),
            'start_time' => '10:00',
            'end_time' => '13:00',
            'capacity' => 1050,
            'expected_attendance' => 1050,
            'tickets_sold' => 1050,
            'ticket_price' => 10,
            'status' => 'prep',
            'ticket_status' => 'open',
        ]);

        EventChecklistItem::where('event_id', $rtuFixture->id)->delete();
        EventChecklistItem::create(['event_id' => $rtuFixture->id, 'label' => 'Pitch inspection & line marking', 'is_done' => true]);
        EventChecklistItem::create(['event_id' => $rtuFixture->id, 'label' => 'Floodlight test', 'is_done' => true]);
        EventChecklistItem::create(['event_id' => $rtuFixture->id, 'label' => 'Security & stewarding briefing', 'is_done' => false]);
        EventChecklistItem::create(['event_id' => $rtuFixture->id, 'label' => 'Medical team on standby', 'is_done' => false]);
        EventChecklistItem::create(['event_id' => $rtuFixture->id, 'label' => 'Broadcast crew access', 'is_done' => false]);

        // ---------------------------------------------------------------
        // Bookings
        // ---------------------------------------------------------------
        Booking::query()->delete();
        $bookings = [
            ['BK-2201', 'Real Tamale United', $mainPitch->id, now()->addDays(3)->toDateString(), '15:00', '17:00', 'league_fixture', 12000, 'confirmed'],
            ['BK-2202', 'GES Athletics Unit', $athleticsTrack->id, now()->addDays(9)->toDateString(), '09:00', '13:00', 'community_event', 8500, 'pending'],
            ['BK-2203', 'Northern FC Academy', $trainingAnnex->id, now()->addDays(5)->toDateString(), '16:00', '18:00', 'training', 2200, 'confirmed'],
            ['BK-2204', 'Regional Assembly', $conferenceSuite->id, now()->addDays(1)->toDateString(), '10:00', '13:00', 'private_function', 4000, 'cancelled'],
            ['BK-2205', 'Tamale Senior High', $athleticsTrack->id, now()->addDays(23)->toDateString(), '10:00', '13:00', 'community_event', 1500, 'pending'],
        ];
        foreach ($bookings as [$ref, $org, $facilityId, $date, $start, $end, $purpose, $amount, $status]) {
            Booking::create([
                'booking_ref' => $ref,
                'user_id' => null,
                'facility_id' => $facilityId,
                'organisation' => $org,
                'date' => $date,
                'start_time' => $start,
                'end_time' => $end,
                'purpose' => $purpose,
                'expected_attendance' => null,
                'contact_name' => $org,
                'contact_email' => strtolower(str_replace(' ', '.', $org)) . '@example.com',
                'contact_phone' => '+233 20 000 0000',
                'notes' => null,
                'amount' => $amount,
                'status' => $status,
            ]);
        }

        // ---------------------------------------------------------------
        // Contact messages
        // ---------------------------------------------------------------
        ContactMessage::updateOrCreate(
            ['email' => 'press@northernsports.gh'],
            [
                'name' => 'Northern Sports Desk',
                'subject' => 'media',
                'message' => 'Requesting media accreditation for the upcoming Independence Cup Final.',
            ]
        );
    }
}
