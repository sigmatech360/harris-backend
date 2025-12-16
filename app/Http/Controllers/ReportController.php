<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Notifications\OrderPlacedNotification;
use App\Notifications\SocialMediaReportNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\User;
use PDF;
use Stripe;

class ReportController extends Controller
{
    public function generateAndSendReport(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
            'user_email'    => 'required|email',
            'email'    => 'required|email',
            'tahoe_id' => 'required|string',
            'types'    => 'required_if:social_media_report,0|array',
            'first_name'    => 'required|string',
            'last_name'    => 'required|string',
            'age'    => 'required|string|max:3',
            'full_address'    => 'required|string',
            'amount'    => 'required|integer',
            'is_mob'    => 'required|in:1,0',
            'stripe_token'    => 'required_if:is_mob,0',
            'intent_id'    => 'required_if:is_mob,1',
            'social_media_report'    => 'required|in:0,1',
            ]);
            
         if ($validate->fails()) {
            $response =
                [
                    'status' => false,
                    'message' => $validate->errors()
                ];
            return response()->json($response, 200);
        }
        $data = $request->all();

        // charging payment here
        try{
             Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $flag = false; 
            if($request->is_mob == 1){
               $intent = Stripe\PaymentIntent::retrieve($request->intent_id);
               if($intent->status === 'succeeded'){
                  $flag = true; 
               }else{
                  return response()->json([
                        'status' => false,
                        'message' => 'Payment was not successfull!',
                    ], 200); 
               }
            }
            
            $charge=null;
            if($request->is_mob == 0){
            $charge = Stripe\Charge::create ([
                    "amount" => $request->amount * 100,
                    "currency" => "usd",
                    "source" => $request->stripe_token,
                    "description" => "Payment Of My Vritual PI Search Reports" 
            ]);
            }
            
            if($flag || (!empty($charge) && $charge->status === 'succeeded')){

                // Map of APIs and templates
                $apiMap = [
                    // 'Background'           => ['url' => 'https://api.galaxysearchapi.com/personsearch',           'type' => 'BackgroundReport', 'result' => 'backgroundReportRecords', 'template' => 'template'],
                    // 'Criminal'             => ['url' => 'https://api.galaxysearchapi.com/CriminalSearch/V2',      'type' => 'CriminalV2',          'result' => 'criminalRecords',     'template' => 'template'],
                    'Business Records'     => ['url' => 'https://api.galaxysearchapi.com/BusinessV2Search',     'type' => 'BusinessV2',        'result' => 'businessV2Records',   'template' => 'business'],
                    // 'Fein Records'         => ['url' => 'https://api.galaxysearchapi.com/feinsearch',           'type' => 'Fein',              'result' => 'feinRecords',         'template' => 'template'],
                    'Debt Records'         => ['url' => 'https://api.galaxysearchapi.com/DebtSearch/V2',       'type' => 'DebtV2',            'result' => 'debtRecords',         'template' => 'debt'],
                    // 'Ofac'                 => ['url' => 'https://api.galaxysearchapi.com/OfacSearch',          'type' => 'Ofac',              'result' => 'ofac',                'template' => 'template'],
                    'Property Records'     => ['url' => 'https://api.galaxysearchapi.com/PropertyV2Search',   'type' => 'PropertyV2',         'result' => 'propertyV2Records',   'template' => 'property'],
                    'Workplace Records'    => ['url' => 'https://api.galaxysearchapi.com/WorkplaceSearch',     'type' => 'Workplace',         'result' => 'workplaceRecords',    'template' => 'workplace'],
                    'DEA Records'          => ['url' => 'https://api.galaxysearchapi.com/deasearch',          'type' => 'Dea',               'result' => 'deaRecords',         'template' => 'dea'],
                    'Marriage Records'     => ['url' => 'https://api.galaxysearchapi.com/MarriageSearch',    'type' => 'Marriage',          'result' => 'records',            'template' => 'marriage'],
                    'Divorce Records'      => ['url' => 'https://api.galaxysearchapi.com/DivorceSearch',     'type' => 'Divorce',           'result' => 'records',            'template' => 'divorce'],
                    'Foreclosures Records' => ['url' => 'https://api.galaxysearchapi.com/ForeclosureV2Search','type' => 'ForeclosureV2',    'result' => 'foreclosureV2Records','template' => 'foreclosures'],
                    'Domains Records'      => ['url' => 'https://api.galaxysearchapi.com/DomainSearch',      'type' => 'Domain',            'result' => 'domainRecords',      'template' => 'domain'],
                    'Comprehensive Report' => ['url' => 'https://api.galaxysearchapi.com/personsearch',      'type' => 'BackgroundReport',  'result' => 'persons', 'resultCounts' => 'counts',     'template' => 'template'],
                    // 'Identity Verification' => [
                    //     'url'     => 'https://api.galaxysearchapi.com/IdentitySearch',
                    //     'type'    => 'Identity',
                    //     'result'  => 'identityRecords',
                    //     'template' => 'template'
                    // ],
        
                    // 'Census Search' => [
                    //     'url'     => 'https://api.galaxysearchapi.com/CensusSearch',
                    //     'type'    => 'Census',
                    //     'result'  => 'censusRecords',
                    //     'template' => 'template'
                    // ],
        
                    'Eviction Search' => [
                        'url'     => 'https://api.galaxysearchapi.com/EvictionSearch',
                        'type'    => 'Eviction',
                        'result'  => 'evictionRecords',
                        'template' => 'eviction'
                    ],
        
                    'Pro License Search' => [
                        'url'     => 'https://api.galaxysearchapi.com/ProLicenseSearch',
                        'type'    => 'ProLicense',
                        'result'  => 'proLicenseRecords',
                        'template' => 'pro_license'
                    ],
        
                    // 'Reverse Phone Search' => [
                    //     'url'     => 'https://api.galaxysearchapi.com/ReversePhoneSearch',
                    //     'type'    => 'ReversePhone',
                    //     'result'  => 'reversePhoneRecords',
                    //     'template' => 'template'
                    // ],
        
                    'Vehicle Registration Search' => [
                        'url'     => 'https://api.galaxysearchapi.com/VehicleRegistrationSearch',
                        'type'    => 'VehicleRegistration',
                        'result'  => 'vehicleRegistrations',
                        'template' => 'vehicle_registration'
                    ],
        
                ];
        
                // Ensure directory exists
                $pdfPath = storage_path('app/public/reports');
                if (!file_exists($pdfPath)) {
                    mkdir($pdfPath, 0755, true);
                }
        
                // Summary for comprehensive
                $summary = [
                    'name'    =>  $request->first_name.' '. $request->last_name,
                    'age'     => $request->age,
                ];
        
                $attachments = [];
                $pdfPaths = [];
        
                // Check if comprehensive requested
                $labels = array_column($data['types'], 'label');
                $isComprehensive = in_array('Comprehensive Report', $labels);
                if ($isComprehensive) {
                    // Fetch all sections data
                    // $allResults = [];
                    // foreach ($labels as $label) {
                     
                        // if (!isset($apiMap[$label]) || $label === 'Comprehensive Report') continue;
                        $map = $apiMap['Comprehensive Report'];
                        $resp = Http::timeout(60)->withHeaders([
                            'Galaxy-Ap-Password' => env('GALAXY_AP_PASSWORD', '2397b0ba0f8a4ea0aaea17e781e11305'),
                            'Galaxy-Search-Type' => $map['type'] ?? '',
                            'Galaxy-Ap-Name'     => env('GALAXY_AP_NAME', 'ethosinv'),
                            'Accept'             => 'application/json',
                            'Content-Type'       => 'application/json',
                        ])->post($map['url'], ['TahoeId' => $data['tahoe_id']]);
                        // $allResults[Str::slug($label, '_')] = [
                        //     'label' => $label,
                        //     'results'=> $resp->json()[$map['result']] ?? []
                        // ];
                        $persons = $resp->json($map['result']) ?? [];
                        $person  = $persons[0] ?? [];
                        $indicators  = $person['indicators'] ?? [];
                        $counts = $resp->json($map['resultCounts']) ?? [];
        
                    // Map to view variables
                    $persons = $resp->json($map['result'])   ?? [];
                    $person  = $persons[0]                   ?? [];
                //    return response()->json($person['feinRecords']);
                    $counts  = $resp->json($map['resultCounts']) ?? [];
        
                    // map all your sections:
                    $persons = $person  ?? [];
                    $associatedAddresses= $person['addresses']               ?? [];
                    $phoneNumbers       = $person['phoneNumbers']            ?? [];
                    $emailAddresses     = $person['emailAddresses']          ?? [];
                    $neighbors          = $person['neighbors']               ?? [];
                    // $historicalNeighbors= $person['historicalNeighbors']     ?? [];
                    $aliases            = $person['akas']                    ?? [];
                    $imposters          = $person['imposters']               ?? [];
                    $relativesSummary          = $person['relativesSummary']       ?? [];
                    $associatesSummary         = $person['associatesSummary']      ?? [];
                    $relativesDetails  = $person['relatives']      ?? [];
                    $associatesDetails  = $person['associates']      ?? [];
                    $criminalRecords    = $person['criminalV2Records']   ?? [];
                    $debtV2Records      = $person['debtV2Records']       ?? [];
                    $feinRecords        = $person['feinRecords']         ?? [];
                    $vehicles           = $person['vehicles']                ?? [];
                    $workRecords        = $person['peopleAtWorkRecords']     ?? [];
                    $driversLicenses    = $person['driversLicenses']         ?? [];
                    $bankruptcyRecords  = $person['bankruptcyRecords']       ?? [];
                    //workplaceRecords  = $person['workplaceRecords']       ?? [];  //it was being returned till nov 15
                    $datesOfBirth =  $person['datesOfBirth'] ?? ''; 
                    $dob =  $person['dob'] ?? '';
                    $businessRecords =  $person['businessRecords'] ?? [];
                    
                    
                    
                    $recentFiveAddress = array_slice($associatedAddresses,0,5);
                    $fiveAddressWithNeighbors = [];
                    foreach($recentFiveAddress as $recentAddress){
                        $fiveAddressWithNeighbors[$recentAddress['fullAddress']] = $recentAddress['neighborSummaryRecords'];
                    }
                    
                    $otherAddress = array_slice($associatedAddresses,5);
                    $otherAddressWithNeighbors = [];
                    foreach($otherAddress as $recentAddress){
                        $otherAddressWithNeighbors[$recentAddress['fullAddress']] = $recentAddress['neighborSummaryRecords'];
                    }
                    
                    
                    // using proertyv2 API for assessor and deed records
                    $propertyRecords=[];
                    if(!empty($indicators) && isset($indicators['hasPropertyV2Records']) && $indicators['hasPropertyV2Records'] > 0){
                        $map = $apiMap['Property Records'];
                        $resp = Http::timeout(60)->withHeaders([
                            'Galaxy-Ap-Password' => env('GALAXY_AP_PASSWORD', '2397b0ba0f8a4ea0aaea17e781e11305'),
                            'Galaxy-Search-Type' => $map['type'] ?? '',
                            'Galaxy-Ap-Name'     => env('GALAXY_AP_NAME', 'ethosinv'),
                            'Accept'             => 'application/json',
                            'Content-Type'       => 'application/json',
                        ])->post($map['url'], ['TahoeId' => $data['tahoe_id']]);
                        $propertyRecords = $resp->json($map['result']) ?? [];
                        // $propertyRecords = $resp->json()[$map['result']] ?? [];
                    }
                    
                    $assessorDeedRecords = [
                        'summary' => [],
                        'assessorRecords' => [],
                        'recorderRecords' => []
                    ];
                        foreach($propertyRecords ?? [] as $record){
                            $assessorDeedRecords['summary'][] = $record['property']['summary'] ?? [];
                            $assessorDeedRecords['assessorRecords'][] = $record['property']['assessorRecords'] ?? [];
                            $assessorDeedRecords['recorderRecords'][] = $record['property']['recorderRecords'] ?? [];
                        }
                        
                      
                    
                    // categorize them by sourceType
                    $corporateRecords= array_filter($businessRecords, fn($r) => ($r['sourceType'] ?? '') === 'Corp');
                    $uccFilings  = array_filter($businessRecords, fn($r) => ($r['sourceType'] ?? '') === 'UCC');
                    $dbaFbnRecords  = array_filter($businessRecords, fn($r) => in_array(($r['sourceType'] ?? ''), ['DBA', 'FBN']));  
                    
                    
                    // Separate by debtType
                    $bankruptcyRecords = array_filter($debtV2Records, fn($r) => strtolower($r['debtType'] ?? '') === 'bankruptcy');
                    $taxLienRecords     = array_filter($debtV2Records, fn($r) => strtolower($r['debtType'] ?? '') === 'tax lien');
                    $judgmentRecords    = array_filter($debtV2Records, fn($r) => strtolower($r['debtType'] ?? '') === 'judgment');
                    
                    
                    
                    // using DEA API for dea license
                    $deaRecords=[];
                    if(!empty($indicators) && isset($indicators['hasDeaRecords']) && $indicators['hasDeaRecords'] > 0){
                        $map = $apiMap['DEA Records'];
                        $resp = Http::timeout(60)->withHeaders([
                            'Galaxy-Ap-Password' => env('GALAXY_AP_PASSWORD', '2397b0ba0f8a4ea0aaea17e781e11305'),
                            'Galaxy-Search-Type' => $map['type'] ?? '',
                            'Galaxy-Ap-Name'     => env('GALAXY_AP_NAME', 'ethosinv'),
                            'Accept'             => 'application/json',
                            'Content-Type'       => 'application/json',
                        ])->post($map['url'], ['TahoeId' => $data['tahoe_id']]);
                        $deaRecords = $resp->json($map['result']) ?? [];
                    }
                    
                    // using Workplace API for Workplace Records
                    $workplaceRecords=[];
                    if(!empty($indicators) && isset($indicators['hasWorkplaceRecords']) && $indicators['hasWorkplaceRecords'] > 0){
                        $map = $apiMap['Workplace Records'];
                        $resp = Http::timeout(60)->withHeaders([
                            'Galaxy-Ap-Password' => env('GALAXY_AP_PASSWORD', '2397b0ba0f8a4ea0aaea17e781e11305'),
                            'Galaxy-Search-Type' => $map['type'] ?? '',
                            'Galaxy-Ap-Name'     => env('GALAXY_AP_NAME', 'ethosinv'),
                            'Accept'             => 'application/json',
                            'Content-Type'       => 'application/json',
                        ])->post($map['url'], [
                                                "FirstName" => $person['name']['firstName'] ?? '',
                                                "LastName" => $person['name']['lastName'] ?? '',
                                                "DOB" => $person['dob'] ?? '',
                                                "Age" => $person['age'] ?? '',
                                                "Email" => !empty($person['emailAddresses'])? $person['emailAddresses'][0]['emailAddress'] : '',
                                            ]);
                        $workplaceRecords = $resp->json($map['result']) ?? [];
                    }
                      
                    $report = [
                        'dob' => $dob,
                        'associatedAddresses' => $associatedAddresses,
                        'phoneNumbers' => $phoneNumbers,
                        'emailAddresses' => $emailAddresses,
                        'neighbors' => $neighbors,
                        // 'historicalNeighbors' => $historicalNeighbors,
                        'aliases' => $aliases,
                        'imposters' => $imposters,
                        'relativesSummary' => $relativesSummary,
                        'associatesSummary' => $associatesSummary,
                        'relativesDetails' => $relativesDetails,
                        'associatesDetails' => $associatesDetails,
                        'criminalRecords' => $criminalRecords,
                        'debtV2Records' => $debtV2Records,
                        'feinRecords' => $feinRecords,
                        'vehicles' => $vehicles,
                        'workRecords' => $workRecords,
                        'driversLicenses' => $driversLicenses,
                        'bankruptcyRecords' => $bankruptcyRecords,
                        'workplaceRecords' => $workplaceRecords ,
                        'counts' => $counts,
                        'datesOfBirth' => $datesOfBirth,
                        'corporateRecords' => $businessRecords,
                        'fiveAddressWithNeighbors' => $fiveAddressWithNeighbors,
                        'otherAddressWithNeighbors' => $otherAddressWithNeighbors,
                        'assessorDeedRecords' => $assessorDeedRecords,
                        'dbaFbnRecords' => $dbaFbnRecords,
                        'uccFilings' => $uccFilings,
                        'corporateRecords' => $corporateRecords,
                        'bankruptcyRecords' => $bankruptcyRecords,
                        'taxLienRecords' => $taxLienRecords,
                        'judgmentRecords' => $judgmentRecords,
                        'deaRecords' => $deaRecords,
                    ];
                    
                    
                        try {   
                            set_time_limit(600); // Increase to 10 minutes
                            // ini_set('memory_limit', '512M'); // Increase memory limit
                            $pdf = PDF::loadView('reports.template', compact(
                                'summary',
                                'report'
                             ))->setPaper('a4','portrait')
                              ->setOption('enable-javascript', false)
                              ->setOption('enable-smart-shrinking', true)
                              ->setOption('isHtml5ParserEnabled', true);
        
                            $file = 'comprehensive_' . time() . '.pdf';
                            $path = "{$pdfPath}/{$file}";
                            $pdf->save($path);
                        } catch (\Exception $e) {
                            \Log::error('PDF Generation Failed: '.$e);
                            return response()->json([
                                'status' => false,
                                'message' => $e->getMessage(),
                            ], 500);
                        }
        
        
        
        
                    
                    $attachments[] = $path;
                    $pdfPaths[] = 'storage/app/public/reports/'.$file;
                } else {
                    if(!empty($data['types'])){
                        // Individual per type
                        foreach ($data['types'] as $item) {
                            $label = $item['label'];
                            if (!isset($apiMap[$label])) continue;
                            $map = $apiMap[$label];
                            $records = [];
                            if (isset($map['url'])) {
                                $resp = Http::timeout(60)->withHeaders([
                                    'Galaxy-Ap-Password' => env('GALAXY_AP_PASSWORD', '2397b0ba0f8a4ea0aaea17e781e11305'),
                                    'Galaxy-Search-Type' => $map['type'],
                                    'Galaxy-Ap-Name'     => env('GALAXY_AP_NAME', 'ethosinv'),
                                    'Accept'             => 'application/json',
                                    'Content-Type'       => 'application/json',
                                ])->post($map['url'], ['TahoeId' => $data['tahoe_id'], 'Page' => 1, 'ResultsPerPage' => 200]);
                                $records = $resp->json()[$map['result']] ?? [];      
                       
                            }
                            $key = Str::slug($label, '_');
                            $view = 'reports.' . $map['template'];
                            $pdf  = PDF::loadView($view, compact('summary','records'))->setPaper('a4','portrait');
                            $file = "{$key}_" . time() . '.pdf';
                            $path = "{$pdfPath}/{$file}";
                            $pdf->save($path);
                            $attachments[] = $path;
                            $pdfPaths[] = 'storage/app/public/reports/'.$file;
                        }
                    }
                }
               
               
            //  creating order here
            $order = Order::create([
                'user_email' => $request->user_email,
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'user_id' => auth()->id(),
                'reports' => $request->types,
                'age' => $request->age,
                'full_address' => $request->full_address,
                'total' => $request->amount,
                'is_mob' => $request->is_mob,
                'has_social_media_report' => $request->social_media_report,
                'report_links' => $pdfPaths,
            ]);
            $userEmail = $request->user_email??'';
                // Send email
                Mail::send('mail.report', [], function ($m) use ($data, $attachments,$userEmail) {
                    $m->to($userEmail)->subject('Your Reports');
                    foreach ($attachments as $file) {
                        $m->attach($file);
                    }
                });
                try{
                    auth()->user()->notify(new OrderPlacedNotification($order));
                    // also send notifications to admin
                    $admin = User::where('user_role',1)->first();
                    if($admin){
                        $admin->notify(new OrderPlacedNotification($order));
                        if($order->has_social_media_report == 1 || $order->has_social_media_report == '1'){
                            $data = [
                                'title' => 'Social Media Report Request',
                                'body' => auth()->user()->name." has requested for social media report in order #{$order->id}", 
                                'order_id' => $order->id,
                                ];
                             $admin->notify(new SocialMediaReportNotification($data));
                        }
                    }
                }catch(\Exception $e){
                     \Log::info('OrderPlacedNotification failed! '.$e->getMessage());
                    // return response()->json([
                    //             'status' => false,
                    //             'message' => 'Something went while sending notifications',
                    //             'errors' => $e->getMessage()
                    //         ], 200);
                }
                 return response()->json([
                                'status' => true,
                                'message' => 'Report generated and sent successfully',
                           ], 200);
                    
            }else{
                return response()->json([
                        'status' => false,
                        'message' => $charge->failure_message ?? 'Payment failed to charge',
                ], 200);
            }
        }catch(\Exception $e){
            \Log::error('generateAndSendReport function failed! '.$e->getMessage());
            return response()->json([
                        'status' => false,
                        'message' => 'Something went wrong please try again!',
                        'errors' => $e->getMessage()
                    ], 200);
        }
    }
    
    
    public function generateAndSendReport2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_email'     => 'required|email',
            'searched_type'  => 'required|string|in:ofac,criminal',
            'searched_data'  => 'required|string',
            'amount'         => 'required|integer',
            'is_mob'         => 'required|in:1,0',
            'stripe_token'   => 'required_if:is_mob,0',
            'intent_id'      => 'required_if:is_mob,1',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()
            ], 200);
        }
    
        $data = $request->all();
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    
        try {
            $paymentSuccess = false;
    
            if ($request->is_mob) {
                $intent = Stripe\PaymentIntent::retrieve($request->intent_id);
                $paymentSuccess = ($intent->status === 'succeeded');
            } else {
                $charge = Stripe\Charge::create([
                    "amount"      => $request->amount * 100,
                    "currency"    => "usd",
                    "source"      => $request->stripe_token,
                    "description" => "Payment Of My Virtual PI Search Reports"
                ]);
                $paymentSuccess = ($charge->status === 'succeeded');
            }
    
            if (!$paymentSuccess) {
                return response()->json([
                    'status'  => false,
                    'message' => $charge->failure_message ?? 'Payment was not successful!',
                ], 200);
            }
    
            // Generate and save PDF
            $label = $request->searched_type;
            $key = Str::slug($label, '_');
            $view = 'reports.' . $label;
            $record = json_decode($request->searched_data, true);
            $pdf = null;
            $hasTempImage = false;
            if($label == 'criminal'){
                $tempImagePath = null;
                if(!empty($record['images']) && (!empty($record['images'][0]['thumbUrl']) || !empty($record['images'][0]['imageUrl']))){
                    $hasTempImage = true;
                    $imageUrl = !empty($record['images'][0]['thumbUrl'])? $record['images'][0]['thumbUrl'] : $record['images'][0]['imageUrl'];
                    $contents = file_get_contents($imageUrl);
                    $tempFileName = 'user_image_' . Str::random(10) . '.png';
                    Storage::disk('local')->put('tmp/' . $tempFileName, $contents);
                    $tempImagePath = storage_path('app/tmp/' . $tempFileName);
                }
                $defaultImagePath = public_path('images/default.jpg');
    
                $pdf = PDF::loadView($view, compact('record','tempImagePath','defaultImagePath'))->setPaper('a4', 'portrait');
            }else{
                 $pdf = PDF::loadView($view, compact('record'))->setPaper('a4', 'portrait');
            }
          
            $fileName = "{$key}_" . time() . '.pdf';
            $pdfPath = storage_path("app/public/reports/{$fileName}");
    
            if (!file_exists(dirname($pdfPath))) {
                mkdir(dirname($pdfPath), 0755, true);
            }
    
            $pdf->save($pdfPath);
            $publicPath = "storage/app/public/reports/{$fileName}";
            if($hasTempImage){
                Storage::disk('local')->delete('tmp/' . $tempFileName);
            }
    
            // Create order
            $order = Order::create([
                'user_email'   => $request->user_email,
                'user_id'      => auth()->id(),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'total'        => $request->amount,
                'reports' => $request->types,
                'is_mob'       => $request->is_mob,
                'report_links' => [$publicPath],
            ]);
    
            // Send email
            Mail::send('mail.report', [], function ($message) use ($request, $pdfPath) {
                $message->to($request->user_email)
                    ->subject('Your Reports')
                    ->attach($pdfPath);
            });
    
            // Send notifications
            try {
                auth()->user()->notify(new OrderPlacedNotification($order));
                $admin = User::where('user_role', 1)->first();
                if ($admin) {
                    $admin->notify(new OrderPlacedNotification($order));
                }
            } catch (\Exception $e) {
                \Log::info('OrderPlacedNotification failed! ' . $e->getMessage());
            }
    
            return response()->json([
                'status'  => true,
                'message' => 'Report generated and sent successfully',
            ], 200);
    
        } catch (\Exception $e) {
            \Log::error('generateAndSendReport2 failed: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong, please try again!',
                'errors'  => $e->getMessage(),
            ], 200);
        }
    }

    
    public function search(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
            'firstName'    => 'sometimes',
            'lastName'    => 'required_if:firstName,not_null',
            'email'    => 'sometimes|email',
            'phone'    => 'sometimes',
            'address'    => 'sometimes',
            'EntityName'    => 'sometimes',
            'PersonName'    => 'sometimes',
            ]);
            
         if ($validate->fails()) {
            $response =
                [
                    'status' => false,
                    'message' => $validate->errors()
                ];
            return response()->json($response, 200);
        }
        
        
        // filter empty values
        $reqBody = array_filter($request->all(), fn($value) => !empty($value));
        $reqBody["resultsPerPage"] = 80; 
        
        $api = 'https://api.galaxysearchapi.com/PersonSearch';
        $results = 'persons';
        $searchType = 'Person';
        if(!empty($request->indicator) && $request->indicator == 'hasCriminalRecordsV2'){
            $api = 'https://api.galaxysearchapi.com/CriminalSearch/V2';
             $results = 'criminalRecords';
             $searchType = 'CriminalV2';
        }
        if(!empty($request->indicator) && $request->indicator == 'hasOfacRecords'){
            $api = 'https://api.galaxysearchapi.com/OfacSearch';
             $results = 'ofacRecords';
             $searchType = 'Ofac';
        }
        
        try{
            set_time_limit(600); // Increase to 10 minutes
            $resp = Http::timeout(60)->withHeaders([
                            'Galaxy-Ap-Password' => env('GALAXY_AP_PASSWORD', '2397b0ba0f8a4ea0aaea17e781e11305'),
                            'Galaxy-Search-Type' => $searchType,
                            'Galaxy-Ap-Name'     => env('GALAXY_AP_NAME', 'ethosinv'),
                            'Accept'             => 'application/json',
                            'Content-Type'       => 'application/json',
                        ])->post($api, $reqBody);
            
            if ($resp->status() == 400) {
                // Handle Bad Request
                return response()->json([
                    'status'  => false,
                    'message' => 'Bad Request - Please check your input and try again.',
                    'errors'  => $resp->json() // or $resp->body() depending on how the API returns errors
                ], 200);
            } elseif ($resp->failed()) {
                // Handle any other failure response from the API
                return response()->json([
                    'status'  => false,
                    'message' => 'Something went wrong. Please try again!',
                    'errors'  => $resp->body() // or $resp->json()
                ], 200);
            }
            
            $persons = $resp->json($results) ?? [];
            $persons = array_values($persons); // convert object to array of values
            if(empty($request->indicator)){
                // dd('if');
                // filter: only records with email address
                $persons = array_filter($persons, fn($item) => !empty($item['emailAddresses']));
            }else{
                //dd('else');
                // dd($persons);
                if($request->indicator != 'hasOfacRecords' && $request->indicator != 'hasCriminalRecordsV2'){
                    // filter: only records with email address & requested indicator
                    $persons = array_filter($persons, fn($item) => !empty($item['emailAddresses']) && ($item['indicators'][$request->indicator] > 0));
                }
            }
            //  dd($persons);
            return response()->json([
                        'status' => true,
                        'persons' => array_values($persons),
                        'message' => 'Data fetched successfully',
                    ], 200);
 
        }catch(\Exception $e){
            
            return response()->json([
                        'status' => false,
                        'message' => 'Something went wrong please try again!',
                        'errors' => $e->getMessage()
                    ], 200);
        }
    }
 
 
    // public function generalSearch(Request $request)
    // {
    //     $validate = Validator::make(
    //         $request->all(),
    //         [
    //             'firstName' => 'sometimes',
    //             'lastName' => 'required_if:firstName,not_null',
    //             'email' => 'sometimes|email',
    //             'phone' => 'sometimes',
    //             'address' => 'sometimes',
    //         ]
    //     );
    
    //     if ($validate->fails()) {
    //         $response = [
    //             'status' => false,
    //             'message' => $validate->errors()
    //         ];
    //         return response()->json($response, 200);
    //     }
    
    //     // filter empty values
    //     $reqBody = array_filter($request->all(), fn($value) => !empty($value));
    //     $reqBody["resultsPerPage"] = 100;
    
    //     // Define the apiMap dynamically, including Person Search
    //     $apiMap = [
    //         'Business Records'     => ['url' => 'https://api.galaxysearchapi.com/BusinessV2Search', 'type' => 'BusinessV2', 'result' => 'businessV2Records', 'template' => 'business'],
    //         'Debt Records'         => ['url' => 'https://api.galaxysearchapi.com/DebtSearch/V2', 'type' => 'DebtV2', 'result' => 'debtRecords', 'template' => 'debt'],
    //         'Property Records'     => ['url' => 'https://api.galaxysearchapi.com/PropertyV2Search', 'type' => 'PropertyV2', 'result' => 'propertyV2Records', 'template' => 'property'],
    //         'Workplace Records'    => ['url' => 'https://api.galaxysearchapi.com/WorkplaceSearch', 'type' => 'Workplace', 'result' => 'workplaceRecords', 'template' => 'workplace'],
    //         'Marriage Records'     => ['url' => 'https://api.galaxysearchapi.com/MarriageSearch', 'type' => 'Marriage', 'result' => 'records', 'template' => 'marriage'],
    //         'Divorce Records'      => ['url' => 'https://api.galaxysearchapi.com/DivorceSearch', 'type' => 'Divorce', 'result' => 'records', 'template' => 'divorce'],
    //         'Foreclosures Records' => ['url' => 'https://api.galaxysearchapi.com/ForeclosureV2Search', 'type' => 'ForeclosureV2', 'result' => 'foreclosureV2Records', 'template' => 'foreclosures'],
    //         'Domains Records'      => ['url' => 'https://api.galaxysearchapi.com/DomainSearch', 'type' => 'Domain', 'result' => 'domainRecords', 'template' => 'domain'],
    //         'Comprehensive Report' => ['url' => 'https://api.galaxysearchapi.com/personsearch', 'type' => 'BackgroundReport', 'result' => 'persons', 'resultCounts' => 'counts', 'template' => 'template'],
    //         'Eviction Search'      => ['url' => 'https://api.galaxysearchapi.com/EvictionSearch', 'type' => 'Eviction', 'result' => 'evictionRecords', 'template' => 'eviction'],
    //         'Pro License Search'   => ['url' => 'https://api.galaxysearchapi.com/ProLicenseSearch', 'type' => 'ProLicense', 'result' => 'proLicenseRecords', 'template' => 'pro_license'],
    //         'Vehicle Registration Search' => ['url' => 'https://api.galaxysearchapi.com/VehicleRegistrationSearch', 'type' => 'VehicleRegistration', 'result' => 'vehicleRegistrations', 'template' => 'vehicle_registration'],
    //         'Person Search' => ['url' => 'https://api.galaxysearchapi.com/PersonSearch','type' => 'PersonSearch','result' => 'persons','template' => 'person_search'],
    //     ];
    
    //     try {
    //         // Check if search type is valid and exists in the apiMap
    //         $api = 'https://api.galaxysearchapi.com/PersonSearch'; // Default to 'Person Search'
    //         $searchType = 'Person';
    //         if (!empty($request->search_type) && isset($apiMap[$request->search_type])) {
    //             $api = $apiMap[$request->search_type]['url'];
    //             $searchType = $apiMap[$request->search_type]['type'];
    //         }
    
    //         // Make the API request
    //         $resp = Http::timeout(60)->withHeaders([
    //             'Galaxy-Ap-Password' => env('GALAXY_AP_PASSWORD', '2397b0ba0f8a4ea0aaea17e781e11305'),
    //             'Galaxy-Search-Type' => $searchType,
    //             'Galaxy-Ap-Name' => env('GALAXY_AP_NAME', 'ethosinv'),
    //             'Accept' => 'application/json',
    //             'Content-Type' => 'application/json',
    //         ])->post($api, $reqBody);
            
    //         $results = null;
    //         if (!empty($request->search_type) && isset($apiMap[$request->search_type])){
    //             // Get results dynamically based on the search type
    //             $results = $resp->json($apiMap[$request->search_type]['result']) ?? [];
    //             $results = array_values($results);
    //         }else{
    //             $results = $resp->json('persons') ?? [];
    //             $results = array_values($results);
    //         }
    //         // Filter records if it's the Person Search and needs specific fields like email
    //         if ($request->search_type === 'Person Search') {
    //             $results = array_filter($results, fn($item) => !empty($item['emailAddresses']));
    //         }
    
    //         return response()->json([
    //             'status' => true,
    //             'results' => array_values($results),
    //             'message' => 'Data fetched successfully',
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Something went wrong. Please try again!',
    //             'errors' => $e->getMessage()
    //         ], 200);
    //     }
    // }

    
}
