// src/types/hotelRoom.ts

export interface RoomType {
  type: string;
  id: string;
  attribute: {
    name: string;
    created_at: string;
  };
}

export interface AccommodationType {
  type: string;
  id: string;
  attribute: {
    name: string;
    created_at: string;
  };
}

export interface RoomAccommodationRule {
  type: string;
  id: string;
  attribute: {
    room_type: RoomType;
    accommodation_type: AccommodationType;
    created_at: string;
  };
}

export interface HotelRoom {
  type: string;
  id: string;
  attribute: {
    hotel: {
      type: string;
      id: string;
      attribute: {
        name: string;
      };
    };
    room_type: RoomType;
    accommodation_type: AccommodationType;
    quantity: number;
    created_at: string;
  };
}

export interface HotelRoomCreatePayload {
  hotel_id: string;
  room_type_id: string;
  accommodation_type_id: string;
  quantity: number;
}

export interface ApiResponse<T> {
  status: boolean;
  message: string;
  data: {
    current_page: number;
    data: T[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: {
      url: string | null;
      label: string;
      active: boolean;
    }[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
  };
}

export const defaultHotelRoom = {
  type: '',
  id: '',
  attribute: {
    hotel: {
      type: '',
      id: '',
      attribute: {
        name: '',
      }
    },
    room_type:{
      type: '',
      id: '',
      attribute: {
        name: '',
        created_at: ''
      }
    },
    accommodation_type: {
      type: '',
      id: '',
      attribute: {
        name: '',
        created_at: ''
      }
    },
    quantity: 0,
    created_at: ''
  }
}