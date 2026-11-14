import { ReactNode } from 'react';

interface ModalProps {
  children?: ReactNode;
  title?: string;
  isOpen?: boolean;
  onClose?: () => void;
}

export const Modal: React.FC<ModalProps>;
