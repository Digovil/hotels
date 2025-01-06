/* eslint-disable @typescript-eslint/no-explicit-any */
// src/components/Notification.tsx
import React from 'react';
import { Modal, ModalContent, Button } from "@nextui-org/react";

interface NotificationProps {
  isOpen: boolean;
  onClose: () => void;
  message: string;
  type: 'success' | 'error';
}

export default function Notification({ isOpen, onClose, message, type }: NotificationProps) {
  const bgColor = type === 'success' ? 'bg-green-100' : 'bg-red-100';
  const textColor = type === 'success' ? 'text-green-700' : 'text-red-700';
  const icon = type === 'success' ? '✓' : '✕';

  return (
    <Modal
      isOpen={isOpen}
      onClose={onClose}
      size="sm"
      placement="top"
      hideCloseButton
      className={`${bgColor} p-4`}
    >
      <ModalContent className="py-2 px-4">
        <div className="flex items-center gap-2">
          <span className={`font-bold ${textColor}`}>{icon}</span>
          <p className={`${textColor}`}>{message}</p>
        </div>
      </ModalContent>
    </Modal>
  );
}