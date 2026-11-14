import {Link, router} from "@inertiajs/react";
import {Button} from "@/components/ui/button";
import {Modal} from "@inertiakit/modal-react";
import React from "react";

export default function MoviesIndex() {
    const [isOpen, setIsOpen] = React.useState(false);

    const handleModalOpen = () => {

        setIsOpen(!isOpen);

        /*router.visit('movies/some-movie-id', {
            preserveScroll: true,
            preserveState: true,
            preserveUrl: false,
            replace: false
        })*/

    }

    return (
        <div>
            <Button onClick={handleModalOpen}>Open Modal</Button>
            <Modal isOpen={isOpen} />
        </div>
    )

}
