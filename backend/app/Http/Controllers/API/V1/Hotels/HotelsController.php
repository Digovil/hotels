<?php

namespace App\Http\Controllers\API\V1\Hotels;

use App\Http\Controllers\Controller;
use App\Models\Hotels;
use App\Models\HotelRooms;
use App\Http\Resources\API\V1\Hotels\HotelsResource;
use App\Services\ResponseFormatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;

class HotelsController extends Controller
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
            $query = Hotels::select(
                'hotels_id',
                'name', 
                'address', 
                'city', 
                'tax_id', 
                'total_rooms',
                'created_at'
            );

            // Filtrar por cualquier campo sin necesidad de especificar el campo
            $searchTerm = $request->input('search');
            if ($searchTerm) {
                $query->where(function ($q) use ($searchTerm) {
                  $q->whereRaw('LOWER(name) like ?', ['%' . strtolower($searchTerm) . '%'])
                      ->orWhereRaw('LOWER(address) like ?', ['%' . strtolower($searchTerm) . '%'])
                      ->orWhereRaw('LOWER(city) like ?', ['%' . strtolower($searchTerm) . '%']);
                });
            }

            // $users = $query->with([
            //     'rol' => function ($q) {
            //         $q->select(
            //             'id_roles',
            //             'names_roles',
            //             'created_at_roles'
            //         );
            //     },
            //     'sede' => function ($q) {
            //         $q->select(
            //             'id_sedes',
            //             'names_sedes',
            //             'created_at_sedes'
            //         );
            //     }
            // ])->get();
            $hotels = $query->orderBy('created_at', 'DESC')->get();
            $hotels = HotelsResource::collection($hotels)->paginate($limit);
            return $this->responseFormat->success($hotels, "Mostrando hoteles disponibles", 200);
        } catch (\Exception $e) {
            return $this->responseFormat->error("Error en el servidor " . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:hotels,name', 
                'address' => 'required|string', 
                'city' => 'string', 
                'tax_id' => 'string', 
                'total_rooms' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return $this->responseFormat->error($validator->errors()->all(), 422);
            }

            $hotels = Hotels::create([
                'name' => $request['name'], 'address' => $request['address'], 'city' => $request['city'], 'tax_id' => $request['tax_id'], 'total_rooms' => $request['total_rooms']
            ]);

            $hotels->save();

            return $this->responseFormat->success(new HotelsResource($hotels), 'Hotel creado exitosamente', 201);

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

            $respuesta = Hotels::orWhere('hotels_id', $id)->first();

            if (!$respuesta) {
                return $this->responseFormat->error("Hotel no encontrado", 404);
            }

            return $this->responseFormat->success(new HotelsResource($respuesta), 'Mostrando hotels', 200);

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
                'name' => 'string|unique:hotels,name', 
                'address' => 'string', 
                'city' => 'string', 
                'tax_id' => 'string', 
                'total_rooms' => 'integer'
            ]);

            $respuesta = Hotels::orWhere('hotels_id', $id)->first();

            if ($respuesta == null) {
                return $this->responseFormat->error("No encontrado", 404);
            }

            $respuesta->update([
                'name' => $request['name'], 'address' => $request['address'], 'city' => $request['city'], 'tax_id' => $request['tax_id'], 'total_rooms' => $request['total_rooms']
            ]);

            return $this->responseFormat->success(new HotelsResource($respuesta), 'Hotel actualizado exitosamente', 200);

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

            
            $hotelrooms = HotelRooms::where('hotel_id', $id);

            if (!$hotelrooms) {
                return $this->responseFormat->error("Cuartos de hotel no encontrado", 404);
            }

            $hotelrooms->delete();
            $hotels = Hotels::find($id);

            if (!$hotels) {
                return $this->responseFormat->error("Hotel no encontrado", 404);
            }

            $hotels->delete();

            return $this->responseFormat->success(null, 'Hotel eliminado con éxito', 200);
        } catch (\Exception $e) {
            return $this->responseFormat->error("Error en el servidor " . $e->getMessage());
        }
    }
}
