import React, { type ReactNode } from 'react';

interface ModalProps {
    children?: ReactNode;
    title?: string;
    isOpen: boolean;
    onClose: () => void;
}

export default function Modal({ children, title, isOpen, onClose }: ModalProps) {
    if (!isOpen) return null;
    
    return (
        <div className="fixed inset-0 flex items-center justify-center z-50">
            <div 
                className="fixed inset-0 bg-black/50 backdrop-blur-sm"
                onClick={onClose}
                aria-hidden="true"
            />
            
            <div className="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 max-w-lg w-full mx-4 z-10">
                {title && (
                    <div className="border-b dark:border-gray-700 pb-3 mb-4">
                        <h3 className="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {title}
                        </h3>
                    </div>
                )}
                
                <div className="space-y-4">
                    {children || <div className="py-4">Hello from InertiaKit Modal</div>}
                </div>
                
                <div className="mt-6 flex justify-end">
                    <button
                        type="button"
                        onClick={onClose}
                        className="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    );
}
