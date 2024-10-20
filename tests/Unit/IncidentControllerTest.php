<?php

namespace Tests\Unit;

use App\Models\Incident;
use App\Models\IncidentDivision;
use Tests\TestCase;
use App\Models\File;

class IncidentControllerTest extends TestCase
{
    /** @test */
    public function validates_the_request()
    {
        $response = $this->postJson('/api/store', [
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['report_month', 'incident_date']);
    }

    /** @test */
    public function validates_incident_creation()
    {
        $response = $this->postJson('/api/store', [
            'report_month' => '',
            'report_quarter' => 'Q5',
            'incident_date' => 'invalid-date',
            'found_date' => 'invalid-date',
            'incident_description' => '',
            'root_cause' => '',
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['report_month', 'report_quarter', 'incident_date', 'found_date', 'incident_description', 'root_cause']);
    }
    /** @test */
    public function shows_incident_with_related_data()
    {
        $incident = Incident::create([
            'report_month' => 'January',
            'report_quarter' => 'Q1',
            'incident_date' => '2023-01-01',
            'found_date' => '2023-01-02',
            'amount' => 1000,
            'incident_description' => 'Test incident description',
            'root_cause' => 'Test root cause',
        ]);

        $incidentDivision = IncidentDivision::create([
            'incidentId' => $incident->id,
            'divisionId' => 1,
        ]);

        $fileEvent = File::create([
            'incidentId' => $incident->id,
            'file_description' => 'Test file description',
            'file' => 'testfile.pdf',
        ]);

        $response = $this->getJson("/api/incident-detail/{$incident->id}");

        $response->assertStatus(200);

        $response->assertJson([
            'incident' => [
                'id' => $incident->id,
                'report_month' => $incident->report_month,
                'report_quarter' => $incident->report_quarter,
                'incident_date' => $incident->incident_date,
                'found_date' => $incident->found_date,
                'amount' => $incident->amount,
                'incident_description' => $incident->incident_description,
                'root_cause' => $incident->root_cause,
            ],
            'incidentDivision' => [
                [
                    'id' => $incidentDivision->id,
                    'incidentId' => $incidentDivision->incidentId,
                    'divisionId' => $incidentDivision->divisionId,
                ]
            ],
            'fileEvent' => [
                [
                    'id' => $fileEvent->id,
                    'incidentId' => $fileEvent->incidentId,
                    'file_description' => $fileEvent->file_description,
                    'file' => $fileEvent->file,
                ]
            ]
        ]);
    }
}
