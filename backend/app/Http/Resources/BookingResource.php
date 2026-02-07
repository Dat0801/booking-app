<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $startDate = $this->scheduled_date ? Carbon::parse($this->scheduled_date) : null;
        $endDate = $this->end_time ? Carbon::parse($this->end_time) : null;
        
        // Generate initials from guest name
        $initials = $this->getInitials($this->user->name);

        return [
            'id' => $this->id,
            'booking_number' => $this->booking_number,
            'guest' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone' => $this->user->phone ?? 'N/A',
                'initials' => $initials,
            ],
            'property' => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'type' => $this->product->type,
                'location' => $this->product->location,
                'price' => (float) $this->product->price,
                'image_url' => $this->product->image_url,
            ],
            'stay_dates' => [
                'check_in' => $startDate?->format('M d, Y'),
                'check_out' => $endDate?->format('M d, Y'),
                'display' => $startDate && $endDate 
                    ? $startDate->format('M d') . ' - ' . $endDate->format('d, Y')
                    : ($startDate ? $startDate->format('M d, Y') : 'N/A'),
                'start_date_iso' => $this->scheduled_date,
                'end_date_iso' => $this->end_time ? Carbon::parse($this->end_time)->toDateString() : null,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
            ],
            'amount' => [
                'total' => (float) $this->total_amount,
                'formatted' => '$' . number_format((float) $this->total_amount, 2),
            ],
            'status' => [
                'value' => $this->status,
                'label' => $this->getStatusLabel($this->status),
                'badge_class' => $this->getStatusBadgeClass($this->status),
            ],
            'payment_status' => [
                'value' => $this->payment_status,
                'label' => $this->getPaymentStatusLabel($this->payment_status),
                'badge_class' => $this->getPaymentStatusBadgeClass($this->payment_status),
            ],
            'notes' => $this->notes,
            'created_at' => $this->created_at?->format('M d, Y H:i'),
            'created_at_iso' => $this->created_at,
            'updated_at' => $this->updated_at?->format('M d, Y H:i'),
            'updated_at_iso' => $this->updated_at,
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
        ];
    }

    /**
     * Get initials from a name
     */
    private function getInitials(string $name): string
    {
        $parts = explode(' ', trim($name));
        $initials = '';
        
        foreach (array_slice($parts, 0, 2) as $part) {
            if (!empty($part)) {
                $initials .= strtoupper($part[0]);
            }
        }
        
        return $initials ?: 'U';
    }

    /**
     * Get human-readable status label
     */
    private function getStatusLabel(string $status): string
    {
        return match($status) {
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'no_show' => 'No Show',
            default => ucfirst($status),
        };
    }

    /**
     * Get CSS badge class for status
     */
    private function getStatusBadgeClass(string $status): string
    {
        return match($status) {
            'pending' => 'badge-warning',
            'confirmed' => 'badge-info',
            'in_progress' => 'badge-primary',
            'completed' => 'badge-success',
            'cancelled' => 'badge-danger',
            'no_show' => 'badge-secondary',
            default => 'badge-light',
        };
    }

    /**
     * Get human-readable payment status label
     */
    private function getPaymentStatusLabel(string $status): string
    {
        return match($status) {
            'unpaid' => 'Unpaid',
            'pending' => 'Pending',
            'paid' => 'Paid',
            'failed' => 'Failed',
            'refunded' => 'Refunded',
            'partially_paid' => 'Partially Paid',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    /**
     * Get CSS badge class for payment status
     */
    private function getPaymentStatusBadgeClass(string $status): string
    {
        return match($status) {
            'unpaid' => 'badge-danger',
            'pending' => 'badge-warning',
            'paid' => 'badge-success',
            'failed' => 'badge-danger',
            'refunded' => 'badge-info',
            'partially_paid' => 'badge-warning',
            default => 'badge-light',
        };
    }
}
