import Modal from "@/components/modal";

interface MovieDetailsProps{
    title: string
}

export default function MovieDetailsModal({title, ...props}: MovieDetailsProps) {

    return (
        <div>
            <h1>Hello from details modal</h1>
            <h2>{title}</h2>
        </div>
    )

}
