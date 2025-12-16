<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\File;

class ProgramController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $programs = Program::get();
        // if(count($programs) > 0){
        //     foreach($programs as $program){
        //         $program->date =  $program->created_at->format('F jS, Y');
        //     }
        // }
           
            $response =
            [
                'status' => true,
                'data' => $programs,
                'message' => 'All Programs'
            ];
            return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $msg = "";
        $validate = validator::make(
            $request->all(),
            [
                'title' => 'required',
                'short_description' => 'required',
                'long_description' => 'sometimes',
                'image' => 'required|image|mimes:jpg,jpeg,png,webp,svg',  //|max:2048|dimensions:min_width=100,min_height=100',
                'is_hidden' => 'required',
                'show_in_mobile' => 'required',
                'show_in_web' => 'required',
            ]
        );
        if ($validate->fails()) {
            $response =
                [
                    'status' => false,
                    'message' => $validate->errors()
                ];
            return response()->json($response, 400);
        }
 
        // Create a new entry
        $input = $request->all();
        $extension = $request->file('image')->getClientOriginalExtension();
        $fileName = time().'.'.$extension;
        $request->file('image')->move(public_path('images/program-images/'), $fileName);
        $imagePath = 'images/program-images/'.$fileName;
        $input['image'] = $imagePath;
        $program = Program::create($input);
        $msg = "Program added successfully";
        $response = [
            'status' => true,
            'data' => $program,
            'message' => $msg,
        ];
 
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $program = Program::find($id);
        if($program !== null){
            // $program->date =  $program->created_at->format('F jS, Y');
            $response =
            [
                'status' => true,
                'data' => $program,
                'message' => 'Program found'
            ];
            return response()->json($response, 200);
        }else{
            $response =
            [
                'status' => false,
                'message' => 'Program not found!!'
            ];
            return response()->json($response, 400);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $msg = "";
        $validate = validator::make(
            $request->all(),
            [
                'title' => 'required',
                'short_description' => 'required',
                'long_description' => 'sometimes',
                'is_hidden' => 'required',
                'show_in_mobile' => 'required',
                'show_in_web' => 'required',
            ]
        );
        if ($validate->fails()) {
            $response =
                [
                    'status' => false,
                    'message' => $validate->errors()
                ];
            return response()->json($response, 400);
        }
 
        $input = $request->all();
        $program = Program::find($id);
        if ($program !== null) {
            if ($request->has('image')) {
                // Deleting previous image if it exists
                $oldImagePath = public_path($program->image);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
        
                // Handling the new image upload
                $extension = $request->file('image')->getClientOriginalExtension();
                $fileName = time() . '.' . $extension;
                $destinationPath = public_path('images/program-images/');
                
                // Ensure the directory exists
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
        
                $request->file('image')->move($destinationPath, $fileName);
                $imagePath = 'images/program-images/' . $fileName;
        
                // Add the new image path to input
                $input['image'] = $imagePath;
            }
        
            // Attempt to update the program
            $updated = $program->update($input);
        
            if (!$updated) {
                $msg = "Failed to update the program!";
                $response = [
                    'status' => false,
                    'data' => $program,
                    'message' => $msg,
                ];
        
                return response()->json($response, 400);
            }
        
            $msg = "Program updated successfully";
            $response = [
                'status' => true,
                'data' => $program,
                'message' => $msg,
            ];
        
            return response()->json($response, 200);
        } else {
            $response =
                [
                    'status' => false,
                    'message' => 'Program not found !!'
                ];
            return response()->json($response, 400);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $program = Program::find($id);
        if($program !== null){
            $program->delete();
            $response =
            [
                'status' => true,
                'message' => 'Program Removed Successfully'
            ];
            return response()->json($response, 200);
        }else{
            $response =
            [
                'status' => false,
                'message' => 'Program not found!!'
            ];
            return response()->json($response, 400);
        }
    }
}