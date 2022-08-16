<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\LeadResource;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeadController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lead = Lead::all();

        return $this->sendResponse(LeadResource::collection($lead), 'Leads retrieved successfully.');
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
            'name' => 'required',
            'notes' => 'sometimes',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $category = Lead::create($input);
        return $this->sendResponse(new LeadResource($category), 'Lead created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Lead $lead)
    {
        if (is_null($lead)) {
            return $this->sendError('Lead not found.');
        }

        return $this->sendResponse(new LeadResource($lead), 'Lead retrieved successfully.');
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
    public function update(Request $request, Lead $lead)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'demand' => 'sometimes',
            'sold_in' => 'sometimes',
            'commission_received' => 'sometimes',
            'actual_commission_amount' => 'sometimes',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $lead->demand = $input['demand'] ?? null;
        $lead->sold_in = $input['sold_in'] ?? null;
        $lead->commission_received = $input['commission_received'] ?? null;
        $lead->actual_commission_amount = $input['actual_commission_amount'] ?? null;
        $lead->save();
        return $this->sendResponse(new LeadResource($lead), 'Lead updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();
        return $this->sendResponse([], 'Lead deleted successfully.');
    }
}
