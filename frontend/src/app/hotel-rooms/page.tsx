// src/app/hotel-rooms/page.tsx
/* eslint-disable @typescript-eslint/no-explicit-any */
'use client';

import React, { useEffect, useState } from 'react';
import {
  Table,
  TableHeader,
  TableColumn,
  TableBody,
  TableRow,
  TableCell,
  Button,
  useDisclosure,
  Pagination,
  Modal,
  ModalContent,
  ModalHeader,
  ModalBody,
  ModalFooter,
} from '@nextui-org/react';
import { Plus, Pencil, Trash2 } from 'lucide-react';
import { getHotelRooms, createHotelRoom, updateHotelRoom, deleteHotelRoom, getRoomAccommodationRules } from '../../services/hotelRoomService';
import { getHotels } from '../../services/hotelService';
import HotelRoomForm from '../../components/HotelRoomForm';
import Notification from '../../components/Notification';
import { Hotel } from '../../types/hotel';
import { defaultHotelRoom, HotelRoom, RoomAccommodationRule } from '../../types/hotelRoom';

export default function HotelRoomsPage() {
  const [hotelRooms, setHotelRooms] = useState<HotelRoom[]>([]);
  const [hotels, setHotels] = useState<Hotel[]>([]);
  const [accommodationRules, setAccommodationRules] = useState<RoomAccommodationRule[]>([]);
  const [page, setPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [selectedRoom, setSelectedRoom] = useState<HotelRoom>(defaultHotelRoom);
  const [isDeleteModalOpen, setIsDeleteModalOpen] = useState(false);
  const [notification, setNotification] = useState({
    isOpen: false,
    message: '',
    type: 'success' as 'success' | 'error'
  });

  const createModal = useDisclosure();
  const updateModal = useDisclosure();

  const showNotification = (message: string, type: 'success' | 'error') => {
    setNotification({
      isOpen: true,
      message,
      type
    });
    setTimeout(() => {
      setNotification(prev => ({ ...prev, isOpen: false }));
    }, 3000);
  };

  const loadHotelRooms = async (pageNumber: number) => {
    try {
      const response = await getHotelRooms(pageNumber);
      setHotelRooms(response.data.data);
      setTotalPages(response.data.last_page);
    } catch (error) {
      showNotification((error as Error).message, 'error');
    }
  };

  const loadHotels = async () => {
    try {
      const response = await getHotels();
      setHotels(response.data.data);
    } catch (error) {
      showNotification((error as Error).message, 'error');
    }
  };

  const loadAccommodationRules = async () => {
    try {
      const response = await getRoomAccommodationRules();
      setAccommodationRules(response.data.data);
    } catch (error) {
      showNotification((error as Error).message, 'error');
    }
  };

  useEffect(() => {
    loadHotelRooms(page);
  }, [page]);

  useEffect(() => {
    loadHotels();
    loadAccommodationRules();
  }, []);

  const handleCreate = async (formData: any) => {
    try {
      await createHotelRoom(formData);
      createModal.onClose();
      loadHotelRooms(page);
      showNotification('Habitación creada exitosamente', 'success');
    } catch (error) {
      showNotification((error as Error).message, 'error');
    }
  };

  const handleUpdate = async (formData: any) => {
    if (!selectedRoom) return;
    try {
      await updateHotelRoom(selectedRoom.id, formData);
      updateModal.onClose();
      setSelectedRoom(defaultHotelRoom);
      loadHotelRooms(page);
      showNotification('Habitación actualizada exitosamente', 'success');
    } catch (error) {
      showNotification((error as Error).message, 'error');
    }
  };

  const handleDelete = async () => {
    if (!selectedRoom) return;
    try {
      await deleteHotelRoom(selectedRoom.id);
      setIsDeleteModalOpen(false);
      setSelectedRoom(defaultHotelRoom);
      loadHotelRooms(page);
      showNotification('Habitación eliminada exitosamente', 'success');
    } catch (error) {
      showNotification((error as Error).message, 'error');
    }
  };

  const columns = [
    { key: 'hotel', label: 'HOTEL' },
    { key: 'room_type', label: 'TIPO DE HABITACIÓN' },
    { key: 'accommodation_type', label: 'TIPO DE ALOJAMIENTO' },
    { key: 'quantity', label: 'CANTIDAD' },
    { key: 'created_at', label: 'FECHA CREACIÓN' },
    { key: 'actions', label: 'ACCIONES' }
  ];

  return (
    <div className="p-8">
      <Notification
        isOpen={notification.isOpen}
        onClose={() => setNotification(prev => ({ ...prev, isOpen: false }))}
        message={notification.message}
        type={notification.type}
      />

      <div className="flex justify-between mb-4">
        <h1 className="text-2xl font-bold">Habitaciones</h1>
        <Button 
          color="primary" 
          onPress={createModal.onOpen}
          startContent={<Plus size={20} />}
        >
          Agregar Habitación
        </Button>
      </div>

      <Table aria-label="Tabla de habitaciones">
        <TableHeader>
          {columns.map((column) => (
            <TableColumn key={column.key}>{column.label}</TableColumn>
          ))}
        </TableHeader>
        <TableBody>
          {hotelRooms.map((room) => (
            <TableRow key={room.id}>
              <TableCell>{room.attribute.hotel.attribute.name}</TableCell>
              <TableCell>{room.attribute.room_type.attribute.name}</TableCell>
              <TableCell>{room.attribute.accommodation_type.attribute.name}</TableCell>
              <TableCell>{room.attribute.quantity}</TableCell>
              <TableCell>{room.attribute.created_at}</TableCell>
              <TableCell>
                <div className="flex gap-2">
                  <Button
                    isIconOnly
                    size="sm"
                    variant="light"
                    onPress={() => {
                      setSelectedRoom(room);
                      updateModal.onOpen();
                    }}
                  >
                    <Pencil size={20} />
                  </Button>
                  <Button
                    isIconOnly
                    size="sm"
                    color="danger"
                    variant="light"
                    onPress={() => {
                      setSelectedRoom(room);
                      setIsDeleteModalOpen(true);
                    }}
                  >
                    <Trash2 size={20} />
                  </Button>
                </div>
              </TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>

      <div className="flex justify-center mt-4">
        <Pagination
          total={totalPages}
          page={page}
          onChange={setPage}
        />
      </div>

      {/* Modal de Creación */}
      <HotelRoomForm
        isOpen={createModal.isOpen}
        onClose={createModal.onClose}
        onSubmit={handleCreate}
        hotels={hotels}
        accommodationRules={accommodationRules}
        title="Crear Nueva Habitación"
      />

      {/* Modal de Actualización */}
      <HotelRoomForm
        isOpen={updateModal.isOpen}
        onClose={updateModal.onClose}
        onSubmit={handleUpdate}
        hotels={hotels}
        accommodationRules={accommodationRules}
        initialData={selectedRoom}
        title="Actualizar Habitación"
      />

      {/* Modal de Confirmación de Eliminación */}
      <Modal isOpen={isDeleteModalOpen} onClose={() => setIsDeleteModalOpen(false)}>
        <ModalContent>
          <ModalHeader>Confirmar Eliminación</ModalHeader>
          <ModalBody>
            ¿Está seguro que desea eliminar esta habitación?
          </ModalBody>
          <ModalFooter>
            <Button color="default" variant="light" onPress={() => setIsDeleteModalOpen(false)}>
              Cancelar
            </Button>
            <Button color="danger" onPress={handleDelete}>
              Eliminar
            </Button>
          </ModalFooter>
        </ModalContent>
      </Modal>
    </div>
  );
}