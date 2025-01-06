/* eslint-disable @typescript-eslint/no-explicit-any */
// src/components/HotelForm.tsx
import React, { useEffect } from 'react';
import { Input, Button, Modal, ModalContent, ModalHeader, ModalBody, ModalFooter } from '@nextui-org/react';
import { Hotel } from '../types/hotel';

interface HotelFormProps {
  isOpen: boolean;
  onClose: () => void;
  onSubmit: (formData: any) => void;
  initialData?: Hotel;
  title: string;
}

export default function HotelForm({ isOpen, onClose, onSubmit, initialData, title }: HotelFormProps) {
  const [formData, setFormData] = React.useState({
    name: '',
    address: '',
    city: '',
    tax_id: '',
    total_rooms: '',
  });

  // Actualizar el formulario cuando cambia initialData o se abre el modal
  useEffect(() => {
    if (initialData && isOpen) {
      setFormData({
        name: initialData.attribute.name || '',
        address: initialData.attribute.address || '',
        city: initialData.attribute.city || '',
        tax_id: initialData.attribute.tax_id || '',
        total_rooms: initialData.attribute.total_rooms.toString() || '',
      });
    } else if (!initialData && isOpen) {
      // Limpiar el formulario cuando se abre para crear nuevo
      setFormData({
        name: '',
        address: '',
        city: '',
        tax_id: '',
        total_rooms: '',
      });
    }
  }, [initialData, isOpen]);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // Convertir total_rooms a número antes de enviar
    const formDataToSubmit = {
      ...formData,
      total_rooms: parseInt(formData.total_rooms)
    };
    onSubmit(formDataToSubmit);
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
  };

  return (
    <Modal 
      isOpen={isOpen} 
      onClose={() => {
        onClose();
        // Si no hay initialData, limpiar el formulario al cerrar
        if (!initialData) {
          setFormData({
            name: '',
            address: '',
            city: '',
            tax_id: '',
            total_rooms: '',
          });
        }
      }} 
      size="2xl"
    >
      <ModalContent>
        <form onSubmit={handleSubmit}>
          <ModalHeader>{title}</ModalHeader>
          <ModalBody>
            <div className="space-y-4">
              <Input
                label="Nombre"
                name="name"
                value={formData.name}
                onChange={handleChange}
                placeholder="Ingrese el nombre del hotel"
                required
              />
              <Input
                label="Dirección"
                name="address"
                value={formData.address}
                onChange={handleChange}
                placeholder="Ingrese la dirección"
                required
              />
              <Input
                label="Ciudad"
                name="city"
                value={formData.city}
                onChange={handleChange}
                placeholder="Ingrese la ciudad"
                required
              />
              <Input
                label="ID Fiscal"
                name="tax_id"
                value={formData.tax_id}
                onChange={handleChange}
                placeholder="Ingrese el ID fiscal"
                required
              />
              <Input
                type="number"
                label="Total de Habitaciones"
                name="total_rooms"
                value={formData.total_rooms}
                onChange={handleChange}
                placeholder="Ingrese el número de habitaciones"
                required
                min="0"
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