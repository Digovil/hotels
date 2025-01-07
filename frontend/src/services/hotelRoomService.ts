// src/services/hotelRoomService.ts
import axios, { AxiosError } from 'axios';
import { ApiResponse, HotelRoom, RoomAccommodationRule, HotelRoomCreatePayload } from '../types/hotelRoom';

const API_URL = process.env.NEXT_PUBLIC_API_URL;

// eslint-disable-next-line @typescript-eslint/no-explicit-any
const handleError = (error: AxiosError<any>) => {
  if (error.response?.data?.message) {
    const message = Array.isArray(error.response.data.message)
      ? error.response.data.message[0]
      : error.response.data.message;
    throw new Error(message);
  }
  throw new Error('Ha ocurrido un error inesperado');
};

export const getHotelRooms = async (page: number = 1): Promise<ApiResponse<HotelRoom>> => {
  try {
    const response = await axios.get(`${API_URL}/hotel_rooms?page=${page}`);
    return response.data;
  } catch (error) {
    throw handleError(error as AxiosError);
  }
};

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export const createHotelRoom = async (data: HotelRoomCreatePayload): Promise<any> => {
  try {
    const response = await axios.post(`${API_URL}/hotel_rooms/create`, data);
    return response.data;
  } catch (error) {
    throw handleError(error as AxiosError);
  }
};

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export const updateHotelRoom = async (id: string, data: Partial<HotelRoomCreatePayload>): Promise<any> => {
  try {
    const response = await axios.put(`${API_URL}/hotel_rooms/update/${id}`, data);
    return response.data;
  } catch (error) {
    throw handleError(error as AxiosError);
  }
};

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export const deleteHotelRoom = async (id: string): Promise<any> => {
  try {
    const response = await axios.delete(`${API_URL}/hotel_rooms/delete/${id}`);
    return response.data;
  } catch (error) {
    throw handleError(error as AxiosError);
  }
};

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export const getRoomAccommodationRules = async (): Promise<ApiResponse<RoomAccommodationRule>> => {
  try {
    const response = await axios.get(`${API_URL}/room_accommodation_rules`);
    return response.data;
  } catch (error) {
    throw handleError(error as AxiosError);
  }
};