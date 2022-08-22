<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\MapResource;
use App\Models\Map;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MapsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $maps = Map::all();

        return $this->sendResponse(MapResource::collection($maps), 'Maps retrieved successfully.');
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
            'type' => 'required',
            'url' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $category = Map::create($input);
        return $this->sendResponse(new MapResource($category), 'Map created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Map $map)
    {
        if (is_null($map)) {
            return $this->sendError('Map not found.');
        }

        return $this->sendResponse(new MapResource($map), 'Map retrieved successfully.');
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
    public function update(Request $request, Map $map)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'type' => 'required',
            'url' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $map->name = $input['name'];
        $map->type = $input['type'] ?? null;
        $map->url = $input['url'] ?? null;
        $map->save();
        return $this->sendResponse(new MapResource($map), 'Map updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Map $map)
    {
        $map->delete();
        return $this->sendResponse([], 'Map deleted successfully.');
    }
}
