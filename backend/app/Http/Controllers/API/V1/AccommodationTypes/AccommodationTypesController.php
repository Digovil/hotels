<?php

namespace App\Http\Controllers\API\V1\AccommodationTypes;

use App\Http\Controllers\Controller;
use App\Models\AccommodationTypes;
use App\Http\Resources\API\V1\AccommodationTypes\AccommodationTypesResource;
use App\Services\ResponseFormatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccommodationTypesController extends Controller
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
            $accommodationtypes = AccommodationTypesResource::collection(AccommodationTypes::get())->paginate($limit);
            return $this->responseFormat->success($accommodationtypes, "Mostrando tipos de acomodación disponibles", 200);
        } catch (\Exception $e) {
            return $this->responseFormat->error("Error en el servidor " . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:accommodation_types,name', 
            ]);

            if ($validator->fails()) {
                return $this->responseFormat->error($validator->errors()->all(), 422);
            }

            $accommodationtypes = AccommodationTypes::create([
                'name' => strtoupper($request['name']), 
            ]);

            $accommodationtypes->save();

            return $this->responseFormat->success(new AccommodationTypesResource($accommodationtypes), 'Tipo de acomodación creado exitosamente', 201);

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

            $respuesta = AccommodationTypes::orWhere('accommodation_types_id', $id)->first();

            if (!$respuesta) {
                return $this->responseFormat->error("Tipo de acomodación no encontrado", 404);
            }

            return $this->responseFormat->success(new AccommodationTypesResource($respuesta), 'Mostrando accommodationtypes', 200);

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
                return $this->responseFormat->error(json_decode($validator->errors(), true), 422);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'string', 
            ]);

            if ($validator->fails()) {
              return $this->responseFormat->error(json_decode($validator->errors(), true), 422);
            }


            $respuesta = AccommodationTypes::orWhere('accommodation_types_id', $id)->first();

            if ($respuesta == null) {
                return $this->responseFormat->error("No encontrado", 404);
            }

            $respuesta->update([
                'name' => strtoupper($request['name']), 
            ]);

            return $this->responseFormat->success(new AccommodationTypesResource($respuesta), 'Tipo de acomodación actualizado exitosamente', 200);

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

            $accommodationtypes = AccommodationTypes::find($id);

            if (!$accommodationtypes) {
                return $this->responseFormat->error("Tipo de acomodación no encontrado", 404);
            }

            $accommodationtypes->delete();

            return $this->responseFormat->success(null, 'Tipo de acomodación eliminado con éxito', 200);
        } catch (\Exception $e) {
            return $this->responseFormat->error("Error en el servidor " . $e->getMessage());
        }
    }
}
