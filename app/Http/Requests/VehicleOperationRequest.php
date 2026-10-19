<?php

namespace App\Http\Requests;

use App\Models\VehicleOperation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehicleOperationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $type = $this->input('event_type');

        $rules = [
            'event_type' => ['required', Rule::in(array_keys(VehicleOperation::TYPES))],
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],
            'driver_id' => ['required', 'integer', 'exists:drivers,id'],
            'operation_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'idempotency_key' => ['nullable', 'string', 'max:64'],
        ];

        if (in_array($type, [VehicleOperation::TYPE_DIESEL, VehicleOperation::TYPE_OIL], true)) {
            $rules['liters'] = ['required', 'numeric', 'gt:0', 'max:100000'];
            $rules['maintenance_cost'] = ['prohibited'];
        } elseif ($type === VehicleOperation::TYPE_MAINTENANCE) {
            $rules['maintenance_cost'] = ['required', 'numeric', 'min:0', 'max:100000000'];
            $rules['liters'] = ['prohibited'];
        } else {
            $rules['liters'] = ['nullable', 'numeric', 'gt:0'];
            $rules['maintenance_cost'] = ['nullable', 'numeric', 'min:0'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'event_type.required' => 'نوع الحدث مطلوب.',
            'event_type.in' => 'نوع الحدث غير صالح.',
            'vehicle_id.required' => 'المركبة مطلوبة.',
            'vehicle_id.exists' => 'المركبة المختارة غير موجودة.',
            'driver_id.required' => 'السائق مطلوب.',
            'driver_id.exists' => 'السائق المختار غير موجود.',
            'liters.required' => 'كمية اللترات مطلوبة للسولار والزيت.',
            'liters.gt' => 'كمية اللترات يجب أن تكون أكبر من صفر.',
            'maintenance_cost.required' => 'تكلفة الصيانة مطلوبة.',
            'maintenance_cost.min' => 'التكلفة لا يجوز أن تكون سالبة.',
            'maintenance_cost.prohibited' => 'التكلفة غير مسموحة لنوع السولار/الزيت.',
            'liters.prohibited' => 'اللترات غير مسموحة لنوع الصيانة.',
            'operation_date.required' => 'تاريخ العملية مطلوب.',
        ];
    }
}
