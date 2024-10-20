<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Division;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $divisions = [
            ['division_name' => 'DSP'],
            ['division_name' => 'DSDM'],
            ['division_name' => 'DP2'],
            ['division_name' => 'DPPU1'],
            ['division_name' => 'DPPU2'],
            ['division_name' => 'DAA'],
            ['division_name' => 'DTI'],
            ['division_name' => 'DEPI'],
            ['division_name' => 'DAI'],
            ['division_name' => 'DRE'],
            ['division_name' => 'DPB'],
            ['division_name' => 'DPP'],
            ['division_name' => 'DPPU3'],
            ['division_name' => 'DPOP'],
            ['division_name' => 'DPPIK'],
            ['division_name' => 'DPKMI'],
            ['division_name' => 'DP1'],
            ['division_name' => 'DUS'],
            ['division_name' => 'DJK'],
            ['division_name' => 'DKHI'],
            ['division_name' => 'DUP'],
            ['division_name' => 'DMRT'],
            ['division_name' => 'DELST'],
            ['division_name' => 'DH'],
        ];

        foreach ($divisions as $division) {
            Division::create($division);
        }
    }
}
