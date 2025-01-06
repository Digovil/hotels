/* eslint-disable @typescript-eslint/no-explicit-any */
// src/components/HotelTable.tsx
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
import { Pencil, Trash2, Plus } from 'lucide-react';
import { getHotels, createHotel, updateHotel, deleteHotel } from '../services/hotelService';
import { Hotel } from '../types/hotel';
import HotelForm from './HotelForm';
import Notification from './Notification';

export default function HotelTable() {
  const [hotels, setHotels] = useState<Hotel[]>([]);
  const [page, setPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [selectedHotel, setSelectedHotel] = useState<Hotel | null>(null);
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
    // Cerrar la notificación después de 3 segundos
    setTimeout(() => {
      setNotification(prev => ({ ...prev, isOpen: false }));
    }, 3000);
  };

  const loadHotels = async (pageNumber: number) => {
    try {
      const response = await getHotels(pageNumber);
      setHotels(response.data.data);
      setTotalPages(response.data.last_page);
    } catch (error) {
      showNotification((error as Error).message, 'error');
    }
  };

  useEffect(() => {
    loadHotels(page);
  }, [page]);

  const handleCreate = async (formData: any) => {
    try {
      await createHotel(formData);
      createModal.onClose();
      loadHotels(page);
      showNotification('Hotel creado exitosamente', 'success');
    } catch (error) {
      showNotification((error as Error).message, 'error');
    }
  };

  const handleUpdate = async (formData: any) => {
    if (!selectedHotel) return;
    try {
      await updateHotel(selectedHotel.id, formData);
      updateModal.onClose();
      setSelectedHotel(null);
      loadHotels(page);
      showNotification('Hotel actualizado exitosamente', 'success');
    } catch (error) {
      showNotification((error as Error).message, 'error');
    }
  };

  const handleDelete = async () => {
    if (!selectedHotel) return;
    try {
      await deleteHotel(selectedHotel.id);
      setIsDeleteModalOpen(false);
      setSelectedHotel(null);
      loadHotels(page);
      showNotification('Hotel eliminado exitosamente', 'success');
    } catch (error) {
      showNotification((error as Error).message, 'error');
    }
  };

  const columns = [
    { key: 'name', label: 'NOMBRE' },
    { key: 'address', label: 'DIRECCIÓN' },
    { key: 'city', label: 'CIUDAD' },
    { key: 'tax_id', label: 'ID FISCAL' },
    { key: 'total_rooms', label: 'HABITACIONES' },
    { key: 'created_at', label: 'FECHA CREACIÓN' },
    { key: 'actions', label: 'ACCIONES' },
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
        <h1 className="text-2xl font-bold">Hoteles</h1>
        <Button 
          color="primary" 
          onPress={createModal.onOpen}
          startContent={<Plus size={20} />}
        >
          Agregar Hotel
        </Button>
      </div>

      <Table aria-label="Tabla de hoteles">
        <TableHeader>
          {columns.map((column) => (
            <TableColumn key={column.key}>{column.label}</TableColumn>
          ))}
        </TableHeader>
        <TableBody>
          {hotels.map((hotel) => (
            <TableRow key={hotel.id}>
              <TableCell>{hotel.attribute.name}</TableCell>
              <TableCell>{hotel.attribute.address}</TableCell>
              <TableCell>{hotel.attribute.city}</TableCell>
              <TableCell>{hotel.attribute.tax_id}</TableCell>
              <TableCell>{hotel.attribute.total_rooms}</TableCell>
              <TableCell>{hotel.attribute.created_at}</TableCell>
              <TableCell>
                <div className="flex gap-2">
                  <Button
                    isIconOnly
                    size="sm"
                    variant="light"
                    onPress={() => {
                      setSelectedHotel(hotel);
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
                      setSelectedHotel(hotel);
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
      <HotelForm
        isOpen={createModal.isOpen}
        onClose={createModal.onClose}
        onSubmit={handleCreate}
        title="Crear Nuevo Hotel"
      />

      {/* Modal de Actualización */}
      <HotelForm
        isOpen={updateModal.isOpen}
        onClose={updateModal.onClose}
        onSubmit={handleUpdate}
        initialData={selectedHotel || undefined}
        title="Actualizar Hotel"
      />

      {/* Modal de Confirmación de Eliminación */}
      <Modal isOpen={isDeleteModalOpen} onClose={() => setIsDeleteModalOpen(false)}>
        <ModalContent>
          <ModalHeader>Confirmar Eliminación</ModalHeader>
          <ModalBody>
            ¿Está seguro que desea eliminar este hotel?
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