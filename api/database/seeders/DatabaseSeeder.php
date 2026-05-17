<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\ProcessingRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Senior Candidate',
            'email' => 'senior@example.com',
            'password' => Hash::make('password'),
        ]);

        $alpha = Project::create(['name' => 'Alpha', 'slug' => 'alpha', 'is_active' => true]);
        $beta = Project::create(['name' => 'Beta', 'slug' => 'beta', 'is_active' => true]);
        Project::create(['name' => 'Legacy', 'slug' => 'legacy', 'is_active' => false]);

        ProcessingRequest::create([
            'project_id' => $alpha->id,
            'created_by' => $user->id,
            'reference' => 'ALPHA-001',
            'payload_json' => [
                'customer' => 'ACME',
                'items' => [
                    ['sku' => 'A1', 'qty' => 2, 'price' => 10],
                    ['sku' => 'B2', 'qty' => 1, 'price' => 30],
                ],
            ],
            'status' => ProcessingRequest::STATUS_COMPLETED,
            'result_json' => [
                'customer' => 'ACME',
                'items_count' => 2,
                'total_amount' => 50,
                'vat' => 11,
                'grand_total' => 61,
            ],
            'processed_at' => now()->subHour(),
            'created_at' => now()->subHours(2),
            'updated_at' => now()->subHour(),
        ]);

        ProcessingRequest::create([
            'project_id' => $beta->id,
            'created_by' => $user->id,
            'reference' => 'BETA-001',
            'payload_json' => [
                'customer' => 'Globex',
                'items' => [
                    ['sku' => 'C3', 'qty' => 0, 'price' => 50],
                ],
            ],
            'status' => ProcessingRequest::STATUS_FAILED,
            'error_message' => 'Invalid qty.',
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        for ($i = 2; $i <= 30; $i++) {
            ProcessingRequest::create([
                'project_id' => $i % 2 === 0 ? $alpha->id : $beta->id,
                'created_by' => $user->id,
                'reference' => 'TEST-REF-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'payload_json' => [
                    'customer' => 'Customer ' . $i,
                    'items' => [
                        ['sku' => 'SKU-' . $i, 'qty' => 1, 'price' => $i * 5],
                    ],
                ],
                'status' => $i % 4 === 0 ? ProcessingRequest::STATUS_COMPLETED 
                          : ($i % 4 === 1 ? ProcessingRequest::STATUS_FAILED 
                          : ($i % 4 === 2 ? ProcessingRequest::STATUS_PROCESSING 
                          : ProcessingRequest::STATUS_PENDING)),
            ]);
        }
    }
}
