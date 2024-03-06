
"use client"

import { useEffect, useState } from 'react'
import { useDispatch } from 'react-redux';
import { removeItem, InitCart } from '../../lib/features/cart/cartSlice';
import { getSession, useSession,  } from 'next-auth/react';
import axios from 'axios';
import Link from 'next/link';
import { ToastContainer, toast } from 'react-toastify';


const RemoveCartBtn = (props) => {
    const { data } = props;

    const dispatch = useDispatch();
    const [loading, setLoading] = useState(false);
    const handleRemoveItem = async (data) => {
        try {
          
          await dispatch(removeItem(data));
          await dispatch(InitCart());
          toast.error('Successfully deleted !', {
            position: "bottom-right",
            autoClose: 2000,
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: false,
            progress: undefined,
            theme: "light",
            });
        } catch (error) {
          console.error('Failed to add item to cart', error);
          setLoading(false);
        }
      }

    return (
        <> 
          
            <div className="table-item product-remove" onClick={() => handleRemoveItem(data)}>
                <i className="fa fa-trash"></i>
            </div> 
        </>
    )
}

export default RemoveCartBtn;