/* eslint-disable @typescript-eslint/no-explicit-any */
// src/components/HotelRoomForm.tsx
import React, { useEffect, useState } from 'react';
import {
  Modal,
  ModalContent,
  ModalHeader,
  ModalBody,
  ModalFooter,
  Button,
  Input,
  Select,
  SelectItem,
  Autocomplete,
  AutocompleteItem
} from '@nextui-org/react';
import { Search } from 'lucide-react';
import { Hotel } from '../types/hotel';
import { RoomAccommodationRule, HotelRoom } from '../types/hotelRoom';
import { getHotels } from '../services/hotelService';
import debounce from 'lodash/debounce';

interface HotelRoomFormProps {
  isOpen: boolean;
  onClose: () => void;
  onSubmit: (formData: any) => void;
  accommodationRules: RoomAccommodationRule[];
  initialData?: HotelRoom;
  hotels: Hotel[]; // Agrega esta línea si falta.
  title: string;
}

export default function HotelRoomForm({
  isOpen,
  onClose,
  onSubmit,
  accommodationRules,
  initialData,
  title
}: HotelRoomFormProps) {
  const [formData, setFormData] = useState({
    hotel_id: '',
    room_type_id: '',
    accommodation_type_id: '',
    quantity: ''
  });
  
  const [searchHotels, setSearchHotels] = useState<Hotel[]>([]);
  const [isLoadingHotels, setIsLoadingHotels] = useState(false);
  const [initialHotel, setInitialHotel] = useState<Hotel | null>(null);

  const [availableRoomTypes, setAvailableRoomTypes] = useState<Set<string>>(new Set());
  const [availableAccommodationTypes, setAvailableAccommodationTypes] = useState<Set<string>>(new Set());

  // Cargar datos iniciales cuando se está editando
  useEffect(() => {
    if (initialData && isOpen) {
      setFormData({
        hotel_id: initialData.attribute.hotel.id,
        room_type_id: initialData.attribute.room_type.id,
        accommodation_type_id: initialData.attribute.accommodation_type.id,
        quantity: initialData.attribute.quantity.toString()
      });

      const initialHotelData = {
        id: initialData.attribute.hotel.id,
        type: 'hotels',
        attribute: {
          name: initialData.attribute.hotel.attribute.name,
          address: '',
          city: '',
          tax_id: '',
          total_rooms: 0,
          created_at: ''
        }
      };

      setInitialHotel(initialHotelData);
      setSearchHotels([initialHotelData]);
    } else {
      setFormData({
        hotel_id: '',
        room_type_id: '',
        accommodation_type_id: '',
        quantity: ''
      });
      setInitialHotel(null);
      setSearchHotels([]);
    }
  }, [initialData, isOpen]);

  const debouncedSearch = React.useMemo(
    () =>
      debounce(async (value: string) => {
        if (!value.trim() || value.length < 1) {
          setSearchHotels(initialHotel ? [initialHotel] : []);
          return;
        }

        setIsLoadingHotels(true);
        try {
          const response = await getHotels(1);
          // Si hay un hotel inicial y no está en los resultados, añadirlo
          const hotels = response.data.data;
          if (initialHotel && !hotels.find(h => h.id === initialHotel.id)) {
            hotels.unshift(initialHotel);
          }
          setSearchHotels(hotels);
        } catch (error) {
          console.error('Error buscando hoteles:', error);
        } finally {
          setIsLoadingHotels(false);
        }
      }, 300),
    [initialHotel]
  );

  useEffect(() => {
    return () => {
      debouncedSearch.cancel();
    };
  }, [debouncedSearch]);

  useEffect(() => {
    const roomTypes = new Set(
      accommodationRules.map(rule => rule.attribute.room_type.id)
    );
    setAvailableRoomTypes(roomTypes);
  }, [accommodationRules]);

  useEffect(() => {
    if (formData.room_type_id) {
      const accommodationTypes = new Set(
        accommodationRules
          .filter(rule => rule.attribute.room_type.id === formData.room_type_id)
          .map(rule => rule.attribute.accommodation_type.id)
      );
      setAvailableAccommodationTypes(accommodationTypes);
    } else {
      setAvailableAccommodationTypes(new Set());
    }
  }, [formData.room_type_id, accommodationRules]);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    onSubmit({
      ...formData,
      quantity: parseInt(formData.quantity)
    });
  };

  const getRoomTypeName = (id: string) => {
    const rule = accommodationRules.find(
      rule => rule.attribute.room_type.id === id
    );
    return rule?.attribute.room_type.attribute.name || '';
  };

  const getAccommodationTypeName = (id: string) => {
    const rule = accommodationRules.find(
      rule => rule.attribute.accommodation_type.id === id
    );
    return rule?.attribute.accommodation_type.attribute.name || '';
  };

  return (
    <Modal isOpen={isOpen} onClose={onClose} size="2xl">
      <ModalContent>
        <form onSubmit={handleSubmit}>
          <ModalHeader>{title}</ModalHeader>
          <ModalBody>
            <div className="space-y-4">
              <Autocomplete
                label="Hotel"
                placeholder="Buscar hotel..."
                defaultItems={searchHotels}
                items={searchHotels}
                isLoading={isLoadingHotels}
                startContent={<Search size={16} />}
                onInputChange={debouncedSearch}
                selectedKey={formData.hotel_id}
                onSelectionChange={(key) => {
                  setFormData(prev => ({ ...prev, hotel_id: key as string }));
                }}
              >
                {(hotel) => (
                  <AutocompleteItem key={hotel.id} textValue={hotel.attribute.name}>
                    {hotel.attribute.name}
                  </AutocompleteItem>
                )}
              </Autocomplete>

              <Select
                label="Tipo de Habitación"
                placeholder="Selecciona un tipo de habitación"
                selectedKeys={formData.room_type_id ? [formData.room_type_id] : []}
                onChange={(e) => {
                  setFormData(prev => ({
                    ...prev,
                    room_type_id: e.target.value,
                    accommodation_type_id: ''
                  }));
                }}
                required
              >
                {Array.from(availableRoomTypes).map((id) => (
                  <SelectItem key={id} value={id}>
                    {getRoomTypeName(id)}
                  </SelectItem>
                ))}
              </Select>

              <Select
                label="Tipo de Alojamiento"
                placeholder="Selecciona un tipo de alojamiento"
                selectedKeys={formData.accommodation_type_id ? [formData.accommodation_type_id] : []}
                onChange={(e) => setFormData(prev => ({ ...prev, accommodation_type_id: e.target.value }))}
                required
                isDisabled={!formData.room_type_id}
              >
                {Array.from(availableAccommodationTypes).map((id) => (
                  <SelectItem key={id} value={id}>
                    {getAccommodationTypeName(id)}
                  </SelectItem>
                ))}
              </Select>

              <Input
                type="number"
                label="Cantidad"
                placeholder="Ingrese la cantidad"
                value={formData.quantity}
                onChange={(e) => setFormData(prev => ({ ...prev, quantity: e.target.value }))}
                required
                min="1"
              />
            </div>
          </ModalBody>
          <ModalFooter>
            <Button color="danger" variant="light" onPress={onClose}>
              Cancelar
            </Button>
            <Button color="primary" type="submit">
              Guardar
            </Button>
          </ModalFooter>
        </form>
      </ModalContent>
    </Modal>
  );
}