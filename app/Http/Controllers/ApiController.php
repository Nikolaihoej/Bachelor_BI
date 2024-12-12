<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        // Validate the uploaded file
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        // Store the file
        $path = $request->file('csv_file')->store('csv_files');

        // Open the file for reading
        if (($handle = fopen(Storage::path($path), 'r')) !== false) {
            // Get the header row
            $header = fgetcsv($handle, 1000, ',');

            // Define the valid fields for each model
            $validCustomerFields = ['CustomerID', 'Name', 'Address', 'Age'];
            $validActivityStatusFields = ['ActivityStatus', 'ActivityStatusID', 'MemberSinceMonths', 'HasTrainedLastMonth' , 'DaysSinceLastVisit' , 'TrainingSessionsThisMonth' , 'created_at', 'updated_at' ];
            $validMembershipTypeFields = ['MembershipTypeID', 'MembershipType'];

            // Check if the CSV header contains any valid fields
            $validFields = array_merge($validCustomerFields, $validActivityStatusFields, $validMembershipTypeFields);
            $matchedFields = array_intersect($header, $validFields);

            if (empty($matchedFields)) {
                return response()->json(['error' => 'CSV does not contain any valid fields'], 400);
            }

            // Loop through the file and insert data into the database
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $csvData = array_combine($header, $data);

                // Insert or update Customer data
                $customerData = array_intersect_key($csvData, array_flip($validCustomerFields));
                if (!empty($customerData)) {
                    $customer = Customer::updateOrCreate(
                        ['CustomerID' => $csvData['CustomerID']],
                        $customerData
                    );
                }

                // Insert or update CustomerActivityStatus data
                if (isset($csvData['ActivityStatus'])) {
                    CustomerActivityStatus::updateOrCreate(
                        ['customer_id' => $customer->id],
                        ['status' => $csvData['ActivityStatus']]
                    );
                }

                // Insert or update MembershipType data
                if (isset($csvData['MembershipTypeID']) && isset($csvData['MembershipType'])) {
                    MembershipType::updateOrCreate(
                        ['id' => $csvData['MembershipTypeID']],
                        ['type' => $csvData['MembershipType']]
                    );
                }
            }

            fclose($handle);
        }

        return response()->json(['message' => 'CSV data imported successfully']);
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