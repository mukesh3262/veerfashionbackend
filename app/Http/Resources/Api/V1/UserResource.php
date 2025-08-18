<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'stripe_customer_id' => $this->stripe_customer_id,
            'email' => $this->email,
            'isd_code' => $this->isd_code,
            'mobile' => $this->mobile,
            'mobile_verified_at' => $this->mobile_verified_at,
            'email_verified_at' => $this->email_verified_at,
            'is_push_enabled' => $this->is_push_enabled,
            'profile_photo' => Storage::url(config('constants.s3.customer_profile'). '/' . $this->profile_photo)
        ];
    }
}
