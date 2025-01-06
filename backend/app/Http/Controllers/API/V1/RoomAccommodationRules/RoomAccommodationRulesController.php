<?php

namespace App\Http\Controllers\API\V1\RoomAccommodationRules;

use App\Http\Controllers\Controller;
use App\Models\RoomAccommodationRules;
use App\Http\Resources\API\V1\RoomAccommodationRules\RoomAccommodationRulesResource;
use App\Services\ResponseFormatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomAccommodationRulesController extends Controller
{
    protected $responseFormat;

    public function __construct(ResponseFormatService $responseFormat)
    {
        $this->responseFormat = $responseFormat;
    }

    public function index(Request $request)
    {
        try {
            $limit = $request->input('limit', 10);
            $roomaccommodationrules = RoomAccommodationRulesResource::collection(RoomAccommodationRules::with('roomType', 'accommodationType')->get())->paginate($limit);
            return $this->responseFormat->success($roomaccommodationrules, "Mostrando reglas de alojamiento de la habitación disponibles", 200);
        } catch (\Exception $e) {
            return $this->responseFormat->error("Error en el servidor " . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'room_type_id' => 'uuid', 'accommodation_type_id' => 'uuid'
            ]);

            if ($validator->fails()) {
                return $this->responseFormat->error($validator->errors()->all(), 422);
            }

            $roomaccommodationrules = RoomAccommodationRules::create([
                'room_type_id' => $request['room_type_id'], 'accommodation_type_id' => $request['accommodation_type_id'], 
            ]);

            $roomaccommodationrules->save();
            $roomaccommodationrules->load('roomType', 'accommodationType');

            return $this->responseFormat->success(new RoomAccommodationRulesResource($roomaccommodationrules), 'Reglas de alojamiento de la habitación creado exitosamente', 201);

        } catch (\Exception $e) {
            return $this->responseFormat->error("Error en el servidor " . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|uuid',
            ]);

            if ($validator->fails()) {
                return $this->responseFormat->error($validator->errors()->all(), 422);
            }

            $respuesta = RoomAccommodationRules::with('roomType', 'accommodationType')->orWhere('room_accommodation_rules_id', $id)->first();

            if (!$respuesta) {
                return $this->responseFormat->error("Reglas de alojamiento de la habitación no encontrado", 404);
            }

            return $this->responseFormat->success(new RoomAccommodationRulesResource($respuesta), 'Mostrando roomaccommodationrules', 200);

        } catch (\Exception $e) {
            return $this->responseFormat->error("Error en el servidor " . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|uuid',
            ]);

            if ($validator->fails()) {
                return $this->responseFormat->error($validator->errors()->all(), 422);
            }

            $validator = Validator::make($request->all(), [
                'room_type_id' => 'uuid', 'accommodation_type_id' => 'uuid',
            ]);

            $respuesta = RoomAccommodationRules::orWhere('room_accommodation_rules_id', $id)->first();

            if ($respuesta == null) {
                return $this->responseFormat->error("No encontrado", 404);
            }

            $respuesta->update([
                'room_type_id' => $request['room_type_id'], 'accommodation_type_id' => $request['accommodation_type_id'],
            ]);

            $respuesta->load('roomType', 'accommodationType');

            return $this->responseFormat->success(new RoomAccommodationRulesResource($respuesta), 'Reglas de alojamiento de la habitación actualizado exitosamente', 200);

        } catch (\Exception $e) {
            return $this->responseFormat->error("Error en el servidor " . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|uuid',
            ]);

            if ($validator->fails()) {
                return $this->responseFormat->error($validator->errors()->all(), 422);
            }

            $roomaccommodationrules = RoomAccommodationRules::find($id);

            if (!$roomaccommodationrules) {
                return $this->responseFormat->error("Reglas de alojamiento de la habitación no encontrado", 404);
            }

            $roomaccommodationrules->delete();

            return $this->responseFormat->success(null, 'Reglas de alojamiento de la habitación eliminado con �xito', 200);
        } catch (\Exception $e) {
            return $this->responseFormat->error("Error en el servidor " . $e->getMessage());
        }
    }
}
