<?php

namespace App\Http\Controllers;

use App\Models\SocialActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class SocialDevelopmentController extends Controller
{
    /**
     * Display the social development report page
     */
    public function index()
    {
        return view('social-development.report');
    }

   /**
     * Get all activities (for AJAX loading)
     */
    public function getActivities(Request $request)
    {
        $query = SocialActivity::query()->orderBy('date_conducted', 'desc');

        // Filter by type if provided
        if ($request->has('type')) {
            $query->ofType($request->type);
        }

        // Filter by month and year if provided
        if ($request->has('month') || $request->has('year')) {
            $query->byMonthYear($request->month, $request->year);
        }

        $activities = $query->get();
        
        // Add photo URLs to each activity
        $activities->each(function($activity) {
            $activity->photo_urls = $activity->photo_urls; // This calls the getPhotoUrlsAttribute accessor
        });

        return response()->json([
            'success' => true,
            'activities' => $activities
        ]);
    }
    
    /**
     * Save cooperative activities
     */
    public function saveCooperativeActivities(Request $request)
    {
        try {
            // Decode the JSON activities string
            $activities = json_decode($request->input('activities'), true);
            
            if (!$activities || !is_array($activities)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid activities data'
                ], 422);
            }

            // Validate each activity
            foreach ($activities as $index => $activityData) {
                if (empty($activityData['activity_name']) || 
                    empty($activityData['date_conducted']) || 
                    empty($activityData['fund_source'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Please fill in all required fields for activity ' . ($index + 1)
                    ], 422);
                }
            }

            DB::beginTransaction();

            $savedActivities = [];

            foreach ($activities as $index => $activityData) {
                // Handle photo uploads
                $photosPaths = [];
                
                // Check for photos in the activities array
                if ($request->hasFile("activities.{$index}.photos")) {
                    $photos = $request->file("activities.{$index}.photos");
                    
                    // Handle both single file and array of files
                    if (!is_array($photos)) {
                        $photos = [$photos];
                    }
                    
                    foreach ($photos as $photo) {
                        if ($photo && $photo->isValid()) {
                            // Store each photo in S3
                            $path = $photo->store('social-activities/cooperative', 's3');
                            $photosPaths[] = $path;
                        }
                    }
                }

                $activity = SocialActivity::create([
                    'activity_type' => SocialActivity::TYPE_COOPERATIVE,
                    'activity_name' => $activityData['activity_name'],
                    'date_conducted' => $activityData['date_conducted'],
                    'participants_count' => $activityData['participants_count'] ?? 0,
                    'amount_utilized' => $activityData['amount_utilized'] ?? 0,
                    'fund_source' => $activityData['fund_source'],
                    'photos' => $photosPaths,
                    'created_by' => auth()->id(),
                ]);

                $savedActivities[] = $activity;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cooperative activities saved successfully!',
                'activities' => $savedActivities
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving cooperative activities: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error saving activities: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save community activities
     */
    public function saveCommunityActivities(Request $request)
    {
        try {
            // Decode the JSON activities string
            $activities = json_decode($request->input('activities'), true);
            
            if (!$activities || !is_array($activities)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid activities data'
                ], 422);
            }

            // Validate each activity
            foreach ($activities as $index => $activityData) {
                if (empty($activityData['activity_name']) || 
                    empty($activityData['date_conducted']) || 
                    empty($activityData['fund_source'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Please fill in all required fields for activity ' . ($index + 1)
                    ], 422);
                }
            }

            DB::beginTransaction();

            $savedActivities = [];

            foreach ($activities as $index => $activityData) {
                // Handle photo uploads
                $photosPaths = [];
                
                // Check for photos in the activities array
                if ($request->hasFile("activities.{$index}.photos")) {
                    $photos = $request->file("activities.{$index}.photos");
                    
                    // Handle both single file and array of files
                    if (!is_array($photos)) {
                        $photos = [$photos];
                    }
                    
                    foreach ($photos as $photo) {
                        if ($photo && $photo->isValid()) {
                            // Store each photo in S3 under community folder
                            $path = $photo->store('social-activities/community', 's3');
                            $photosPaths[] = $path;
                        }
                    }
                }

                $activity = SocialActivity::create([
                    'activity_type' => SocialActivity::TYPE_COMMUNITY,
                    'activity_name' => $activityData['activity_name'],
                    'date_conducted' => $activityData['date_conducted'],
                    'participants_count' => $activityData['participants_count'] ?? 0,
                    'amount_utilized' => $activityData['amount_utilized'] ?? 0,
                    'fund_source' => $activityData['fund_source'],
                    'photos' => $photosPaths,
                    'created_by' => auth()->id(),
                ]);

                $savedActivities[] = $activity;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Community activities saved successfully!',
                'activities' => $savedActivities
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving community activities: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error saving activities: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an activity
     */
    public function deleteActivity($id)
    {
        try {
            $activity = SocialActivity::findOrFail($id);

            // Delete photos from storage
            if ($activity->photos && is_array($activity->photos)) {
                foreach ($activity->photos as $photo) {
                    Storage::disk('s3')->delete($photo);
                }
            }

            $activity->delete();

            return response()->json([
                'success' => true,
                'message' => 'Activity deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting activity: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an activity - WITH DEBUGGING
     */
    public function updateActivity(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'activity_name' => 'required|string|max:255',
            'date_conducted' => 'required|date',
            'participants_count' => 'required|integer|min:0',
            'amount_utilized' => 'required|numeric|min:0',
            'fund_source' => 'required|string',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $activity = SocialActivity::findOrFail($id);
            
            // DEBUG: Log what we received
            Log::info('Update Activity Request Data:', [
                'id' => $id,
                'received_date' => $request->date_conducted,
                'old_date' => $activity->date_conducted,
                'has_new_photos' => $request->hasFile('photos'),
            ]);

            // Handle photo uploads - if new photos provided, REPLACE old ones
            $photosPaths = $activity->photos ?? [];
            
            if ($request->hasFile('photos')) {
                // Delete old photos from storage before uploading new ones
                if ($activity->photos && is_array($activity->photos)) {
                    foreach ($activity->photos as $photo) {
                        Storage::disk('s3')->delete($photo);
                    }
                    Log::info('Deleted old photos:', ['count' => count($activity->photos)]);
                }
                
                // Upload new photos and replace the array
                $photosPaths = [];

                foreach ($request->file('photos') as $photo) {
                    if ($photo && $photo->isValid()) {
                        // Store in S3 under dynamic folder based on activity type
                        $path = $photo->store('social-activities/' . $activity->activity_type, 's3');
                        $photosPaths[] = $path;
                    }
                }
                Log::info('Uploaded new photos:', ['count' => count($photosPaths)]);
            }

            $activity->update([
                'activity_name' => $request->activity_name,
                'date_conducted' => $request->date_conducted,
                'participants_count' => $request->participants_count,
                'amount_utilized' => $request->amount_utilized,
                'fund_source' => $request->fund_source,
                'photos' => $photosPaths,
            ]);

            // Refresh to get the latest data from database
            $activity->refresh();
            
            // DEBUG: Log what's in the database after update
            Log::info('After Update:', [
                'activity_id' => $activity->id,
                'date_in_model' => $activity->date_conducted,
                'date_raw' => $activity->getRawOriginal('date_conducted'),
                'photo_count' => count($activity->photos ?? []),
            ]);

            // Get the activity as array
            $activityArray = $activity->toArray();
            
            // DEBUG: Log what we're sending back
            Log::info('Sending Response:', [
                'date_conducted' => $activityArray['date_conducted'],
                'photos' => $activityArray['photos'],
            ]);

            // With this:
            $activity->photo_urls = $activity->photo_urls; // Add photo URLs
            return response()->json([
                'success' => true,
                'message' => 'Activity updated successfully!',
                'activity' => $activity
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating activity: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating activity: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate PDF report for social development activities
     */
    public function generatePDF(Request $request)
    {
        try {
            // Get filter parameters
            $month = $request->input('month');
            $year = $request->input('year');

            // Build queries for both activity types
            $cooperativeQuery = SocialActivity::where('activity_type', SocialActivity::TYPE_COOPERATIVE)
                ->orderBy('date_conducted', 'desc');
            
            $communityQuery = SocialActivity::where('activity_type', SocialActivity::TYPE_COMMUNITY)
                ->orderBy('date_conducted', 'desc');

            // Apply filters if provided
            if ($month) {
                $cooperativeQuery->whereMonth('date_conducted', $month);
                $communityQuery->whereMonth('date_conducted', $month);
            }

            if ($year) {
                $cooperativeQuery->whereYear('date_conducted', $year);
                $communityQuery->whereYear('date_conducted', $year);
            }

            // Get the activities
            $cooperativeActivities = $cooperativeQuery->get();
            $communityActivities = $communityQuery->get();

            // Preload all photos in parallel for both activity types
            $cooperativeActivities->each(function($activity) {
                $activity->cached_photos = $this->loadPhotosInParallel($activity->photos ?? []);
            });

            $communityActivities->each(function($activity) {
                $activity->cached_photos = $this->loadPhotosInParallel($activity->photos ?? []);
            });

            // Prepare data for PDF
            $data = [
                'cooperativeActivities' => $cooperativeActivities,
                'communityActivities' => $communityActivities,
                'month' => $month,
                'year' => $year,
            ];

            // Generate PDF filename
            $filename = 'Social_Development_Report';
            if ($month || $year) {
                $filename .= '_';
                if ($month) {
                    $filename .= \Carbon\Carbon::createFromFormat('m', $month)->format('F');
                }
                if ($year) {
                    $filename .= '_' . $year;
                }
            }
            $filename .= '_' . now()->format('Y-m-d') . '.pdf';

            // Generate PDF with longer timeout and enable remote images
            $pdf = Pdf::loadView('admin.social-report-pdf', $data)
                ->setPaper('a4', 'portrait')
                ->setOption('margin-top', '0.4in')
                ->setOption('margin-bottom', '0.4in')
                ->setOption('margin-left', '0.4in')
                ->setOption('margin-right', '0.4in')
                ->setOption('isRemoteEnabled', true);

            // Return PDF download
            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Error generating social development PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Load photos in parallel from S3
     */
    private function loadPhotosInParallel($photoPaths)
    {
        if (empty($photoPaths) || !is_array($photoPaths)) {
            return [];
        }
        
        $disk = Storage::disk('s3');
        $photos = [];
        
        // For Laravel 10.43+ with concurrent support
        if (method_exists(\Illuminate\Support\Facades\Concurrency::class, 'run')) {
            $results = \Illuminate\Support\Facades\Concurrency::run(
                collect($photoPaths)->map(function($path) use ($disk) {
                    return function() use ($disk, $path) {
                        return $this->loadSinglePhoto($disk, $path);
                    };
                })->toArray()
            );
            
            return array_filter($results);
        }
        
        // Fallback for older Laravel versions - sequential but optimized
        foreach ($photoPaths as $path) {
            $photo = $this->loadSinglePhoto($disk, $path);
            if ($photo !== null) {
                $photos[] = $photo;
            }
        }
        
        return $photos;
    }

    /**
     * Load a single photo from S3 and convert to base64
     */
    private function loadSinglePhoto($disk, $path)
    {
        try {
            $contents = $disk->get($path);
            
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $mimeType = match(strtolower($extension)) {
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                default => 'image/jpeg'
            };
            
            return 'data:' . $mimeType . ';base64,' . base64_encode($contents);
        } catch (\Exception $e) {
            Log::error('Error loading photo for PDF: ' . $path . ' - ' . $e->getMessage());
            return null;
        }
    }

}