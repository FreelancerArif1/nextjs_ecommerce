
// components/LogoutButton.js
"use client"

import { getSession, useSession,  } from 'next-auth/react';
import axios from 'axios';
import Link from 'next/link';
import { ToastContainer, toast } from 'react-toastify';

const Action = (props) => {
    const { data } = props;
    const { data:userdata, status, update, session }= useSession();
    
    
    const addToWishlist = async (product_id) => {
        const token = localStorage.getItem('token');
        let session_key = localStorage.getItem("session_key");
        let formData = new FormData();
        formData.append('product_id', product_id);
        formData.append('session_key', session_key);

        try {
            const response = await axios.post(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/add-to-wishlist`, formData,{
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            if (response.data.status == 0) {
                toast.error(response.data.message, {
                    position: "bottom-right",
                    autoClose: 2000,
                    hideProgressBar: false,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: false,
                    progress: undefined,
                    theme: "light",
                });
            }else if(response.data.status == 1){
                update({ 
                    wishlist_data: response.data.wishlists,
                });
                toast.success(response.data.message, {
                    position: "bottom-right",
                    autoClose: 2000,
                    hideProgressBar: false,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: false,
                    progress: undefined,
                    theme: "light",
                });
            }
        } catch (error) {
            console.log('error ', error);
        }
    }

    
    const addToCompare = async (product_id) => {
        const token = localStorage.getItem('token');
        let session_key = localStorage.getItem("session_key");
        let formData = new FormData();
        formData.append('product_id', product_id);
        formData.append('session_key', session_key);

        try {
            const response = await axios.post(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/add-to-compare`, formData,{
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            if (response.data.status == 0) {
                toast.error(response.data.message, {
                    position: "bottom-right",
                    autoClose: 2000,
                    hideProgressBar: false,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: false,
                    progress: undefined,
                    theme: "light",
                });
            }else if(response.data.status == 1){
                update({ 
                    compares: response.data.compares,
                });
                toast.success(response.data.message, {
                    position: "bottom-right",
                    autoClose: 2000,
                    hideProgressBar: false,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: false,
                    progress: undefined,
                    theme: "light",
                });
            }
        } catch (error) {
            console.log('error ', error);
        }
    }


return (
    <> 
    <ul className="product-option">
        {/* <li data-bs-toggle="tooltip" data-bs-placement="top"
            title="View">
                <i className="fa fa-eye" aria-hidden="true"></i>
        
        </li> */}

        <li onClick={() => addToCompare(data.id)} data-bs-toggle="tooltip" data-bs-placement="top"
            title="Compare">
            {/* <i data-feather="refresh-cw"></i> */}
            <i className="fa fa-refresh" aria-hidden="true"></i>

        </li>

        <li data-bs-toggle="tooltip" onClick={() => addToWishlist(data.id)} data-bs-placement="top"
            title="Wishlist">
            <i className="fa fa-heart-o" aria-hidden="true"></i>
        </li>
    </ul>
    </>
)
}

export default Action;



