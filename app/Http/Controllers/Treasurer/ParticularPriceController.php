<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use App\Models\ParticularPrice;
use App\Models\AuditTrail;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ParticularPriceController extends Controller
{
    /**
     * Display the particular prices management page
     */
    public function index()
    {
        $prices = ParticularPrice::with('creator')
            ->orderBy('start_date', 'desc')
            ->paginate(15);

        $particularTypes = ParticularPrice::getParticularTypes();

        return view('treasurer.particular-prices', compact('prices', 'particularTypes'));
    }

    /**
     * Store a new particular price
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'particular' => 'required|in:subscription_capital,management_fee,membership_fee,monthly_dues,business_permit',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check for overlapping date ranges for the same particular
        $overlapping = ParticularPrice::where('particular', $validated['particular'])
            ->where('status', 'active')
            ->where(function($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                      ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                      ->orWhere(function($q) use ($validated) {
                          $q->where('start_date', '<=', $validated['start_date'])
                            ->where('end_date', '>=', $validated['end_date']);
                      });
            })
            ->exists();

        if ($overlapping) {
            return response()->json([
                'success' => false,
                'message' => 'Date range overlaps with an existing price setting for this particular.'
            ], 422);
        }

        $validated['created_by'] = auth()->id();

        $price = ParticularPrice::create($validated);

        // Log the action
        AuditTrail::log(
            'created',
            "Added new price for {$price->formatted_particular}: ₱" . number_format($price->amount, 2) .
            " from {$price->start_date->format('M d, Y')} to {$price->end_date->format('M d, Y')}",
            'ParticularPrice',
            $price->id
        );

        return response()->json([
            'success' => true,
            'message' => 'Particular price added successfully.',
            'price' => $price->load('creator')
        ]);
    }

    /**
     * Update a particular price
     */
    public function update(Request $request, ParticularPrice $price)
    {
        $validated = $request->validate([
            'particular' => 'required|in:subscription_capital,management_fee,membership_fee,monthly_dues,business_permit',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'notes' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
        ]);

        $overlapping = ParticularPrice::where('particular', $validated['particular'])
            ->where('status', 'active')
            ->where('id', '!=', $price->id)
            ->where(function($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                      ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                      ->orWhere(function($q) use ($validated) {
                          $q->where('start_date', '<=', $validated['start_date'])
                            ->where('end_date', '>=', $validated['end_date']);
                      });
            })
            ->exists();

        if ($overlapping) {
            return response()->json([
                'success' => false,
                'message' => 'Date range overlaps with an existing price setting for this particular.'
            ], 422);
        }

        // Capture original values before update
        $originalValues = $price->getOriginal();

        $price->update($validated);

        // Track changes for audit trail
        $changes = [];
        $changedFields = [];
        $fieldsToCheck = ['particular', 'amount', 'start_date', 'end_date', 'notes', 'status'];

        foreach ($fieldsToCheck as $field) {
            $oldValue = $originalValues[$field] ?? null;
            $newValue = $validated[$field] ?? null;

            // Only log if value actually changed
            if ($oldValue != $newValue) {
                $changedFields[] = $field;

                // Format values for better readability
                if ($field === 'amount') {
                    $changes[$field] = [
                        'old' => $oldValue ? '₱' . number_format($oldValue, 2) : 'None',
                        'new' => $newValue ? '₱' . number_format($newValue, 2) : 'None'
                    ];
                } elseif (in_array($field, ['start_date', 'end_date'])) {
                    $changes[$field] = [
                        'old' => $oldValue ? \Carbon\Carbon::parse($oldValue)->format('M d, Y') : 'None',
                        'new' => $newValue ? \Carbon\Carbon::parse($newValue)->format('M d, Y') : 'None'
                    ];
                } else {
                    $changes[$field] = [
                        'old' => $oldValue ?? 'None',
                        'new' => $newValue ?? 'None'
                    ];
                }
            }
        }

        // Log the action with changes
        $description = "Updated price for {$price->formatted_particular}: ₱" . number_format($price->amount, 2) .
            " from {$price->start_date->format('M d, Y')} to {$price->end_date->format('M d, Y')}";

        if (!empty($changedFields)) {
            $description .= " (Changed: " . implode(', ', $changedFields) . ")";
        }

        AuditTrail::log(
            'updated',
            $description,
            'ParticularPrice',
            $price->id,
            $changes
        );

        return response()->json([
            'success' => true,
            'message' => 'Particular price updated successfully.',
            'price' => $price->fresh()->load('creator')
        ]);
    }

    /**
     * Delete a particular price
     */
    public function destroy(ParticularPrice $price)
    {
        $priceInfo = "{$price->formatted_particular}: ₱" . number_format($price->amount, 2) .
                     " from {$price->start_date->format('M d, Y')} to {$price->end_date->format('M d, Y')}";

        $price->delete();

        // Log the action
        AuditTrail::log(
            'deleted',
            "Deleted price setting for {$priceInfo}",
            'ParticularPrice',
            $price->id
        );

        return response()->json([
            'success' => true,
            'message' => 'Particular price deleted successfully.'
        ]);
    }

    /**
     * Calculate price for a particular over a date range
     */
    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'particular' => 'required|string',
            'from_month' => 'required|string',
            'to_month' => 'required|string',
            'year' => 'required|integer',
        ]);

        $result = ParticularPrice::calculateAmount(
            $validated['particular'],
            $validated['from_month'],
            $validated['to_month'],
            $validated['year']
        );

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
}
