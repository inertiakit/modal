import {Link, router, usePage} from "@inertiajs/react";
import React, {lazy, Suspense, useEffect, useMemo} from "react";
import {cn} from "@/lib/utils";
import {X} from "lucide-react";
import {browser} from "globals";

export default function Modal() {

    const [open, setOpen] = React.useState<boolean>(false);
    const [Component, setComponent] = React.useState<React.FC | null>(null);

    const {props} = usePage()

    useEffect(() => {
        if (props.modal?.component) {
            import(`../pages/${props.modal.component}`)
                .then(module => {
                    setComponent(() => module.default);
                })
                .catch(error => {
                    console.error(error);
                    setComponent(() => () => <div>Component not found.</div>);
                });
        }
    }, [props.modal?.component]);


    useEffect(() => {
        if (props.modal) {
            console.log(props);
            setOpen(true);
        } else {
            setOpen(false);
        }
    }, [props.modal]);

    return (

        <div className={cn('absolute flex flex-col justify-center items-center inset-0 z-10 bg-black/80', !open ? 'hidden' : '')}>
            <div className="max-w-3xl w-96 px-4 bg-white rounded-sm">
                <div className="flex items-center justify-between py-2">
                    <div>Title</div>
                    <div>
                        <Link href={props?.modal?.redirectUrl ?? ''}>
                            <X className="text-gray-500" />
                        </Link>
                    </div>
                </div>
                <div className="pb-2">
                    {Component && <Component {...props.modal.props} />}
                </div>
            </div>
        </div>
    )




}
