<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;
use League\Csv\Statement;
use App\Models\Customer;
use App\Models\CustomerActivityStatus;
use App\Models\MembershipType;
use App\Models\FactTable;

class ApiController extends Controller
{

    /*
        Handeling of importing CSV file
    */
 
    public function csv(Request $request) 
    {
        // Validate that the file is a CSV
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048', // Max 2MB
        ]);

        // Get the file from the request
        $file = $request->file('csv_file');

        // Generate a unique filename to avoid conflicts
        $filename = uniqid('csv_') . '.' . $file->getClientOriginalExtension();

        // Save the file in the private folder
        $path = $file->storeAs('private/csv', $filename);

        // Read the CSV file using League\Csv
        $csv = Reader::createFromPath(Storage::path($path), 'r');
        $csv->setHeaderOffset(0); // Set the header offset

        // Get the header row
        $header = $csv->getHeader();
        Log::info('CSV Header: ', $header); // Log the CSV header for debugging

        // Map CSV headers to valid fields
        $headerMap = [
            'CustomerID' => 'CustomerID',
            'customer_Name' => 'Name',
            'customer_Address' => 'Address',
            'customer_Age' => 'Age',
            'ActivityStatusID' => 'ActivityStatusID',
            'activity_status_MemberSinceMonths' => 'MemberSinceMonths',
            'activity_status_HasTrainedLastMonth' => 'HasTrainedLastMonth',
            'activity_status_DaysSinceLastVisit' => 'DaysSinceLastVisit',
            'activity_status_TrainingSessionsThisMonth' => 'TrainingSessionsThisMonth',
            'MembershipTypeID' => 'MembershipTypeID',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        ];

        // Check if the CSV header contains any valid fields
        $matchedFields = array_intersect($header, array_keys($headerMap));

        if (empty($matchedFields)) {
            Log::error('CSV does not contain any valid fields');
            return response()->json(['error' => 'CSV indeholder ikke gyldige felter'], 400);
        }

        DB::beginTransaction();
        try {
            // Create a statement to process the CSV
            $stmt = (new Statement());

            // Loop through the file and insert data into the database
            foreach ($stmt->process($csv) as $record) {
                Log::info('Parsed row: ', $record); // Log the parsed row for debugging

                // Map the row to the valid fields
                $mappedRow = [];
                foreach ($headerMap as $csvField => $dbField) {
                    $mappedRow[$dbField] = $record[$csvField] ?? null;
                }

                // Validate and clean data
                $mappedRow['Age'] = filter_var($mappedRow['Age'], FILTER_VALIDATE_INT);
                if ($mappedRow['Age'] === false) {
                    Log::error('Invalid Age value in row: ', $record);
                    continue; // Skip this row
                }

                // Check for required fields
                if (empty($mappedRow['Name']) || empty($mappedRow['Address']) || empty($mappedRow['Age'])) {
                    Log::error('Missing required fields in row: ', $record);
                    continue; // Skip this row
                }

                
                Log::debug('Mapped row: ', $mappedRow); // Log the mapped row for debugging
                Log::debug('CustomerID: ', $mappedRow['CustomerID']); // Log the mapped row for debugging
                Log::debug('ActivityStatusID: ', $mappedRow['ActivityStatusID']); // Log the mapped row for debugging
                
                // Ensure the customer exists before inserting into the fact_table
                
                    // Insert data into the customer_activity_status table
                    $activityStatus = CustomerActivityStatus::updateOrCreate(
                        ['ActivityStatusID' => (int)$mappedRow['ActivityStatusID']],
                        [
                            'MemberSinceMonths' => $mappedRow['MemberSinceMonths'],
                            'HasTrainedLastMonth' => $mappedRow['HasTrainedLastMonth'],
                            'DaysSinceLastVisit' => $mappedRow['DaysSinceLastVisit'],
                            'TrainingSessionsThisMonth' => $mappedRow['TrainingSessionsThisMonth'],
                            'created_at' => $mappedRow['created_at'],
                            'updated_at' => $mappedRow['updated_at']
                        ]
                        );
                        
                        // Insert data into the customers table
                        $customer = Customer::updateOrCreate(
                            ['CustomerID' => (int)$mappedRow['CustomerID']],
                            [
                                'Name' => $mappedRow['Name'],
                                'Address' => $mappedRow['Address'],
                                'Age' => $mappedRow['Age'],
                                'created_at' => $mappedRow['created_at'],
                                'updated_at' => $mappedRow['updated_at']
                            ]
                        );
                    // Insert data into the membership_types table
                    MembershipType::updateOrCreate(
                        ['MembershipTypeID' => (int)$mappedRow['MembershipTypeID']],
                        [
                            'created_at' => $mappedRow['created_at'],
                            'updated_at' => $mappedRow['updated_at']
                        ]
                    );

                    // Insert data into the fact_table
                    FactTable::create([
                        'CustomerID' => (int)$mappedRow['CustomerID'],
                        'ActivityStatusID' => (int)$mappedRow['ActivityStatusID'],
                        'MembershipTypeID' => (int)$mappedRow['MembershipTypeID'],
                        'created_at' => $mappedRow['created_at'],
                        'updated_at' => $mappedRow['updated_at']
                    ]);

            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error inserting CSV data: ' . $e->getMessage());
            return response()->json(['error' => 'Fejl ved indsættelse af data: ' . $e->getMessage()], 500);
        }

        // Return a success message
        return response()->json([
            'message' => 'Data blev succesfuldt gemt i databasen!',
        ]);
    }



    public function all()
    {
        $factTables = FactTable::with(['customer', 'activityStatus', 'membershipType'])->get();

        return response()->json($factTables);
    }
    

    /*
        Customers table
    */
    public function customers()
    {
        $Customers = Customer::all();
        return response()->json($Customers);
    }

    /*
        Customers column
    */
    public function customerID()
    {
        $CustomerIDs = Customer::pluck('CustomerID');
        return response()->json($CustomerIDs);
    }

    public function customerName()
    {
        $CustomerNames = Customer::pluck('Name');
        return response()->json($CustomerNames);
    }
    
    public function customerAddress()
    {
        $CustomerAddresses = Customer::pluck('Address');
        return response()->json($CustomerAddresses);
    }

    public function customerAge()
    {
        $CustomerAges = Customer::pluck('Age');
        return response()->json($CustomerAges);
    }

    public function signupDate()
    {
        $signupDate = Customer::pluck('Signup_Date');
        return response()->json($signupDate);
    }
    

    /*
      customer_activity_status table
    */
    public function customersactivitystatus()
    {
        $customersactivitystatus = CustomerActivityStatus::all();
        return response()->json($customersactivitystatus);
    }
    
    /*
        customer_activity_status column
    */    
    public function activityStatusID()
    {
        $activityStatusID = CustomerActivityStatus::pluck('ActivityStatusID');
        return response()->json($activityStatusID);
    }

    public function memberSinceMonths()
    {
        $memberSinceMonths = CustomerActivityStatus::pluck('MemberSinceMonths');
        return response()->json($memberSinceMonths);
    }

    public function hasTrainedLastMonth()
    {
        $hasTrainedLastMonth = CustomerActivityStatus::pluck('HasTrainedLastMonth');
        return response()->json($hasTrainedLastMonth);
    }

    public function daysSinceLastVisit()
    {
        $daysSinceLastVisit = CustomerActivityStatus::pluck('DaysSinceLastVisit');
        return response()->json($daysSinceLastVisit);
    }

    public function trainingSessionsThisMonth()
    {
        $trainingSessionsThisMonth = CustomerActivityStatus::pluck('TrainingSessionsThisMonth');
        return response()->json($trainingSessionsThisMonth);
    }


    /*
      membershiptype table
    */
    public function membershipType()
    {
        $membershiptype = MembershipType::all();
        return response()->json($membershiptype);
    }
    
    /*
      membershiptype columns
    */    
    public function membershipTypeID()
    {
        $membershipTypeID = MembershipType::pluck('MembershipTypeID');
        return response()->json($membershipTypeID);
    }

    public function typeName()
    {
        $typeName = MembershipType::pluck('TypeName');
        return response()->json($typeName);
    }
}