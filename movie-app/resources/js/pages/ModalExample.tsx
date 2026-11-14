import React, { useState } from 'react';
import { Head } from '@inertiajs/react';
import { Modal } from '@inertiakit/modal-react';
import AppLayout from '@/layouts/app-layout';

export default function ModalExample() {
    const [isOpen, setIsOpen] = useState(false);

    return (
        <>
            <Head title="Modal Example" />
            
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            <h1 className="text-2xl font-semibold mb-4">InertiaKit Modal Example</h1>
                            
                            <button
                                type="button"
                                onClick={() => setIsOpen(true)}
                                className="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                Open Modal
                            </button>
                            
                            <Modal 
                                isOpen={isOpen} 
                                onClose={() => setIsOpen(false)}
                                title="Example Modal"
                            >
                                <p>This is an example of using the InertiaKit Modal component in your Laravel application.</p>
                                <p className="mt-2">You can customize the content here as needed for your application.</p>
                            </Modal>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}

ModalExample.layout = (page: React.ReactNode) => <AppLayout children={page} />;
