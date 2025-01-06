<?php

namespace App\Http\Controllers\API\V1\RoomTypes;

use App\Http\Controllers\Controller;
use App\Models\RoomTypes;
use App\Http\Resources\API\V1\RoomTypes\RoomTypesResource;
use App\Services\ResponseFormatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomTypesController extends Controller
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
            $roomtypes = RoomTypesResource::collection(RoomTypes::get())->paginate($limit);
            return $this->responseFormat->success($roomtypes, "Mostrando roomtypes disponibles", 200);
        } catch (\Exception $e) {
            return $this->responseFormat->error("Error en el servidor " . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
            ]);

            if ($validator->fails()) {
                return $this->responseFormat->error($validator->errors()->all(), 422);
            }

            $roomtypes = RoomTypes::create([
                'name' => $request['name'], 'created_at' => $request['created_at'], 'updated_at' => $request['updated_at']
            ]);

            $roomtypes->save();

            return $this->responseFormat->success(new RoomTypesResource($roomtypes), 'RoomTypes creado exitosamente', 201);

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

            $respuesta = RoomTypes::orWhere('room_types_id', $id)->first();

            if (!$respuesta) {
                return $this->responseFormat->error("RoomTypes no encontrado", 404);
            }

            return $this->responseFormat->success(new RoomTypesResource($respuesta), 'Mostrando roomtypes', 200);

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
                'name' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
            ]);

            $respuesta = RoomTypes::orWhere('room_types_id', $id)->first();

            if ($respuesta == null) {
                return $this->responseFormat->error("No encontrado", 404);
            }

            $respuesta->update([
                'name' => $request['name'], 'created_at' => $request['created_at'], 'updated_at' => $request['updated_at']
            ]);

            return $this->responseFormat->success(new RoomTypesResource($respuesta), 'RoomTypes actualizado exitosamente', 200);

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

            $roomtypes = RoomTypes::find($id);

            if (!$roomtypes) {
                return $this->responseFormat->error("RoomTypes no encontrado", 404);
            }

            $roomtypes->delete();

            return $this->responseFormat->success(null, 'RoomTypes eliminado con �xito', 200);
        } catch (\Exception $e) {
            return $this->responseFormat->error("Error en el servidor " . $e->getMessage());
        }
    }
}
