<?php

namespace App\Http\Controllers\Api\v1\Handbook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Handbook\Handbook;
use Validator;
use Exception;
use DB;

class HandbookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        try {
            DB::beginTransaction();
            $this->generalValidation($request->all());
            $data = $request[0];
            $data['user_id'] = auth()->id();
            $handbook = Handbook::create($data);

            if(isset($request[1]['name']) && isset($request[1]['description'])){
                if(count($request[1]['name']) == count($request[1]['description'])){
                    for($k = 0 ; $k < count($request[1]['name']); $k++){
                        if (isset($request[1]['name']) ? $request[1]['name'] : '' || isset($request[1]['name']) ? $request[1]['name'] : '') {
                            $handbook->subtitles()->create([
                                'name' => $request[1]['name'][$k],
                                'description' => $request[1]['description'][$k]
                            ]);
                        }
                    }
                }
            }

            $handbooks = Handbook::whereId($handbook->id)->with('subtitles','user')->first();
            DB::commit();
            return response()->json([
                'message' => 'Manual creado con éxito',
                'data' => $handbooks
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'HandbookController.store.failed',
                'error' => $e->getMessage()
            ], 505);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function generalValidation($data){
        $validator_handbook = Validator::make($data[0], [
            'name'       => 'required|string|min:5|max:255',
            'description' => 'required|string|min:5|max:600'
        ]);
        $validator_subtitle = Validator::make($data[1], [
            'name.*'        => 'string|min:5|max:255',
            'description.*' => 'string|min:5|max:600'
        ]);

        if ($validator_handbook->fails()) {
           throw new Exception($validator_handbook->errors()->first(), 422);
        }
        if ($validator_subtitle->fails()) {
           throw new Exception($validator_subtitle->errors()->first(), 422);
        }
    }
}
