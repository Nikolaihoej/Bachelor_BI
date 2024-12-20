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
        $fileName = uniqid('csv_') . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('private/csv', $fileName);

        // Read the CSV file using League\Csv
        $csv = Reader::createFromPath(Storage::path($path), 'r');
        $csv->setHeaderOffset(0); 

        // Get the header row
        $header = $csv->getHeader();
        Log::info('CSV Header: ', $header);

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
            // Process the CSV file
            $stmt = (new Statement());

            foreach ($stmt->process($csv) as $record) {
                Log::info('Parsed row: ', $record); 

                $mappedRow = [];
                foreach ($headerMap as $csvField => $dbField) {
                    $mappedRow[$dbField] = $record[$csvField] ?? null;
                }

                $mappedRow['Age'] = filter_var($mappedRow['Age'], FILTER_VALIDATE_INT);
                if ($mappedRow['Age'] === false) {
                    Log::error('Invalid Age value in row: ', $record);
                    continue; 
                }

                if (empty($mappedRow['Name']) || empty($mappedRow['Address']) || empty($mappedRow['Age'])) {
                    Log::error('Missing required fields in row: ', $record);
                    continue; 
                }

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

                MembershipType::updateOrCreate(
                    ['MembershipTypeID' => (int)$mappedRow['MembershipTypeID']],
                    [
                        'created_at' => $mappedRow['created_at'],
                        'updated_at' => $mappedRow['updated_at']
                    ]
                );

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

        return response()->json([
            'message' => 'Data blev succesfuldt gemt i databasen!',
        ]);
    }

    /*
        Get all data
    */
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
        $customers = Customer::all();
        return response()->json($customers);
    }

    /*
        Customers columns
    */
    public function customerID()
    {
        $customerIDs = Customer::pluck('CustomerID');
        return response()->json($customerIDs);
    }

    public function customerName()
    {
        $customerNames = Customer::pluck('Name');
        return response()->json($customerNames);
    }

    public function customerAddress()
    {
        $customerAddresses = Customer::pluck('Address');
        return response()->json($customerAddresses);
    }

    public function customerAge()
    {
        $customerAges = Customer::pluck('Age');
        return response()->json($customerAges);
    }

    public function signupDate()
    {
        $signupDate = Customer::pluck('Signup_Date');
        return response()->json($signupDate);
    }


    /*
      customer_activity_status table
    */
    public function customersActivityStatus()
    {
        $customersActivityStatus = CustomerActivityStatus::all();
        return response()->json($customersActivityStatus);
    }

    /*
        customer_activity_status columns
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
        $membershipType = MembershipType::all();
        return response()->json($membershipType);
    }

    /*
      membershiptype columnss
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