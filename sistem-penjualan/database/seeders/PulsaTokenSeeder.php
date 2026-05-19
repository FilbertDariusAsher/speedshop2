<?php

namespace Database\Seeders;

use App\Models\PulsaProvider;
use App\Models\PulsaNominal;
use App\Models\TokenNominal;
use Illuminate\Database\Seeder;

class PulsaTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ==================== PULSA PROVIDERS & NOMINALS ====================
        
        $providers = [
            ['name' => 'Telkomsel', 'nominals' => [
                ['nominal_amount' => 10, 'markup' => 2000],
                ['nominal_amount' => 20, 'markup' => 2000],
                ['nominal_amount' => 50, 'markup' => 2000],
                ['nominal_amount' => 100, 'markup' => 5000],
            ]],
            ['name' => 'Tri', 'nominals' => [
                ['nominal_amount' => 10, 'markup' => 1500],
                ['nominal_amount' => 20, 'markup' => 1500],
                ['nominal_amount' => 50, 'markup' => 2000],
                ['nominal_amount' => 100, 'markup' => 5000],
            ]],
            ['name' => 'Indosat', 'nominals' => [
                ['nominal_amount' => 10, 'markup' => 2000],
                ['nominal_amount' => 20, 'markup' => 2000],
                ['nominal_amount' => 50, 'markup' => 2000],
                ['nominal_amount' => 100, 'markup' => 5000],
            ]],
            ['name' => 'XL Axiata', 'nominals' => [
                ['nominal_amount' => 10, 'markup' => 2000],
                ['nominal_amount' => 20, 'markup' => 2000],
                ['nominal_amount' => 50, 'markup' => 2000],
                ['nominal_amount' => 100, 'markup' => 5000],
            ]],
        ];

        foreach ($providers as $providerData) {
            $provider = PulsaProvider::create([
                'name' => $providerData['name'],
                'active' => true,
            ]);

            foreach ($providerData['nominals'] as $nominalData) {
                PulsaNominal::create([
                    'provider_id' => $provider->id,
                    'nominal_amount' => $nominalData['nominal_amount'],
                    'markup' => $nominalData['markup'],
                    'active' => true,
                ]);
            }
        }

        // ==================== TOKEN NOMINALS ====================
        
        $tokenNominals = [
            ['nominal_amount' => 300000, 'harga_final' => 300000, 'profit' => 3000],
            ['nominal_amount' => 500000, 'harga_final' => 500000, 'profit' => 5000],
            ['nominal_amount' => 1000000, 'harga_final' => 1000000, 'profit' => 10000],
        ];

        foreach ($tokenNominals as $tokenData) {
            TokenNominal::create([
                'nominal_amount' => $tokenData['nominal_amount'],
                'harga_final' => $tokenData['harga_final'],
                'profit' => $tokenData['profit'],
                'active' => true,
            ]);
        }
    }
}
