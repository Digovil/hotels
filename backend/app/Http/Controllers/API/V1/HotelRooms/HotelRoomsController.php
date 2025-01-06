<?php

namespace App\Http\Controllers\API\V1\HotelRooms;

use App\Http\Controllers\Controller;
use App\Models\HotelRooms;
use App\Http\Resources\API\V1\HotelRooms\HotelRoomsResource;
use App\Models\Hotels;
use App\Services\ResponseFormatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HotelRoomsController extends Controller
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
            $hotelrooms = HotelRoomsResource::collection(HotelRooms::with('hotel', 'roomType', 'accommodationType')->orderBy('created_at', 'desc')->get())->paginate($limit);
            return $this->responseFormat->success($hotelrooms, "Mostrando cuartos de hotel disponibles", 200);
        } catch (\Exception $e) {
            return $this->responseFormat->error("Error en el servidor " . $e->getMessage());
        }
    }

    public function checkRoomAvailability(Request $request, string $hotelId, string $hotelRoomsId=null, bool $exclude = true)
    {
        // Buscar el hotel por ID
        $hotel = Hotels::with(['hotelRooms' => function ($query) use ($exclude, $hotelRoomsId) {
          
          if($exclude) {
            $query->where('hotel_rooms.hotel_rooms_id', '!=', $hotelRoomsId); // Excluir comentarios con estado 'spam'
          }
        }])->find($hotelId);

        if (!$hotel) {
            return $this->responseFormat->error('Hotel no encontrado', 404);
        }

        // Calcular habitaciones ocupadas
        $totalHabitacionesOcupadas = $hotel->hotelRooms->sum('quantity');


        // Calcular habitaciones disponibles
        $habitacionesDisponibles = $hotel->total_rooms - $totalHabitacionesOcupadas;

        $validator = Validator::make($request->all(), [
            'quantity' => ($exclude ? 'required|' : '') . 'integer|min:1|max:'.str($habitacionesDisponibles)
        ]);

        if ($validator->fails()) {
            return ['status' => false, 'message' => $validator->errors()->all()];
        } else {
            return ['status' => true];
        }
    }

    public function validateUniqueCombination($hotelId, $roomTypeId, $accommodationTypeId, $excludeId = null)
    {
        $query = HotelRooms::where('hotel_id', $hotelId)
            ->where('room_type_id', $roomTypeId)
            ->where('accommodation_type_id', $accommodationTypeId);

        if ($excludeId) {
            $query->where('hotel_rooms_id', '!=', $excludeId);
        }

        return $query->exists();
    }

    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'hotel_id' => 'required|uuid|exists:hotels,hotels_id', 
                'room_type_id' => 'required|uuid', 
                'accommodation_type_id' => 'required|uuid', 
            ]);

            if ($validator->fails()) {
                return $this->responseFormat->error($validator->errors()->all(), 422);
            }

            $checkRoomAvailability = $this->checkRoomAvailability($request, $request['hotel_id']);
            if (!$checkRoomAvailability['status']) {
                return $this->responseFormat->error($checkRoomAvailability['message'], 422);
            }

            if ($this->validateUniqueCombination($request['hotel_id'], $request['room_type_id'], $request['accommodation_type_id'])) {
              return $this->responseFormat->error('La combinación de tipo de habitación y tipo de acomodación ya existe en este hotel.', 422);
            }

            $hotelrooms = HotelRooms::create([
                'hotel_id' => $request['hotel_id'], 
                'room_type_id' => $request['room_type_id'], 
                'accommodation_type_id' => $request['accommodation_type_id'], 
                'quantity' => $request['quantity']
            ]);

            $hotelrooms->save();
            $hotelrooms->load('hotel', 'roomType', 'accommodationType');

            return $this->responseFormat->success(new HotelRoomsResource($hotelrooms), 'Cuartos de hotel creado exitosamente', 201);
        
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

            $respuesta = HotelRooms::with('hotel', 'roomType', 'accommodationType')->orWhere('hotel_rooms_id', $id)->first();

            if (!$respuesta) {
                return $this->responseFormat->error("Cuartos de hotel no encontrado", 404);
            }

            return $this->responseFormat->success(new HotelRoomsResource($respuesta), 'Mostrando hotelrooms', 200);

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
                'hotel_id' => 'uuid', 
                'room_type_id' => 'uuid', 
                'accommodation_type_id' => 'uuid', 
            ]);

            $respuesta = HotelRooms::orWhere('hotel_rooms_id', $id)->first();

            if ($respuesta == null) {
                return $this->responseFormat->error("No encontrado", 404);
            }

            
            $checkRoomAvailability = $this->checkRoomAvailability($request, $respuesta['hotel_id'], $id,true);
            if (!$checkRoomAvailability['status']) {
                return $this->responseFormat->error($checkRoomAvailability['message'], 422);
            }


            if (!($request['room_type_id'] == $respuesta->room_type_id && $request['accommodation_type_id'] == $respuesta->accommodation_type_id)) {
              $hotelId = $request['hotel_id'] ?? $respuesta->hotel_id;
              $roomTypeId = $request['room_type_id'] ?? $respuesta->room_type_id;
              $accommodationTypeId = $request['accommodation_type_id'] ?? $respuesta->accommodation_type_id;
              if ($this->validateUniqueCombination($hotelId, $roomTypeId, $accommodationTypeId, $id)) {
                return $this->responseFormat->error('La combinación de tipo de habitación y tipo de acomodación ya existe en este hotel.', 422);
              }
            }

            $respuesta->update([
                'hotel_id' => $request['hotel_id'] ?? $respuesta->hotel_id, 
                'room_type_id' => $request['room_type_id'] ?? $respuesta->room_type_id,  
                'accommodation_type_id' => $request['accommodation_type_id'] ?? $respuesta->accommodation_type_id, 
                'quantity' => $request['quantity'] ?? $respuesta->quantity, 
            ]);
            $respuesta->load('hotel', 'roomType', 'accommodationType');

            return $this->responseFormat->success(new HotelRoomsResource($respuesta), 'Cuartos de hotel actualizado exitosamente', 200);

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

            $hotelrooms = HotelRooms::find($id);

            if (!$hotelrooms) {
                return $this->responseFormat->error("Cuartos de hotel no encontrado", 404);
            }

            $hotelrooms->delete();

            return $this->responseFormat->success(null, 'Cuartos de hotel eliminado con éxito', 200);
        } catch (\Exception $e) {
            return $this->responseFormat->error("Error en el servidor " . $e->getMessage());
        }
    }
}
