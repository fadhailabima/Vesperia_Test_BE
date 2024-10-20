<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\File;
use App\Models\Incident;
use App\Models\IncidentDivision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
{
    public function divisi()
    {
        $divisions = Division::all();
        return response()->json($divisions);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report_month' => 'required|in:January,February,March,April,May,June,July,August,September,October,November,December',
            'report_quarter' => 'required|in:Q1,Q2,Q3,Q4',
            'incident_date' => 'required|date',
            'found_date' => 'required|date',
            'incident_description' => 'required|string',
            'root_cause' => 'required|string',
            'file_description' => 'nullable|string',
            'amount' => 'required|integer',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,png,jpg|max:51200',
            'division_ids' => 'nullable|array',
            'division_ids.*' => 'integer|exists:divisions,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $incident = new Incident();
        $incident->report_month = $request->input('report_month');
        $incident->report_quarter = $request->input('report_quarter');
        $incident->incident_date = $request->input('incident_date');
        $incident->found_date = $request->input('found_date');
        $incident->incident_description = $request->input('incident_description');
        $incident->root_cause = $request->input('root_cause');
        $incident->amount = $request->input('amount');
        $incident->save();

        $incidentDivisions = [];
        if ($request->filled('division_ids')) {
            $divisionIds = $request->input('division_ids');
            if (is_array($divisionIds)) {
                foreach ($divisionIds as $divisionId) {
                    $incidentDivision = new IncidentDivision();
                    $incidentDivision->incidentId = $incident->id;
                    $incidentDivision->divisionId = $divisionId;
                    $incidentDivision->save();
                    $incidentDivisions[] = $incidentDivision;
                }
            } else {
                $incidentDivision = new IncidentDivision();
                $incidentDivision->incidentId = $incident->id;
                $incidentDivision->divisionId = $divisionIds;
                $incidentDivision->save();
                $incidentDivisions[] = $incidentDivision;
            }
        }
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $fileIncident = new File();
            $fileIncident->file_description = $request->input('file_description');
            $file = $request->file('file');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/incidentFiles/', $fileName);
            $fileIncident->file = $fileName;
            $fileIncident->incidentId = $incident->id;
            $fileIncident->save();
        }

        $response = [
            'incident' => $incident,
            'incidentDivisions' => $incidentDivisions,
            'fileIncident' => $fileIncident
        ];

        return response()->json($response, 200);
    }

    public function incident()
    {
        $incidents = Incident::all();

        return response()->json($incidents, 200);
    }

    public function detail($id)
    {
        $incident = Incident::find($id);

        if (!$incident) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        $incidentDivision = IncidentDivision::where('incidentId', $id)->get();
        $fileIncident = File::where('incidentId', $id)->first();

        if ($fileIncident) {
            $fileIncident->file = $fileIncident->file ? url('api/storage/app/public/incidentFiles/' . $fileIncident->file) : null;
        }
        foreach ($incidentDivision as $division) {
            $divisionData = Division::find($division->divisionId);
            $division->division_name = $divisionData ? $divisionData->division_name : null;
        }

        $response = [
            'incident' => $incident,
            'incidentDivision' => $incidentDivision,
            'fileIncident' => $fileIncident
        ];

        return response()->json($response, 200);
    }
}
