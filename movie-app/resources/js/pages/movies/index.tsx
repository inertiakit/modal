import {Link, router} from "@inertiajs/react";
import {Button} from "@/components/ui/button";
import AppLayout from "@/layouts/app-layout";
import Modal from "@/components/modal";

export default function MoviesIndex() {

    const handleModalOpen = () => {

        router.visit('movies/some-movie-id', {
            preserveScroll: true,
            preserveState: true,
            preserveUrl: false,
            replace: false
        })

    }

    return (
        <div>
            <Button onClick={handleModalOpen}>Open Modal</Button>
            <Modal />
        </div>
    )

}
