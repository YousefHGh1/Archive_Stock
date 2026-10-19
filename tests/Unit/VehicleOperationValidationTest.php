<?php

namespace Tests\Unit;

use App\Http\Requests\VehicleOperationRequest;
use App\Models\VehicleOperation;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class VehicleOperationValidationTest extends TestCase
{
    protected function rules(array $input): array
    {
        $request = new VehicleOperationRequest();
        // نحاكي input النوع لتحديد القواعد الشرطية
        $request->merge($input);

        return $request->rules();
    }

    public function test_diesel_requires_liters_and_forbids_cost(): void
    {
        $rules = $this->rules(['event_type' => 'diesel']);

        $this->assertContains('required', $rules['liters']);
        $this->assertContains('prohibited', $rules['maintenance_cost']);

        $v = Validator::make(['liters' => 0, 'maintenance_cost' => 5], $rules);
        $this->assertTrue($v->fails());
    }

    public function test_maintenance_requires_cost_and_forbids_liters(): void
    {
        $rules = $this->rules(['event_type' => 'maintenance']);

        $this->assertContains('required', $rules['maintenance_cost']);
        $this->assertContains('prohibited', $rules['liters']);

        $v = Validator::make(['maintenance_cost' => -1, 'liters' => 5], $rules);
        $this->assertTrue($v->fails());
    }

    public function test_event_types_map_to_arabic_labels(): void
    {
        $this->assertSame('سولار', VehicleOperation::TYPES['diesel']);
        $this->assertSame('زيت', VehicleOperation::TYPES['oil']);
        $this->assertSame('صيانة', VehicleOperation::TYPES['maintenance']);
    }
}
