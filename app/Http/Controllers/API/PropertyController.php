<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\PropertyResource;
use App\Models\Media;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PropertyController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $property = Property::all();
        return $this->sendResponse(PropertyResource::collection($property), 'Property retrieved successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'property_number'   =>  'required',
            'location_id'       =>  'required',
            'detail'            =>  'sometimes',
            'status'            =>  'sometimes',
            'sold_by_user_id'   =>  'sometimes',
            'sold_to_user_id'   =>  'sometimes',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $property = Property::create($input);
        if(!empty($request->uploadedImages)){
            foreach($request->uploadedImages as $image){
                Media::create(
                    [
                        'imageable_type' => Property::class,
                        'imageable_id' => $property->id,
                        'url' => $image
                    ]
                );
            }
        }

        return $this->sendResponse(new PropertyResource($property), 'Property created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Property $property)
    {
        if (is_null($property)) {
            return $this->sendError('Property not found.');
        }

        return $this->sendResponse(new PropertyResource($property), 'Property retrieved successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Property $property)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'property_number'   =>  'required',
            'location_id'       =>  'required',
            'detail'            =>  'sometimes',
            'status'            =>  'sometimes',
            'sold_by_user_id'   =>  'sometimes',
            'sold_to_user_id'   =>  'sometimes',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $property->property_number = $input['property_number'];
        $property->location_id = $input['location_id'];
        $property->detail = $input['detail'] ?? null;
        $property->status = $input['status'] ?? null;
        $property->sold_by_user_id = $input['sold_by_user_id'] ?? null;
        $property->sold_to_user_id = $input['sold_to_user_id'] ?? null;
        $property->save();
        return $this->sendResponse(new PropertyResource($property), 'Property updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Property $property)
    {
        $property->delete();
        return $this->sendResponse([], 'Property deleted successfully.');
    }

    public function imageUpload(Request $request)
    {
        $files = $request->file('files');
        $fileURLs = [];
        $storagePath = 'public/media';

        foreach ($files as $file) {
            $fName = $this->generateRandomString();
            $filename = time() . '-' . $fName . '.' . $file->getClientOriginalExtension();
            $url = Storage::disk('local')->putFileAs($storagePath, $file, $filename);
            $fileURLs[] = $url;
        }
        return response()->json(['status' => 'success', 'message' => 'file has been uploaded successfully', 'urls' => $fileURLs], 200);
    }

    private function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
