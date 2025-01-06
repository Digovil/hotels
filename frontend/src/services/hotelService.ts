// src/services/hotelService.ts
import axios, { AxiosError } from 'axios';
import { ApiResponse, HotelAttribute } from '../types/hotel';

const API_URL = 'http://127.0.0.1:8000/api/v1';

interface ErrorResponse {
  status: boolean;
  message: string | string[];
}

const handleError = (error: AxiosError<ErrorResponse>) => {
  if (error.response?.data?.message) {
    const message = Array.isArray(error.response.data.message)
      ? error.response.data.message[0]
      : error.response.data.message;
    throw new Error(message);
  }
  throw new Error('Ha ocurrido un error inesperado');
};

export const getHotels = async (page: number = 1): Promise<ApiResponse> => {
  try {
    const response = await axios.get(`${API_URL}/hotels?page=${page}`);
    return response.data;
  } catch (error) {
    throw handleError(error as AxiosError<ErrorResponse>);
  }
};

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export const createHotel = async (hotelData: Omit<HotelAttribute, 'created_at'>): Promise<any> => {
  try {
    const response = await axios.post(`${API_URL}/hotels/create`, hotelData);
    return response.data;
  } catch (error) {
    throw handleError(error as AxiosError<ErrorResponse>);
  }
};

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export const updateHotel = async (id: string, hotelData: Partial<HotelAttribute>): Promise<any> => {
  try {
    const response = await axios.put(`${API_URL}/hotels/update/${id}`, hotelData);
    return response.data;
  } catch (error) {
    throw handleError(error as AxiosError<ErrorResponse>);
  }
};

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export const deleteHotel = async (id: string): Promise<any> => {
  try {
    const response = await axios.delete(`${API_URL}/hotels/delete/${id}`);
    return response.data;
  } catch (error) {
    throw handleError(error as AxiosError<ErrorResponse>);
  }
};